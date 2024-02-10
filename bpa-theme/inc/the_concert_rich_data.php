<?php

function the_concert_rich_data($concert, $concert_datetime, $tag =true){

    $concert_location = get_concert_location($concert);

    $concertIsProjectConcert = get_post_meta($concert->ID, "concert_is_project_concert",true);
    ?>

    <?= $tag ? '<script type="application/ld+json">':"" ?>
        {
            "@context": "http://schema.org",
            "@type": "Event",
            "name": "<?=$concertIsProjectConcert? "Konzert der ": "" ?><?= $concert->post_title ?>",
            "url": "<?= get_the_permalink($concert) . "tag/". date("Y-m-d", $concert_datetime) . "/"?>",
            "location": {
                "@type": "Place",
                "name": "<?= $concert_location->getName()?>",
                "address":{
                    "@type": "PostalAddress",
                    "streetAddress": "<?= $concert_location->getStreet()?>",
                    "addressLocality": "<?= $concert_location->getCity()?>",
                    "postalCode": "<?= $concert_location->getPostalCode()?>"
                }
            },
            "startDate": "<?=date("c",$concert_datetime)?>",
            "description": "<?=str_replace('"', '\"', apply_filters("the_content", $concert->post_content))?>",
            "performer": {
                "@type": "PerformingGroup",
                "name": "Bläserphilharmonie Aachen"
            }
            <?php
            if (class_exists('MultiPostThumbnails')) {


                $post_type = $concert->post_type;
                $image_id  = 'secondary-image-' . $post_type;

                if ( MultiPostThumbnails::has_post_thumbnail($post_type, $image_id, $concert->ID) ) {

                    echo ",\n";
                    echo '"image":"' . MultiPostThumbnails::get_post_thumbnail_url($post_type, $image_id, $concert->ID) . '"';

                }
            }

            ?>
        }
    <?= $tag ? '</script>':"" ?>
<?php
}