<?php

include ("../connectPDO.php");

$photoid = $_POST['photoid'];

$sql = "SELECT images_path FROM images WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->execute(array($photoid));
$image = $stmt->fetchAll();

$filename = "../content_images/" . $image[0][0];

if (file_exists($filename)) {
    unlink($filename);
}

$sql = "DELETE FROM images WHERE id = ?";        
$stmt = $conn->prepare($sql);
if($stmt->execute(array($photoid)))
{
	echo "Η εικόνα διαγράφηκε με επιτυχία";
}

?>