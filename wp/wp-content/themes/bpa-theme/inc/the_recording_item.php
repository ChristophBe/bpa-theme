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
            <div class="recording-img"  style="background: url(<?=get_the_post_thumbnail_url($recording, array(300,300))?>) center; background-size: cover;">
                <div class="recording-info">
                    <h4 class="recording-name h2"><?=get_the_title($recording)?></h4>
                    <h5 class="recording-composer h5"><?=$composer?></h5>
                </div>
            </div>

        </a>
    </div>

    <?php
}