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

  <link rel="stylesheet" href="../css/admin_email.css"> 
  
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


if(isset($_SESSION['name'])) {
	$admin_id = $_SESSION['name'];
}
else
{
header('Location: ../register_login_form.php');
}



echo '<div class="container">';
echo	'<h2 align="center">Στοιχεία Email</h2><hr>';

echo 	'<label for="text">Παραλήπτες:</label><br>';
if(!empty($_POST['recipients'])) {
	$recipients = $_POST['recipients'];
	//$count = count($recipients); gia testing tou scroll bale allon ena apofoito
	//$recipients[$count]  = 3;
	echo	'<div class="table-container">';
    echo	'<table style="width:100%;" id="table4">';
	echo	  '<thead>';	
	echo	 	'<tr>';
	echo			'<th>Ονοματεπώνυμο</th>';
	echo			'<th>Εmail</th>';
	echo			'<th></th>';
	echo	  	'</tr>';
	echo	  '</thead>';
	echo	  '<tbody>';
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
				$id = $row['id'];
				
				
				echo	 '<tr id = "'.$id.'">';
				echo		'<td width="45%">'.$fullname.'</td>';
				echo		'<td width="45%">'.$email.'</td>';
				echo		'<td width="10%"><input id="'.$id.'" type="image" src="../assets/delete.png" width=20 height=20"></td>';
				echo	  '</tr>';
				
			}
		}
    }
	echo	'</tbody>';
	echo	'</table>';
	echo	'</div>';
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
		
	$.post("admin_header.php", {	
	}, function(data) {
		$('header').html(data);
	});
	
	$.post("../footer.php", {	
	}, function(data) {
		$('footer').html(data);
	});
	
	//παραλήπτες!! ενδέχεται να τροποποιηθούν!! για αυτο αποθηκεύονται σαν global μεταβλητη
	var recipients = <?php if(!empty($_POST['recipients'])) { echo json_encode($recipients); } else { echo json_encode(""); } ?>;
	
	$("#submit").click( function(){
        var title = $("#title").val();
		var email_content = $("#email_content").val();
				
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
			}, function() {
				if ($(window).width() < 768) {
					mcxDialog.alert("Τα email στάλθηκαν με επιτυχία");
				}
				else 
				{
					alert("Τα email στάλθηκαν με επιτυχία");
				}
				window.location.href = "admin_index.php";
			}); 
		}
	});
	
	$('input[type=image]').click(function(){
		var id = $(this).attr('id');
		var index = recipients.indexOf(id); //αφαίρεσε το συγκεκριμένο id απο τον πίνακα με τους παραλήπτες
		if (index > -1) {
		  recipients.splice(index, 1);
		}
		$('#'+id).remove();	//σβήσε τωρα και την γραμμή από τον πίνακα εμφάνισης
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
