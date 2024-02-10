<?php
get_header();
?>
<section class="content">
    <div class="container">
        <div class="row">
            <h2 class="col h1">Neuigkeiten</h2>
        </div>
        <?php


        global $post;
        while(have_posts()):
            the_post();

            $post_type = $post->post_type;
            $image_id  = 'secondary-image-' . $post_type;


            ?>
            <div class="row post-row">
                <div class="<?=class_exists('MultiPostThumbnails') && MultiPostThumbnails::has_post_thumbnail($post_type, $image_id, $post->ID) ?"col-md-8":"col-md-12"?> col-sm-12 post-content">

                    <div class="content-item">
                        <h2 class="h2">
                            <a href="<?=get_the_permalink()?>"><?php the_title() ?></a>

                        </h2>
                        <div class="post-meta"><i><?php the_date() ?></i></div>
                        <?php the_content("Weiterlesen...",true) ?>
                    </div>

                </div>

                <?php if (class_exists('MultiPostThumbnails') && MultiPostThumbnails::has_post_thumbnail($post_type, $image_id, $post->ID)) : ?>
                    <div class="col-md-4 col-sm-12 post-thumbnail">
                        <a href="<?=get_the_permalink()?>"><img loading="lazy" src="<?= MultiPostThumbnails::get_post_thumbnail_url($post_type, $image_id, $post->ID, array(300,200)) ?>" alt="<?php the_title() ?>"></a>
                    </div>
                <?php endif; ?>
            </div>
        <?php endwhile; ?>
    </div>

</section>


<?php
get_footer();
?>