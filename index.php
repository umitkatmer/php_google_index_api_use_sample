<?php

//ini_set('display_errors', '1');
//ini_set('display_startup_errors', '1');
//error_reporting(E_ALL);

include("vendor/autoload.php");


$url_datas = array(
"",

);

$client = new Google_Client();
$client->setAuthConfig('pragmatic-braid-333209-5008403fc5dc.json');
$client->addScope('https://www.googleapis.com/auth/indexing');
$client->setUseBatch(true);

$finish_data = array();
foreach($url_datas as $url_data){

//init google batch and set root URL
$batch = new Google_Http_Batch($client,false,'https://indexing.googleapis.com');

//init service Notification to sent request
$postBody = new Google_Service_Indexing_UrlNotification();
$postBody->setType('URL_UPDATED');
$postBody->setUrl($url_data);

//init service Indexing ( like updateJobPosting )
$service = new Google_Service_Indexing($client);
//create request
//$service->urlNotifications->createRequestUri('https://indexing.googleapis.com/batch');
$request_kame = $service->urlNotifications->publish($postBody);
//add request to batch
$batch ->add($request_kame);

$results   = $batch->execute();

$res_count = count( $results );
$data      = [];
foreach ( $results as $id => $response ) {
	// Change "response-url-1" to "url-1".
	$local_id = substr( $id, 9 );
	if ( is_a( $response, 'Google_Service_Exception' ) ) {
		$data[ $local_id ] = json_decode( $response->getMessage() );
	} else {
		$data[ $local_id ] = (array) $response->toSimpleObject();
	}
	if ( $res_count === 1 ) {
		$data = $data[ $local_id ];
	}
}

$finish_data[] = $data;

}

print_r($finish_data);


?>
