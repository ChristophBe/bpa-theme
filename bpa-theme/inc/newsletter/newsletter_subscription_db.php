<?php


global $newsletter_subscription_db_version;
$newsletter_subscription_db_version = '1.1';
global $newsletter_subscription_table_name;
$newsletter_subscription_table_name = "newsletter_subscriptions";
function newsletter_subscription_db_init() {
    global $wpdb;
    global $newsletter_subscription_db_version;
    global $newsletter_subscription_table_name;

    $table_name = $wpdb->prefix . $newsletter_subscription_table_name;

    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE $table_name (
		    id INT PRIMARY KEY AUTO_INCREMENT,
            email varchar(500) unique not null,
            subscribed BOOL DEFAULT 0 not null,
            acknowledged BOOL DEFAULT 0 not null,
            title ENUM('mr','mrs','other'),
            firstname TINYTEXT,
            lastname TINYTEXT,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
	) $charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta( $sql );

    add_option( 'newsletter_subscription_db_version ', $newsletter_subscription_db_version);
}


function subscriptionToArray($subscription){
    if($subscription instanceof NewsletterSubscription){
        return array(
            'email' => $subscription->getEmail(),
            'subscribed' => $subscription->getSubscribed(),
            'acknowledged' => $subscription->getAcknowledged(),
            'firstname' => $subscription->getFirstname(),
            'lastname' => $subscription->getLastname(),
            'title' => $subscription->getTitle(),
        );
    }
}
function add_subscription($subscription) {
    global $wpdb;
    global $newsletter_subscription_table_name;

    $table_name = $wpdb->prefix . $newsletter_subscription_table_name;

    if($subscription instanceof NewsletterSubscription){
        $wpdb->insert(
            $table_name,
            subscriptionToArray($subscription)
        );
        return $wpdb->insert_id;
    }
}

function update_subscription($subscription){
    global $wpdb;
    global $newsletter_subscription_table_name;

    $table_name = $wpdb->prefix . $newsletter_subscription_table_name;

    if ($subscription instanceof NewsletterSubscription) {
        $wpdb->update(
            $table_name,
            subscriptionToArray($subscription),
            array('id' => $subscription->getId())
        );
    }
}
function get_all_subscriptions() {
    global $wpdb;
    global $newsletter_subscription_table_name;

    $table_name = $wpdb->prefix . $newsletter_subscription_table_name;
    $query = "SELECT * FROM ".$table_name;
    $rows = $wpdb->get_results( $query );
    return parse_subscriptions_form_rows($rows);

}
function get_active_subscriptions() {
    global $wpdb;
    global $newsletter_subscription_table_name;

    $table_name = $wpdb->prefix . $newsletter_subscription_table_name;
    $query = "SELECT * FROM ".$table_name ." WHERE acknowledged = 1 AND subscribed = 1";
    $rows = $wpdb->get_results( $query );
    return parse_subscriptions_form_rows($rows);

}


function get_subscription_by_id($id){
    global $wpdb;

    global $newsletter_subscription_table_name;

    $table_name = $wpdb->prefix . $newsletter_subscription_table_name;
    $query = $wpdb->prepare("SELECT * FROM ".$table_name . " where id=%d",array($id));

    $rows = $wpdb->get_results( $query);
    if(sizeof($rows)>0){
        return parse_subscription_form_row($rows[0]);
    }
}


function get_subscription_by_email($email){
    global $wpdb;

    global $newsletter_subscription_table_name;
    $table_name = $wpdb->prefix . $newsletter_subscription_table_name;
    $query = $wpdb->prepare("SELECT * FROM ".$table_name . " where email=%s",array($email));

    $rows = $wpdb->get_results( $query);
    if(sizeof($rows)>0){
        return parse_subscription_form_row($rows[0]);
    }
}

/**
 * @param $row
 * @return NewsletterSubscription
 */
function parse_subscription_form_row($row)
{
    $subscriptions = new NewsletterSubscription();
    $subscriptions->setId($row->id);
    $subscriptions->setEmail($row->email);
    $subscriptions->setSubscribed($row->subscribed);
    $subscriptions->setAcknowledged($row->acknowledged);
    $subscriptions->setFirstname($row->firstname);
    $subscriptions->setLastname($row->lastname);
    $subscriptions->setTitle($row->title);
    return $subscriptions;
}

/**
 * @param array $rows
 * @return array
 */
function parse_subscriptions_form_rows(array $rows)
{
    $subscriptions = array();
    foreach ($rows as $row) {
        $subscription = parse_subscription_form_row($row);
        array_push($subscriptions, $subscription);
    }
    return $subscriptions;
}