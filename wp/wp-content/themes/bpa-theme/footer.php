<?php
require_once ("inc/the_concert_row.php");
require_once ("inc/the_recording_item.php");
require_once ("inc/page_elements.php");
if(!is_post_type_archive("post")  && get_post_type() !="post" && !is_home()):?>
<section class="news">
    <div class="container">
        <div class="row">
            <div class="col">
                <h3 class="h1">Neuigkeiten</h3>
            </div>
        </div>
        <div class="row news-row"></div>
        <div class="row news-items">
            <?php $args = array(
                'posts_per_page'   => -1,
                'offset'           => 0,
                'orderby'          => 'date',
                'order'            => 'DESC',
                'post_type'        => 'post',
                'post_status'      => 'publish',
                'suppress_filters' => true,
                'fields'           => '',
            );
            $posts_array = get_posts( $args );

            foreach ($posts_array as $post): setup_postdata($post);?>
                <div class="col-md-6 col-lg-4">
                    <div class="content-item">

                        <div class="post-meta"><i><?php the_date() ?></i></div>
                        <h3 class="h3">
                            <a href="<?= get_the_permalink() ?>"><?php the_title() ?></a>
                        </h3>


                        <?php


                        $post_type = $post->post_type;
                        $image_id  = 'secondary-image-' . $post_type;
                        if (class_exists('MultiPostThumbnails') && MultiPostThumbnails::has_post_thumbnail($post_type, $image_id, $post->ID)) : ?>
                            <div class="post-thumbnail">
                                <a href="<?= get_the_permalink() ?>"><img loading="lazy" src="<?= MultiPostThumbnails::get_post_thumbnail_url($post_type, $image_id, $post->ID, array(300,200)) ?>" alt="<?php the_title() ?>"></a>
                            </div>
                        <?php endif; ?>
                        <?php the_content("Weiterlesen...",true) ?>
                    </div>
                </div>

            <?php endforeach; ?>


        </div>
        <div class="row">
            <div class="col">

                <a href="<?=get_post_type_archive_link( "post" );?>" class="btn btn-outline-dark">mehr Neuigkeiten</a>

            </div>
        </div>
    </div>
</section>
<?php endif; ?>

<?php if(!is_front_page() || is_post_type_archive("concert")){
   the_concert_row();
}

the_recording_row();

if (is_front_page()){
    the_newletter_row();
}
?>

<footer>
    <div class="container">
        <div class="row">
            <div class="col 12">
                <h3><?= get_theme_mod("footer_follow_us", "") ?></h3>
                <nav id="social-media-nav" class="nav">
                    <ul>
                        <li><a href="https://www.facebook.com/blaeserphilharmonie" target="_blank"><i class="fab fa-facebook-square hidden-xs hidden-sm"></i>Facebook</a></li>
                        <li><a href="https://www.youtube.com/channel/UCmAv_4pM-klpeeijncjA3rw" target="_blank"><i class="fab fa-youtube hidden-xs hidden-sm"></i>Youtube</a></li>
                        <li><a href="https://www.instagram.com/blaeserphilharmonie_aachen/" target="_blank"><i class="fab fa-instagram hidden-xs hidden-sm"></i>Instagram</a></li>
                        <li><a href="https://open.spotify.com/artist/0IpPjWBTar1hey5IOyKoXX" target="_blank"><i class="fab fa-spotify hidden-xs hidden-sm"></i>Spotify</a></li>
                    </ul>
                </nav>
            </div>
        </div>
        <div class="row">

            <div class="col-12">
                <h3><?= get_theme_mod("footer_supports_us", "") ?></h3>
                <div class="support-items">

                    <div class="support-item" >
                        <img loading="lazy" src="https://blaeserphilharmonie-aachen.de/wp-content/uploads/2019/07/STAWAG-Logo-weiß-auf-orange_190225.png" alt="Stadtwerke Aachen"/>
                    </div>

                    <div class="support-item" >
                        <img loading="lazy" src="https://blaeserphilharmonie-aachen.de/wp-content/uploads/2022/03/logos_zusatz_mit_unterstuetzung_der_schwarz.png" alt="Stadt Aachen"/>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <nav id="footer-nav" class="nav">

                    <?php
                    $main_nav_args = array(
                        'theme_location' => 'footer_menu',
                        'container'=> 'ul',
                        'menu_class'=> "footer-nav--items",
                    );
                    wp_nav_menu($main_nav_args);
                    ?>
                </nav>
            </div>
            <div class="col-md-4 col-sm-12">

            </div>
        </div>
    </div>
    <?php
        the_copyright_row();
    ?>
</footer>


<?php

the_scripts();
wp_footer();
?>
</body>
</html>
