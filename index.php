<?php

include("vendor/autoload.php");

$client = new Google_Client();
 
$client->setAuthConfig('*****.json');


$client->addScope('https://www.googleapis.com/auth/indexing');
$client->setUseBatch(true);


$batch = new Google_Http_Batch($client,false,'https://indexing.googleapis.com');


$postBody = new Google_Service_Indexing_UrlNotification();
$postBody->setType('URL_UPDATED'); // URL_DELETED


$postBody->setUrl('https://wwww*****');


$service      = new Google_Service_Indexing($client);

$request_kame = $service->urlNotifications->publish($postBody);

$batch->add($request_kame);

$results   = $batch->execute();
$data      = [];
$res_count = count( $results );

foreach ( $results as $id => $response ) {

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

print_r($data);

?>