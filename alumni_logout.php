<?php

session_start();
unset($_SESSION['student']);  // **session_unset();**
if(isset($_SESSION['params'])) {	
	unset($_SESSION['params']);
}
if(isset($_SESSION['csv_query'])) {	
	unset($_SESSION['csv_query']);
}
if(isset($_SESSION['query'])) {	
	unset($_SESSION['query']);
}
header("Location: guest/index.php");

?>