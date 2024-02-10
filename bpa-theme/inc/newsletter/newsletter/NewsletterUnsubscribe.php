<?php


class NewsletterUnsubscribe
{

    public function __construct()
    {
        add_shortcode('unsubscribe-newsletter', array($this,"unsubscribeShortCode"));

        add_filter('query_vars', array($this, 'query_vars'));

    }
    public function query_vars($query_vars)
    {
        global $unsubscribeTokenVarName;
        $query_vars[] = $unsubscribeTokenVarName;
        return $query_vars;
    }
    public function unsubscribeShortCode(){
        $acknowledgeUnsubscribe = "acknowledge-unsubscribe";
        global $unsubscribeTokenVarName;
        if($_SERVER["REQUEST_METHOD"] === "POST"){
            $token = $_POST["unsubscribe-token"];
            $subscription_id = verify_newsletter_token($token,$acknowledgeUnsubscribe);
            if ($subscription_id) {
                $subscription = get_subscription_by_id($subscription_id);
                if ($subscription) {
                    $subscription->setSubscribed(false);
                    update_subscription($subscription);
                    return "<p class='alert alert-success'>Wir haben Sie erfolgreich von unserem Newsletter abgemeldet.</p>"
                        . '<p>Sie haben jederzeit die Möglichkeit, sich erneut zu unserem Newsletter anzumelden.</p>';
                }
            }

        }
        else{
            if (isset($_GET[$unsubscribeTokenVarName])) {

                $token = $_GET[$unsubscribeTokenVarName];
                $subscription_id = $this->verifyToken($token);
                if ($subscription_id) {
                    $subscription = get_subscription_by_id($subscription_id);
                    if ($subscription) {
                        return '<form method="post">'
                                . '<input type="hidden" name="unsubscribe-token" value="' . generate_newsletter_token($subscription, $acknowledgeUnsubscribe) . '">'
                                . '<p class="alert alert-light"> Sie haben jederzeit die Möglichkeit, sich erneut zu unserem Newsletter anzumelden.</p>'
                                . '<p>Wollen Sie wirklich den Newsletter der Bläserphilharmonie Aachen e.V. abbestellen?<p>'
                                . '<div class="btn-group">'
                                    . '<button class="btn btn-dark">Ja, ich möchte den Newsletter abbstellen.</button>'
                                    . '<a href="/" class="btn btn btn-secondary">Nein, ich möchte den Newsletter nicht abbstellen.</a>'
                                . '</div>'
                            . '</form>';

                    }
                }
            }
        }
        return "<p class='alert alert-danger'>Es ist ein Fehler beim abmelden von Newsletter aufgetreten. Bitte versuchen sie es später noch einmal.</p>";
    }

    private function verifyToken($token){
        return verify_newsletter_token($token,"unsubscribe");
    }
}
$newsletterUnsubscribe = new NewsletterUnsubscribe();