<?php
session_start();
include ("../connectPDO.php");

if(isset($_POST['id'])) {
	$id = $_POST['id'];
}


$sql = "DELETE from ekkremothtes WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->execute(array($id));

?>	