<?php
require_once ("inc/the_recording_item.php");
get_header();

?>
    <section class="content">
        <div class="container">
            <div class="row">
                <div class="col">
                    <h1 >Konzertmitschnitte</h1>
                </div>

            </div>

            <div class="row">
                <?php while(have_posts()): the_post();?>
                <div class="col-md-4 col-sm-6 col-xs-12">
                   <?php  the_recording_item() ?>
                </div>
                <?php endwhile; ?>
            </div>


        </div>

    </section>


<?php
get_footer();
?>