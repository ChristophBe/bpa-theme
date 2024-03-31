<?php
get_header();
the_post();
?>
    <section class="content">
        <div class="container">

            <?php
            $post_type = $post->post_type;
            $image_id  = 'secondary-image-' . $post_type;


            ?>
            <div class="row post-row">
                <div class="<?=class_exists('MultiPostThumbnails') && MultiPostThumbnails::has_post_thumbnail($post_type, $image_id, $post->ID) ?"col-md-8":"col-md-12"?> col-sm-12 post-content">

                    <div class="content-item">
                        <?php the_content() ?>
                    </div>

                </div>

                <?php if (class_exists('MultiPostThumbnails') && MultiPostThumbnails::has_post_thumbnail($post_type, $image_id, $post->ID)) : ?>
                    <div class="col-md-4 col-sm-12 hidden-sm post-thumbnail">
                        <img loading="lazy" src="<?= MultiPostThumbnails::get_post_thumbnail_url($post_type, $image_id, $post->ID, array(300,200)) ?>" alt="<?php the_title() ?>">
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>

<?php
get_footer();
?>