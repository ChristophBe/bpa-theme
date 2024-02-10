<?php

require_once("inc/page_elements.php");
get_header();
the_post();


setlocale(LC_TIME,"de_DE");

global $post;



?>
<body class="landing-page">
<section class="content">
    <div class="container">
        <div class="row">

            <div class="col-12">
                <div class="content-item">

                    <?php
                    the_content();
                    ?>

                </div>
            </div>
        </div>
    </div>
</section>
<footer>

    <?php

    the_newletter_row();
    the_concert_row();
    the_recording_row();

    ?>

</footer>

<?php
the_copyright_row();
the_scripts();
wp_footer();
?>
</body>
</html>