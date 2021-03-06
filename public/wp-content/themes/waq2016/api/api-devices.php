<?php
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept');
  exit;
}

$postdata = file_get_contents("php://input");
$device = json_decode($postdata);

$postArgs = array(
    'post_status' => 'publish',
    'post_title' => $device->uuid,
    'post_type' => 'device',
    'post_date' => date("Y-m-d H:i:s")
);

if ($id = waq2016_device_exists($device->uuid)) {
    //Post arguments generaux
    $postArgs['ID'] = $id;
}

if (isset($postArgs['ID'])) {
} else {
    $id = wp_insert_post($postArgs);
}

if(isset($device->notifid)){
    update_post_meta($id, 'notifid', ($device->notifid ? $device->notifid : ""));
}

if(isset($device->type)){
    update_post_meta($id, 'type', ($device->type ? $device->type : ""));
}

if(isset($device->location)){
    update_post_meta($id, 'location', ($device->location ? $device->location : ""));
    update_post_meta($id, 'lastUpdate', ($device->lastUpdate ? $device->lastUpdate : ""));
}

if(isset($device->schedule)){
    update_post_meta($id, 'schedule', ($device->schedule ? $device->schedule : ""));
}

$post = get_post($id);
$metas = get_post_meta($post->ID);
$post = array_merge((array) $post,(array) $metas);
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
echo json_encode($post);

function waq2016_device_exists($identifier) {
    global $wpdb;
    $post = $wpdb->get_row("SELECT ID FROM " . $wpdb->posts . " WHERE post_type IN ('device') AND post_title='" . $identifier . "'");
    return  ($post) ? $post->ID : false;
}
