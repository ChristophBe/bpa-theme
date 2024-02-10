<?php
require_once ("inc/is_sidebar_visible.php");
require_once ("inc/the_concert_row.php");
require_once("inc/page_elements.php");


global $post;
the_html_header()
?>

<body>
<header<?= is_front_page() ? ' class="front-page-header"':'' ?> style="background:url(<?= !is_archive() && has_post_thumbnail() && $post->post_type!= "recording" ? get_the_post_thumbnail_url($post) : header_image()?>) center; background-size: cover;">
    <div class="container">
        <div class="row header-row">
            <?php the_logo(); ?>
            <?php if (get_post_type() !="landing-page") : ?>
            <div id="nav" class="nav">
                <div class="nav-button">
                    <div class="bar"></div>
                    <div class="bar"></div>
                    <div class="bar"></div>
                </div>
                <nav>
                    <?php $main_nav_args = array(
                        'theme_location' => 'main_menu',
                        'container'=> 'ul',
                        'menu_class'=> "nav ml-1",
                    );
                    wp_nav_menu($main_nav_args);
                    ?>
                </nav>

            </div>
            <?php endif; ?>
        </div>
    </div>


    <?php
    if(is_front_page()) {

        the_concert_row("concerts-home");
    }
    ?>
</header>
