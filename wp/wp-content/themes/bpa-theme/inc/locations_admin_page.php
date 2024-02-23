<?php

require_once("locations_db.php");
require_once("Location.php");
/** Step 2 (from text above). */
add_action( 'admin_menu', 'my_plugin_menu' );

/** Step 1. */
function my_plugin_menu() {


    add_menu_page( 'Veranstaltungsorte verwalten', 'Veranstaltungsorte', 'manage_options', 'concert-locations', 'my_plugin_options','',6 );
    //add_options_page( 'My Plugin Options', 'My Plugin', 'manage_options', 'my-unique-identifier', 'my_plugin_options', );
}

/** Step 3. */
function my_plugin_options() {
    if ( !current_user_can( 'manage_options' ) )  {
        wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
    }
    $action = isset($_GET['action']) ? $_GET['action']:'';
    $base_locations_url = admin_url( 'admin.php?page=concert-locations');
    global $wpdb;


    ?>
    <div class="wrap">

        <?php
        switch ($action) {

            case "edit":

                $id = handle_location_form();

                if(empty($id) || $id == null) {
                    $id = $_GET['location-id'] ? $_GET['location-id'] : null ;
                }
                if(empty($id) || $id == null){
                    show_location_edit_form($base_locations_url, new Location());
                }
                else{
                    $location = get_location_by_id($id);
                    show_location_edit_form($base_locations_url, $location);
                }
                break;


            case "trash":


                $nonce_name   = $_GET['location_nonce_trash'];
                $nonce_action = 'location_trash';

                // Check if a nonce is set.
                if (  isset( $nonce_name ) &&  wp_verify_nonce( $nonce_name, $nonce_action ) ){
                    if (isset($_GET['location-id'])){
                        $id = $_GET['location-id'];
                        $wpdb->delete($wpdb->prefix . 'locations', array('id'=> $id));
                    }
                }

            default:
                $orderedAsc = isset($_GET['order']) && $_GET['order'] =="asc";
                show_location_list($base_locations_url , $orderedAsc);
        }
        ?>

    <?php
}

function show_location_list($base_url, $ordered_asc){
    $locations = get_locations($ordered_asc);
    ?>
    <h1 class="wp-heading-inline">Veranstaltungsorte</h1>

    <a href="<?= $base_url . '&action=edit' ?>" class="page-title-action">Erstellen</a>
    <hr class="wp-header-end">
    <h2 class='screen-reader-text'>Beitragsliste</h2>
    <table class="wp-list-table widefat fixed striped posts">
        <thead>
        <tr>
            <th scope="col" id='title' class='manage-column column-title column-primary sortable <?=( $ordered_asc ?  'desc' : 'asc')?>'>
                <a href="<?= $base_url . '&order=' .( $ordered_asc ?  'desc' : 'asc') ?>">
                    <span>Titel</span><span class="sorting-indicator"></span>
                </a>
            </th>
        </tr>
        </thead>

        <tbody id="the-list">

        <?php
        foreach ($locations as $location){
            if($location instanceof Location){
                $edit_link = $base_url . '&action=edit&location-id=' . $location->getId();
                $trash_link = wp_nonce_url( $base_url . '&action=trash&location-id=' . $location->getId(), "location_trash", "location_nonce_trash");
                ?>

                <tr id="locations-<?= $location->getId() ?>" class="iedit author-self level-0 locations-<?= $location->getId() ?> type-concert status-publish has-post-thumbnail hentry">
                    <td class="title column-title has-row-actions column-primary page-title" data-colname="Titel">
                        <strong>
                            <a class="row-title" href="<?= $edit_link ?>" aria-label="<?=$location->getName()?>(Bearbeiten)">
                                <?=$location->getName()?>
                            </a>
                        </strong>


                        <div class="row-actions">
                            <span class='edit'><a href="<?= $edit_link ?>" aria-label="<?=$location->getName()?> bearbeiten">Bearbeiten</a> | </span>
                            <span class='trash'><a href="<?= $trash_link ?>" class="submitdelete" aria-label="<?=$location->getName()?> unwiederuflich Löschen">unwiederuflich Löschen</a></span>
                        </div>
                    </td>
                </tr>

            <?php }} ?>
        </tbody>

        <tfoot>
        <tr>
            <td>#<?=sizeof($locations)?></td>
        </tr>
        </tfoot>

    </table>
    </div>

    <?php
}


