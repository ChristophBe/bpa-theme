<?php


function add_news_letter_widget_area(){

    register_sidebar( array(
        'name'          => 'Newsletter Anmeldung',
        'id'            => 'newsletter_widget_area',
        'before_widget' => '<div class="row"><div class="col-12">',
        'after_widget'  => '</div></div>',
        'before_title'  => '<h3 class="h2">',
        'after_title'   => '</h3>',
    ) );
}