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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css">

  
    	<link rel="stylesheet" href="../css/edit_alumni.css"> 
				
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

echo "<br><br><br><br><br><br>";
echo '<div class="container">';	
echo '<h2 align="center">Αλλαγή Κωδικού Πρόσβασης</h2>';

echo '<br><p id="login"><span class="label label-info"><b>INFO </span>  Αυτή η σελίδα είναι ορατή μόνο όταν ο χρήστης έχει σαν μοναδικό ρόλο αυτόν του διαχειριστή</b></p><br>';

echo	'<label for="pass">Κώδικός Πρόσβασης <i id="pass-status" class="fa fa-eye" aria-hidden="true" onClick="viewPassword()"></i></label><br>';
echo    '<input type="password" class="form-control fontsize" name="pass" id="admin_password"/>';
echo	'<label for="npass">Νέος Κώδικός Πρόσβασης <i id="pass-status2" class="fa fa-eye" aria-hidden="true" onClick="viewPassword2()"></i></label>';
echo    '<input type="password" class="form-control fontsize" name="npass" id="new_password"/>';
echo	'<label for="cpass">Επαλήθευση Νέου Κωδικού <i id="pass-status3" class="fa fa-eye" aria-hidden="true" onClick="viewPassword3()"></i></label>';
echo	'<input type="password" class="form-control fontsize" name="cpass" id="con_new_pass"/>';	
echo	"<br>";
echo	'<table width="100%">';
echo		"<tr>";
echo			'<td width="90%"></td>';
echo			'<td width="10%"><input id ="change_password"  type="button" value="Αποθήκευση" class="btn btn-primary"></td>';
echo		'</tr>';
echo	'</table>';
echo	'<br>';	
echo '</div>';

echo "<br><br><br>";
echo '<div class="container">';	
echo '<h2 align="center">Αλλαγή Username</h2>';

echo	'<label for="text">Νέο Username</label><br>';
echo    '<input type="text" class="form-control fontsize" name="username" id="username"/>';
echo	"<br>";
echo	'<table width="100%">';
echo		"<tr>";
echo			'<td width="90%"></td>';
echo			'<td width="10%"><input id ="change_username"  type="button" value="Αποθήκευση" class="btn btn-primary"></td>';
echo		'</tr>';
echo	'</table>';
echo	'<br>';	
echo '</div>';

?>
<br><br><br><br>
<footer class="container-fluid text-center"></footer>


