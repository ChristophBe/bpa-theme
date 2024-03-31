<?php
get_header();
the_concert_row();


?>
<section class="content">
    <div class="container">
        <div class="row">
            <div class="col">
                <h2 class="h1"><?php the_title() ?></h2>
                <?php the_content() ?>
            </div>
        </div>
    </div>

</section>
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
                    <?php
                    $post_type = $post->post_type;
                    $image_id  = 'secondary-image-' . $post_type;
                    ?>
                    <div class="col-md-6 col-lg-4">
                        <div class="content-item">

                            <a href="<?= get_the_permalink() ?>" class="post-header" >
                                <div class="post-image" style="background: url(<?= MultiPostThumbnails::get_post_thumbnail_url($post_type, $image_id, $post->ID, array(300,200)) ?>) center; background-size: cover;">

                                    <h2 class="post-title h2">
                                        <?php the_title() ?>
                                    </h2>
                                    <div class="post-date">
                                        <?php the_date() ?>
                                    </div>
                                </div>
                            </a>

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
<?php
the_recording_row();
get_footer();