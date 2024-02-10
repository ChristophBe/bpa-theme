<?php


function the_recording_item($recording =false){
    if(!$recording){
        global $post;
        $recording = $post;
    }

    $composer = get_post_meta($recording->ID,"recording_composer",true);

    $alt = get_the_title($recording) . ", " .$composer;
    ?>
    <div class="recording-item">
        <a href="<?=get_the_permalink($recording)?>#player" title="<?=$alt?>">
            <span class="recording-info">
                <span class="recording-composer"><?=$composer?></span>
                <span class="recording-name h4"><?=get_the_title($recording)?></span>
            </span>
            <span class="recording-img">
                <img loading="lazy" src="<?=get_the_post_thumbnail_url($recording, array(300,300))?>" alt="<?= $alt?>">
            </span>
        </a>
    </div>

    <?php
}