<script>
$(document).ready(function() {
		
	var password = false;
	var npassword = false;
	
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
	
	$("#admin_password").change(function() {
		var pass = $(this).val();

		$.post("check_password.php", {	
			pasword: pass
		}, function(data) {
			
			if (!$.trim(data)){  // αν η αποκριση ειναι μηδενική τοτε ο χρήστης εβαλε λαθος κωδικο
				if ($(window).width() < 768) {
					mcxDialog.alert("Λάθος Κώδικός");
				}
				else 
				{
					alert("Λάθος Κώδικός");
				}
				$("#admin_password").css("border","2px solid red");
			}
			else
			{
				$("#admin_password").css("border","2px solid green");
				password = true;
			}
			
		});
	});
	
	$("#new_password").change(function() {
		var new_password = $(this).val();
		var pass = $("#admin_password").val();
		
		if(password == false) {
			if ($(window).width() < 768) {
				mcxDialog.alert("Πρέπει να συμπληρώσετε πρώτα τον τωρινό κωδικό σας");
			}
			else 
			{
				alert("Πρέπει να συμπληρώσετε πρώτα τον τωρινό κωδικό σας");
			}
			$("#admin_password").css("border","2px solid red");
		}
		else
		{
			if(new_password !== pass) {
				$("#new_password").css("border","2px solid green");
			}
			else
			{				
				if ($(window).width() < 768) {
					mcxDialog.alert("Ο νέος κωδικός πρέπει να διαφέρει από τον τωρινό");
				}
				else 
				{
					alert("Ο νέος κωδικός πρέπει να διαφέρει από τον τωρινό");
				}				
				$("#new_password").css("border","2px solid red");
			}
		}
		
	});
	
	
	$("#con_new_pass").change(function() {
		var confirmpass = $(this).val();
		var new_password = $("#new_password").val();
		
		if(password == true) {
			if(new_password !== confirmpass) {
				if ($(window).width() < 768) {
					mcxDialog.alert("Λάθος Κώδικός");
				}
				else 
				{
					alert("Λάθος Κώδικός");
				}
				$("#con_new_pass").css("border","2px solid red");
			}
			else
			{
				$("#con_new_pass").css("border","2px solid green");
				npassword = true;
			}
		}
		else
		{
			if ($(window).width() < 768) {
				mcxDialog.alert("Πρέπει να συμπληρώσετε πρώτα τον τωρινό κωδικό σας");
			}
			else 
			{
				alert("Πρέπει να συμπληρώσετε πρώτα τον τωρινό κωδικό σας");
			}
			$("#admin_password").css("border","2px solid red");
		}
	});
	
	$("#change_password").click(function(){
		
		var p = $("#new_password").val();
		var confirmpass = $("#con_new_pass").val();
		var pass = $("#admin_password").val();
		
		if(npassword == false) {
			if ($(window).width() < 768) {
				mcxDialog.alert("Λάθος καταχωρήσεις σε κάποια από τα πεδία");
			}
			else 
			{
				alert("Λάθος καταχωρήσεις σε κάποια από τα πεδία");
			}
			if(p == '') {
				$("#new_password").css("border","2px solid red");
			}
			if(confirmpass == '') {
				$("#con_new_pass").css("border","2px solid red");
			}
			if(pass == '') {
				$("#admin_password").css("border","2px solid red");
			}
		}
		else
		{
			$.post("update_password.php", {	
				pass: p
			}, function(data) {
				if ($(window).width() < 768) {
					mcxDialog.alert("Οι αλλαγη του κωδικού πρόσβασης έγινε με επιτυχία");
				}
				else 
				{
					alert("Οι αλλαγη του κωδικού πρόσβασης έγινε με επιτυχία");
				}
			});
		}
	});
	
	$("#change_username").click(function(){
		
		var username = $("#username").val();
		
		if(username == '') {
			if ($(window).width() < 768) {
				mcxDialog.alert("Συμπληρώστε το πεδίο πρωτού προχωρήσετε");
			}
			else 
			{
				alert("Συμπληρώστε το πεδίο πρωτού προχωρήσετε");
			}
			$("#username").css("border","2px solid red");
		}
		else
		{
			$.post("update_username.php", {	
				username: username
			}, function(data) {
				if ($(window).width() < 768) {
					mcxDialog.alert("Οι αλλαγη του username έγινε με επιτυχία");
				}
				else 
				{
					alert("Οι αλλαγη του username έγινε με επιτυχία");
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
function viewPassword()
{
  var passwordInput = document.getElementById('admin_password');
  var passStatus = document.getElementById('pass-status');
 
  if (passwordInput.type == 'password'){
    passwordInput.type='text';
    passStatus.className='fa fa-eye-slash';
    
  }
  else{
    passwordInput.type='password';
    passStatus.className='fa fa-eye';
  }
}

function viewPassword2()
{
  var passwordInput = document.getElementById('new_password');
  var passStatus = document.getElementById('pass-status2');
 
  if (passwordInput.type == 'password'){
    passwordInput.type='text';
    passStatus.className='fa fa-eye-slash';
    
  }
  else{
    passwordInput.type='password';
    passStatus.className='fa fa-eye';
  }
}

function viewPassword3()
{
  var passwordInput = document.getElementById('con_new_pass');
  var passStatus = document.getElementById('pass-status3');
 
  if (passwordInput.type == 'password'){
    passwordInput.type='text';
    passStatus.className='fa fa-eye-slash';
    
  }
  else{
    passwordInput.type='password';
    passStatus.className='fa fa-eye';
  }
}

</script>

</body>
</html>	