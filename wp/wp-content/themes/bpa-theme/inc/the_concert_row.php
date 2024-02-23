<?php
require_once ("add_concert_post_type.php");



function the_concert_row($class = false){

    $args = array( 'post_type' => 'concert',

        'post_status'       => 'publish',
        "posts_per_page"=>-1,

    );


    $concerts = get_posts( $args );
    $concertItems = array();

    foreach ($concerts as $concert){

        $times = get_concert_time($concert);


        $title = get_the_title($concert);
        $permalink = get_the_permalink($concert);


        $concert_tickets_on_sell = get_post_meta($concert->ID,"concert_tickets_on_sell",true);
        $concert_is_project_concert = get_post_meta($concert->ID,"concert_is_project_concert",true);
        foreach ($times as $time){
            if($time > time()){
                $concertItems[$time] = array(
                    "post" => $concert,
                    "title"=> $title,

                    "permalink"=> $permalink ."tag/". date("Y-m-d", $time) . "/",
                    "time"=> $time,
                    "concert_tickets_on_sell"=> $concert_tickets_on_sell,
                    "concert_is_project_concert"=> $concert_is_project_concert,
                );
            }
        }
    }
    ksort($concertItems );
    setlocale(LC_TIME,"de_DE");

    if ( $concertItems && !is_post_type_archive("concert") ) :?>


    <section class="upcoming-concerts">
        <div class="container">

            <div class="row">
                <div class="col">
                    <h3 class="h1">Bevorstehende Konzerte</h3>
                </div>
            </div>
            <div class="row concerts-row"></div>
            <div class="row concerts-items">

                <?php
                foreach ($concertItems as $concert):
                ?>


                <div class="col-lg-4 col-md-6 col-sm-12">
                    <div class="concert-item">
                        <div class="concert-image" style="background: url(<?=get_the_post_thumbnail_url($concert["post"], array(300,300))?>) center; background-size: cover;">

                            <h2 class="concert-title-subtitle h3">
                                <?= $concert["title"] ?>
                            </h2>
                        </div>
                        <div class="concert-title" >

                            <h2 class="concert-title-subtitle h3">
                                <?= $concert["title"] ?>
                            </h2>
                        </div>

                        <div class="concert-date">
                            <?=utf8_encode(strftime('<span class="font-weight-bold"><span class="hidden-sm-down"></span>%e. %B</span> %H:%M', $concert["time"]))?>
                        </div>
                        <div class="concert-date">
                            <?=utf8_encode(strftime('<span class="font-weight-bold"><span class="hidden-sm-down"></span>%e. %B</span> %H:%M', $concert["time"]))?>
                        </div>

                        <div class="btn-group">

                            <?php if($concert["concert_tickets_on_sell"]): ?>

                                <a href="<?= get_site_url()?>/karten" class="btn btn-primary mr-2">Kartenverkauf</a>

                            <?php endif; ?>
                            <a href="<?= $concert["permalink"] ?>" class="btn btn-outline-dark">Mehr Informationen</a>
                        </div>
                    </div>
                </div>
                <?php endforeach;?>
            </div>
        </div>
    </section>


    <?php
    endif;
}