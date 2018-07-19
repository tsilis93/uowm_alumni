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
  
<link rel="stylesheet" type="text/css" href="../phones_specs/css/dialog-mobile.css"/> <!-- css & javascript για τις ειδοποιήσεις (alerts) στα κινητα -->
<script type="text/javascript" src="../phones_specs/mcx-dialog.js"></script>  
    
</head>

<body>

<header>

</header>

<br><br><br><br><br><br>
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

echo 	'<h2 align = "center">Οι Ειδοποιήσεις μου</h2>';

$stmt = $conn->prepare("SELECT * FROM notifications WHERE alumni_id = ?");
$stmt->execute(array($alumni_id));
$result = $stmt->fetchAll();
		

if(empty($result))
{
	echo '<br><p style="color:black" align="center"><font size="4">Δεν υπάρχουν διαθέσιμες Ειδοποιήσεις</font></p><br>';
}
else
{
	echo '<table align="center" class="table">';
	foreach($result as $row) {
		$text = $row['text'];
		$id = $row['id'];
		
		echo "<tr>";
		echo	'<td class="text-center">'.$text.'</td>';
		echo	'<td class="text-center"><input class = "messageCheckbox" type="checkbox" value="'.$id.'"></td>';	
		echo "</tr>";
	}

	echo "</table>";
	
	echo	'<div id = "bloc4">';
	echo			'<input id ="submit"  type="button" value="Διαγραφή Ειδοποιήσεων" class="btn btn-primary">'; 
	echo	"</div>";
	echo	"<br><br>";
}	
	
echo  "</div>";
?>
<br><br><br><br><br><br><br><br><br><br><br><br><br><br>
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


	$("#submit").click(function() {
		
		var checkedValue = [];
		var counter = 0;
		var inputElements = document.getElementsByClassName('messageCheckbox');
		for(var i=0; inputElements[i]; ++i){
			if(inputElements[i].checked){
				checkedValue[counter] = inputElements[i].value;
				counter = counter + 1;
			}
		}
		if(checkedValue.length != 0) { 
		
			$.post("delete_notifications.php", {
				ids: checkedValue
			}, function(data) {
				if ($(window).width() < 768) {
					mcxDialog.alert("Η διαγραφή των ειδοποιήσεων έγινε με επιτυχία");
				}
				else 
				{
					alert("Η διαγραφή των ειδοποιήσεων έγινε με επιτυχία");
				}
				location.reload();
			});
		}
		else
		{			
			if ($(window).width() < 768) {
				mcxDialog.alert("Πρέπει να επιλέξεις ειδοποιήσεις πρώτα");
			}
			else 
			{
				alert("Πρέπει να επιλέξεις ειδοποιήσεις πρώτα");
			}		
		}
		
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
