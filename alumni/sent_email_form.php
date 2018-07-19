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

  <link rel="stylesheet" href="../css/alumni_email.css"> 
  
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.4/css/bootstrap-select.min.css">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.4/js/bootstrap-select.min.js"></script>
  
   <link rel="stylesheet" type="text/css" href="../phones_specs/css/dialog-mobile.css"/> <!-- css & javascript για τις ειδοποιήσεις (alerts) στα κινητα -->
  <script type="text/javascript" src="../phones_specs/mcx-dialog.js"></script>
  
    
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
echo	'<h2 align="center">Στοιχεία Email</h2><hr>';

echo 	'<label for="text">Παραλήπτες:</label>';
if(!empty($_POST['recipients'])) {
	$recipients = $_POST['recipients'];
    echo	'<table style="width:100%;">';
	echo	 '<tr>';
	echo		'<th style="width:50%">Ονοματεπώνυμο</th>';
	echo		'<th style="width:50%">Εmail</th>';
	echo	  '</tr>';	
	foreach($recipients as $recipient) {
		
		$stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
		$stmt->execute(array($recipient));
		$result = $stmt->fetchAll();	
		if(sizeof($result)>0) {
			foreach($result as $row) {
				$name = $row['name'];
				$lastname = $row['lastname'];
				$fullname = $lastname . ' ' . $name;
				$email = $row['email'];
				
				
				echo	 '<tr>';
				echo		'<td style="width:50%">'.$fullname.'</td>';
				echo		'<td style="width:50%">'.$email.'</td>';
				echo	  '</tr>';
				
			}
		}
    }
	echo	'</table>';
}
else
{
echo  '<div class="form-group">';
echo 	'<input class="form-control" id="title2" name="title2" value="Η λίστα παραληπτών είναι κενή..." disabled>';
echo	'<a href="javascript:history.back()" class="btn btn-link">Επιστροφή στην επιλογή παραληπτών</a>';
echo  '</div>';	
}
echo "<br>";
echo  '<div class="form-group">';
echo 	'<label for="text">Θέμα:</label>';
echo 	'<input class="form-control" id="title" name="title">';
echo  '</div>';

echo '<div class="form-group">';
echo    '<label for="text">Περιεχόμενο:</label>';
echo    '<textarea class="form-control" id="email_content" style="height:150px;" name = "email_content"></textarea>';
echo '</div>';

echo '<div class="form-group" style="float:right";>';
echo	'<input id = "submit" type="button" name = "submit" value="Αποστολή" class="btn btn-primary">';
echo '</div>';

echo '</div>';
	
?>
<br><br><br><br>
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
	
	$("#submit").click( function(){
        var title = $("#title").val();
		var email_content = $("#email_content").val();
		var recipients = <?php if(!empty($_POST['recipients'])) { echo json_encode($recipients); } else { echo json_encode(""); } ?>;
		
		if(title == '' || email_content == '' || recipients == '') {
			if ($(window).width() < 768) {
				mcxDialog.alert("Επιλέξτε παραλήπτες και συμπληρώστε την φόρμα πριν την αποστολή");
			}
			else 
			{
				alert("Επιλέξτε παραλήπτες και συμπληρώστε την φόρμα πριν την αποστολή");
			}
		}
		else
		{
			$.post("sent_email.php", {
				recipients: recipients,
				title: title,
				email_content: email_content
			}, function(data) {
				if ($(window).width() < 768) {
					mcxDialog.alert("Τα email στάλθηκαν με επιτυχία");
				}
				else 
				{
					alert(data);
				}
				window.location.href = "alumni_index.php";
			}); 
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
