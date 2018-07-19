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

if(isset($_SESSION['name'])) {
	$admin_id = $_SESSION['name'];
}
else
{
header('Location: ../register_login_form.php');
}

?>	
<br><br><br>

<h2>Νέο SMS</h2><br>
<div class="container"><br>
	<p style="color:black;"><span class="label label-info"><b>INFO</span> Με διπλό click στο τηλέφωνο συμπληρώνεται αυτόματα η φόρμα</b></p><br>
	<label for="text">Αναζήτηση Παραλήπτη:</label>
	<div class="form-group">
		<input class="form-control" id="onoma" name="onoma" placeholder="Πληκτρολογήστε το όνομα ή το επίθετο του παραλήπτη">
	</div>
	<div id="suggestions">
	</div><br><hr>
	<div class="form-group">
		<label for="text">Τηλέφωνο:</label>
		<input class="form-control" id="phone" name="phone">
	</div>
	<div class="form-group">
		<label for="text">Μήνυμα:</label>
		<textarea class="form-control" id="message" onkeyup="countChar(this)" style="height:80px;" name = "message"></textarea>
	</div>
	<div class="form-group">
		<table width="100%">
			<tr>
				<td width="98%"><p class = "characters">Χαρακτήρες: </p></td>
				<td width="2%">&nbsp;&nbsp;<p id="charNum">150</p> / 150</td>
			</tr>
			<tr>
				<td width="90%"></td>
				<td width="10%"><input type="button" class="btn btn-primary" value ="Αποστολή" id = "submit"></input></td>
			</tr>
		</table>
	</div>
</div>

<br><br>
<footer class="container-fluid text-center">
  Footer Text
</footer>


<script>
$(document).ready(function() {
	
	//$('#notify').attr("src", "assets/red.png");
	
	$.post("admin_header.php", {	//admin_header2.php
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
	
	$(function(){ 
		$('#onoma').keyup(function() {
			
			var input = this.value;
			
			$.post("suggest.php", {
				input: input
			}, function(data) {
				$('#suggestions').html(data);
			});
			
		});
	});
	
	$("#submit").click(function() {
		
		var w = Math.max(document.documentElement.clientWidth, window.innerWidth || 0);  //συναρτηση υπολογισμου του πλατους της οθόνης

		var phone = $("#phone").val();
		var message = $("#message").val();
		
		if(phone == '' || message == '') {
			if (w < 768) {
				mcxDialog.alert("Παρακαλώ συμπληρώστε όλα τα πεδία προτού προχωρήσετε στην αποστολή");
			}
			else 
			{
				alert("Παρακαλώ συμπληρώστε όλα τα πεδία προτού προχωρήσετε στην αποστολή");
			}
		}
		else
		{
			$.post("send_sms.php", {
				phone: phone,
				message: message
			}, function(data) {
				if(data == 0) {
					if ($(window).width() < 768) { //to mnm den efuge
						mcxDialog.alert("Υπήρξε πρόβλημα με την αποστολή του SMS. Παρακαλώ δοκιμάστε αργότερα");
					}
					else 
					{
						alert("Υπήρξε πρόβλημα με την αποστολή του SMS. Παρακαλώ δοκιμάστε αργότερα");
					}

				}
				else	//to mnm efuge me epituxia
				{
					if ($(window).width() < 768) {
						mcxDialog.alert("Η αποστολή του SMS πραγματοποιήθηκε με επιτυχία");
					}
					else 
					{
						alert("Η αποστολή του SMS πραγματοποιήθηκε με επιτυχία");
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

<script>
	function myFunction(id) {
		var phone = $("#"+id).text();
		$("#phone").val(phone);
	}
	
      function countChar(val) {
        var len = val.value.length;
        if (len >= 150) {
          val.value = val.value.substring(0, 150);
        } else {
          $('#charNum').text(150 - len);
        }
      }	
</script>

</body>
</html>
