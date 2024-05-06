<?php
require_once ("inc/is_sidebar_visible.php");
require_once ("inc/the_concert_row.php");
require_once("inc/page_elements.php");

function the_concert_header()
{
    global $post;
    $concertTimes = get_concert_time($post);
    $concertTicketsOnSell = get_post_meta($post->ID, "concert_tickets_on_sell",true);
    $concertFormatTime = '%e. %B %Y um %H:%M Uhr';

    $featuredDate = get_featured_concert($concertTimes);

    $showTicketButton = should_show_ticket_button($concertTicketsOnSell,$concertTimes, $featuredDate);

    setlocale(LC_TIME,"de_DE");

    if($featuredDate){
        the_concert_rich_data($post,$featuredDate);
    }
    ?>
    <div class="container headline">
        <div class="row concert-header ">
            <div class="col-md-8">
                <div class="info">
                    <h2 class="h1">
                        <?= get_the_title($post) ?>
                    </h2>
                    <div class="concert-meta">
                        <div class="concert-meta--item">
                            <?php
                            if($featuredDate){
                                echo '<h4 class="h4">' . utf8_encode(strftime($concertFormatTime, $featuredDate ) ) . '</h4>';
                            }
                            else
                                foreach ($concertTimes as $concertTime){

                                    $dateString =utf8_encode(strftime($concertFormatTime, $concertTime ));

                                    echo '<h4 class="h4">' . $dateString . '</h4>';

                                }
                            ?>
                        </div>
                    </div>
                </div>

            </div>
            <div class="col-md-4">
                <?php if($showTicketButton):?>
                    <div class="tickets" >
                        <a class="btn btn-light" href="<?= get_site_url() ?>/karten">Kartenverkauf</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>

    </div>
    <?php
}

function the_default_single_header()
{
    global $post
?>
    <div class="container headline">
        <div class="row">
            <h2 class="col h1">
               <?= get_the_title($post) ?>
            </h2>
        </div>

    </div>
<?php
}
function the_post_header()
{
    global $post
?>
    <div class="container headline">
        <div class="row">
            <h2 class="col-12 h1">
               <?= get_the_title($post) ?>
            </h2>

            <span class="col-12 h4"><?= get_the_date("", $post) ?></span>
        </div>

    </div>
<?php
}function the_recording_header()
{
    global $post;
    $recording_composer = get_post_meta( $post->ID, 'recording_composer', true );
?>
    <div class="container headline">
        <div class="row">
            <h2 class="col-12 h1" id="player">
               <?= get_the_title($post) ?>
            </h2>

            <span class="col-12 h4"><?= $recording_composer ?></span>
        </div>

    </div>
<?php
}

global $post;
the_html_header()
?>

<body>
<header style="background:url(<?= !is_archive() && has_post_thumbnail() && $post->post_type!= "recording" ? get_the_post_thumbnail_url($post) : header_image()?>) center; background-size: cover;">
    <div class="container">
        <div class="row header-row">
            <div class="col-12 header-row">
                <?php the_logo(); ?>
                <?php if (get_post_type() !="landing-page") : ?>
                <div id="nav" class="nav">
                    <div class="nav-button">
                        <div class="bar"></div>
                        <div class="bar"></div>
                        <div class="bar"></div>
                    </div>
                    <nav>
                        <?php $main_nav_args = array(
                            'theme_location' => 'main_menu',
                            'container'=> 'ul',
                            'menu_class'=> "nav ml-1",
                        );
                        wp_nav_menu($main_nav_args);
                        ?>
                    </nav>
            </div>


            </div>
            <?php endif; ?>
        </div>
    </div>
    



    <?php if (is_front_page()):?>
    <div class="container headline">
        <div class="row">
            <h2 class="col h1">
                <?= get_theme_mod("homepage_title", "") ?>
            </h2>
        </div><div class="row">
            <h2 class="col h4">
                <?= get_theme_mod("homepage_subtitle", "") ?>
            </h2>
        </div>

    </div>

    <?php endif; ?>

    <?php
    global $post;
    if (!is_front_page() && (is_single($post) || is_page($post))){
        switch ($post->post_type){
            case "concert":
                the_concert_header();
                break;
            case "post":
                the_post_header();
                break;
            case "recording":
                the_recording_header();
                break;


            default:
                the_default_single_header();
        }
    }

    ?>


</header>
