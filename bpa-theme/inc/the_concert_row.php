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


    <section class="concerts<?=$class? " " . $class :""?>">
        <div class="container">

            <?php if(!is_front_page()): ?>
            <div class="row">
                <div class="col">
                    <h3 class="h2">Nächste Konzerte</h3>
                </div>
            </div>
            <?php endif; ?>
            <div class="row concerts-row"></div>
            <div class="row concerts-items">

                <?php
                foreach ($concertItems as $concert):
                ?>


                <div class="col-lg-4 col-md-6 col-sm-12">
                    <div class="concert-item">
                    <span>
                        <?=utf8_encode(strftime('<span class="font-weight-bold"><span class="hidden-sm-down">%A, </span>%e. %B %Y</span> um %H:%M Uhr', $concert["time"]))?>
                    </span>
                        <div class="concert-title" >


                        <h2 class="concert-title-subtitle h3">
                            <?php if($concert["concert_is_project_concert"]): ?><span class="concert-title-subtitle">Konzert der </span><? endif;?>
                            <?= $concert["title"] ?>
                        </h2>
                        </div>
                        <div class="btn-group">

                            <?php if($concert["concert_tickets_on_sell"]): ?>

                                <a href="<?= get_site_url()?>/karten" class="btn btn-light mr-2">Kartenverkauf</a>

                            <?php endif; ?>
                            <a href="<?= $concert["permalink"] ?>" class="btn btn-outline-light">Mehr Informationen</a>
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