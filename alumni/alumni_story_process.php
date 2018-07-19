<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>UOWM Alumni</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  
  <link rel="stylesheet" href="../css/alumni_index.css">  
  
</head>
<body>

<header>

</header>

<br><br><br><br><br>

 <div class="container">
    <h2 align="center">Οι Ιστορίες μου</h2><hr>
	<div style="overflow-x:auto;">
    <table style="width:100%" class = "table" id="cssTable">
	<thead>
	<tr>
		<th class="text-center">Τίτλος</th>
		<th class="text-center">Σύντομη Περιγραφή</th>
		<th class="text-center">Status</th> 
		<th class="text-center">Περισσότερα</th>
		<th class="text-center"></th>
	</tr>
	</thead>
	<tbody>
				
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

$stmt = $conn->prepare("SELECT * FROM stories WHERE userID = ? ORDER by id desc");
$stmt->execute(array($alumni_id));
$result = $stmt->fetchAll();

$row_count = 1;					
if(sizeof($result)>0) {
	foreach($result as $row)
	{
		$title=$row['title'];
		$description=$row['description'];
		$body = $row['body'];
		$dbstatus = $row['status'];
		$status = "";
		$comments = $row['comments'];
		if($dbstatus == 1) {
			$status = "Δημοσιευμένη";
		}
		else
		{
			$status = "Σε εκκρεμότητα";
		}
		if($comments == "") {
			$comments = "Δεν υπάρχουν διαθέσιμα σχόλια από τον διαχειριστή.";
		}
		$id = $row['id'];
								
		echo	"<tr>";
		echo	'<td width = "(100/5)%">'.$title.'</td>';
		echo	'<td width = "(100/5)%">'.$description.'</td>';
		echo	'<td width = "(100/5)%">'.$status.'</td>';
		echo	'<td width = "(100/5)%">'; 
		echo    '<form action="story_overview.php?story='.$id.'" method="post">';
		echo		'<button type="submit" class="btn btn-primary">Επεξεργασία</button>';
		echo	"</form></td>";
		echo	'<td width = "(100/5)%"><a href="#s'.$row_count.'" class="btn btn-info" data-toggle="collapse">Σχόλια</a></td>';
		echo		"<tr>";
		echo			'<td colspan = "5"><div id="s'.$row_count.'" class="collapse"> <textarea disabled class="form-control" style="height:50px;" id="comment" name = "comment">'.$comments.'</textarea> </div></td>';
		echo		"</tr>";
		echo	"</tr>";
		$row_count = $row_count + 1;	
	}
}
else
{

		echo    '<tr><td colspan = "5"> Δεν έχετε δημιουργήσει Ιστορίες</td></tr>';
}

?>

	</tbody>
    </table>
  </div>
</div>

<br><br><br>
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
