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
<?php 

session_start();
include ("../connectPDO.php");

if(isset($_SESSION['student']))
{
	$alumni_id = $_SESSION['student'];
}
else
{
header('Location: ../register_login_form.php');
}

$var = 0;
if(!isset($_GET['Failed'])) {
	$loginFailed = "";
	$reasons="";
	$var=1;
}
$reasons = array(
	"blank" => "Παρακαλώ επιλέξτε ένα αρχείο .pdf για υποβολή",
	"size"	=> "Το αρχείο που επιλέξατε έχει μέγεθος μεγαλύτερο από 5 MB, γι αυτό και απορρίφθηκε",
	"error"	=> "Το αρχείο δεν αποθηκεύτηκε στην βάση παρακαλώ δοκιμάστε ξανά ή ενημερώστε τους διαχειριστές",
	"type"	=> "Το αρχείο που επιλέξατε δεν είναι pdf. Παρακαλώ επιλέξτε ένα αρχείο .pdf για υποβολή",
);
if($var == 0) {
	if($_GET["Failed"] == 'true') {
		$message = $reasons[$_GET["reason"]];
		echo "<script type='text/javascript'> var w = Math.max(document.documentElement.clientWidth, window.innerWidth || 0); if (w < 768) {  mcxDialog.alert('$message'); } else { alert('$message'); }</script>";
	}
	else
	{
		$message = "Το βιογραφικό σας ανέβηκε με επιτυχία";
		echo "<script type='text/javascript'> var w = Math.max(document.documentElement.clientWidth, window.innerWidth || 0); if (w < 768) {  mcxDialog.alert('$message'); } else { alert('$message'); }</script>";
	}
}

?>
<br><br><br><br><br><br>
<div class="container">
  <h2 id="login">Ανέβασμα Βιογραφικού</h2><hr>
  <br>
    <form action="upload_pdfFile.php" method="post" enctype="multipart/form-data">
		<div class="form-group">
		  <label for="text">Επιλέξτε ένα .pdf αρχείο το οποίο περιλαμβάνει το βιογραφικό σας:</label>
		  <input name="mycvPDF" class="btn btn-default" type="file" id = "myPdf"/>
		</div>
		<br>
		<div class="form-group" id = "bloc1">
			<input type="submit" class="btn btn-info" value="Υποβολή"></input>
		</div><br><br>
  </form>
 	
 </div>
 
 <br><br><br><br><br><br><br><br><br><br><br><br>
<footer class="container-fluid text-center">
  Footer Text
</footer>
 
<script>
$(document).ready(function(){
	
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