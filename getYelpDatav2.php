<?php

// Authors : Robert Walport, Rashmi Raman
// Code to get restaurant data from the Yelp API using OAuth with a specific location and page offset



// Enter the path that the oauth library is in relation to the php file
require_once ('lib/OAuth.php');

$opt = $_POST["Location"];
$locations = array(
    "hellskitchen"=>"Hells Kitchen",
	"chinatown"=>"Chinatown",
	"flushing"=>"Flushing",
	"chelsea"=>"Chelsea%20Manhattan",
"uws"=>"Upper West Side",
);

$loc = $locations[$opt];


$filename = "C:Users/Caesar/Documents/Caesar/acads/FrontiersOfComputationalJournalism/Assignment1/YelpData/bk/".$opt".csv";
$file = fopen( $filename, "w" );
if( $file == false )
{
   echo ( "Error in opening new file" );
   exit();
}

$filestr = "restaurant,street_address,city_zip,comments\n";
//$filestr = "";

for($offset=3;$offset<=3;$offset++){

echo $offset;

	// For example, search for 'restaurants' in 'Flushing, Queens'
	$unsigned_search_url = "http://api.yelp.com/v2/search?term=restaurants&category_filter=restaurants&location={$loc}%20New%20York&offset={$offset}";


	// OAUTH keys
	$consumer_key = "cly3yug4ZJislcMSL7z5Ig";
	$consumer_secret = "TYtSHzO0GypmldpPuYhUANODJ1g";
	$token = "mcT-A8YWEHF5djZvP-5tYIfO7p17stfv";
	$token_secret = "5C_AOoRPX1VFwdDzmhwqJZBAWuE";

	//$consumer_key = "FYn2U-z-yhtL433V33OdbQ";
	//$consumer_secret = "DUky5ZAYrLCYoXi90MwS_KH2FGo";
	//$token = "9d6sS44jQwHhGX11br_XQMm6fAELBQyQ";
	//$token_secret = "p6EroQVsmSq46sn0EQ6sdeRbz5c";

	// Token object built using the OAuth library
	$token = new OAuthToken($token, $token_secret);

	// Consumer object built using the OAuth library
	$consumer = new OAuthConsumer($consumer_key, $consumer_secret);

	// Yelp uses HMAC SHA1 encoding
	$signature_method = new OAuthSignatureMethod_HMAC_SHA1();

	// Build OAuth Request using the OAuth PHP library. Uses the consumer and token object created above.
	$oauthrequest = OAuthRequest::from_consumer_and_token($consumer, $token, 'GET', $unsigned_search_url);

	// Sign the request
	$oauthrequest->sign_request($signature_method, $consumer, $token);

	// Get the signed URL
	$signed_url = $oauthrequest->to_url();

	// Send Yelp API Call
	$ch = curl_init($signed_url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_HEADER, 0);
	$data = curl_exec($ch); // Yelp response
	curl_close($ch);



	// Handle Yelp response data
	$response = json_decode($data);
    //print_r($response->businesses);


	foreach($response->businesses as $business){
		$id = $business->id;
		$busstr = getBusinessDetails($id);
		$filestr = $filestr.$busstr ."\n";
	}
	echo "=========================================================================================================================================<br />";
	echo $filestr;
}


fwrite( $file, $filestr);

fclose( $file );

echo "Done";




function getBusinessDetails($id){

// For example, Yelp Business Search API requires the Yelp ID of the establishment
$unsigned_bus_url = "http://api.yelp.com/v2/business/{$id}";


// OAUTH Keys
$consumer_key = "cly3yug4ZJislcMSL7z5Ig";
$consumer_secret = "TYtSHzO0GypmldpPuYhUANODJ1g";
$token = "mcT-A8YWEHF5djZvP-5tYIfO7p17stfv";
$token_secret = "5C_AOoRPX1VFwdDzmhwqJZBAWuE";

/* $consumer_key = "FYn2U-z-yhtL433V33OdbQ";
$consumer_secret = "DUky5ZAYrLCYoXi90MwS_KH2FGo";
$token = "9d6sS44jQwHhGX11br_XQMm6fAELBQyQ";
$token_secret = "p6EroQVsmSq46sn0EQ6sdeRbz5c"; */

// Token object built using the OAuth library
$token = new OAuthToken($token, $token_secret);

// Consumer object built using the OAuth library
$consumer = new OAuthConsumer($consumer_key, $consumer_secret);

// Yelp uses HMAC SHA1 encoding
$signature_method = new OAuthSignatureMethod_HMAC_SHA1();

// Build OAuth Request using the OAuth PHP library. Uses the consumer and token object created above.
$oauthrequest = OAuthRequest::from_consumer_and_token($consumer, $token, 'GET', $unsigned_bus_url);

// Sign the request
$oauthrequest->sign_request($signature_method, $consumer, $token);

// Get the signed URL
$signed_url = $oauthrequest->to_url();

// Send Yelp API Call
$ch = curl_init($signed_url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HEADER, 0);
$data = curl_exec($ch); // Yelp response
curl_close($ch);

// Handle Yelp response data
$response = json_decode($data);

$busstr = "";

$busstr = $busstr.$response->name.",";
$busstr = $busstr.$response->location->display_address[0]." ";
$busstr = $busstr.$response->location->display_address[1]." ";
$busstr = $busstr.$response->location->display_address[2]." ";
$busstr = $busstr.$response->location->display_address[3].",";
$review_str = "";
foreach($response->reviews as $review){
     $review_str = $review_str.$review->excerpt;
}
$review_str = str_replace(","," ",$review_str);
$review_str = str_replace("\n"," ",$review_str);
$busstr = $busstr.$review_str;

return $busstr;

}


?>
