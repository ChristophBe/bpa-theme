<?php
require_once ("inc/the_recording_item.php");
get_header();
global $post;
the_post();
$recording_composer = get_post_meta( $post->ID, 'recording_composer', true );
$recording_youtube_id = get_post_meta( $post->ID, 'recording_youtube_id', true );
$recording_date = get_post_meta( $post->ID, 'recording_date', true );

?>
<section class="content" id="player">

    <div class="container">
        <div class="row">
            <div class="col-12">
                <h2 class="h1">
                    <?php the_title()?>
                </h2>
                <h3 class="h3">
                    <?=$recording_composer?>
                </h3>
            </div>
            <div class="col-12">
                <div class="recording-player">
                    <iframe  src="https://www.youtube-nocookie.com/embed/<?=$recording_youtube_id?>?autoplay=1&rel=0&amp;controls=1" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>
                </div>

            </div>
        </div>
        <div class="row mt-5">
            <div class="col-12">

                <h3 class="h1">Weitere Konzertmittschnitte</h3>
            </div>

            <?php
            $args = array(
                'posts_per_page'   => 3,
                'offset'           => 0,
                'orderby'          => 'rand',
                'post__not_in'     => array($post->ID),
                'post_type'        => 'recording',
                'post_status'      => 'publish',
                'suppress_filters' => true,
                'fields'           => '',
            );
            $posts_array = get_posts( $args );

            foreach ($posts_array as $post): setup_postdata($post);?>

            <div class="col-md-4 col-sm-6 col-xs-12">
                <?php  the_recording_item() ?>
            </div>
            <? endforeach;?>

            <div class="col-12 mt-4">
                <a href="<?=get_post_type_archive_link("recording")?>" class="btn btn-outline-dark">Alle Konzertmitschnitte</a>
            </div>
        </div>
        <?php

        ?>
    </div>
</section>

<?php
get_footer();