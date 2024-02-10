<?php
/**
 * Created by IntelliJ IDEA.
 * User: christoph
 * Date: 03/10/18
 * Time: 14:14
 */

function add_landing_page_post_type(){


// Register Custom Post Type
    function landing_page_post_type() {

        $labels = array(
            'name'                  => _x( 'Landing-Pages', 'Post Type General Name', 'Landing-Pages' ),
            'singular_name'         => _x( 'Landing-Page', 'Post Type Singular Name', 'Landing-Page' ),
            'menu_name'             => __( 'Landing-Pages', 'Landing-Page' ),
            'name_admin_bar'        => __( 'Landing-Pages', 'Landing-Page' ),

        );
        $args = array(
            'label'                 => __( 'Landing-Page', 'Landing-Page' ),
            'description'           => __( 'PostType für Landing-Page', 'Landing-Page' ),
            'labels'                => $labels,
            'supports'              => array( 'thumbnail', 'editor', 'title', 'revisions' ),
            'hierarchical'          => false,
            'public'                => true,
            'show_ui'               => true,
            'show_in_menu'          => true,
            'menu_position'         => 5,
            'show_in_admin_bar'     => true,
            'show_in_nav_menus'     => true,
            'can_export'            => true,
            'has_archive'           => false,
            'exclude_from_search'   => true,
            'publicly_queryable'    => true,
            'capability_type'       => 'page',
            'show_in_rest' => true,

            'rewrite'   => array('slug' => "l")
        );
        register_post_type( 'landing-page', $args );

    }
    add_action( 'init', 'landing_page_post_type', 0 );


}
