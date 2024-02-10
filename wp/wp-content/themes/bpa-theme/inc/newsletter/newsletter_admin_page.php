<?php

require_once("newsletter_subscription_db.php");
require_once("NewsletterSubscription.php");

global $unsubscribePageOption;
global $acknowledgePageOption;
global $privacyPolicyLink;
global $recaptcha_site_key_option;
global $recaptcha_secret_option;
$unsubscribePageOption = "unsubscribePageOption";
$acknowledgePageOption = "acknowledgePageOption";
$privacyPolicyLink = "privacyPolicyLink";
$recaptcha_site_key_option = "recaptcha_site_key_option";
$recaptcha_secret_option = "recaptcha_secret_option";

/** Step 2 (from text above). */
add_action( 'admin_menu', 'my_plugin_newsletter_menu' );

/** Step 1. */
function my_plugin_newsletter_menu() {
    add_menu_page( 'Newsletter', 'Newsletter', 'manage_options', 'newsletter_subscriptions', 'my_plugin_newsletter_options','');
}

/** Step 3. */
function my_plugin_newsletter_options() {
    if ( !current_user_can( 'manage_options' ) )  {
        wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
    }
    $action = isset($_GET['action']) ? $_GET['action']:'';
    $base_locations_url = admin_url( 'admin.php?page=newsletter_subscriptions');
    global $wpdb;
    global $newsletter_subscription_table_name


    ?>
    <div class="wrap">

        <?php
        switch ($action) {

            case "unsubscribe":

                $nonce_name   = $_GET['newsletter_subscription_nonce_unsubscribe'];
                $nonce_action = 'newsletter_subscription_unsubscribe';

                // Check if a nonce is set.
                if (  isset( $nonce_name ) &&  wp_verify_nonce( $nonce_name, $nonce_action ) ){
                    if (isset($_GET['subscription-id'])){
                        $id = $_GET['subscription-id'];
                        $subscription = get_subscription_by_id($id);
                        $subscription->setSubscribed(false);
                        update_subscription($subscription);
                    }
                }

                $orderedAsc = isset($_GET['order']) && $_GET['order'] =="asc";
                show_subscription_list($base_locations_url , $orderedAsc);
                break;

            case "trash":
                $nonce_name   = $_GET['newsletter_subscription_nonce_trash'];
                $nonce_action = 'newsletter_subscription_trash';

                // Check if a nonce is set.
                if (  isset( $nonce_name ) &&  wp_verify_nonce( $nonce_name, $nonce_action ) ){
                    if (isset($_GET['subscription-id'])){
                        $id = $_GET['subscription-id'];
                        $wpdb->delete($wpdb->prefix . $newsletter_subscription_table_name, array('id'=> $id));
                    }
                }

            default:
                $orderedAsc = isset($_GET['order']) && $_GET['order'] =="asc";
                show_subscription_list($base_locations_url , $orderedAsc);
        }
        ?>

    <?php
}

function show_subscription_list($base_url, $ordered_asc){
    $subscriptions = get_all_subscriptions();
    ?>
    <h1 class="wp-heading-inline">Newsletter Abonenten</h1>

    <a href="<?= $base_url . '&download-newsletter-subscribers=1' ?>" class="page-title-action">Export aktuelle Abonenten</a>
    <hr class="wp-header-end">
    <?php
    selectUnsubscribePage();
    ?>

    <h2 class='screen-reader-text'>Beitragsliste</h2>


    <table class="wp-list-table widefat fixed striped posts">
        <thead>
        <tr>
            <th scope="col" id='title' class='manage-column column-title column-primary sortable <?=( $ordered_asc ?  'desc' : 'asc')?>'>
                <a href="<?= $base_url . '&order=' .( $ordered_asc ?  'desc' : 'asc') ?>">
                    <span>E-Mail</span><span class="sorting-indicator"></span>
                </a>
            </th>
            <th scope="col" id='title' class='manage-column column-title column-primary sortable <?=( $ordered_asc ?  'desc' : 'asc')?>'>
                <a href="<?= $base_url . '&order=' .( $ordered_asc ?  'desc' : 'asc') ?>">
                    <span>Status</span><span class="sorting-indicator"></span>
                </a>
            </th>
        </tr>
        </thead>

        <tbody id="the-list">

        <?php
        foreach ($subscriptions as $subscription){
            if($subscription instanceof NewsletterSubscription){
                $unsubscribe_link = wp_nonce_url( $base_url . '&action=unsubscribe&subscription-id=' . $subscription->getId(), "newsletter_subscription_unsubscribe", "newsletter_subscription_nonce_unsubscribe");
                $trash_link = wp_nonce_url( $base_url . '&action=trash&subscription-id=' . $subscription->getId(), "newsletter_subscription_trash", "newsletter_subscription_nonce_trash");
                ?>

                <tr id="locations-<?= $subscription->getId() ?>" class="column-title has-row-actions iedit author-self level-0 locations-<?= $subscription->getId() ?> type-concert status-publish has-post-thumbnail hentry">
                    <td data-colname="Titel">
                        <strong>
                               <?=$subscription->getEmail()?>
                        </strong>
                        <div class="row-actions">
                            <span class='unsubscribe'><a href="<?= $unsubscribe_link ?>" aria-label="<?=$subscription->getEmail()?> abo beebden">Abo Beenden</a> | </span>
                            <span class='trash'><a href="<?= $trash_link ?>" class="submitdelete" aria-label="<?=$subscription->getEmail()?> unwiederuflich Löschen">unwiederuflich Löschen</a></span>
                        </div>
                    </td><td class="" data-colname="Titel">
                        <?php
                            if ($subscription->getSubscribed() && $subscription->getAcknowledged() ){
                                echo "aboniert";
                            }
                            else if (!$subscription->getSubscribed() && $subscription->getAcknowledged()){
                                echo "Abo beendet";
                            }
                            else{
                                echo "unbestätigt";
                            }
                            ?>

                    </td>

                </tr>

            <?php }} ?>
        </tbody>

        <tfoot>
        <tr>
            <td colspan="2">#<?=sizeof($subscriptions)?></td>
        </tr>
        </tfoot>

    </table>
    </div>

    <?php
}


