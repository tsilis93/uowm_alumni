<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>UOWM Alumni</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.4/css/bootstrap-select.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.4/js/bootstrap-select.min.js"></script>
  
  <link rel="stylesheet" href="css/register_login.css"> 
  <link rel="stylesheet" type="text/css" href="phones_specs/css/dialog-mobile.css"/> <!-- css & javascript για τις ειδοποιήσεις (alerts) στα κινητα -->
  <script type="text/javascript" src="phones_specs/mcx-dialog.js"></script>
   
</head>
<body>
<br>
<?php
	session_start();
	
	include ("connectPDO.php");
	
	$_SESSION = array();

	include("simple-php-captcha.php");
	$_SESSION['captcha'] = simple_php_captcha();
	
	$var = 0;
	if(!isset($_GET['loginFailed'])) {
		$loginFailed = "";
		$reasons="";
		$var=1;
	}
	if(!isset($_GET['java'])) {
			
	}
	$reasons = array(
		"password" => "&emsp;Λάθος κωδικός",
		"blank" => "&emsp;Συμπληρώστε όλα τα πεδία",
		"username" => "&emsp;Λάθος όνομα χρήστη ή ανενεργός χρήστης"
	);
	
	$stmt = $conn->prepare("SELECT * FROM departments");
	$stmt->execute();
	$result2 = $stmt->fetchAll();


echo '<div class="container">';
echo	'<div class="row">';
    
echo		'<div class="col-lg-6">';
			
echo			'<div class = "leftdiv_up">';	
echo				  '<h2 id="login2" align="center">Είσοδος</h2>';
				  				  
echo				  '<form id = "login" action="register_login.php" method="post">'; 
echo					'<div class="form-group">';
echo					   '<label for="text">Username:</label>';
echo					   '<input type="text" class="form-control" id="email" placeholder="Username" name="email" onchange="myScript();"/>';
echo					'</div>';
echo					'<div class="form-group">';
echo					   '<label for="pwd">Password:</label>';
echo					   '<input type="password" class="form-control" id="pwd" placeholder="Password" name="pwd">';
echo					'</div><br>';

echo					'<table width="100%" id = "signformtable">';
echo						'<tr>';
echo							'<td width="10%"><a href = "guest/index.php"><span id = "arrow" class="glyphicon">&#xe091;</span></a></td>';
								if($var == 0) {
									if($_GET["loginFailed"])
										echo '<td width="65%"><b>' .$reasons[$_GET["reason"]] . '</b></td>';
								}
								else
								{
									echo '<td width="65%"></td>';
								}	
echo							'<td width="15%"><input type="submit" class="btn btn-primary" value="Σύνδεση"></input></td>';
echo						'</tr>';
echo					'</table>';	
echo				  '</form>';
	
echo			'</div><br><br>';
echo			'<div class = "leftdiv_down">';
echo				  '<h2 id="login2" align="center">Υπενθύμιση Κωδικού Πρόσβασης</h2><br>';

echo					'<form>';
echo							'<div class="form-group">';
echo							  	'<label for="text">Username:</label>';
echo							  	'<input type="text" class="form-control" id="remind_email" placeholder="Username" name="email" />';
echo							'</div>';
echo							'<table width="100%" id="signformtable">';
echo								'<tr>';
echo									'<td width="80%"><b><p id="remind_message"></p></b></td>';							
echo									'<td width="20%"><input type="button" class="btn btn-info" value="Υπενθύμιση" id="remind"></input></td>';
echo								'</tr>';
echo							'</table>';
echo					 '</form>';

echo			'</div>';
			
echo		'</div>';  
		
echo		'<div class="col-lg-6">';
			
echo			'<div class = "rightdiv">';
echo				'<form id = "form" name = "form">';
					
echo						'<h2 align="center">Φόρμα Εγγραφής</h2><br>';
echo						'<p ><span class="label label-info"><b>INFO   </span> Έχετε δικαίωμα εγγραφής μόνο σε ένα από τα τμήματα</b> </p>';
echo						'<p ><span class="label label-info"><b>INFO 2</span> Για οποιοδήποτε πρόβλημα ή απορία παρακαλώ συμπληρώστε το πεδίο "Σχόλια" και το κινητό σας</b> </p><br>';
						
echo						'<div class="form-group">'; 
echo							'<label for="text">Επώνυμο:</label>';
echo							'<input class="form-control" id="lastname" name="lastname">';
echo						'</div>';
echo						'<div class="form-group">';
echo							'<label for="text">Όνομα:</label>';
echo							'<input class="form-control" id="name" name="name">';
echo						'</div>';
echo						'<div class="form-group">';
echo							'<label for="text">Email:</label>';
echo							'<input class="form-control" id="mail" name="mail">';
echo						'</div>';
echo					    '<div class="form-group">';
echo							'<label for="text">Αριθμός Μητρώου Φοιτητή:</label>';
echo							'<input type="text" class="form-control" id="aem" name="aem">';
echo					 	'</div>';
echo						'<div class="form-group">';
echo						'<label for="text">Τμήμα Φοίτησης:</label>';
echo						'<div id = "select" style="border-radius: 7px;">';
echo							'<select class="selectpicker" data-width="100%" name="department" id = "department">';
echo							'<option value="" disabled selected>Select your option</option>';