function show_location_edit_form($base_url, $location){
    if(!$location instanceof Location){
       return;
    }

    ?>
    <h1>Veranstaltungsorte > <?=empty($location->getId())? "erstellen":"bearbeiten"?></h1>
    <form action="<?=$base_url . '&action=edit' . (!empty($location->getId()) ? '&location-id=' . $location->getId() : '' )?>" method="post">
        
        <?php
        wp_nonce_field( 'location_nonce_action', 'location_nonce' );
        ?>
        <input type="hidden" name="location-id" value="<?= $location->getId() ?>">
        <input type="hidden" name="location_form_action" value="<?= (empty($location->getId())?"add":"update" )?>">


        <table class="form-table">

            <tr class="new-event-location">
                <th>
                    <label for="location_name" class="price_label"><?= __( 'Veranstaltungsort', 'text_domain' ) ?></label>
                </th>
                <td>
                    <input type="text" id="location_name" name="location_name" class="location_name_field" placeholder="<?=esc_attr__( 'Veranstaltungsort', 'text_domain' )?>" value="<?=esc_attr__( $location->getName() )?>">
                    <p class="description"><?= __( 'Veranstaltungsort', 'text_domain' ) ?></p>
                </td>
            </tr>
            <tr class="new-event-location">
                <th>
                    <label for="location_street" class="price_label"><?= __( 'Straße', 'text_domain' ) ?></label>
                </th>
                <td>
                    <input type="text" id="location_street" name="location_street" class="location_street_field" placeholder="<?=esc_attr__( 'Straße', 'text_domain' )?>" value="<?=esc_attr__( $location->getStreet() )?>">
                    <p class="description"><?= __( 'Straße', 'text_domain' )  ?></p>
                </td>
            </tr>

            <tr class="new-event-location">
                <th>
                    <label for="location_city" class="price_label"><?= __( 'Ort', 'text_domain' ) ?></label>
                </th>
                <td>
                    <input type="text" id="location_city" name="location_city" class="location_street_field" placeholder="<?=esc_attr__( 'Ort', 'text_domain' )?>" value="<?=esc_attr__( $location->getCity() )?>">
                    <p class="description"><?= __( 'Ort', 'text_domain' ) ?></p>
                </td>
            </tr> <tr class="new-event-location">
                <th>
                    <label for="location_city" class="price_label"><?= __( 'PLZ', 'text_domain' ) ?></label>
                </th>
                <td>
                    <input type="text" id="location_postalcode" name="location_postalcode" class="location_postalcode_field" placeholder="<?=esc_attr__( 'PLZ', 'text_domain' )?>" value="<?=esc_attr__( $location->getPostalCode() )?>">
                    <p class="description"><?= __( 'PLZ', 'text_domain' ) ?></p>
                </td>
            </tr>
        </table>
        <p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary" value="Speichert"></p>
    </form>


    <?php

}

function handle_location_form(){
    if($_SERVER['REQUEST_METHOD'] != "POST"){
        return null;
    }

    $nonce_name   = $_POST['location_nonce'];
    $nonce_action = 'location_nonce_action';

    // Check if a nonce is set.
    if ( ! isset( $nonce_name ) )
        return null;

    // Check if a nonce is valid.
    if ( ! wp_verify_nonce( $nonce_name, $nonce_action ) )
        return null;


    $location_form_action = isset( $_POST[ 'location_form_action' ] ) ? sanitize_text_field( $_POST[ 'location_form_action' ] ) : '';
    $location_name = isset( $_POST[ 'location_name' ] ) ? sanitize_text_field( $_POST[ 'location_name' ] ) : '';
    $location_street = isset( $_POST[ 'location_street' ] ) ? sanitize_text_field( $_POST[ 'location_street' ] ) : '';
    $location_city = isset( $_POST[ 'location_city' ] ) ? sanitize_text_field( $_POST[ 'location_city' ] ) : '';
    $location_postalcode = isset( $_POST[ 'location_postalcode' ] ) ? sanitize_text_field( $_POST[ 'location_postalcode' ] ) : '';

    $location_street = str_ireplace("\\'","'", $location_street);
    $location = new Location($location_name,$location_street,$location_city,$location_postalcode);

    switch ($location_form_action){
        case "add":
            return add_location($location);
        case "update":
            $location_id = (int) $_POST['location-id'];
            $location->setId((int)$location_id);
            update_location($location);
            return $location_id;
    }
}
?>