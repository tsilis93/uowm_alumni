<?php
$done = true;

$phone = $_POST['phone'];
$message = $_POST['message'];
$authcode = 244112;


$url = 'http://vlsi.gr/sms/webservice/process.php';
$data = array('authcode' => $authcode, 'mobilenr' => $phone, 'message' => $message);

$options = array(
    'http' => array(
        'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
        'method'  => 'POST',
        'content' => http_build_query($data)
    )
);
$context  = stream_context_create($options);
$result = file_get_contents($url, false, $context);
if ($result === FALSE) { 
	/* Handle error */
	$done = false;
	echo $done; 
}
else
{
	echo $done;
//var_dump($result);
}