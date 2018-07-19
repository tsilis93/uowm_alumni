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
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.4/css/bootstrap-select.min.css">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.4/js/bootstrap-select.min.js"></script>
  
    	<link rel="stylesheet" href="../css/admin.css"> 
  <link rel="stylesheet" type="text/css" href="../phones_specs/css/dialog-mobile.css"/> <!-- css & javascript για τις ειδοποιήσεις (alerts) στα κινητα -->
  <script type="text/javascript" src="../phones_specs/mcx-dialog.js"></script> 
 
</head>
<body>

<header>

</header>

<?php

session_start();
include ("../connectPDO.php");

$admin_id = 0;
if(isset($_SESSION['name'])) {
	$admin_id = $_SESSION['name'];
}
else
{
header('Location: ../register_login_form.php');
}

?>

<br><br><br>
  <h2>Νέος Διαχειριστής</h2><br>
<div class="container">
<br>
<form id = "forma" name = "forma">
<br>
  <div class="form-group">
	<label for="text">Επώνυμο:</label>
	<input class="form-control" id="lastname" name="lastname">
  </div>  
  <div class="form-group">
    <label for="text">Όνομα:</label>
    <input class="form-control" id="name" name="name">
  </div>
  <div class="form-group">
    <label for="text">Email:</label>
    <input class="form-control" id="mail" name="mail">
  </div>
  <div class="form-group">
	<label for="text">Ρόλος:</label>
	<div id = "div_role" style="border-radius: 7px;">
	<select class="selectpicker" data-width="100%" name="role" id = "role">
		<option value="" selected disabled>Select your option</option>
		<option value="2">Διαχειριστής</option>
		<option value="3">Διαχειριστής-Απόφοιτος</option>
	</select>
	</div>
  </div>
   <div class="form-group" id = "bloc4">
		<input type="button" class="btn btn-primary" value ="Δημιουργία" id = "submit2"></input>
   </div>
   
</form>

<br><br>

</div>

<br><br>
<footer class="container-fluid text-center">
  Footer Text
</footer>


<script>
$(document).ready(function () {
	
	$.post("admin_header.php", {	
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
		
	$("#submit2").click(function() {  
		var name = $("#name").val();
		var mail = $("#mail").val();
		var lastname = $("#lastname").val();
		var role = $("#role").val();
		
		var blank = false;
		
		/*
		if (lastname == '' || name == '' || mail == '' || username == '' || graduate == '' || grade == '' || pass == '' || did == null) {
			alert("Παρακαλώ συμπληρώστε όλα τα πεδία προτού προχωρήσετε στην δημιουργία του αποφοίτου");
		} */
		if (name == '') {
			$("#name").css("border", "red solid 2px");
			blank = true;			
		}
		else
		{
			$("#name").css("border", "");
		}
		if (mail == '') {
			$("#mail").css("border", "red solid 2px");
			blank = true;			
		}
		else
		{
			$("#mail").css("border", "");
		}
		if (lastname == '') {
			$("#lastname").css("border", "red solid 2px");
			blank = true;			
		}
		else
		{
			$("#lastname").css("border", "");
		}
		if (role == null) {
			$("#div_role").css("border", "red solid 2px");
			blank = true;			
		}
		else
		{
			$("#div_role").css("border", "");
		}
		if(blank) {
			if ($(window).width() < 768) {
				mcxDialog.alert("Παρακαλώ συμπληρώστε όλα τα πεδία προτού προχωρήσετε στην δημιουργία του διαχειριστή");
			}
			else 
			{
				alert("Παρακαλώ συμπληρώστε όλα τα πεδία προτού προχωρήσετε στην δημιουργία του διαχειριστή");
			}
		}
		else
		{			

			$.post("create_admin_process.php", { 
				name1: name,
				mail1: mail,
				lastname1: lastname,
				role1: role
			}, function(data, status) {
				//alert(data);
				
				if(status) {
					var message;
					document.getElementById("forma").reset();
					if(data == 1) {
						message = "Η δημιουργία του διαχειριστή πραγματοποιήθηκε με επιτυχία";
					}
					else
					{
						message = "Παρουσιάστηκε πρόβλημα στην δημιουργία νέου διαχειριστή";
					}
					if ($(window).width() < 768) {
						mcxDialog.alert(message);
					}
					else 
					{
						alert(message);
					}
				} 
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
