<?php
the_post();
get_header();
?>
    <section class="content">
        <div class="container">
            <div class="row">
                <div class="col-md-12 col-sm-12 content-main-content">
                    <div class="content-item">
                        <?php the_content() ?>
                    </div>
                </div>
            </div>
        </div>
    </section>

<?php
get_footer();
?>