<?php
require_once "Location.php";
require_once "locations_db.php";


class ConcertMetaBox
{

    public function __construct() {

        if ( is_admin() ) {
            add_action( 'load-post.php',     array( $this, 'init_metabox' ) );
            add_action( 'load-post-new.php', array( $this, 'init_metabox' ) );
        }

    }

    public function init_metabox() {

        add_action( 'add_meta_boxes', array( $this, 'add_metabox'  )        );
        add_action( 'save_post',      array( $this, 'save_metabox' ), 10, 2 );

    }

    public function add_metabox() {

        add_meta_box(
            'concert_meta',
            __( 'Konzert', 'text_domain' ),
            array( $this, 'render_metabox' ),
            'concert',
            'advanced',
            'default'
        );

    }

    public function render_metabox( $post ) {

        // Add nonce for security and authentication.

        wp_nonce_field( 'concert_nonce_action', 'concert_nonce' );

        // Retrieve an existing value from the database.
        $concert_datetimes = get_post_meta( $post->ID, 'concert_datetimes', true );
        $concert_location = get_post_meta( $post->ID, 'concert_location', true );
        $concert_location_id = get_post_meta( $post->ID, 'concert_location_id', true );
        $concert_tickets_on_sell = get_post_meta( $post->ID, 'concert_tickets_on_sell', true );
        $concert_is_project_concert = get_post_meta( $post->ID, 'concert_is_project_concert', true );



        $concertDates = array();
        $concertTimes = array();

        $datesCount = 0;
        if( !empty( $concert_datetimes ) ){

            $datesCount  = sizeof($concert_datetimes);
        }



        for($i=0;$i < $datesCount ; $i ++ ){
            $concert_datetime = $concert_datetimes[$i];


            $concertDates[$i] = date("Y-m-d", $concert_datetime);
            $concertTimes[$i] = date("H:i", $concert_datetime);
        }
        $concertDates[] = "";
        $concertTimes[] = "";


        if( empty( $concert_location ) ){
            $concert_location = new Location();
        }


            // Set default values.


        // Form fields.
        echo '<table class="form-table">';


        for($i=0;$i < sizeof($concertDates) ; $i ++ ){
            $number = $i + 1;


            ?>
                <tr>
                    <th>Termin <?= $number ?></th>
                    <td style="display: flex">
                        <div>
                            <input type="date" id="concert_date" name="concert_date[]" class="concert_date_field" placeholder="<?= esc_attr__( 'Konzert Datum', 'text_domain' )?>" value="<?= esc_attr__( $concertDates[$i] ) ?>">
                            <p class="description"><?= __( 'Datum', 'text_domain' ) ?></p>

                        </div>
                        <div>
                            <input type="time" id="concert_time" name="concert_time[]" class="concert_date_field" placeholder="<?= esc_attr__( 'Konzert Uhrzeit', 'text_domain' ) ?>" value="<?= esc_attr__( $concertTimes[$i] ) ?>">
                            <p class="description"><?= __( 'Uhrzeit', 'text_domain' ) ?></p>
                        </div>
                    </td>
                </tr>
            <?php
        }

        ?>

        <tr>
            <th><label for="concert_tickets_on_sell" class="concert_tickets_on_sell"><?= __( 'Ticketverkauf', 'text_domain' ) ?></label></th>
            <td>
                <input type="checkbox" id="concert_tickets_on_sell" name="concert_tickets_on_sell" class="concert_tickets_on_sell" value="checked"<?= $concert_tickets_on_sell ?' checked':'' ?>> <label for="concert_tickets_on_sell">Ticketverkauf online</label>
            </td>
        </tr>
        <tr>
            <th><label for="concert_is_project_concert" class="concert_is_project_concert"><?= __( 'Arbeitsphasen Konzert', 'text_domain' ) ?></label></th>
            <td>
                <input type="checkbox" id="concert_is_project_concert" name="concert_is_project_concert" class="concert_is_project_concert" value="checked"<?= $concert_is_project_concert ?' checked':'' ?>> <label for="concert_is_project_concert">Ist eine Arbeitsphasen Konzert</label>
            </td>
        </tr>
        <?php


        echo '	<tr>';
        echo '		<th><label for="concert_location_id" class="concert_price_label">' . __( 'Veranstaltungsort', 'text_domain' ) . '</label></th>';
        echo '		<td>';
        echo '			<select id="concert_location_id" name="concert_location_id">';
        echo '			<option disabled>Veranstaltung auswählen</option>';
        $locations = get_locations();

        foreach ($locations as $location){
            if($location instanceof Location){
                echo '<option value="' . $location->getId() . '"' . ($concert_location_id == $location->getId()? ' selected="selected" ': '') . '>' . $location->getName() . '</option>';
            }
        }
        echo '			</select>';
        echo '		</td>';
        echo '	</tr>';
        echo '</table>';
        ?>
            <script type="application/javascript">
                $(document).ready(function () {
                    var locationidElement = $('#concert-location-id');
                    locationidElement.change(function () {
                        
                    });
                });
            </script>
        <?php

    }

    public function save_metabox( $post_id, $post )
    {

        // Add nonce for security and authentication.
        // Check if a nonce is set.
        if (!isset($_POST['concert_nonce']))
            return;
        $nonce_name = $_POST['concert_nonce'];
        $nonce_action = 'concert_nonce_action';


        // Check if a nonce is valid.
        if (!wp_verify_nonce($nonce_name, $nonce_action))
            return;

        // Check if the user has permissions to save data.
        if (!current_user_can('edit_post', $post_id))
            return;


        $concert_dates = array();
        $concert_times = array();

        $datesNumber = isset($_POST['concert_date']) ? sizeof($_POST['concert_date']): 0;
        for ($i = 0;$i < $datesNumber;$i++){

            // Sanitize user input.
            $concert_date = isset($_POST['concert_date'][$i]) ? sanitize_text_field($_POST['concert_date'][$i]) : '';
            $concert_dates[] =  $concert_date;

        }

        for ($i = 0;$i < $datesNumber;$i++){

            // Sanitize user input.
            $concert_time= isset($_POST['concert_time'][$i]) ? sanitize_text_field($_POST['concert_time'][$i]) : '';
            $concert_times[] =  $concert_time;

        }

        // Sanitize user input.


        $location_id = isset( $_POST[ 'concert_location_id' ] ) ? (int) $_POST['concert_location_id']  : '';


        $concert_datetimes = array();
        for ($i = 0;$i < $datesNumber;$i++){

            if(!empty($concert_dates[$i]) && !empty($concert_times[$i])){
                $concert_datetimes[] = strtotime($concert_dates[$i]. " " .  $concert_times[$i], time());
            }

        }


        $concert_tickets_on_sell = isset($_POST["concert_tickets_on_sell"]) ? $_POST["concert_tickets_on_sell"] =="checked" : false;
        $concert_is_project_concert = isset($_POST["concert_is_project_concert"]) ? $_POST["concert_is_project_concert"] =="checked" : false;


        // Update the meta field in the database.
        update_post_meta( $post_id, 'concert_datetimes',$concert_datetimes) ;
        update_post_meta( $post_id, 'concert_location_id',  $location_id);
        update_post_meta( $post_id, 'concert_tickets_on_sell',  $concert_tickets_on_sell);
        update_post_meta( $post_id, 'concert_is_project_concert',  $concert_is_project_concert);

    }

}


function add_concert_post_meta(){
    new ConcertMetaBox();
}