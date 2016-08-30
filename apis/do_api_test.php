<?php

require_once './digitalocean_api.php';
$api = new DigitaloceanApi("your api key here");
echo "<pre>";


echo "<h2>Images</h1>";
// shows all images available in your account, you should take the image id or slug (if slug is available)
$images = $api->getGetResults("images");
print_r($images);
echo "<hr>";



echo "<h2>Regions</h1>";
// shows all regions available for your account, you should take the region id or slug (if slug is available)
$regions = $api->getGetResults("regions");
print_r($regions);
