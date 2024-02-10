<?php
require_once("inc/the_concert_rich_data.php");

get_header();


$args = array( 'post_type' => 'concert',

    'post_status'       => 'publish',


    'posts_per_page' => -1

);


$concerts = get_posts( $args );
$futureConcerts = array();
$pastConcerts = array();
$hasFuture = false;
foreach ($concerts as $concert){

    $times = get_concert_time($concert);

    foreach ($times as $time){
        if($time > time()){
            $futureConcerts[$time] = $concert;
        }
        else{
            $pastConcerts[$time] = $concert;
        }

    }


}
ksort($futureConcerts );
krsort($pastConcerts );
setlocale(LC_TIME,"de_DE");



function concertRow($concert, $time)
{

    $url = get_the_permalink($concert) ."tag/" .  date("Y-m-d", $time) . "/";

    $concertLocation = get_concert_location($concert);

    $concertTicketsOnSell = get_post_meta($concert->ID, "concert_tickets_on_sell",true);
    $concertIsProjectConcert = get_post_meta($concert->ID, "concert_is_project_concert",true);

    $concertFormatTime = '%e. %B %Y um %H:%M Uhr';
   ?>
    <div class="row post-row concert-row">


        <?php if (has_post_thumbnail($concert)) : ?>
            <div class="col-md-4">
                <div class="concert-thumbnail">
                    <a href="<?= $url ?>"><img loading="lazy" src="<?= get_the_post_thumbnail_url($concert, array(300,300)) ?>"
                                               alt="<?= get_the_title($concert) ?>"></a>
                </div>
            </div>
        <?php endif; ?>

        <div class="<?= !has_post_thumbnail($concert) ? "offset-md-4 " : "" ?>col-md-8 col-sm-12 post-content">

            <div class="content-item">
                <h2 class="h2">
                    <?php if($concertIsProjectConcert): ?><span class="concert-title-subtitle">Konzert der </span><? endif;?>
                    <a href="<?= $url ?>"><?= get_the_title($concert) ?></a>

                </h2>
                <div class="concert-meta">
                    <div class="concert-meta--item">
                        <?php
                        $dateString = utf8_encode(strftime($concertFormatTime, $time));
                        echo '<h4 class="h4">' . $dateString . '</h4>';
                        ?>
                    </div>
                    <div class="concert-meta--item">
                        <?= $concertLocation->getName() ?>
                    </div>
                    <div class="btn-group">
                        <?php if($time > time() && $concertTicketsOnSell):?>

                            <a class="btn btn-dark mr-2" href="<?= get_site_url() ?>/karten">Kartenverkauf</a>
                        <?php endif; ?>
                        <a class="btn btn-outline-dark" href="<?= $url ?>">mehr Informationen</a>

                    </div>
                </div>
            </div>

        </div>


    </div>
    <?php

}
?>

    <section class="content">
        <div class="container">
            <?php if($futureConcerts): ?>
            <div class="row">
                <div class="col">
                    <h1 >Nächste Konzerte</h1>
                </div>

            </div>




            <?php
            $first = true;
            echo '<script type="application/ld+json">[';
            foreach ($futureConcerts as $time => $concert){
                if(!$first){
                    echo ",\n";
                }
                else{
                    $first = false;
                }
                the_concert_rich_data($concert,$time,false);
            }
            echo']</script>';

            foreach ($futureConcerts as $time => $concert){
                concertRow($concert,$time);
            }

            endif;

            if($pastConcerts) :?>

                <div class="row">
                    <div class="col">
                        <h2 class="h1">Bisherige Konzerte</h2>
                    </div>

                </div>
            <?php
            foreach ($pastConcerts as $time => $concert){
                concertRow($concert,$time);
            }
            endif; ?>

        </div>

    </section>


<?php
get_footer();
?>