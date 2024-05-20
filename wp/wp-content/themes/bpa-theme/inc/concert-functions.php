<?php
require_once ("Concert.php");
function get_featured_concert($concertTimes)
{
    $featuredDate = false;

    $day = false;
    if (get_query_var('tag')) {
        $day = get_query_var("tag");
    }
    foreach ($concertTimes as $concertTime) {
        if ($day == date("Y-m-d", $concertTime)) {
            $featuredDate = $concertTime;
        }
    }
    return $featuredDate;
}

function should_show_ticket_button($ticketOnSell, $concertTimes, $featuredConcertDate): bool{
    if(!$ticketOnSell){
        return false;
    }
    if($featuredConcertDate){
        return  $featuredConcertDate > time();
    }

    foreach ($concertTimes as $time){
        if($time > time()){
            return true;
        }
    }
    return false;
}


function is_project_concert(WP_Post $post): bool{
    return get_post_meta($post->ID, "concert_is_project_concert", true);
}

/**
 * @param $post
 * @return Concert[]
 */
function get_concerts_from_post($post): array
{

    $times = get_concert_time($post);


    $title = get_the_title($post);
    $concertLocation = get_concert_location($post);
    $concert_tickets_on_sell = get_post_meta($post->ID, "concert_tickets_on_sell", true);
    $concert_is_project_concert =is_project_concert($post);

    $concerts = array();
    foreach ($times as $time) {

        $dateTime = new \DateTime();
        $dateTime -> setTimestamp((int) $time);
        $permalink = get_the_permalink($post)  ."tag/". date("Y-m-d", $time) . "/";
        $concerts[] = new Concert($post, $title, $dateTime, $permalink, $concert_tickets_on_sell, $concert_is_project_concert, $concertLocation);
    }
    return $concerts;
}