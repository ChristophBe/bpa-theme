<?php
require_once ("inc/add_concert_post_type.php");
require_once ("inc/the_concert_rich_data.php");
require_once("inc/concert-functions.php");

global $post;

setlocale(LC_TIME,"de_DE");

get_header();
the_post();



global $post;




$concertTimes = get_concert_time($post);
$concertAdmissionTimes = get_concert_admission_time($post);
$concertLocation = get_concert_location($post);
$concertTicketsOnSell = get_post_meta($post->ID, "concert_tickets_on_sell",true);
$concertIsProjectConcert = get_post_meta($post->ID, "concert_is_project_concert",true);
$concertFormatTime = '<strong>%e. %B %Y</strong> %H:%M Uhr';

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
                <?php the_content() ?>
            </div>
            <div class="col-md-4 concert-info">

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

                $address = $concertLocation->getStreet() . ', ' .$concertLocation->getPostalCode().  ' ' . $concertLocation->getCity();

                $url = "https://www.google.de/maps/place/" . urlencode($address);
                ?>


                <div class="concert-info--item">
                    <h4 class="h3">Ort</h4>
                    <a href="<?= $url ?>" target="_blank"><?=$concertLocation->getName()?></a><br/>
                    <?=$address?>
                </div>
                <?php

                $admissionTime  = get_featured_concert($concertAdmissionTimes);
                if($featuredDate && $admissionTime && $featuredDate>time()):
                ?>
                <div class="concert-info--item">
                    <h4 class="h3">Einlass</h4>
                    <?= utf8_encode(strftime("%H:%M Uhr", $admissionTime )); ?>
                </div>
                <?php endif; ?>
                <div class="concert-info--item">
                    <?php
                    if($featuredDate && sizeof( $concertTimes )>1 ){

                        $alternativesNumber  = sizeof($concertTimes) -1;
                        if($alternativesNumber>1){
                            echo '<h4 class="h3">Weitere Termine</h4>';
                        }
                        else{
                            echo '<h3 class="h3">Weiterer Termin</h3>';
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
    </div>
</section>


<?php
get_footer();
?>