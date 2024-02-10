<?php

use bpause bpause bpause bpause bpause bpause bpause bpause bpause bpause bpause bparequire_once "newsletter_token.php";
require_once "newsletter_send_mail.php";

global $acknowledgeTokenVarName;
$acknowledgeTokenVarName = "newsletter-token";

class NewsletterAcknowledgeShortCode
{
    private $actionName = "personal_information";
    private $nonceFieldName = "personal_information_nonce";
    private $tokeAction = "personal_information";

    public function __construct()
    {
        add_shortcode('acknowledge-newsletter', array($this, "acknowledgeShortCode"));
        add_filter('query_vars', array($this, 'query_vars'));
    }

    public function query_vars($query_vars)
    {
        $query_vars[] = 'download-newsletter-subscribers';
        return $query_vars;
    }

    public function acknowledgeShortCode()
    {
        $submitted = false;
        $title = "";
        $firstName = "";
        $lastName = "";
        $html ="";
        if ($_SERVER["REQUEST_METHOD"] === "GET") {
            list($acknowledged, $subscription)=$this->handleAcknowledgement();
            if($acknowledged){
                $html.= '<p class="alert alert-success">Ihre Newsletter-Anmeldung wurde bestätigt.</p>';
                $html .= $this->renderForm($subscription,$title,$firstName,$lastName,$submitted);
            }
            else{
                return '<p class="alert alert-danger">Es ist ein Fehler aufgetreten. Bitte versuchen Sie später noch einmal Ihre Anmeldung zu bestätigen.</p>';
            }

        }
        elseif($_SERVER["REQUEST_METHOD"] === "POST"){
            list($subscription, $saved, $submitted ,$title, $firstName ,$lastName )=$this->handleNameForm();
            if($saved){
                return '<p class="alert alert-success">Vielen Dank, wir haben Ihren Namen gespeichert.</p>';
            }
            else{
                $html.= '<p class="alert alert-danger">Bitte füllen Sie die rot markierten Felder aus.</p>';
                $html .= $this->renderForm($subscription,$title,$firstName,$lastName,$submitted);
            }

        }


        return $html;
    }

    private function handleAcknowledgement()
    {
        global $acknowledgeTokenVarName;
        if(isset($_GET[$acknowledgeTokenVarName])){
            $token = $_GET[$acknowledgeTokenVarName];

            $subscription_id = verify_newsletter_token($token,"acknowledgement");

            if($subscription_id){
                $subscription = get_subscription_by_id($subscription_id);

                $sendAckMail = !$subscription->getAcknowledged();

                $subscription->setAcknowledged(true);
                $subscription->setSubscribed(true);

                update_subscription($subscription);

                if($sendAckMail){
                    $this->sendAcknowledgementMail($subscription);
                }
                return array(true,$subscription);
            }
        }
        return array(false,null);
    }

