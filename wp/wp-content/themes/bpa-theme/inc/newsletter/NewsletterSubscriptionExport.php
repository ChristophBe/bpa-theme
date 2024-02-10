<?php

global $unsubscribeTokenVarName;
$unsubscribeTokenVarName ="unsubscribe-newsletter-token";

class NewsletterSubscriptionExport
{
    /**
     * Constructor
     */
    public function __construct()
    {


// Add extra menu items for admins
        add_action('init', array($this, 'export'));

// Create end-points
        add_filter('query_vars', array($this, 'query_vars'));
    }

    public function export(){
        if (isset($_GET['download-newsletter-subscribers'])) {
            if ( !current_user_can( 'manage_options' ) )  {
                wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
            }

            $csv = $this->generate_csv();

            $filename= "bpa_newsletter_subscriptions_" . time() .".csv";

            header("Pragma: public");
            header("Expires: 0");
            header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
            header("Cache-Control: private", false);
            header("Content-Type: application/octet-stream");
            header("Content-Disposition: attachment; filename=\"$filename\";");
            header("Content-Transfer-Encoding: binary");

            echo $csv;
            exit;
        }
    }

    /**
     * Allow for custom query variables
     */
    public function query_vars($query_vars)
    {
        $query_vars[] = 'download-newsletter-subscribers';
        return $query_vars;
    }






    /**
     * Converting data to CSV
     */
    public function generate_csv()
    {
        $csv_output = '';
        $subscriptions = get_active_subscriptions();

        global $unsubscribePageOption;
        global $unsubscribeTokenVarName;
        $unsubscribePageUrl = "";
        $unsubscribePageId = get_option($unsubscribePageOption);
        if($unsubscribePageId){

            $page_url = get_permalink($unsubscribePageId);

            $unsubscribePageUrl = $page_url;
            $unsubscribePageUrl.= strpos($page_url, '?') !== false ? "&" : "?" ;
            $unsubscribePageUrl.= $unsubscribeTokenVarName ."=";
        }

        foreach ( $subscriptions as $subscription ) {
            if($subscription instanceof NewsletterSubscription){

                $translateTitles = array(
                    ""=>"",
                    "mr"=>"Herr",
                    "mrs"=>"Frau",
                    "other"=>""
                );
                $data_row = array();
                $data_row[] = $subscription->getEmail();
                $data_row[] = $translateTitles[$subscription->getTitle()];
                $data_row[] = $subscription->getFirstname();
                $data_row[] = $subscription->getLastname();
                $data_row[] = $unsubscribePageUrl. urlencode($this->generateUnsubscribeToken($subscription));
                $csv_output.= implode(",", $data_row);
                $csv_output.= "\n";
            }
        }

        return $csv_output;
    }

    private function generateUnsubscribeToken(NewsletterSubscription $subscription)
    {
        return generate_newsletter_token($subscription,"unsubscribe");
    }
}

// Instantiate a singleton of this plugin
$newsletterSubscriptionExport = new theme\inc\newsletter\newsletter\NewsletterSubscriptionExport();
