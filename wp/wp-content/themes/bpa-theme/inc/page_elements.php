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
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">
    <link href="<?=get_template_directory_uri()?>/assets/css/theme.min.css?v=1.9.0" rel="stylesheet">
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
        <a href="<?=get_site_url()?>">
            <svg version="1.1" id="Ebene_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                 viewBox="0 0 647 216.5"  xml:space="preserve">
             <g>
                 <path class="logo-fill-white" d="M234.2,65V28h10.9c4,0,7,0.8,9,2.5c2,1.6,3.1,4,3.1,7.2c0,1.9-0.5,3.6-1.6,5c-1,1.4-2.5,2.4-4.3,3
		c2.1,0.5,3.9,1.5,5.2,3.2c1.3,1.6,2,3.5,2,5.7c0,3.3-1.1,5.8-3.2,7.6c-2.1,1.8-5,2.8-8.8,2.8H234.2z M237.3,44.6h8.5
		c2.6-0.1,4.6-0.7,6.1-1.8c1.4-1.2,2.1-2.9,2.1-5.1c0-2.4-0.7-4.1-2.2-5.2c-1.5-1.1-3.7-1.7-6.7-1.7h-7.8V44.6z M237.3,47.2v15.1
		h9.4c2.7,0,4.8-0.7,6.4-2c1.6-1.4,2.3-3.2,2.3-5.7c0-2.3-0.7-4.1-2.2-5.4c-1.5-1.3-3.6-2-6.2-2H237.3z"/>
                 <path class="logo-fill-white" d="M269.2,62.3h18.2V65H266V28h3.1V62.3z"/>
                 <path class="logo-fill-white" d="M313.8,54.6h-16.8L293.2,65H290L304,28h3l14,36.9h-3.2L313.8,54.6z M297.9,21.6c0-0.6,0.2-1.1,0.6-1.5
		c0.4-0.4,0.9-0.6,1.5-0.6s1.2,0.2,1.5,0.6s0.6,0.9,0.6,1.5c0,0.6-0.2,1-0.6,1.4c-0.4,0.4-0.9,0.6-1.5,0.6s-1.2-0.2-1.5-0.6
		C298.1,22.6,297.9,22.1,297.9,21.6z M298,52h14.8l-7.4-20.1L298,52z M309,21.6c0-0.6,0.2-1.1,0.6-1.5c0.4-0.4,0.9-0.6,1.5-0.6
		s1.2,0.2,1.5,0.6c0.4,0.4,0.6,0.9,0.6,1.5c0,0.6-0.2,1-0.6,1.4c-0.4,0.4-0.9,0.6-1.5,0.6s-1.2-0.2-1.5-0.6
		C309.2,22.6,309,22.1,309,21.6z"/>
                 <path class="logo-fill-white" d="M347.2,55.9c0-2-0.7-3.7-2.2-4.9c-1.4-1.2-4.1-2.3-7.9-3.4s-6.6-2.2-8.4-3.5c-2.5-1.8-3.8-4.1-3.8-7.1
		c0-2.8,1.2-5.1,3.5-6.9c2.3-1.8,5.3-2.7,8.9-2.7c2.5,0,4.6,0.5,6.6,1.4c1.9,0.9,3.4,2.3,4.5,4c1.1,1.7,1.6,3.6,1.6,5.7H347
		c0-2.5-0.9-4.6-2.6-6.1c-1.7-1.5-4-2.3-7-2.3c-2.8,0-5.1,0.6-6.8,1.9c-1.7,1.3-2.5,2.9-2.5,4.9c0,1.9,0.8,3.4,2.3,4.6
		c1.5,1.2,3.9,2.2,7.2,3.1c3.3,0.9,5.8,1.8,7.5,2.8c1.7,1,3,2.1,3.9,3.5c0.9,1.4,1.3,3,1.3,4.8c0,2.9-1.2,5.2-3.5,7
		c-2.3,1.8-5.4,2.6-9.2,2.6c-2.6,0-5-0.5-7.2-1.4c-2.2-0.9-3.8-2.2-4.9-3.9s-1.7-3.6-1.7-5.8h3.1c0,2.6,1,4.7,2.9,6.2
		c1.9,1.5,4.5,2.3,7.8,2.3c2.9,0,5.2-0.6,6.9-1.9C346.4,59.7,347.2,58,347.2,55.9z"/>
                 <path class="logo-fill-white" d="M377.5,47.3h-17.3v15.1h19.9V65h-23V28H380v2.6h-19.7v14h17.3V47.3z"/>
                 <path class="logo-fill-white" d="M400.5,49.6h-10.7V65h-3.1V28h12c3.9,0,7,1,9.2,2.9s3.3,4.6,3.3,8c0,2.4-0.7,4.5-2.1,6.3s-3.2,3.1-5.6,3.8
		l9.2,15.7V65h-3.3L400.5,49.6z M389.7,46.9h9.6c2.6,0,4.7-0.7,6.3-2.2c1.6-1.5,2.4-3.4,2.4-5.8c0-2.6-0.8-4.6-2.5-6.1
		c-1.7-1.4-4-2.2-7-2.2h-8.8V46.9z"/>
                 <path class="logo-fill-accent" d="M241.2,114.8v12.5h-8.9V90.4H247c2.8,0,5.3,0.5,7.5,1.6c2.2,1,3.9,2.5,5,4.4c1.2,1.9,1.8,4.1,1.8,6.5
		c0,3.6-1.3,6.5-3.9,8.6c-2.6,2.2-6.1,3.2-10.6,3.2H241.2z M241.2,107.9h5.8c1.7,0,3-0.4,3.9-1.3s1.4-2.1,1.4-3.7
		c0-1.7-0.5-3.1-1.4-4.1c-0.9-1-2.2-1.6-3.8-1.6h-5.9V107.9z"/>
                 <path class="logo-fill-accent" d="M298,127.3h-8.9v-15.4h-13.1v15.4h-8.9V90.4h8.9v14.7h13.1V90.4h8.9V127.3z"/>
                 <path class="logo-fill-accent" d="M315.2,127.3h-8.9V90.4h8.9V127.3z"/>
                 <path class="logo-fill-accent" d="M332.4,120.5h15.5v6.9h-24.4V90.4h8.9V120.5z"/>
                 <path class="logo-fill-accent" d="M383.5,127.3h-8.9v-15.4h-13.1v15.4h-8.9V90.4h8.9v14.7h13.1V90.4h8.9V127.3z"/>
                 <path class="logo-fill-accent" d="M411.7,120.4h-12.2l-2.1,6.9h-9.5l13.6-36.9h8.4l13.7,36.9h-9.6L411.7,120.4z M401.6,113.6h7.9l-4-12.8
		L401.6,113.6z"/>
                 <path class="logo-fill-accent" d="M440.8,114.3H436v13.1h-8.9V90.4h14.5c4.4,0,7.8,1,10.3,2.9s3.7,4.7,3.7,8.2c0,2.6-0.5,4.7-1.6,6.4
		c-1,1.7-2.7,3.1-4.9,4.1l7.7,14.9v0.4h-9.5L440.8,114.3z M436,107.4h5.6c1.7,0,3-0.4,3.8-1.3c0.9-0.9,1.3-2.1,1.3-3.7
		s-0.4-2.8-1.3-3.8c-0.9-0.9-2.1-1.4-3.8-1.4H436V107.4z"/>
                 <path class="logo-fill-accent" d="M473.8,90.4l8.2,25.7l8.2-25.7h11.7v36.9H493v-8.6l0.9-17.7l-8.9,26.3h-5.9l-9-26.3l0.9,17.7v8.6h-8.9V90.4
		H473.8z"/>
                 <path class="logo-fill-accent" d="M539.9,109.6c0,3.6-0.7,6.8-2,9.6c-1.3,2.8-3.2,4.9-5.7,6.4c-2.5,1.5-5.3,2.2-8.4,2.2s-5.9-0.7-8.4-2.2
		c-2.4-1.4-4.3-3.5-5.7-6.2c-1.4-2.7-2.1-5.8-2.1-9.3v-2.1c0-3.6,0.7-6.8,2-9.6s3.2-4.9,5.7-6.4c2.5-1.5,5.3-2.3,8.5-2.3
		c3.1,0,5.9,0.7,8.4,2.2c2.5,1.5,4.4,3.6,5.7,6.4c1.4,2.7,2.1,5.9,2.1,9.5V109.6z M530.9,108.1c0-3.7-0.6-6.5-1.8-8.4
		s-3-2.9-5.3-2.9c-4.5,0-6.8,3.3-7.1,10l0,2.7c0,3.6,0.6,6.4,1.8,8.3c1.2,1.9,3,2.9,5.4,2.9c2.2,0,4-1,5.2-2.9
		c1.2-1.9,1.8-4.7,1.9-8.2V108.1z"/>
                 <path class="logo-fill-accent" d="M576.4,127.3h-8.9l-13.1-23v23h-8.9V90.4h8.9l13.1,23v-23h8.9V127.3z"/>
                 <path class="logo-fill-accent" d="M593.6,127.3h-8.9V90.4h8.9V127.3z"/>
                 <path class="logo-fill-accent" d="M624.7,111.7h-14v8.8h16.5v6.9h-25.5V90.4h25.5v6.9h-16.6v7.8h14V111.7z"/>
                 <path class="logo-fill-white" d="M254.1,179.3h-16.8l-3.8,10.4h-3.3l14-36.9h3l14,36.9h-3.2L254.1,179.3z M238.3,176.7h14.8l-7.4-20.1
		L238.3,176.7z"/>
                 <path class="logo-fill-white" d="M286.6,179.3h-16.8l-3.8,10.4h-3.3l14-36.9h3l14,36.9h-3.2L286.6,179.3z M270.8,176.7h14.8l-7.4-20.1
		L270.8,176.7z"/>
                 <path class="logo-fill-white" d="M325,178.2c-0.4,3.9-1.8,6.9-4.2,8.9s-5.5,3.1-9.4,3.1c-2.7,0-5.2-0.7-7.3-2.1s-3.7-3.3-4.9-5.8
		c-1.2-2.5-1.7-5.4-1.8-8.6v-4.8c0-3.3,0.6-6.2,1.7-8.7c1.2-2.5,2.8-4.5,5-5.9s4.6-2.1,7.4-2.1c4,0,7.1,1.1,9.4,3.2
		c2.3,2.1,3.6,5.1,4,8.9h-3.1c-0.8-6.3-4.2-9.4-10.2-9.4c-3.3,0-6,1.3-8,3.8c-2,2.5-3,6-3,10.4v4.5c0,4.3,1,7.7,2.9,10.2
		c1.9,2.5,4.6,3.8,7.9,3.8c3.3,0,5.7-0.8,7.4-2.3c1.7-1.6,2.7-3.9,3.1-7H325z"/>
                 <path class="logo-fill-white" d="M359.9,189.7h-3.1V172h-21.1v17.7h-3.1v-36.9h3.1v16.6h21.1v-16.6h3.1V189.7z"/>
                 <path class="logo-fill-white" d="M389.7,172h-17.3v15.1h19.9v2.6h-23v-36.9h22.9v2.6h-19.7v14h17.3V172z"/>
                 <path class="logo-fill-white" d="M426.4,189.7h-3.1L402,158.1v31.6h-3.1v-36.9h3.1l21.3,31.6v-31.6h3.1V189.7z"/>
             </g>
                <g>
                    <path class="logo-fill-accent" d="M107.2,197.5c-37.1,0-68.9-22.8-82.2-55.1c-3.4-7.8-5.6-16.3-6.3-25.1c-0.3-2.9-0.4-5.8-0.4-8.7
		c0-49,39.9-89,88.9-89c49,0,88.9,39.9,88.9,89S156.2,197.5,107.2,197.5z M93.1,190.3c4.6,0.8,9.3,1.2,14,1.2
		c45.7,0,82.9-37.2,82.9-82.9c0-45.7-37.2-83-82.9-83c-19.1,0-36.6,6.5-50.7,17.3C68.6,35.5,82.8,31.2,98,31.2
		c43.9,0,79.6,35.7,79.6,79.6s-35.7,79.6-79.6,79.6C96.4,190.4,94.8,190.4,93.1,190.3z M44.5,161.3C58,175.5,77,184.4,98,184.4
		c40.6,0,73.6-33,73.6-73.6c0-39.4-31.2-71.7-70.1-73.5C134.7,43,160,72,160,106.8c0,13.4-3.7,25.9-10.2,36.5
		c-3.9,7.1-9.3,13.3-15.8,18.2c-12.2,9.9-27.7,15.9-44.6,15.9C72.4,177.4,56.7,171.4,44.5,161.3z M25,101.6
		c-0.1,1.7-0.2,3.4-0.2,5.2c0,34.9,27.8,63.4,62.5,64.6c-25.2-5.8-44-28.4-44-55.4c0-31.3,25.5-56.8,56.8-56.8
		c24.2,0,44.8,15.2,53,36.5c-5.3-30.3-31.8-53.5-63.6-53.5c-17.9,0-34.1,7.3-45.8,19.1C33.6,72.2,26.9,86.2,25,101.6z M100,65.3
		c-28,0-50.8,22.8-50.8,50.8S72,166.9,100,166.9c11.3,0,21.8-3.7,30.3-10c5.6-4.6,10.5-10.2,14.3-16.5c4-7.2,6.2-15.5,6.2-24.3
		C150.8,88,128,65.3,100,65.3z"/>
                </g>
</svg></a>
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
    <section class="copyright text-center">
    &copy;  2022 Bläserphilharmonie Aachen e.V.
</section>
<?php
}

function the_recording_row(){
    ?>

    <section class="recordings">
        <div class="container">
            <div class="row"><h3 class="col-12 h2">Konzertmitschnitte</h3></div>
            <div class="row recordings-row"></div>
            <div class="row recordings-items">
                <?php
                $args = array(
                    'posts_per_page'   => 10,
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
                    <a href="<?=get_post_type_archive_link("recording")?>" class="btn btn-outline-light">weitere Konzertmitschnitte</a>
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
