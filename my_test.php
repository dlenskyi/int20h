<!DOCTYPE html>
<html>
	<style>
	img {
		max-height: 205px;
		margin: 3px;
		border: 1px solid #dedede;
	}
	</style>
	<body>

	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>


	<script>

		var settings = {
		  "async": true,
		  "crossDomain": true,
		  "url": "https://api.flickr.com/services/rest/?method=flickr.photosets.getPhotos&api_key=2a947f62a60af27569de1a3dce51ec4a&photoset_id=72157674388093532&user_id=144522605%40N06&format=json&nojsoncallback=1",
		  "method": "GET",
		  "headers": {}
		}
		
		$.ajax(settings).done(function (data) {
		  console.log(data);
		
		
		$("#photosetTitle").append(data.photoset.photo[0].title + " Photoset");
		    	$.each( data.photoset.photo, function( i, gp ) {
		
		var farmId = gp.farm;
		var serverId = gp.server;
		var id = gp.id;
		var secret = gp.secret;
		
		console.log(farmId + ", " + serverId + ", " + id + ", " + secret);
		
		//  https://farm{farm-id}.staticflickr.com/{server-id}/{id}_{secret}.jpg
		
		// URL for tags: https://api.flickr.com/services/rest/?method=flickr.tags.getListUser&api_key=9a0c761a0f9917ec4fdf98588e5d8de8&user_id=144522605%40N06&format=json&nojsoncallback=1

		$("#flickr").append('<img src="https://farm' + farmId + '.staticflickr.com/' + serverId + '/' + id + '_' + secret + '.jpg"/>');
		
			});
		});

</script>

<?php

require_once('FppClient.php');

use Fpp\FppClient;

$host = "https://api-cn.faceplusplus.com";
$apiKey = "3ipmRrYkT6eA2dNtwh1Vuhyu_aWagf4J";
$apiSecret = "ev5ufkXSjnQCZoO9XCXXfl-w0nd325Jg";

$client = new FppClient($apiKey, $apiSecret, $host);

$data = array(
    'image_url' => "https://www.faceplusplus.com.cn/scripts/demoScript/images/demo-pic10.jpg",
    'return_landmark' => '2',
    'return_attributes' => 'age,headpose'
);

$resp = $client->detectFace($data);
print_r($resp);

?>

<h2><div id="photosetTitle"></div></h2>
<div style="clear:both;"/>
<div id="flickr"/>


</body>
</html>