<?php


class RecordingMetaBox
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
            'recording_meta',
            __( 'Autnahme', 'text_domain' ),
            array( $this, 'render_metabox' ),
            'recording',
            'advanced',
            'default'
        );

    }

    public function render_metabox( $post ) {

        // Add nonce for security and authentication.

        wp_nonce_field( 'recording_nonce_action', 'recording_nonce' );

        // Retrieve an existing value from the database.
        $recording_composer = get_post_meta( $post->ID, 'recording_composer', true );
        $recording_youtube_id = get_post_meta( $post->ID, 'recording_youtube_id', true );
        $recording_date = get_post_meta( $post->ID, 'recording_date', true );


        $recording_composer = empty($recording_composer) ? "" : $recording_composer;
        $recording_youtube_id= empty($recording_youtube_id) ? "" : $recording_youtube_id;
        $recording_date= empty($recording_date) ? "" : date("Y-m-d", $recording_date);


        // Form fields.
        echo '<table class="form-table">';
        echo ' <tr>';
        echo '		<th><label for="recording_composer" class="recording_composer_label">' . __( 'Komponist', 'text_domain' ) . '</label></th>';
        echo '		<td>';
        echo '			<input type="text" id="recording_composer" name="recording_composer" class="concert_date_field" placeholder="' . esc_attr__( 'Komponist', 'text_domain' ) . '" value="' . esc_attr__( $recording_composer ) . '">';
        echo '			<p class="description">' . __( 'Komponist', 'text_domain' ) . '</p>';
        echo '		</td>';
        echo '	</tr>';
        echo '	<tr>';
        echo '		<th><label for="recording_youtube_id" class="recording_youtube_id_label">' . __( 'Aufnahme Datum', 'text_domain' ) . '</label></th>';
        echo '		<td>';
        echo '			<input type="date" id="recording_date" name="recording_date" class="recording_date" placeholder="' . esc_attr__( 'Aufnahme Datum', 'text_domain' ) . '" value="' . esc_attr__( $recording_date) . '">';
        echo '			<p class="description">' . __( 'Aufnahme Datum', 'text_domain' ) . '</p>';
        echo '		</td>';
        echo '	</tr>';
        echo '  <tr>';
        echo '		<th><label for="recording_youtube_id" class="recording_youtube_id_label">' . __( 'Youtube Video-Id', 'text_domain' ) . '</label></th>';
        echo '		<td>';
        echo '			<input type="string" id="recording_youtube_id" name="recording_youtube_id" class="recording_youtube_id" placeholder="' . esc_attr__( 'Video-Id', 'text_domain' ) . '" value="' . esc_attr__( $recording_youtube_id) . '">';
        echo '			<p class="description">' . __( 'https://www.youtube.com/watch?v=<video-id> (z.B. https://www.youtube.com/watch?v=9bYk1mabKqI -> 9bYk1mabKqI)', 'text_domain' ) . '</p>';
        echo '		</td>';
        echo '	</tr>';
        echo '</table>';


    }

    public function save_metabox( $post_id, $post )
    {

        // Check if a nonce is set.
        if (!isset($_POST['recording_nonce']))
            return;

        // Add nonce for security and authentication.
        $nonce_name = $_POST['recording_nonce'];
        $nonce_action = 'recording_nonce_action';

        // Check if a nonce is valid.
        if (!wp_verify_nonce($nonce_name, $nonce_action))
            return;

        // Check if the user has permissions to save data.
        if (!current_user_can('edit_post', $post_id))
            return;




        $location_id = isset( $_POST[ 'concert_location_id' ] ) ? (int) $_POST['concert_location_id']  : '';

        // Retrieve an existing value from the database.
        $recording_composer = isset( $_POST[ 'recording_composer' ] ) ? esc_attr($_POST['recording_composer'])  : '';
        $recording_youtube_id = isset( $_POST[ 'recording_youtube_id' ] ) ? esc_attr($_POST['recording_youtube_id'])  : '';
        $recording_date = isset( $_POST[ 'recording_date' ] ) ? strtotime($_POST[ 'recording_date' ], time())  : '';


        // Update the meta field in the database.
        update_post_meta( $post_id, 'recording_composer', $recording_composer) ;
        update_post_meta( $post_id, 'recording_youtube_id',  $recording_youtube_id);
        update_post_meta( $post_id, 'recording_date',  $recording_date);

    }

}


function add_recording_post_meta(){
    new RecordingMetaBox();
}