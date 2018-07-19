<?php

session_start();

if(isset($_SESSION['params'])) {	
	unset($_SESSION['params']);
}
if(isset($_SESSION['csv_query'])) {	
	unset($_SESSION['csv_query']);
}
if(isset($_SESSION['query'])) {	
	unset($_SESSION['query']);
}


?>