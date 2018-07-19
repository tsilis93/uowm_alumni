<?php

include ("../connectPDO.php");

$photoid = $_POST['photoid'];

$stmt = $conn->prepare("SELECT images_path FROM images WHERE id = ?");
$stmt->execute(array($photoid));
$image = $stmt->fetchAll();

$filename = "../content_images/" . $image[0][0];

if (file_exists($filename)) {
    unlink($filename);
}
     
$stmt2 = $conn->prepare("DELETE FROM images WHERE id = ?");
if($stmt2->execute(array($photoid)))
{
	echo "Η εικόνα διαγράφηκε με επιτυχία";
}

?>