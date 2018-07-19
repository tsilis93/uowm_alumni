<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>UOWM Alumni</title>
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
  <meta http-equiv="content-type" content="text/html; charset=UTF-8">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.4/css/bootstrap-select.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.4/js/bootstrap-select.min.js"></script>
  
  <link rel="stylesheet" href="../css/alumni_index.css">  
  
</head>
<body>

<header>

</header>

<br><br><br><br><br>
<?php

session_start();
include ("../connectPDO.php");

if(isset($_SESSION['student'])) {
	$alumni_id = $_SESSION['student'];
}
else
{
header('Location: ../register_login_form.php');
}
	

echo '<div class="container">';
echo    '<h2 align="center">Οι Ανακοινώσεις μου</h2><hr>';

echo '<table style="width:100%" class = "table" id="cssTable">';
echo "<thead>";
echo "<tr>";
echo	'<th class="text-center">Τίτλος</th>';
echo	'<th class="text-center">Σύντομη Περιγραφή</th>';
echo	'<th class="text-center">Status</th>';
echo	'<th class="text-center">Περισσότερα</th>';
echo "</tr>";
echo "</thead>";
echo "<tbody>";

$stmt = $conn->prepare("SELECT * FROM contents WHERE userID = ?");
$stmt->execute(array($alumni_id));
$result = $stmt->fetchAll();

if(sizeof($result)>0) {
	foreach($result as $row)
	{
		$title=$row['title'];
		$dbstatus = $row['status'];
		$status = "";
		if($dbstatus == 1) {
			$status = "Δημοσιευμένη";
		}
		else
		{
			$status = "Σε εκκρεμότητα";
		}
		$description=$row['description'];
		$body = $row['body'];
		$id = $row['id'];
		
		echo "<tr>";
		echo	'<td width = "25%">'.$title.'</td>';
		echo	'<td width = "25%">'.$description.'</td>';
		echo	'<td width = "25%">'.$status.'</td>';
		echo	'<td width = "25%">';
		echo		'<form action="content_overview.php?content='.$id.'" method="post">';
		echo			'<button type="submit" class="btn btn-primary">Επεξεργασία</button>';
		echo        "</form>";
		echo	"</td>";
		echo "</tr>";
	}
}
else
{
	echo	'<tr><td colspan = "4"> Δεν έχετε δημιουργήσει Ανακοινώσεις </td></tr>';
}
			
echo	"</tbody>";
echo	"</table>";		

echo '<br>';
echo '</div>';

?>
<br><br><br><br><br><br><br>
<footer class="container-fluid text-center">
  Footer Text
</footer>

<script>
$(document).ready(function() {
		
	$.post("alumni_header.php", {	
	}, function(data) {
		$('header').html(data);
	});
	
	$.post("../footer.php", {	
	}, function(data) {
		$('footer').html(data);
	});
	
	$.post("clear_search_query.php", {	
	}, function(data) {
		//alert(data);
	});	
	
	function updateAlerts() {
		$.ajax({
			url : "updateNotif.php",
			type : "POST",
			success : function(data, textStatus, XMLHttpRequest)
			{
				//console.log(XMLHttpRequest);
				var response = $.parseJSON(data);
				console.log(response);
				
				if (response > 0) {
					$('#notification').attr("src", "../assets/red.png");
				}
				else {
					$('#notification').attr("src", "../assets/white.png");
				}
			}
	   });
	   setTimeout(updateAlerts, 1000); 
	}
	updateAlerts();	

});
</script>

</body>
</html>