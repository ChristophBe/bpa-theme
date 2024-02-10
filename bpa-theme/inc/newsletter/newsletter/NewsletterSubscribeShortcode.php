<?php
require_once "newsletter_token.php";
require_once "NewsletterAcknowledgeShortcode.php";
require_once "newsletter_send_mail.php";


class NewsletterSubscribeShortCode
{
    private $actionName = "subscribe_newsletter";
    private $nonceFieldName = "subscribe_newsletter_nonce";

    public function __construct()
    {
        // Hooking up our functions to WordPress filters


        add_shortcode('subscribe-newsletter', array($this, "subscribeShortCode"));

    }


    public function subscribeShortCode()
    {


        list($submitted, $email, $email_wrong, $except_privacy_policy, $recapture_valid, $success) = $this->handleFormSubmit();


        global $privacyPolicyLink;
        $privacy_policy_link = get_option($privacyPolicyLink);


        global $recaptcha_site_key_option;
        $recaptcha_site_key = get_option($recaptcha_site_key_option);

        if ($success) {

            return "<div class='alert alert-success'>Vielen Dank, dass Sie sich für unseren Newsletter eingetragen haben.</br> Bitte bestätigen Sie Ihre Newsletter-Anmeldung mit dem Bestätigungs-Link, den wir Ihnen per E-Mail zugesendet haben.</div>";
        } else {

            return '

  <script src="https://www.google.com/recaptcha/api.js" async defer></script>


<form method="post" action="#newsletter" id="newsletter-form">
                ' . wp_nonce_field($this->actionName, $this->nonceFieldName, true, false) . '
                <div class="form-group">
                    <label for="newsletter_email">Ihre E-Mail Adresse</label>
                    <input type="email" class="form-control' . ($submitted && $email_wrong ? " is-invalid" : "") . '"
                           id="newsletter_email" name="newsletter_email" value="' . $email . '"
                           aria-describedby="emailHelp" placeholder="Ihre E-Mail Adresse"/>
                    <div class="invalid-feedback">
                        Bitte geben Sie eine gültige E-Mail Adresse an.
                    </div>
                </div>
                <div class="form-check mb-3">
                    <input type="checkbox" name="except_privacy_policy"
                               class="form-check-input pr-3' . ($submitted && !$except_privacy_policy ? " is-invalid" : "") . '"
                           id="except_privacy_policy" value="1"/>
                    <label class="form-check-label' . ($submitted && !$except_privacy_policy ? " is-invalid" : "") . '"
                           for="except_privacy_policy">Ich stimme den <a href="' . $privacy_policy_link . '" target="_blank"> Datenschutzbestimmungen der
                        Bläserphilharmonie Aachen e.V.</a> zu.</label>
                    <div class="invalid-feedback">
                        Bitte stimmen Sie den Datenschutzbestimmungen zu um sich zum Newsletter anzumelden.
                    </div>
                </div>
                <div class="mb-3">
                <div id="recaptcha" class="g-recaptcha" data-sitekey="' . $recaptcha_site_key . '" data-theme="dark"></div>
                ' . ($submitted && !$recapture_valid ? '<small class="text-danger">Bitte bestätigen Sie dass Sie kein Roboter sind.</small>':'') . '  
                </div>
                

                <button type="submit" class="btn btn-outline-dark" id="submit">Anmelden</button>
                
            </form>';
        }

    }

