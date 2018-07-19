<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>UOWM Alumni</title>
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
  <meta http-equiv="content-type" content="text/html; charset=UTF-8">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
 
  <link rel="stylesheet" href="../css/guest.css">
  
  <link rel="stylesheet" type="text/css" href="../phones_specs/css/dialog-mobile.css"/> <!-- css & javascript για τις ειδοποιήσεις (alerts) στα κινητα -->
  <script type="text/javascript" src="../phones_specs/mcx-dialog.js"></script>
 
</head>

<body> 

<header></header>

<?php 
session_start();
include ("../connectPDO.php");

$_SESSION = array();

include("simple-php-captcha.php");
$_SESSION['captcha'] = simple_php_captcha();

if(isset($_GET['id'])) {
	$id = $_GET['id']; // id του τμήματος
}

if(isset($_SESSION['name'])) {
	header('Location: ../admin/admin_index.php');
}
else if (isset($_SESSION['student']))
{
header('Location: ../alumni/alumni_index.php');
}

// κανω fetch τα δεδομένα από την βάση
$stmt2 = $conn->prepare("SELECT * from departments WHERE id = ?");
$stmt2->execute(array($id));
$result2 = $stmt2->fetch(PDO::FETCH_ASSOC);

$nav_color;

if (sizeof($result2)> 0) {
	$nav_color = $result2['nav_color'];	
}


echo 	'<br><br><br><br><br><br>';
echo	'<div class="construct">';
echo		'<form id = "form" name = "form"><br>';
echo			'<h3 bgcolor="#E6E6FA">Φόρμα Εγγραφής</h3>';
echo			'<p style="font-size: 14px; padding-left: 5%; padding-right: 5%;"><span class="label label-info"><b>INFO  </span> Έχετε δικαίωμα εγγραφής μόνο σε ένα από τα τμήματα</b> </p>';
echo			'<p style="font-size: 14px; padding-left: 5%; padding-right: 5%;"><span class="label label-info"><b>INFO 2</span> Για οποιοδήποτε πρόβλημα ή απορία παρακαλώ συμπληρώστε το πεδίο "Σχόλια" και το κινητό σας</b> </p><br>';
echo			'<div class="form-group" style="padding-left:20px; padding-right:20px">'; 
echo    			'<label for="text">Επώνυμο:</label>';
echo	    		'<input class="form-control" id="lastname" name="lastname">';
echo			'</div>';
echo			'<div class="form-group" style="padding-left:20px; padding-right:20px">';
echo	    		'<label for="text">Όνομα:</label>';
echo	    		'<input class="form-control" id="name" name="name">';						
echo	 		'</div>';
echo	 		'<div class="form-group" style="padding-left:20px; padding-right:20px">';
echo	    		'<label for="text">Email:</label>';
echo	    		'<input class="form-control" id="mail" name="mail">';
echo	 		'</div>';
echo	 		'<div class="form-group" style="padding-left:20px; padding-right:20px">';
echo				'<label for="text">Αριθμός Μητρώου Φοιτητή:</label>';
echo				'<input type="text" class="form-control" id="aem" name="aem">';
echo	 		'</div>';
echo	 		'<label for="text" style="padding-left:20px; padding-right:20px">Κινητό:</label> (Προαιρετικά)';
echo	 		'<div class="form-group" style="padding-left:20px; padding-right:20px">';
echo				'<input class="form-control" id="phone" name="phone">';
echo	 		'</div>';
echo	 		'<label for="text" style="padding-left:20px; padding-right:20px">Σχόλια:</label> (Προαιρετικά)';
echo	 		'<div class="form-group" style="padding-left:20px; padding-right:20px">';
echo				'<textarea class="form-control" id="message" style="height:80px;" name = "message"></textarea>';
echo	 		'</div>';				 
echo	 		'<div class="form-group" style="padding-left:20px; padding-right:20px">';
echo	 		'<label for="text">Captcha:</label><br>';


$folder = "simple-php-captcha.php";
$url = $_SERVER['REQUEST_URI']; //returns the current URL
$parts = explode('/',$url);
$homedirectory = $_SERVER['SERVER_NAME'];
for ($i = 0; $i < count($parts) - 1; $i++) {
 $homedirectory .= $parts[$i] . "/";
}
					
$imageSrc = "https://".$homedirectory.$folder.$_SESSION['captcha']['image_src'];


//$imageSrc = $_SESSION['captcha']['image_src'];

