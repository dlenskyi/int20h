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
		  "url": "https://api.flickr.com/services/rest/?method=flickr.photos.search&api_key=251146cadaed129b1f813d5b97b3ce6b&user_id=144522605%40N06&tag_mode=int20h&in_gallery=72157674388093532&format=json&nojsoncallback=1&auth_token=72157706600941354-d7e4d9891e2b3a96&api_sig=e8a10aaa40216cc0b5038d23748b00ad",
		  "method": "GET",
		  // "data": {
		  // 	"api_key": '3ipmRrYkT6eA2dNtwh1Vuhyu_aWagf4J', // api key
		  //   "api_secret": 'ev5ufkXSjnQCZoO9XCXXfl-w0nd325Jg',  // api secret
		  //   "image_url": 'https://c1.staticflickr.com/6/5719/30586950936_707c8a7f31_h.jpg', 
		  //   "return_landmark": '1',  //return value
		  //   "return_attributes": 'gender,emotion' // return atribut
		  // },
		  "headers": {}
		}
		
		$.ajax(settings).done(function (data) {
		  console.log(data);
		
		
		$("#photosetTitle").append(data.photos.photo[0].title + " Photoset");
		    	$.each( data.photos.photo, function( i, gp ) {
		
		var farmId = gp.farm;
		var serverId = gp.server;
		var id = gp.id;
		var secret = gp.secret;

		var str = 'https://farm';
		var info = new FormData();
		info.append('api_key', '3ipmRrYkT6eA2dNtwh1Vuhyu_aWagf4J');
		info.append('api_secret', 'ev5ufkXSjnQCZoO9XCXXfl-w0nd325Jg');
		info.append('image_url', str.concat(farmId, '.staticflickr.com/', serverId, '/', id, '_', secret, '.jpg'));
		info.append('return_landmark', '1');
		info.append('return_attributes', 'gender,emotion');
		
		var xhr = new XMLHttpRequest();
		
		xhr.open('POST', 'https://api-us.faceplusplus.com/facepp/v3/detect', true);
		xhr.onload = function () {
		    // do something to response
		    console.log(this.responseText);
		};
		xhr.setRequestHeader("Access-Control-Allow-Origin", "x-requested-with, x-requested-by");
		// xhr.setRequestHeader("Content-type", "text/plain");
		xhr.send();
		
		// $.ajax({
		//     type: 'POST',
		//     // make sure you respect the same origin policy with this url:
		//     // http://en.wikipedia.org/wiki/Same_origin_policy
		//     url: 'http://nakolesah.ru/',
		//     data: {
		//   	"api_key": '3ipmRrYkT6eA2dNtwh1Vuhyu_aWagf4J', // api key
		//     "api_secret": 'ev5ufkXSjnQCZoO9XCXXfl-w0nd325Jg',  // api secret
		//     "image_url": str.concat(farmId, '.staticflickr.com/', serverId, '/', id, '_', secret, '.jpg'), 
		//     "return_landmark": '1',  //return value
		//     "return_attributes": 'gender,emotion' // return atribut
		//   },
  //   success: function(msg){
  //       alert('wow' + msg);
  //   }
// });

		console.log(farmId + ", " + serverId + ", " + id + ", " + secret);
		
		//  https://farm{farm-id}.staticflickr.com/{server-id}/{id}_{secret}.jpg

		// var url = str.concat(farmId, '.staticflickr.com/', serverId, '/', id, '_', secret, '.jpg');
		// document.write(url);

		$("#flickr").append('<img src="https://farm' + farmId + '.staticflickr.com/' + serverId + '/' + id + '_' + secret + '.jpg"/>');
		
			});
		});



</script>

<!-- <?php

// $url = 'https://api-us.faceplusplus.com/facepp/v3/detect';
// $data = array(
//     'api_key' => '3ipmRrYkT6eA2dNtwh1Vuhyu_aWagf4J', // api key
//     'api_secret' => 'ev5ufkXSjnQCZoO9XCXXfl-w0nd325Jg',  // api secret
//     'image_url' => 'https://c1.staticflickr.com/6/5719/30586950936_707c8a7f31_h.jpg', // url for img parsing from page
//     //<img width="1060" height="707" src="//c1.staticflickr.com/6/5719/30586950936_707c8a7f31_h.jpg" class="main-photo is-hidden" alt="Hackathon INT20h 2016 Kyiv | by alexandra_yefimenko">
//     'return_landmark' => '1',  //return value
//     'return_attributes' => 'gender,emotion', // return atribut
// );

// $fields_string = http_build_query($data);
// $ch = curl_init();

//set the url, number of POST vars, POST data
// curl_setopt($ch,CURLOPT_URL, $url);
// curl_setopt($ch,CURLOPT_POST, count($data));
// curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);

// //So that curl_exec returns the contents of the cURL; rather than echoing it
// curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);

// //execute post

// $result = curl_exec($ch);

// $decod = json_decode($result);

// echo '<pre>';

// var_dump($decod);

// echo '</pre>';

// require_once('FppClient.php');

// use Fpp\FppClient;

// $host = "https://api-us.faceplusplus.com";
// $apiKey = "3ipmRrYkT6eA2dNtwh1Vuhyu_aWagf4J";
// $apiSecret = "ev5ufkXSjnQCZoO9XCXXfl-w0nd325Jg";

// $client = new FppClient($apiKey, $apiSecret, $host);

// $data = array(
// 	 'apiKey' => "3ipmRrYkT6eA2dNtwh1Vuhyu_aWagf4J",
// 	'apiSecret' => "ev5ufkXSjnQCZoO9XCXXfl-w0nd325Jg",
//     'image_url' => "https://www.faceplusplus.com.cn/scripts/demoScript/images/demo-pic8.jpg",
//     'return_landmark' => '2',
//     'return_attributes' => 'age,headpose'
// );

// $resp = $client->detectFace($data);
// print_r($resp);

?> -->

<h2><div id="photosetTitle"></div></h2>
<div style="clear:both;"/>
<div id="flickr"/>


</body>
</html>