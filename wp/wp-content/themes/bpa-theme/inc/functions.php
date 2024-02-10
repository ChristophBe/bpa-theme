<?php

require_once ("inc/add_concert_post_type.php");
require_once ("inc/add_recording_post_type.php");
require_once ("inc/concert-meta_box.php");
require_once ("inc/recording_meta_box.php");
require_once ("inc/locations_admin_page.php");
require_once ("inc/locations_db.php");
require_once("inc/newsletter/NewsletterSubscription.php");
require_once("inc/newsletter/newsletter_subscription_db.php");
require_once("inc/newsletter/newsletter_admin_page.php");
require_once("inc/newsletter/NewsletterSubscriptionExport.php");
require_once("inc/newsletter/NewsletterUnsubscribe.php");
require_once("inc/newsletter/NewsletterSubscribeShortcode.php");
require_once("inc/newsletter/NewsletterAcknowledgeShortcode.php");

require_once ("inc/add_secondary_post_image.php");
require_once ("inc/add_news_letter_widget_area.php");
/**
 * User: Christoph Becker
 * Date: 03/10/18
 * Time: 11:06
 */

function register_nav() {
    register_nav_menus(
        array(
            'main_menu' => __('Hauptmenü'),
            'footer_menu' => __('Fusszeilenmenü')
        )
    );
}

add_action('init', 'register_nav');

add_concert_post_type();
add_recording_post_type();

add_concert_post_meta();
add_recording_post_meta();


function wpb_sender_email( $original_email_address ) {
    return 'no-reply@blaeserphilhamonie-aachen.de';
}

// Function to change sender name
function wpb_sender_name( $original_email_from ) {
    return 'Bläserphilharmonie Aachen e.V.';
}

// Hooking up our functions to WordPress filters
add_filter( 'wp_mail_from', 'wpb_sender_email' );
add_filter( 'wp_mail_from_name', 'wpb_sender_name' );


register_activation_hook( __FILE__, 'location_db_init' );
add_action("after_switch_theme", "location_db_init", 10 ,  2);

register_activation_hook( __FILE__, 'newsletter_subscription_db_init' );
add_action("after_switch_theme", "newsletter_subscription_db_init", 10 ,  2);


function add_query_vars_filter( $vars ){
    $vars[] = "tag";
    return $vars;
}
add_filter( 'query_vars', 'add_query_vars_filter' );

add_action( 'init', 'add_tag_rewrite_endpoint' );

function add_tag_rewrite_endpoint() {
    add_rewrite_endpoint( 'tag', EP_ALL, $query_vars = true );
}


add_theme_support('post-thumbnails', array( 'page', 'concert','recording'));
add_theme_support('featured-image', array('post', 'page','concert', 'recording'));
add_theme_support( 'custom-header'/*, $args*/);

add_secondary_post_image("Konzert Poster", "concert");
add_secondary_post_image("Beitragsbild", "post");


add_action( 'widgets_init', 'add_news_letter_widget_area' );