echo 		'<img src="' . $imageSrc . '" alt="CAPTCHA code">'; 
echo		'<br><br><input type="text" class="form-control" id="user_captcha" name="captcha" placeholder="Συμπληρώστε τον παραπάνω κωδικό..">';
echo	'</div>';
echo	'<div class="form-group" style="padding-left:20px; padding-right:20px">';
echo		'<input type="button" class="btn btn-primary" value ="Εγγραφή" id = "submit2" style="float:right;"></input><br>';
echo	'</div>';
echo '</form>';
echo '<br>';
echo '</div>';

?>
<br><br><br><br><br> 

<footer class="container-fluid"></footer>

<script>
$(document).ready(function(){
	
	$.post("../footer.php", {	
	}, function(data) {
		$('footer').html(data);
	});
	
	var id = <?php echo(json_encode($id)); ?>;  // παίρνω το id από την dropdown λίστα
	var nav_color = <?php if(!empty($nav_color)) { echo(json_encode($nav_color)); } else { echo(json_encode("")); }?>;
	
	$.post("guest_department_header.php", {
		id: id
	 }, function(data) {
		if (!$.trim(data)){ 
			location.replace("../notFound.php");
		}
		else
		{
			$('header').html(data);
			$(".navbar").css("background", nav_color);	
		}
	});
	
	$("#submit2").click(function() {  
		var lastname = $("#lastname").val();
		var name = $("#name").val();
		var mail = $("#mail").val();
		var aem = $("#aem").val();
		var phone = $("#phone").val();
		var department = id;
		var message = $("#message").val();
		var ok = true;
		var empty = false;
				
		if (lastname == '') { 
			$("#lastname").css('border', 'solid 2px red');
			empty = true;
		}
		if (name == '') { 
			$("#name").css('border', 'solid 2px red');
			empty = true;
		}
		if (mail == '') { 
			$("#mail").css('border', 'solid 2px red');
			empty = true;
		}
		if (aem == '') {
			$("#aem").css('border', 'solid 2px red');
			empty = true;			
		}
		var string1 = <?php echo(json_encode($_SESSION['captcha']['code'])); ?>;
        var string2 = document.getElementById('user_captcha').value;
        if (string1 == string2){
			// form validated
			$('#user_captcha').css("border",'');
		}
		else
		{
			if ($(window).width() < 768) {
				mcxDialog.alert("Παρακαλώ συμπληρώστε σωστά το captcha πεδίο");
				$('#user_captcha').css("border","red solid 2px");
			}
			else 
			{
				alert("Παρακαλώ συμπληρώστε σωστά το captcha πεδίο");
				$('#user_captcha').css("border","red solid 2px");
			}			
		}
		if(empty == true) 
		{
			if ($(window).width() < 768) {
				mcxDialog.alert("Παρακαλώ συμπληρώστε όλα τα απαιτούμενα πεδία προτού προχωρήσετε στην εγγραφή");
			}
			else 
			{
				alert("Παρακαλώ συμπληρώστε όλα τα απαιτούμενα πεδία προτού προχωρήσετε στην εγγραφή");
			}				
		}
		else
		{	
			$("#lastname").css('border', '');
			$("#name").css('border', '');
			$("#mail").css('border', '');
			$("#select").css('border', '');
			$("#aem").css('border', '');

			$.post("sign_in_process.php", { 
				lastname1: lastname,
				name1: name,
				mail1: mail,
				aem: aem,
				phone: phone,
				message: message,
				id: department
			}, function(data) {
				if ($(window).width() < 768) 
				{
					if(data == 0) {
						mcxDialog.alert("Παρουσιάστηκε πρόβλημα στην εγγραφή σας. Παρακαλώ ξαναπροσπαθήστε.");
					}
					else
					{
						mcxDialog.alert("Η εγγραφή σας έγινε με επιτυχία και βρίσκεται σε διαδικασία έγκρισης από τον διαχειριστή");
						$('#form')[0].reset();
					}
				}
				else 
				{	
					if(data == 0) {
						alert("Παρουσιάστηκε πρόβλημα στην εγγραφή σας. Παρακαλώ ξαναπροσπαθήστε.");
					}
					else
					{
						alert("Η εγγραφή σας έγινε με επιτυχία και βρίσκεται σε διαδικασία έγκρισης από τον διαχειριστή");
						$('#form')[0].reset();
					}
				}
			});
		}
	});		
	  	
});
</script>	