    private function sendAcknowledgementMail($subscription){

        $unsubscribeLink =$this->generateUnsubscribeLink($subscription);
        $subject ="Willkommen zum Newsletter der Bläserphilharmonie Aachen e.V.";
        $htmlMsg = '<h3>Willkommen zum Newsletter der Bläserphilharmonie Aachen e.V.</h3>
<p>Wir freuen uns Sie über die Konzerte und Projekte der Bläserphilharmonie Aachen e.V. Informieren zudürfen.</p>
<p>Damit Sie unseren Newsletter nie verpassen, können Sie unsere E-Mail Adresse "newsletter@blaeserphilharmonie-aachen.de" in Ihrem Adressbuch abspeichern.</p>


<p>Viele Grüße<br>
Bläserphilharmonie Aachen e.V.<br>
<a href="mailto:vorstand@blaeserphilharmonie-aachen.de">vorstand@blaeserphilharmonie-aachen.de</a> </p>
<p>
<small>Mit dem folgenden Link haben Sie jederzeit die Möglichkeit den Newsletter abzubestellen. <a href="' . $unsubscribeLink . '">Newsletter abmelden</a><br/></small>
<small>Diese E-Mail wurde Automatisch von (https://blaeserphilharmonie-aachen.de) gesendet.</small>
</p>';

        $txtMsg = 'Willkommen zum Newsletter der Bläserphilharmonie Aachen e.V.
        
Wir freuen uns Sie über die Konzerte und Projekte der Bläserphilharmonie Aachen e.V. informieren zu dürfen.
Damit Sie unseren Newsletter nie verpassen, können Sie unsere E-Mail Adresse "newsletter@blaeserphilharmonie-aachen.de" in Ihrem Adressbuch abspeichern.

Viele Grüße
Bläserphilharmonie Aachen e.V.
vorstand@blaeserphilharmonie-aachen.de

--
Mit dem folgenden Link haben Sie jederzeit die Möglichkeit den Newsletter abzubestellen. 
' . $unsubscribeLink . '
Diese E-Mail wurde Automatisch von (https://blaeserphilharmonie-aachen.de) gesendet.';

        return newsletter_send_mail($subscription,$subject,$htmlMsg,$txtMsg);
    }

    private function generateUnsubscribeLink(NewsletterSubscription $subscription ){
        global $unsubscribePageOption;
        global $unsubscribeTokenVarName;

        $unsubscribeToken = generate_newsletter_token($subscription,"unsubscribe");
        $unsubscribePageId = get_option($unsubscribePageOption);
        if($unsubscribePageId){

            $unsubscribePageUrl = get_permalink($unsubscribePageId);
            $unsubscribePageUrl.= strpos($unsubscribePageUrl, '?') !== false ? "&" : "?" ;
            $unsubscribePageUrl.= $unsubscribeTokenVarName ."=" . $unsubscribeToken;
            return $unsubscribePageUrl;
        }

    }

    private function renderForm($subscription, $title, $firstName, $lastName, $submitted){
        return '<p>Damit wir Sie persönlich ansprechen zukönnen haben Sie hier die Möglichkeit Ihren Namen zu hinterlegen.</p>
            <form method="post" action="#newsletter">
                ' . wp_nonce_field($this->actionName, $this->nonceFieldName, true, false) . '
                <input type="hidden" name="token" value="' . generate_newsletter_token($subscription,$this->tokeAction) . '"/> 
                <div class="form-group">
                    <label >Anrede</label>
                    <div class="d-flex flex-row">
                          <div class="form-check form-check-inline">
                              <input class="form-check-input' . ($submitted && !$title ? " is-invalid" : "") . '" type="radio" name="title" id="title-mrs" value="'. NewsletterSubscriptionTitles::MRS. '"' . ( $title == NewsletterSubscriptionTitles::MRS ? " checked" : "") . '>
                              <label class="form-check-label" for="title-mrs">Frau</label>
                        </div>
                        <div class="form-check form-check-inline">
                              <input class="form-check-input' . ($submitted && !$title ? " is-invalid" : "") . '" type="radio" name="title" id="title-mr" value="'. NewsletterSubscriptionTitles::MR. '"' . ( $title == NewsletterSubscriptionTitles::MR ? " checked" : "") . '>
                              <label class="form-check-label" for="title-mr">Herr</label>
                        </div>
                        <div class="form-check form-check-inline">
                              <input class="form-check-input' . ($submitted && !$title ? " is-invalid" : "") . '" type="radio" name="title" id="title-other" value="'. NewsletterSubscriptionTitles::OTHER. '"' . ( $title == NewsletterSubscriptionTitles::OTHER ? " checked" : "") . '>
                              <label class="form-check-label" for="title-other">andere</label>
                        </div>
                    </div>
                    ' . ($submitted && !$title ? "<small class=\"text-danger te\">
                        Bitte wählen Sie eine Anrede aus.
                    </small>" : "") . '
                    
                </div>
                
                 <div class="form-group">
                    <label for="firstname">Vorname</label>
                    <input type="text" class="form-control' . ($submitted && !$firstName ? " is-invalid" : "") . '"
                           id="firstname" name="firstname" value="' . $firstName . '" placeholder="Ihr Vorname"/>
                    <div class="invalid-feedback">
                        Bitte geben Sie Ihren Vornamen an.
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="lastname">Nachname</label>
                    <input type="text" class="form-control' . ($submitted && !$lastName ? " is-invalid" : "") . '"
                           id="lastname" name="lastname" value="' . $lastName . '" placeholder="Ihr Nachname"/>
                    <div class="invalid-feedback">
                        Bitte geben Sie Ihren Vornamen an.
                    </div>
                </div>
                <button type="submit" class="btn btn-outline-dark">Speichern</button>
            </form>';
    }

    private function handleNameForm()
    {
        if (!isset($_POST[$this->nonceFieldName])
            || !wp_verify_nonce($_POST[$this->nonceFieldName], $this->actionName)
        ) {
            print 'Sorry, your nonce did not verify.';
            exit;
        } else {
            // process form data

            $submitted = true;
            $title = "";
            $firstName = "";
            $lastName = "";
            $error = false;
            $subscription =null;

            if (isset($_POST["token"])) {
                $token = $_POST["token"];
                $id = verify_newsletter_token($token, $this->tokeAction);
                if($id){
                    $subscription = get_subscription_by_id($id);
                }
                else{
                    return array(null, false, $submitted ,$title, $firstName ,$lastName );
                }
            }

            if (isset($_POST["title"])) {

                switch ($_POST["title"]){
                    case NewsletterSubscriptionTitles::MR:
                        $title = NewsletterSubscriptionTitles::MR;
                        break;
                    case NewsletterSubscriptionTitles::MRS:
                        $title = NewsletterSubscriptionTitles::MRS;
                        break;
                    case NewsletterSubscriptionTitles::OTHER:
                        $title = NewsletterSubscriptionTitles::OTHER;
                        break;
                    default:
                        $error |= true;
                }
            }
            else{
                $error |= true;
            }


            if (isset($_POST["firstname"])) {
                $firstName = sanitize_text_field( $_POST["firstname"]);
                if(!$firstName){
                    $error |= true;
                }
            }
            else{
                $error |= true;
            }
            if (isset($_POST["lastname"])) {
                $lastName = sanitize_text_field( $_POST["lastname"]);
                if(!$lastName){
                    $error |= true;
                }
            }
            else{
                $error |= true;
            }

            if(!$error){
                $subscription->setTitle($title);
                $subscription->setFirstname($firstName);
                $subscription->setLastname($lastName);

                update_subscription($subscription);
            }


            return array($subscription,!$error, $submitted ,$title, $firstName ,$lastName );
        }


    }
}

$newsletterSubscribeShortCode = new theme\inc\newsletter\NewsletterAcknowledgeShortCode();