    public function is_recaptcha_valid(){

        global $recaptcha_secret_option;
        $recaptcha_secret = get_option($recaptcha_secret_option);

        if(isset($_POST['g-recaptcha-response']) && !empty($_POST['g-recaptcha-response']))
        {
            $verifyResponse = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$recaptcha_secret.'&response='.$_POST['g-recaptcha-response']);
            $responseData = json_decode($verifyResponse);
            return $responseData->success;
        }
        return false;
    }

    /**
     * @return array
     */
    public function handleFormSubmit()
    {
        $email = "";
        $except_privacy_policy = false;

        $email_wrong = true;
        $submitted = false;
        $success = false;
        $recapture_valid = false;


        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            if (!isset($_POST[$this->nonceFieldName])
                || !wp_verify_nonce($_POST[$this->nonceFieldName], $this->actionName)
            ) {
                print 'Sorry, your nonce did not verify.';
                exit;
            } else {
                // process form data
                $submitted = true;
                if (isset($_POST["newsletter_email"])) {
                    $email = filter_var($_POST["newsletter_email"], FILTER_VALIDATE_EMAIL);
                    $email_wrong = !$email;
                }

                if (isset($_POST["except_privacy_policy"])) {

                    $except_privacy_policy = true && $_POST["except_privacy_policy"];
                }
            }
            $recapture_valid = $this->is_recaptcha_valid();

            $sendAcknowledgementMail = false;
            if (!$email_wrong && $except_privacy_policy && $recapture_valid) {
                $subscription = get_subscription_by_email($email);
                if ($subscription) {
                    $sendAcknowledgementMail = !$subscription->getSubscribed();
                    $subscription->setAcknowledged(!$sendAcknowledgementMail);
                    update_subscription($subscription);
                    $success = true;
                } else {

                    $sendAcknowledgementMail = true;
                    $subscription = new NewsletterSubscription();
                    $subscription->setEmail($email);
                    $subscription->setSubscribed(false);
                    $subscription->setAcknowledged(false);
                    $id = add_subscription($subscription);
                    $success = true && $id;
                    $subscription->setId($id);

                }

                if ($success && $sendAcknowledgementMail) {
                    $success = $this->sendAcknowledgementMail($subscription);
                }
            }
        }
        return array($submitted, $email, $email_wrong, $except_privacy_policy,$recapture_valid, $success);
    }

    private function sendAcknowledgementMail(NewsletterSubscription $subscription)
    {
        global $acknowledgePageOption;
        global $acknowledgeTokenVarName;

        $acknowledgePageId = get_option($acknowledgePageOption);
        $acknowledgePageUrl = get_permalink($acknowledgePageId);
        $newsletter_token = generate_newsletter_token($subscription, "acknowledgement");

        $acknowledgementLink = $acknowledgePageUrl;
        $acknowledgementLink .= strpos($acknowledgePageUrl, '?') !== false ? "&" : "?";
        $acknowledgementLink .= $acknowledgeTokenVarName . "=" . urlencode($newsletter_token);


        $subject = "Bitte bestätigen Sie Ihre Newsletter-Anmeldung";
        $msg = "<h3 >Willkommen zum Newsletter der Bläserphilharmonie Aachen e.V.</h3>

<p>Vielen Dank, dass Sie den Newsletter der Bläserphilharmonie Aachen e.V. erhalten wollen.
Bitte bestätigen Sie mit folgendem Link, dass wir Ihnen unseren Newletter zuschicken dürfen.</p>
<p>

<a href=\"" . $acknowledgementLink . "\" class=\"btn\">Newsletter-Anmeldung bestätigen</a>
</p>

<p>Viele Grüße<br>
Bläserphilharmonie Aachen e.V.<br>
<a href='mailto:vorstand@blaeserphilharmonie-aachen.de'>vorstand@blaeserphilharmonie-aachen.de</a> </p>
<p>
<small>Diese E-Mail wurde Automatisch von (https://blaeserphilharmonie-aachen.de) gesendet.</small>
</p>";

        $msg_alt = "Willkommen beim Newsletter der Bläserphilharmonie Aachen e.V.

Vielen Dank, dass Sie den Newsletter der Bläserphilharmonie Aachen e.V. erhalten wollen.
Bitte bestätigen Sie mit folgendem Link, dass wir Ihnen unseren Newletter zuschicken dürfen.

" . $acknowledgementLink . "

Viele Grüße
Bläserphilharmonie Aachen e.V.
vorstand@blaeserphilharmonie-aachen.de

--
Diese E-Mail wurde Automatisch von (https://blaeserphilharmonie-aachen.de) gesendet.";
        return newsletter_send_mail($subscription, $subject, $msg,$msg_alt);


    }

    /**
     * @param NewsletterSubscription $subscription
     * @param $subject
     * @param $msg
     * @return bool
     */
   }

$newsletterSubscribeShortCode = new NewsletterSubscribeShortCode();