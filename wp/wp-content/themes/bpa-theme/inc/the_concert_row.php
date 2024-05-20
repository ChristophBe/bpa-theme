<?php
require_once ("add_concert_post_type.php");
require_once ("concert-functions.php");



function the_concert_item(Concert $concert, $withDescription= true)
{
    ?>

    <div class="col-lg-4 col-md-6 col-sm-12">
        <div class="concert-item">
            <a href="<?= $concert ->getPermalink()?>">
                <div class="concert-image" style="background: url(<?=get_the_post_thumbnail_url($concert->getPost(), array(300,300))?>) center; background-size: cover;">
                    <?php
                    if($concert->isProjectConcert()):
                        ?>
                        <div  class="concert-date">Konzert der</div>
                    <?php endif; ?>
                    <h2 class="concert-title-subtitle h2">
                        <?= $concert->getTitle() ?>
                    </h2>
                    <div class="concert-date">
                        <?=utf8_encode(strftime('<span class="font-weight-bold"><span class="hidden-sm-down"></span>%e. %B %Y</span> %H:%M', $concert->getTime()->getTimestamp()))?>
                    </div>
                    <div class="concert-date">
                        <?=$concert->getLocation()->getName()?>
                    </div>
                </div>
            </a>
            <p>
                <?= get_the_content("",true, $concert->getPost()) ?>
            </p>
            <div class="btn-group">

                <?php if($concert->getTicketsOnSell()): ?>

                    <a href="<?= get_site_url()?>/karten" class="btn btn-dark mr-2">Kartenverkauf</a>

                <?php endif; ?>
                <a href="<?= $concert->getPermalink()?>" class="btn btn-outline-dark">Mehr Informationen</a>
            </div>




        </div>
    </div>
    <?php
}



function the_concert_row($class = false){

    $args = array( 'post_type' => 'concert',

        'post_status'       => 'publish',
        "posts_per_page"=>-1,

    );


    $posts = get_posts( $args );
    $concertItems = array();

    foreach ($posts as $post){

       $concerts = get_concerts_from_post($post);
       foreach ($concerts as $concert){

            if ($concert->isUpcoming()){
                $concertItems[$concert->getTime()->getTimestamp()] = $concert;
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
                    <h3 class="h1"><?= get_theme_mod("concert-archive-title", "Konzerte")?></h3>
                </div>
            </div>
            <div class="row concerts-row"></div>
            <div class="row concerts-items">
                <?php
                foreach ($concertItems as $concert){
                    the_concert_item($concert, true);
                }
                ?>
            </div>
        </div>
    </section>


    <?php
    endif;
}