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

$var = 0;
if(!isset($_GET['Failed'])) {
	$loginFailed = "";
	$reasons="";
	$var=1;
}
$reasons = array(
	"blank" => "Παρακαλώ επιλέξτε ένα αρχείο για υποβολή",
	"csv"	=> "Παρακαλώ επιλέξτε ένα csv αρχείο για υποβολή",
);
if($var == 0) {
	if($_GET["Failed"] == 'true') {
		$message = $reasons[$_GET["reason"]];
		echo "<script type='text/javascript'>var w = Math.max(document.documentElement.clientWidth, window.innerWidth || 0); if (w < 768) { mcxDialog.alert('$message'); } else { alert('$message'); } </script>";
	}
	else
	{
		$alumni = $_GET["number"];
		if($alumni > 1) {
			$message = "Η δημιουργία των ".$alumni." αποφοίτων πραγματοποιήθηκε με επιτυχία";
		}
		else
		{
			$message = "Η δημιουργία του αποφοίτου πραγματοποιήθηκε με επιτυχία";
		}
		echo "<script type='text/javascript'>var w = Math.max(document.documentElement.clientWidth, window.innerWidth || 0); if (w < 768) { mcxDialog.alert('$message'); } else { alert('$message'); } </script>";
	}
}

?>

<br><br><br>
  <h2>Νέος Απόφοιτος</h2><br>
<div class="container">
<br>
<p align="center" id="login"><span class="label label-info"><b>INFO</span> Συμπληρώστε την φόρμα ή επιλέξτε ένα αρχείο csv</b> </p>
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
  	<label for="text">Αριθμός Μητρώου Φοιτητή(ΑΕΜ): (Προαιρετικά)</label>
    <div class="form-group">
	<input class="form-control" id="aem" name="aem">
  </div>
  <div class="form-group">
	  <label for="text">Επιλέξτε Τμήμα:</label><br>
	  <div id = "select_div">
		  <select class="selectpicker form-control" name="department" id = "department">
				<option value="0" disabled selected>--Επιλογή--</option>
		<?php 
		$stmt = $conn->prepare("SELECT * FROM departments");
		$stmt->execute();
		$result = $stmt->fetchAll();
		foreach($result as $row) {
			
			echo '<option value="'.$row['id'].'">'.$row['dname'].'</option>';
			
		}
		 
		?>
			</select>
	  </div>		
  </div>
   <br>
   <div class="form-group" id = "bloc4">
		<input type="button" class="btn btn-primary" value ="Δημιουργία" id = "submit2"></input>
   </div>
   
</form>

<br><br>

<hr>

<form action="create_alumni_csv.php" method="post" enctype="multipart/form-data">
  <label for="InputFile">Επιλογή αρχείου csv: (Το αρχείο πρέπει να έχει κατάληξη .csv)</label>
  <label for="InputFile">Προσοχή! Η τελευταία γραμμή του αρχείου δεν θα πρέπει να είναι κενή</label>
  <div class="form-group">
	<button type="button" class="btn btn-link" data-toggle="modal" data-target="#myModal">Δείτε την πρότυπη μορφή ενός csv αρχείου</button>    
	<input type="file" class="form-control-file" name="file" id="file">
  </div>
  <button type="submit" class="btn btn-primary" name="submit">Υποβολή</button> 
</form>

<br>
</div>

<br><br><br>
<footer class="container-fluid text-center">
  Footer Text
</footer>

<!-- Modal -->
<div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title" align="center">Πρότυπη μορφή csv</h4>
        </div>
        <div class="modal-body">
			<i>(Επώνυμο, Όνομα, Email, DepartmentID)</i><br>
			<p><font color="black">όπου:</font></p>
	<?php		
		echo	'<table class="betta">';
		echo	  '<tr>';
		echo		'<th>DepartmentID</th>';
		echo		'<th>Τμήμα</th>';
		echo	  '</tr>';
		foreach($result as $row) {
			echo	  '<tr>';
			echo		'<td>'.$row['id'].'</td>';
			echo	  	'<td>'.$row['dname'].'</td>';
			echo	  '</tr>';
		}
		echo	'</table>';
	?>		
		</div>
      </div>
    </div>
</div>


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
		var lastname = $("#lastname").val();
		var name = $("#name").val();
		var mail = $("#mail").val();
		var did = $("#department").val();
		var aem = $("#aem").val();

		var blank = false;
		
		/*
		if (lastname == '' || name == '' || mail == '' || username == '' || graduate == '' || grade == '' || pass == '' || did == null) {
			alert("Παρακαλώ συμπληρώστε όλα τα πεδία προτού προχωρήσετε στην δημιουργία του αποφοίτου");
		} */
		if (lastname == '') {
			$("#lastname").css("border", "red solid 2px");
			blank = true;
		}
		else
		{
			$("#lastname").css("border", "");
		}
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

		if (did == null) {
			$("#select_div").css("border", "red solid 2px");
			blank = true;
		}
		else
		{
			$("#select_div").css("border", "");
		}
		if(blank) {
			if ($(window).width() < 768) {
				mcxDialog.alert("Παρακαλώ συμπληρώστε όλα τα πεδία προτού προχωρήσετε στην δημιουργία του αποφοίτου");
			}
			else 
			{
				alert("Παρακαλώ συμπληρώστε όλα τα πεδία προτού προχωρήσετε στην δημιουργία του αποφοίτου");
			}
		}
		else
		{			

			$.post("create_alumni_process.php", { 
				lastname1: lastname,
				name1: name,
				mail1: mail,
				did: did,
				aem: aem
			}, function(data, status) {
				if(status) {
					document.getElementById("forma").reset();
					var message;
					if(data == 1) {
						message = "Η δημιουργία του χρήστη πραγματοποιήθηκε με επιτυχία";
					}
					else
					{
						message = "Παρουσιάστηκε πρόβλημα στην δημιουργία νέου χρήστη";
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
