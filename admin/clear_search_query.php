<?php


session_start();

/* Τα σχολιασμένα τμήματα είναι για testing!!! H συναρτηση σβήνει την επιλογή του χρήστη στην αναζήτηση */

if(isset($_SESSION['params'])) {	
	unset($_SESSION['params']);
}
if(isset($_SESSION['csv_query'])) {	
	unset($_SESSION['csv_query']);
}
if(isset($_SESSION['query'])) {	
	unset($_SESSION['query']);
}


//$time_start = microtime(true);
//xsxsxsxsxs
//$time_end = microtime(true);
//$time = $time_end - $time_start;
//echo "$time seconds";
?>