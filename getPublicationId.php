<?php

function getContentMaxPublicationid() {

	include ("connectPDO.php");
	
	$statement = $conn->prepare("SELECT MAX(publication_id) as publication_id FROM contents");
	$statement->execute();
	$max = $statement->fetch(PDO::FETCH_OBJ);
	$id = $max->publication_id;
	
	return $id;
}

function getContentMaxPublicationidPerDepartment($id) {

	include ("connectPDO.php");
	
	$published = "published_department".$id;
	
	$statement = $conn->prepare("SELECT MAX(publication_id) as publication_id FROM contents WHERE ".$published." = 1 AND status = 1");
	$statement->execute();
	$max = $statement->fetch(PDO::FETCH_OBJ);
	$id = $max->publication_id;
	
	return $id;
}

function getContentMaxPublicationidIndexPage() {

	include ("connectPDO.php");
	
	$published = "published_index_page";
	
	$statement = $conn->prepare("SELECT MAX(publication_id) as publication_id FROM contents WHERE ".$published." = 1 AND status = 1");
	$statement->execute();
	$max = $statement->fetch(PDO::FETCH_OBJ);
	$id = $max->publication_id;
	
	return $id;
}

function getStoryMaxPublicationid() {
	
	include ("connectPDO.php");
	
	$statement = $conn->prepare("SELECT MAX(publication_id) as publication_id FROM stories");
	$statement->execute();
	$max = $statement->fetch(PDO::FETCH_OBJ);
	$id = $max->publication_id;
	
	return $id;
}

function getStoryMaxPublicationidPerDepartment($id) {
	
	include ("connectPDO.php");
	
	$published = "published_department".$id;
	
	$statement = $conn->prepare("SELECT MAX(publication_id) as publication_id FROM stories WHERE ".$published." = 1 AND status = 1");
	$statement->execute();
	$max = $statement->fetch(PDO::FETCH_OBJ);
	$id = $max->publication_id;
	
	return $id;
}

?>