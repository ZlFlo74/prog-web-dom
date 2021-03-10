<?php 

include_once('tp3-helpers.php');

$url_component = "movie/550" ;
$params = "";
$response = tmdbget($url_component, $params);

$response_array = json_decode($response);

print_r($response_array);

?>