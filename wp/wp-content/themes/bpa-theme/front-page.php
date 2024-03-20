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
<?php
get_footer();