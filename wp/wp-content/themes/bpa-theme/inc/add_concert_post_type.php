<?php
/**
 * Created by IntelliJ IDEA.
 * User: christoph
 * Date: 03/10/18
 * Time: 14:14
 */

require_once "Location.php";
function add_concert_post_type(){


// Register Custom Post Type
    function concert_post_type() {

        $labels = array(
            'name'                  => _x( 'Konzerte', 'Post Type General Name', 'Konzert' ),
            'singular_name'         => _x( 'Konzert', 'Post Type Singular Name', 'Konzert' ),
            'menu_name'             => __( 'Konzerte', 'Konzert' ),
            'name_admin_bar'        => __( 'Konzert', 'Konzert' ),

        );
        $args = array(
            'label'                 => __( 'Konzert', 'Konzert' ),
            'description'           => __( 'PostType für Konzerte', 'Konzert' ),
            'labels'                => $labels,
            'supports'              => array( 'title', 'editor', 'thumbnail', 'revisions' ),
            'hierarchical'          => false,
            'public'                => true,
            'show_ui'               => true,
            'show_in_menu'          => true,
            'menu_position'         => 5,
            'show_in_admin_bar'     => true,
            'show_in_nav_menus'     => true,
            'can_export'            => true,
            'has_archive'           => true,
            'exclude_from_search'   => false,
            'publicly_queryable'    => true,
            'capability_type'       => 'page',

            'rewrite'   => array('slug' => "konzerte")
        );
        register_post_type( 'concert', $args );

    }
    add_action( 'init', 'concert_post_type', 0 );


}


function get_concert_location($concert){
    if(!$concert instanceof WP_Post || !$concert->post_type = 'concert'){
        return;
    }
    $concert_location_id = get_post_meta($concert->ID, "concert_location_id", true);
    return get_location_by_id($concert_location_id);
}


function get_concert_time($concert){
    if(!$concert instanceof WP_Post || !$concert->post_type = 'concert'){
        return null;
    }
    return get_post_meta($concert->ID, "concert_datetimes", true);
}