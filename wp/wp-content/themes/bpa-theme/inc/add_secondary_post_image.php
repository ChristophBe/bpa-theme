<?php

function add_secondary_post_image($label, $post_type){

    if (class_exists('MultiPostThumbnails')) {

        new MultiPostThumbnails(array(
            'label' => $label,
            'id' => 'secondary-image-' . $post_type,
            'post_type' => $post_type
        ) );

    }
}