$stmt = $conn->prepare("SELECT * FROM departments");
$stmt->execute();
$result = $stmt->fetchAll();
foreach($result as $row) {
								
								echo '<option value="'.$row['id'].'">'.$row['dname'].'</option>';
								
}	 
echo							'</select>';
echo						'</div>';
echo					'</div>';
echo					'<label for="text">Κινητό:</label> (Προαιρετικά)';
echo					'<div class="form-group">';
echo						'<input class="form-control" id="phone" name="phone">';
echo					'</div>';
echo	 				'<label for="text">Σχόλια:</label> (Προαιρετικά)';
echo	 				'<div class="form-group">';
echo						'<textarea class="form-control" id="message" style="height:80px;" name = "message"></textarea>';
echo	 				'</div>';				 
echo					'<div class="form-group">';
echo						'<label for="text">Captcha:</label><br>';


$folder = "simple-php-captcha.php";
$url = $_SERVER['REQUEST_URI']; //returns the current URL
$parts = explode('/',$url);
$homedirectory = $_SERVER['SERVER_NAME'];
for ($i = 0; $i < count($parts) - 1; $i++) {
 $homedirectory .= $parts[$i] . "/";
}
					
$imageSrc = "https://".$homedirectory.$folder.$_SESSION['captcha']['image_src'];


//$imageSrc = $_SESSION['captcha']['image_src'];					
echo 						'<img src="' . $imageSrc . '" alt="CAPTCHA code">'; 
echo						'<br><br><input type="text" class="form-control" id="user_captcha" name="captcha" placeholder="Συμπληρώστε τον παραπάνω κωδικό..">';
echo					'</div>';
echo					'<div class="form-group">';
echo						'<input type="button" class="btn btn-primary" value ="Εγγραφή" id = "submit2" style="float:right;"></input><br>';
echo					'</div>';
echo				'</form>';	
				  
echo			'</div>';
				
echo		'</div>';
		
echo	'</div>';
echo  '</div>';

?>
<br><br><br><br>
<footer class="container-fluid"></footer>
 
 <script>
$(document).ready(function(){
	
	$.post("footer.php", {	
	}, function(data) {
		$('footer').html(data);
	});
	
	 var CSS = <?php if(isset($_GET["java"])) { echo(json_encode($_GET["java"])); } else { echo(json_encode("")); }?>;
	 
	 if(CSS == 3) {
		$("#email").css("border","2px solid red");
		$("#pwd").css("border","2px solid red");
	 }
	 else if(CSS == 2) {
		$("#email").css("border","2px solid red");
		$("#pwd").css("border","2px solid red");		
	 }
	 else if(CSS == 1) {
		$("#pwd").css("border","2px solid red");
        var value = localStorage.getItem("save");
		$("#email").val(value);
		$("#email").css("border","2px solid green");
	 }
	 else
	 {
		localStorage.removeItem("save"); 
	 }
	 
	
	$("#submit2").click(function() {  
		var lastname = $("#lastname").val();
		var name = $("#name").val();
		var mail = $("#mail").val();
		var aem = $("#aem").val();
		var phone = $("#phone").val();
		var department = $("#department").val();
		var message = $("#message").val();
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
		if (department == null) {
			$('#select').css("border","red solid 2px");
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
			empty = true;
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
			
			$.post("guest/sign_in_process.php", { 
				lastname1: lastname,
				name1: name,
				mail1: mail,
				aem: aem,
				phone: phone,
				message: message,				
				id: department
			}, function(data, status) {
					if(status) 
					{
						if ($(window).width() < 768) {
							mcxDialog.alert("Η εγγραφή σας έγινε με επιτυχία και βρίσκεται σε διαδικασία έγκρισης από τον διαχειριστή");
						}
						else 
						{	
							alert("Η εγγραφή σας έγινε με επιτυχία και βρίσκεται σε διαδικασία έγκρισης από τον διαχειριστή");
							//alert(data);
						}
						$('#form')[0].reset();
					}
			});
		}
	});

	$("#remind").click(function(){
			
		var val = document.getElementById("remind_email").value;

			
		$.post("find_user.php", {	
			username: val
		}, function(data) {

			var message;		
			if(data == 0) {
				message = "Ο χρήστης με το username δεν υπάρχει στην βάση δεδομένων";
				$("#remind_email").css("border","2px solid red");
			}
			else
			{
				message = "Ένα email με τον νέο κωδικό στάλθηκε στο email σας";
				$("#remind_email").css("border","2px solid green");
			}
			$("#remind_message").html(message); 
			
		});
		
	});

	 
});
</script>


<script type="text/javascript">
	function myScript() {
		var val = document.getElementById("email").value;
		localStorage.setItem("save", val);
	}	
</script>
</body>
</html>