function selectUnsubscribePage(){
    $pages = get_pages();

    $nonce_name   = $_POST['unsubscribe-page-nonce'] ?? "";
    $nonce_action = 'unsubscribe-page-action';
    global $unsubscribePageOption;
    global $acknowledgePageOption;
    global $privacyPolicyLink;
    global $recaptcha_site_key_option;
    global $recaptcha_secret_option;
    // Check if a nonce is set.
    if (  isset( $nonce_name ) &&  wp_verify_nonce( $nonce_name, $nonce_action ) ){
        if (isset($_POST['unsubscribe-page'])){
            $unsubscribePageId = (int)($_POST['unsubscribe-page']);
            update_option($unsubscribePageOption,$unsubscribePageId);
        }
        if (isset($_POST['acknowledgement-page'])){
            $acknowledgePageId = (int)($_POST['acknowledgement-page']);
            update_option($acknowledgePageOption,$acknowledgePageId);
        }
        if (isset($_POST['privacy-policy-link'])){
            update_option($privacyPolicyLink,$_POST['privacy-policy-link']);
        }
        if (isset($_POST['recaptcha-site-key'])){
            update_option($recaptcha_site_key_option,$_POST['recaptcha-site-key']);
        }
        if (isset($_POST['recaptcha-secret'])){
            update_option($recaptcha_secret_option,$_POST['recaptcha-secret']);
        }
    }

    $unsubscribePageId = get_option($unsubscribePageOption);
    $acknowledgePageId = get_option($acknowledgePageOption);
    $recaptcha_site_key = get_option($recaptcha_site_key_option);
    $privacy_policy_link = get_option($privacyPolicyLink)

    ?>
     <form  method="post">

        <?php
        wp_nonce_field( 'unsubscribe-page-action', 'unsubscribe-page-nonce' );
        ?>


        <table class="form-table">

            <tr class="new-event-location">
                <th>
                    <label for="unsubscribe-page" class="price_label"><?= __( 'Newsletter abmelden Seite', 'text_domain' ) ?></label>
                </th>
                <td>
                    <select name="unsubscribe-page" id="unsubscribe-page">
                        <?php
                        foreach( $pages as $page){
                            $selected = $page->ID == $unsubscribePageId ?" selected":"";
                            echo '<option value="' . $page->ID . '"' .$selected .'>' . $page->post_title.'</option>';
                        }
                        ?>
                        
                    </select>
                </td>
            </tr>
            <tr class="new-event-location">
                <th>
                    <label for="acknowledgement-page" class="price_label"><?= __( 'Newsletter bestätigen Seite', 'text_domain' ) ?></label>
                </th>
                <td>
                    <select name="acknowledgement-page" id="acknowledgement-page">
                        <?php
                        foreach( $pages as $page){
                            $selected = $page->ID == $acknowledgePageId ?" selected":"";
                            echo '<option value="' . $page->ID . '"' .$selected .'>' . $page->post_title.'</option>';
                        }
                        ?>

                    </select>
                </td>
            </tr>
            <tr class="new-event-location">
                <th>
                    <label for="privacy-policy-link" class="price_label"><?= __( 'Link zur Datenschutzerklärung', 'text_domain' ) ?></label>
                </th>
                <td>
                    <input name="privacy-policy-link" id="privacy-policy-link" type="text" value="<?=$privacy_policy_link?>">
                </td>
            </tr>
            <tr class="new-event-location">
                <th>
                    <label for="recaptcha-site-key" class="price_label"><?= __( 'reCAPTCHA Site Key', 'text_domain' ) ?></label>
                </th>
                <td>
                    <input name="recaptcha-site-key" id="privacy-policy-link" type="text" value="<?=$recaptcha_site_key?>">
                </td>
            </tr>
            <tr class="new-event-location">
                <th>
                    <label for="recaptcha-secret" class="price_label"><?= __( 'reCAPTCHA Secret', 'text_domain' ) ?></label>
                </th>
                <td>
                    <input name="recaptcha-secret" id="privacy-policy-link" type="text">
                </td>
            </tr>

        </table>
        <p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary" value="Speichern"></p>
    </form>
    <?php
}

?>