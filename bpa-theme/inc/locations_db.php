<?php


global $location_db_version;
$location_db_version = '1.0';

function location_db_init(): void
{
    global $wpdb;
    global $location_db_version;

    $table_name = $wpdb->prefix . 'locations';

    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE $table_name (
		    id INT PRIMARY KEY AUTO_INCREMENT,
            name TINYTEXT,
            street TINYTEXT,
            city TINYTEXT,
            postal_code VARCHAR(10)
	) $charset_collate;";

    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    dbDelta( $sql );

    add_option( 'location_db_version ', $location_db_version );
}



function add_location($location) {
    global $wpdb;

    $table_name = $wpdb->prefix . 'locations';

    if($location instanceof Location){
        $wpdb->insert(
            $table_name,
            array(
                'name' => $location->getName(),
                'street' => $location->getStreet(),
                'city' => $location->getCity(),
                'postal_code' => $location->getPostalCode()
            )
        );
        return $wpdb->insert_id;
    }
}

function update_location($location): void
{
    global $wpdb;

    $table_name = $wpdb->prefix . 'locations';

    if ($location instanceof Location) {
        $wpdb->update(
            $table_name,
            array(
                'name' => $location->getName(),
                'street' => $location->getStreet(),
                'city' => $location->getCity(),
                'postal_code' => $location->getPostalCode()
            ),
            array('id' => $location->getId())
        );
    }
}
function get_locations($orderedAsc = false): array
{
    global $wpdb;
    $query = "SELECT * FROM ".$wpdb->prefix . "locations ORDER BY `name` " . ($orderedAsc?'ASC':'DESC') ;
    $rows = $wpdb->get_results( $query );
    $locations = array();
    foreach ($rows as $row){
        $location = getLocationFormResultRow($row);
        $locations[] = $location;
    }
    return $locations;

}
function get_location_by_id($id){
    global $wpdb;
    $query = $wpdb->prepare("SELECT * FROM ".$wpdb->prefix . "locations where id=%d",array($id));

    $rows = $wpdb->get_results( $query);
    if(sizeof($rows)>0){
        return getLocationFormResultRow($rows[0]);
    }
}

/**
 * @param $row
 * @return Location
 */
function getLocationFormResultRow($row): Location
{
    $location = new Location();
    $location->setId($row->id);
    $location->setName($row->name);
    $location->setStreet($row->street);
    $location->setCity($row->city);
    $location->setPostalCode($row->postal_code);
    return $location;
}