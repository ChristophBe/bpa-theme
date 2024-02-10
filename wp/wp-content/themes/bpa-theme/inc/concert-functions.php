<?php
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


