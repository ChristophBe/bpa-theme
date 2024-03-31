<?php
require_once ("the_concert_row.php");
require_once ("the_recording_item.php");

function the_html_header(){
?>
<!DOCTYPE html>
<html lang="en">
<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title><?= wp_get_document_title() ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,300;0,400;0,700;0,900;1,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">
    <link href="<?=get_template_directory_uri()?>/assets/css/theme.min.css?v=<?=md5(time())?>" rel="stylesheet">
    <link rel="shortcut icon" href="<?=get_template_directory_uri()?>/favicon.ico">
    <meta name="theme-color" content="#1e1c32" />

    <?php
    wp_head();
    ?>
</head>
<?php
}
?><?php
function the_logo(){
?>
    <div class="logo">

        <svg id="Layer_2" data-name="Layer 2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 546.45 178.4">
            <defs>
                <style>
                    .cls-1 {
                        font-size: 48px;
                    }

                    .cls-2 {
                        fill: none;
                        stroke: #ffe626;
                        stroke-miterlimit: 10;
                        stroke-width: 6px;
                    }

                    .cls-3 {
                        fill: #fff;
                        font-family: Poppins-Light, Poppins;
                        font-weight: 300;
                    }

                    .cls-4 {
                        fill: #ffe126;
                        font-family: Poppins-Bold, Poppins;
                        font-weight: 700;
                    }
                </style>
            </defs>
            <g id="Ebene_3" data-name="Ebene 3">
                <text class="cls-1" transform="translate(186.06 55.33)"><tspan class="cls-3"><tspan x="0" y="0">BLÄSER</tspan></tspan><tspan class="cls-4"><tspan x="0" y="48">PHILHARMONIE</tspan></tspan><tspan class="cls-3"><tspan x="0" y="96">AACHEN</tspan></tspan></text>
                <g>
                    <circle class="cls-2" cx="82.93" cy="82.93" r="79.92"/>
                    <circle class="cls-2" cx="74.15" cy="81.6" r="71.04"/>
                    <circle class="cls-2" cx="82.54" cy="84.49" r="62.15"/>
                    <ellipse class="cls-2" cx="75.68" cy="78.85" rx="53.27" ry="53.28" transform="translate(-33.57 81.83) rotate(-47.73)"/>
                </g>
            </g>
        </svg>
    </div>
<?php
}

function the_scripts(){
?>


    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>

    <script src="<?=get_template_directory_uri()?>/assets/bootstrap/js/bootstrap.bundle.min.js" type="text/javascript"></script>
    <script src="<?=get_template_directory_uri()?>/assets/js/responsiveSlider.js?v=1.1" type="text/javascript"></script>

    <script type="application/javascript">
        var  nav= $("#nav");
        var  navHandle= $(".nav-button");
        var  subnav= $(".menu-item-has-children");

        let swipeDetector = function(container, onSwipeLeft, onSwipeRight){
            let x0_slider = 0;
            let y0_slider = 0;

            function unify(e) {	return e.changedTouches ? e.changedTouches[0] : e };
            function lock(event){
                x0_slider = unify(event).clientX;
                y0_slider = unify(event).clientY;
            }

            function move(event){

                let x = unify(event).clientX;
                let y = unify(event).clientY;
                let diff_x = x0_slider - x;
                let diff_y = y0_slider - y;

                if (( diff_x > -10 && diff_x < 10 ) || Math.abs(diff_x) < Math.abs(diff_y)) return;


                if(diff_x>0){
                    onSwipeLeft()
                }
                else {
                    onSwipeRight()
                }
            }


            container.on("mousedown",lock);
            container.on("touchstart",lock);
            container.on("mouseup",move);
            container.on("touchend",move);

        };



        var toggleNav = function(e) {

            nav.toggleClass("active");
        };
        navHandle.click(toggleNav);


        swipeDetector(nav, ()=>{},()=>{nav.removeClass("active")});

        var toggleSubNav = function(){
            if(!$(this).hasClass("sub-active")){
                subnav.removeClass("sub-active");
                $(this).addClass("sub-active");
            }
            else{
                $(this).removeClass("sub-active");
            }


        };

        subnav.click(toggleSubNav);

        const concertSliderRow = $(".concerts-row");
        const concertSliderItems = $(".concerts-items");
        const concertSlider= new ResponsiveSlider();

        const ITEMS_PER_PAGE = {
            "xl": 3,
            "lg": 3,
            "md": 2,
            "sm": 1,
            "xs": 1
        };

        concertSlider.init("concerts",concertSliderRow,concertSliderItems,ITEMS_PER_PAGE,()=>{});
        swipeDetector(concertSliderRow,concertSlider.next,concertSlider.prev);

        const newsSliderRow = $(".news-row");
        const newsSliderItems = $(".news-items");
        const newsSlider= new ResponsiveSlider();

        newsSlider.init("news",newsSliderRow,newsSliderItems,ITEMS_PER_PAGE,()=>{});
        swipeDetector(newsSliderRow,newsSlider.next,newsSlider.prev);


        const RECORDINGS_PER_PAGE = {
            "xl": 3,
            "lg": 3,
            "md": 2,
            "sm": 2,
            "xs": 1
        };

        const recordingsRow = $(".recordings-row");
        const recordingsItems = $(".recordings-items");
        const recordingsSlider= new ResponsiveSlider();



        recordingsSlider.init("recordings",recordingsRow,recordingsItems,RECORDINGS_PER_PAGE,()=>{});
        swipeDetector(recordingsRow,recordingsSlider.next,recordingsSlider.prev);
    </script>
<?php
}

function the_copyright_row(){
    ?>
    <div class="container">
        <div class="row">
            <div class="col copyright">

                &copy;  2024 Bläserphilharmonie Aachen e.V.
            </div>
        </div>
    </div>

<?php
}

function the_recording_row(){
    ?>

    <section class="recordings">
        <div class="container">
            <div class="row"><h3 class="col-12 h1">Videos</h3></div>
            <div class="row recordings-row"></div>
            <div class="row recordings-items">
                <?php
                $args = array(
                    'posts_per_page'   => 3,
                    'offset'           => 0,
                    'orderby'          => 'meta_value',
                    'order'            => 'DESC',
                    'meta_key'         => 'recording_date',
                    'post_type'        => 'recording',

                    'post_status'      => 'publish',
                    'suppress_filters' => true,
                );
                $posts_array = get_posts( $args );

                foreach ($posts_array as $post): setup_postdata($post);?>

                    <div class="col-lg-4 col-md-6 col-sm-6 col-xs-12">
                        <?php  the_recording_item($post) ?>
                    </div>
                <? endforeach;?>


            </div>
            <div class="row"><div class="col-12">
                    <a href="<?=get_post_type_archive_link("recording")?>" class="btn btn-outline-dark">weitere Konzertmitschnitte</a>
                </div>
            </div>
        </div>


    </section>
    <?php
}
function the_newletter_row(){
 if ( is_active_sidebar( 'newsletter_widget_area' ) ) { ?>
<section class="newsletter" id="newsletter">
    <div class="container">
        <?php dynamic_sidebar( 'newsletter_widget_area' ); ?>
    </div>
</section>
<?php
 }
}
?>
