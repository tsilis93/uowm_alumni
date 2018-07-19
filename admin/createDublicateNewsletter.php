<?php

session_start();
include ("../connectPDO.php");


if(isset($_POST['id'])) {
	$id = $_POST['id'];
}

$date_created = date("Y-m-d");

$stmt = $conn->prepare("SELECT * FROM newsletter_content WHERE id = ?");
$stmt->execute(array($id));
$result = $stmt->fetchAll();

if(sizeof($result)>0) {
	foreach($result as $row) {
		$titlos = $row['titlos'];
		$body_html = $row['body_html'];
	}
}

$titlos = $titlos . " (dublicate)";

$newsletter_categories = array(); 
$metritis = 0;

$stmt2 = $conn->prepare("SELECT * FROM newsletter_categories");
$stmt2->execute();
$result2 = $stmt2->fetchAll();

foreach ($result2 as $row2) {
	$id = $row2['id'];
	$option = "option_id".$id;
	$newsletter_categories[$metritis] = $option;
	$metritis = $metritis + 1; 												// που έχουν επιλέξει να δεχονται newsletter για τις συγκεκριμένες κατηγορίες 
}

$count = count($newsletter_categories);

$params = array();
$params[0] = $titlos;
$params[1] = $body_html;
$params[2] = $date_created;


$metritis2 = 3; 
$value = 0; 



$query = "INSERT INTO newsletter_content (titlos, body_html, date_created, ";
$query_end = " VALUES (?, ?, ?, ";

for($i=0; $i<$count; $i++) {
	if($i == $count-1) {
		$query = $query . $newsletter_categories[$i] . ")";
		$query_end = $query_end . "?)";
	}
	else
	{
		$query = $query . $newsletter_categories[$i] . ", ";
		$query_end = $query_end . "?, ";
	}
	$params[$metritis2] = $value; //προσθέτω την τιμη στον πίνακα $params
	$metritis2 = $metritis2 + 1;

}
$query = $query . $query_end;

//echo $query;
$stmt = $conn->prepare($query);
if($stmt->execute($params)) {

}
else
{

}

//$stmt = $conn->prepare("INSERT INTO newsletter_content (titlos, body_html, date_created) VALUES (?, ?, ?)");
//$stmt->execute(array($titlos, $body_html, $date_created));

?>