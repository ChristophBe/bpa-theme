<?php
function get_archive_post_type() {
    $post_type = false;

    global $wp_query;
    if( isset($wp_query->query['post_type']) ){
        $post_type = $wp_query->query['post_type'];
    }

    return $post_type;
}