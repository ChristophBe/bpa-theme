<?php
/**
 * Created by IntelliJ IDEA.
 * User: christoph
 * Date: 03/10/18
 * Time: 14:14
 */

function add_recording_post_type(){


// Register Custom Post Type
    function recording_post_type() {

        $labels = array(
            'name'                  => _x( 'Aufnahme', 'Post Type General Name', 'Aufnahme' ),
            'singular_name'         => _x( 'Aufnahmen', 'Post Type Singular Name', 'Aufnahmen' ),
            'menu_name'             => __( 'Aufnahme', 'Aufnahme' ),
            'name_admin_bar'        => __( 'Aufnahme', 'Aufnahme' ),

        );
        $args = array(
            'label'                 => __( 'Aufnahme', 'Aufnahme' ),
            'description'           => __( 'PostType für Aufnahme', 'Aufnahme' ),
            'labels'                => $labels,
            'supports'              => array( 'title','thumbnail', 'revisions' ),
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

            'rewrite'   => array('slug' => "mitschnitte")
        );
        register_post_type( 'recording', $args );

    }
    add_action( 'init', 'recording_post_type', 0 );


}
