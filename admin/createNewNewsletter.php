<?php

session_start();
include ("../connectPDO.php");

if(isset($_POST['html_text'])) {
	$html_body = $_POST['html_text'];
}
if(isset($_POST['title'])) {
	$title = $_POST['title'];
}

$date_created = date("Y-m-d");

$params = array();
$params[0] = $title;
$params[1] = $html_body;
$params[2] = $date_created;


$metritis = 3; //αρχικοποιείται απο το 3 γιατί οι πρωτες 3 θεσεις του πινακα $params εχουν ηδη συμπληρωθεί
$value = 1; // η τιμή που θα παρει το αντιστοιχο πεδίο option_id στον πίνακα newsletter_content

if(isset($_POST['categories'])) {
	$categories = $_POST['categories'];
}
$count = count($categories);

$query = "INSERT INTO newsletter_content (titlos, body_html, date_created, ";
$query_end = " VALUES (?, ?, ?, ";

for($i=0; $i<$count; $i++) {
	$option = "option_id".$categories[$i];
	if($i == $count-1) {
		$query = $query . $option . ")";
		$query_end = $query_end . "?)";
	}
	else
	{
		$query = $query . $option . ", ";
		$query_end = $query_end . "?, ";
	}
	$params[$metritis] = $value; //προσθέτω την τιμη στον πίνακα $params
	$metritis = $metritis + 1;
}
$query = $query . $query_end;

$stmt = $conn->prepare($query);
$stmt->execute($params);
?>