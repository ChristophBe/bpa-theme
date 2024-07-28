<?php
require_once("inc/the_concert_rich_data.php");

get_header();


$args = array( 'post_type' => 'concert',

    'post_status'       => 'publish',


    'posts_per_page' => -1

);


$concertPosts = get_posts( $args );
$futureConcerts = array();
$pastConcerts = array();
$hasFuture = false;
foreach ($concertPosts as $concertPost){

    $concerts = get_concerts_from_post($concertPost);

    $times = get_concert_time($concertPost);

    foreach ($concerts as $concert){

        $timestamp = $concert->getTime()->getTimestamp();
        if($concert->isUpcoming()){
            $futureConcerts[$timestamp] = $concert;
        }
        else{
            $pastConcerts[$timestamp] = $concert;
        }

    }


}
ksort($futureConcerts );
krsort($pastConcerts );
setlocale(LC_TIME,"de_DE");



function concertRow(Concert $concert)
{


    $concertFormatDate = 'j. F Y';
    $concertFormatTime = 'H:i \U\h\r';
   ?>
    <div class="row post-row concert-row">


        <?php if (has_post_thumbnail($concert->getPost())) : ?>
            <div class="col-md-4">
                <div class="concert-thumbnail">
                    <a href="<?= $concert->getPermalink() ?>"><img loading="lazy" src="<?= get_the_post_thumbnail_url($concert->getPost(), array(300,300)) ?>"
                                               alt="<?= $concert->title ?>"></a>
                </div>
            </div>
        <?php endif; ?>

        <div class="<?= !has_post_thumbnail($concert->getPost()) ? "offset-md-4 " : "" ?>col-md-8 col-sm-12 post-content">

            <div class="content-item">
                <h2 class="h2">

                    <a href="<?= $concert->getPermalink() ?>"><?php if($concert->isProjectConcert()): ?><span class="concert-title-subtitle">Konzert der </span><? endif;?><?= $concert->getTitle() ?></a>

                </h2>
                <div class="concert-meta">
                    <div class="concert-meta--item">
                        <?php
                        $dateString = utf8_encode( $concert->getTime()->format($concertFormatDate));
                        $timeString = utf8_encode( $concert->getTime()->format($concertFormatTime));
                        echo '<h4 class="h4"><strong>' . $dateString . '</strong> ' . $timeString . '</h4>';
                        ?>
                    </div>
                    <div class="concert-meta--item">
                        <?= $concert->getLocation()->getName() ?>
                    </div>
                    <div class="btn-group">
                        <?php if($concert->isUpcoming() && $concert->getTicketsOnSell()):?>

                            <a class="btn btn-dark" href="<?= get_site_url() ?>/karten">Kartenverkauf</a>
                        <?php endif; ?>
                        <a class="btn btn-outline-dark" href="<?= $concert->getPermalink() ?>">mehr Informationen</a>

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




            <?php
            $first = true;
            echo '<script type="application/ld+json">[';
            foreach ($futureConcerts as $concert){
                if(!$first){
                    echo ",\n";
                }
                else{
                    $first = false;
                }
                the_concert_rich_data($concert->getPost(),$concert->getTime()->getTimestamp(),false);
            }
            echo']</script>';
            foreach ($futureConcerts as $concert){
                concertRow($concert);
            }
            endif;

            if($pastConcerts) :?>

                <div class="row">
                    <div class="col">
                        <h2 class="h1">Bisherige Konzerte</h2>
                    </div>

                </div>
            <?php
            foreach ($pastConcerts as $concert){
                concertRow($concert);
            }
            endif; ?>

        </div>

    </section>


<?php
get_footer();
?>