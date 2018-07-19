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
  
  <link rel="stylesheet" href="../css/admin.css"> 

  <link rel="stylesheet" type="text/css" href="../phones_specs/css/dialog-mobile.css"/> <!-- css & javascript για τις ειδοποιήσεις (alerts) στα κινητα -->
  <script type="text/javascript" src="../phones_specs/mcx-dialog.js"></script>  
  
    
</head>

<body>

<header>

</header>

<br><br><br><br>
<div align="center">     
	<h2>Αιτήσεις Εγγραφής Αποφοίτων</h2><br>
</div>

<?php
session_start();

if(isset($_SESSION['name'])) {
	$admin_id = $_SESSION['name'];
}
else
{
header('Location: ../register_login_form.php');
}

include ("../connectPDO.php"); 
																	//created_by = 1 => alumni
																	//created_by = 0 => admin
$stmt = $conn->prepare("SELECT * FROM users WHERE created_by = 1");
$stmt->execute();
$result = $stmt->fetchAll();
				
echo '<div class = "container">';
echo '<br>';
echo	'<p id="login"><span class="label label-info"><b>INFO</span> Μπορείτε να τροποποιήσετε τα στοιχεία εγγραφής του υποψήφιου απόφοιτου και να αποθηκεύσετε τις αλλαγές εγκρίνοντας την εγγραφή χρησιμοποιώντας το "check" button.</b> </p>';
echo '<br>';	
echo '<div style="overflow-x:auto;">';
echo '<table style="width:100%" id="myTable2">';
echo    "<tbody>";

if(sizeof($result)>0) {
	foreach($result as $row) {
		$did = $row['department_id'];
		$lastname = $row['lastname'];
		$name = $row['name'];
		$email = $row['email'];
		$id = $row['id'];
		$aem = $row['aem'];
		$cell_phone = $row['cell_phone'];
		$message = $row['messageToadmin'];
		if($cell_phone == "") {
			$cell_phone = "Ο υποψήφιος χρήστης δεν έχει καταχωρήσει κινήτο τηλέφωνο.";
		}
		if($message == "") {
			$message = "Δεν υπάρχει κάποιο μήνυμα από τον υποψήφιο χρήστη απόφοιτο.";
		}
					
		$stmt2 = $conn->prepare("SELECT * FROM departments WHERE id = ?");
		$stmt2->execute(array($did));
		$result2 = $stmt2->fetchAll();
								
		echo	"<tr>";
			echo		'<td colspan="3"><b>Αίτηση εγγραφής: ' .$lastname .' ' . $name . '</b></td>';
			echo		'<tr>';
			echo			'<td width = "80%">';
			echo					'<label for="text">Σχολή:</label>';
			echo					'<select class="selectpicker" data-width="100%" name="department" id = "department'.$id.'">';
									$stmt2 = $conn->prepare("SELECT * FROM departments");
									$stmt2->execute();
									$result2 = $stmt2->fetchAll();
									foreach($result2 as $row2) {
										if($row2['id'] == $did) {
											echo '<option value="'.$row2['id'].'" selected>'.$row2['dname'].'</option>';
										}
										else
										{	
											echo '<option value="'.$row2['id'].'">'.$row2['dname'].'</option>';
										}
									}
			echo					'</select><br>';
			echo					'<label for="text">Αριθμός Μητρώου Φοιτητή:</label><input class="form-control" id="aem'.$id.'" value = "'.$aem.'">';
			echo					'<label for="text">Email:</label><input class="form-control" id="email'.$id.'" value = "'.$email.'">';
			echo					'<label for="text">Κινητο:</label><input class="form-control" id="cell_phone'.$id.'" value = "'.$cell_phone.'">';
			echo					'<label for="text">Μήνυμα Εγγραφής Αποφοίτου:</label><textarea class="form-control" style="resize:none;">'.$message.'</textarea>';
			echo			'</td>';
			echo			'<td align="center" width = "10%"><img id = "'.$id.'" src="../assets/approve1.png" alt="save" width="25" height="25" onclick="saveChangesApprove(this.id)" onmouseover="" style="cursor: pointer;""></td>';						
			echo			'<td align="center" width = "10%"><img id = "'.$id.'" src="../assets/delete.png" alt="delete" width="25" height="25" onclick="reject(this.id)" onmouseover="" style="cursor: pointer;""></td>';
			echo		'</tr>';
		echo	'</tr>';
			
	}
}
else
{
	echo  '<tr><td class="text-center" colspan = "3"> Δεν υπάρχουν ετήσεις εγγραφής</td></tr>'; 
}

?>
    </tbody>
</table>
<br>
</div>
</div>


<br><br><br>
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

<script>

function saveChangesApprove(id) {
	var w = Math.max(document.documentElement.clientWidth, window.innerWidth || 0);  //συναρτηση υπολογισμου του πλατους της οθόνης
	
	var aem = $("#aem"+id).val();
	var did = $("#department"+id).val();
	var email = $("#email"+id).val();
	var cell_phone = $("#cell_phone"+id).val();
	
	$.get("alumni_approval.php", {
		approve: id,
		aem: aem,
		did: did,
		email: email,
		cell_phone: cell_phone
	}, function(data) {
		location.reload();
	});	
	
	
	if (w < 768) {
		mcxDialog.alert("Ο απόφοιτος θα ενημερώθεί για την επιτυχή εγγραφή του.");
	}
	else 
	{
		alert("Ο απόφοιτος θα ενημερώθεί για την επιτυχή εγγραφή του.");
	}
	return true;
}

function reject(id) {
	var w = Math.max(document.documentElement.clientWidth, window.innerWidth || 0);  //συναρτηση υπολογισμου του πλατους της οθόνης
	
	$.get("alumni_approval.php", {
		reject: id
	}, function(data) {
		location.reload();
	});
	
	if (w < 768) {
		mcxDialog.alert("Ο απόφοιτος θα ενημερώθεί για την απόρριψη της άιτησης του.");
	}
	else 
	{
		alert("Ο απόφοιτος θα ενημερώθεί για την απόρριψη της άιτησης του.")
	}
	return true;
}
</script>

</body>
</html>
