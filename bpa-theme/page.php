<?php
get_header();
the_post();
?>
    <section class="content">
        <div class="container">
            <div class="row">
                <div class="col-md-12 col-sm-12 content-main-content">
                    <div class="content-item">
                        <h2 class="h1">
                            <?php the_title() ?>
                        </h2>
                        <?php the_content() ?>
                    </div>
                </div>
            </div>
        </div>
    </section>

<?php
get_footer();
?>