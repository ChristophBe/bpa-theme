<?php
require_once ("inc/add_concert_post_type.php");
require_once ("inc/the_concert_rich_data.php");
require_once("inc/concert-functions.php");
get_header();
the_post();



setlocale(LC_TIME,"de_DE");

global $post;




$concertTimes = get_concert_time($post);
$concertLocation = get_concert_location($post);
$concertTicketsOnSell = get_post_meta($post->ID, "concert_tickets_on_sell",true);
$concertIsProjectConcert = get_post_meta($post->ID, "concert_is_project_concert",true);
$concertFormatTime = '%e. %B %Y um %H:%M Uhr';

$featuredDate = get_featured_concert($concertTimes);

$showTicketButton = should_show_ticket_button($concertTicketsOnSell,$concertTimes, $featuredDate);

if($featuredDate){
    the_concert_rich_data($post,$featuredDate);
}
?>
<section class="content">
    <div class="container">
        <div class="row">

            <div class="col-md-8">
                <div class="content-item">
                    <h2 class="h1"> <?php if($concertIsProjectConcert): ?><span class="concert-title-subtitle">Konzert der </span><? endif;?><?php the_title() ?></h2>


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

                        <?php
                        $address = $concertLocation->getStreet() . ', ' .$concertLocation->getPostalCode().  ' ' . $concertLocation->getCity();

                        $url = "https://www.google.de/maps/place/" . urlencode($address);
                        ?>


                        <div class="concert-meta--item">
                            <a href="<?= $url ?>" target="_blank"><?=$concertLocation->getName()?></a><br/>
                            <?=$address?>
                        </div>
                    </div>

                    <?php if($showTicketButton):?>
                    <div class="my-3">
                        <a class="btn btn-dark" href="<?= get_site_url() ?>/karten">Online Kartenverkauf</a>
                    </div>

                    <hr>
                    <?php endif; ?>
                    <div class="concert-content">
                        <?php

                        the_content()
                        ?>

                        <?php
                        if($featuredDate && sizeof( $concertTimes )>1 ){

                            $alternativesNumber  = sizeof($concertTimes) -1;
                            if($alternativesNumber>1){
                                echo "<h3>Alternativ Termine:</h3>";
                            }
                            else{
                                echo "<h3>Alternativ Termin:</h3>";
                            }

                            echo "<ul>";

                            foreach ($concertTimes as  $time){
                                if($time != $featuredDate){
                                    $url = get_the_permalink($post) ."tag/" .  date("Y-m-d", $time) . "/";
                                    echo '<li><a href="' . $url . '">' .strftime($concertFormatTime, $time ) . '</a></li>';
                                }
                            }
                            echo "</ul>";

                        }
                        ?>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <?php
                $image_id  = 'secondary-image-concert';
                if (class_exists('MultiPostThumbnails')) {


                    if (MultiPostThumbnails::has_post_thumbnail($post->post_type, $image_id, $post->ID)) {
                        ?>
                        <div class="concert-poster">
                                <img loading="lazy" src="<?= MultiPostThumbnails::get_post_thumbnail_url($post->post_type, $image_id, $post->ID, array(350,500)) ?>" alt="<?php the_title() ?> Plakat">
                        </div>
                        <?php
                    }
                }
                ?>
            </div>
        </div>
    </div>
</section>


<?php
get_footer();
?>