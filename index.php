<?php

$folder = "guest";
$redirect = "http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'].$folder;
//echo $redirect;
header("Location: $redirect");

?>