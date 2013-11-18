<?php

// Put your device token here (without spaces):
$deviceToken  = '90ffdf1544c88ac1788840b0d29ee84c22c3a534eb5d3ffd113728ae8745f197';
$deviceToken1 = '6da5afdbe865121ecbb1c1c6d4e9dfbd0de5abf47e1357482a6c3d749d13f562';
$deviceToken2 = 'd5418d6c29839e39470b02e23e33029358515e4b4dff2e72b84314c5b5918b5f';

//6da5afdbe865121ecbb1c1c6d4e9dfbd0de5abf47e1357482a6c3d749d13f562 john
//90ffdf1544c88ac1788840b0d29ee84c22c3a534eb5d3ffd113728ae8745f197 xldz
// Put your private key's passphrase here:
$passphrase = '123456';

// Put your alert message here:
$message = 'new push test!!';
$message = utf8_encode($message);
////////////////////////////////////////////////////////////////////////////////

$ctx = stream_context_create();
stream_context_set_option($ctx, 'ssl', 'local_cert', 'xldz.pem');
stream_context_set_option($ctx, 'ssl', 'passphrase', $passphrase);

// Open a connection to the APNS server
$fp = stream_socket_client(
	'ssl://gateway.sandbox.push.apple.com:2195', $err,
	$errstr, 60, STREAM_CLIENT_CONNECT|STREAM_CLIENT_PERSISTENT, $ctx);

if (!$fp)
	exit("Failed to connect: $err $errstr" . PHP_EOL);

echo 'Connected to APNS' . PHP_EOL;

// Create the payload body
//$body['aps'] = array(
//	'alert' => $message,
//	'sound' => 'default'
//	);
	
$body['aps'] = array('alert' => $message, 'badge' => 1, 'sound' => 'default');

$device_tokens_array = array("90ffdf1544c88ac1788840b0d29ee84c22c3a534eb5d3ffd113728ae8745f197","6da5afdbe865121ecbb1c1c6d4e9dfbd0de5abf47e1357482a6c3d749d13f562");


// Encode the payload as JSON
$payload = json_encode($body);

// Build the binary notification
//$msg = chr(0) . pack('n', 32) . pack('H*', $deviceToken) . pack('n', strlen($payload)) . $payload;

// Send it to the server
//$result = fwrite($fp, $msg, strlen($msg));

$msg = chr(0) . pack('n', 32) . pack('H*', $deviceToken) . pack('n', strlen($payload)) . $payload;
$result = fwrite($fp, $msg, strlen($msg));

$msg1 = chr(0) . pack('n', 32) . pack('H*', $deviceToken1) . pack('n', strlen($payload)) . $payload;
$result = fwrite($fp, $msg1, strlen($msg1));

$msg2 = chr(0) . pack('n', 32) . pack('H*', $deviceToken2) . pack('n', strlen($payload)) . $payload;
$result = fwrite($fp, $msg2, strlen($msg2));


//for ($i=0; $i<=1; $i++)
//{
//echo 	$device_tokens_array[i];
// // Build the binary notification
//$msg = chr(0) . pack('n', 32) . pack('H*', $deviceToken) . pack('n', strlen($payload)) . $payload;
//
//// Send it to the server
//$result = fwrite($fp, $msg, strlen($msg));
//}


if (!$result)
	echo 'Message not delivered' . PHP_EOL;
else
	echo 'Message successfully delivered' . PHP_EOL;

// Close the connection to the server
fclose($fp);
