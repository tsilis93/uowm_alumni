<?php

session_start();
include ("../connectPDO.php");

if(isset($_POST['id'])) {
	$id = $_POST['id'];
}

if(isset($_POST['html_text'])) {
	$html_body = $_POST['html_text'];
}
if(isset($_POST['title'])) {
	$title = $_POST['title'];
}

$params = array();
$params[0] = $title;
$params[1] = $html_body;

$metritis = 2; //αρχικοποιείται απο το 2 γιατί οι πρωτες 2 θεσεις του πινακα $params εχουν ηδη συμπληρωθεί
$value = 1; // η τιμή που θα παρει το αντιστοιχο πεδίο option_id στον πίνακα newsletter_content

$query = "UPDATE newsletter_content SET titlos = ?, body_html = ?";
$query_end = " WHERE id = ?";

if(isset($_POST['categories'])) {
	$categories = $_POST['categories'];
}
$count = count($categories);

for($i=0; $i<$count; $i++) {
	$option = "option_id".$categories[$i];
	$query = $query . ", " . $option . " = ?";
	$params[$metritis] = $value; //προσθέτω την τιμη στον πίνακα $params
	$metritis = $metritis + 1;
}

$query = $query . $query_end;

$count = count($params);
$params[$count] = $id;
$count = count($params);

$stmt = $conn->prepare($query);
$stmt->execute($params);
/*
echo $query;
echo "\n";
for ($i=0; $i<$count; $i++) {
	echo	$params[$i] . "\n";
}
*/
?>