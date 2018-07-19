<?php

session_start();	

include ("../connectPDO.php");

if(isset($_GET['id'])) {
	$alumni_id = $_GET['id'];
}


$UploadFolder = "../cv_files";

if($_FILES['mycvPDF']['error'] > 0) 
{ 
	die(header("location:edit_alumni.php?id=$alumni_id&Failed=true&reason=blank"));
}
else
{
	$name = $_FILES['mycvPDF']['name'];
	$temp = $_FILES['mycvPDF']['tmp_name'];
	$type=$_FILES['mycvPDF']['type'];
	$size=$_FILES['mycvPDF']['size'];

	if ($size > 500000) {
		die(header("location:edit_alumni.php?id=$alumni_id&Failed=true&reason=size"));	
	}
	
	if ($type=="application/pdf") {
		
		$temp_name = explode(".", $name);
		$filename = "cvPdfUser" . $alumni_id . '.' . end($temp_name);
		
		$stmt = $conn->prepare("SELECT * FROM alumni_cv WHERE alumni_id = ?");
		$stmt->execute(array($alumni_id));
		$result = $stmt->fetchAll();

		if (sizeof($result)> 0) 
		{
			
			foreach ($result as $row) {
				$prev_pdf_file = $row['pdf_src'];
			}
			if(file_exists($UploadFolder."/".$prev_pdf_file)) {
				unlink($UploadFolder."/".$prev_pdf_file);
			}		

			$sql = "UPDATE alumni_cv SET pdf_src = ?, original_name = ? WHERE alumni_id = ?";
			$stmt = $conn->prepare($sql);
			if($stmt->execute(array($filename, $name, $alumni_id))) {
				move_uploaded_file($temp, $UploadFolder . "/" . $filename);			
				header("location:edit_alumni.php?id=$alumni_id&Failed=false");
			}
			else
			{
				die(header("location:edit_alumni.php?id=$alumni_id&Failed=true&reason=error"));
			}
			
		}
		else
		{
			$stmt2 = $conn->prepare("INSERT INTO alumni_cv (pdf_src, original_name, alumni_id) VALUES (?, ?, ?)");
			$stmt2->bindParam(1, $filename);
			$stmt2->bindParam(2, $name);
			$stmt2->bindParam(3, $alumni_id);
			if ($stmt2->execute()) {
				move_uploaded_file($temp, $UploadFolder . "/" . $filename);		
				header("location:edit_alumni.php?id=$alumni_id&Failed=false");
			}
			else
			{
				die(header("location:edit_alumni.php?id=$alumni_id&Failed=true&reason=error"));
			}
		}
	}
	else
	{
		die(header("location:edit_alumni.php?id=$alumni_id&Failed=true&reason=type"));
	}
}

