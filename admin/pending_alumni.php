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

<br><br><br><br>
<div align="center">     
	<h2>Λογαριασμοί χρηστών σε εκκρεμότητα<br>αλλαγής κωδικού πρόσβασης</h2><br>
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
	
$stmt = $conn->prepare("SELECT * FROM users WHERE change_password = 0 AND role = 1");
$stmt->execute();
$result = $stmt->fetchAll();
				
echo '<div class = "container">';
echo '<div style="overflow-x:auto;">';
echo '<table class="table">';
echo    "<thead>";
echo      "<tr>";
echo        '<th class="text-center">Επώνυμο</th>';
echo		'<th class="text-center">Όνομα</th>';
echo        '<th class="text-center">Σχολή</th>';
echo		'<th class="text-center">Email εγγραφής</th>';
echo		'<th class="text-center"><input type="checkbox" value="" id = "select_all"></th>';
echo      "</tr>";
echo    "</thead>";
echo    "<tbody>";

if(sizeof($result)>0) {
	foreach($result as $row) {
		$did = $row['department_id'];
		$lastname = $row['lastname'];
		$name = $row['name'];
		$email = $row['email'];
		$id = $row['id'];
					
		$stmt2 = $conn->prepare("SELECT * FROM departments WHERE id = ?");
		$stmt2->execute(array($did));
		$result2 = $stmt2->fetchAll();
					
		foreach($result2 as $row2) {
			$dname = $row2['dname'];
			
			echo	"<tr>";
			echo	'<td class="text-center">'.$lastname.'</td>';
			echo	'<td class="text-center">'.$name.'</td>';
			echo	'<td class="text-center">'.$dname.'</td>';
			echo	'<td class="text-center">'.$email.'</td>';
			echo	'<td class="text-center"><input class = "messageCheckbox" type="checkbox" value="'.$id.'"></td>';
			echo	"</tr>";
		}
	}
}
else
{
	echo  '<tr><td class="text-center" colspan = "5"> Δεν υπάρχουν λογαριασμοί χρηστών σε εκκρεμότητα</td></tr>'; 
}

?>
    </tbody>
</table>
</div><br>
<div id = "bloc">			  
	<input id = "submit" type="button" value="Αποστολή Email" class="btn btn-primary" >					  
</div>
<br><br>
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
	
	$('#select_all').click(function(event) {
	  
	  if(this.checked) {
		  $(':checkbox').each(function() {
			  this.checked = true;
		  });
	  }
	  else {
		$(':checkbox').each(function() {
			  this.checked = false;
		  });
	  }
	  
	});
	
	$('#submit').click(function() {
	
		var checkedValue = []; 
		var counter = 0;
		var inputElements = document.getElementsByClassName('messageCheckbox');
		for(var i=0; inputElements[i]; ++i){
			if(inputElements[i].checked){
				checkedValue[counter] = inputElements[i].value;
				counter = counter + 1;
			}
		}
		if(checkedValue.length != 0) {
			$.post("sent_email.php", {
				recipients: checkedValue
			}, function(status) {
				if(status) {
					var response = counter + " email στάλθηκαν. Οι παραλήπτες θα ενημερωθούν για να αλλάξουν κωδικό πρόσβασης";
					if ($(window).width() < 768) {
						mcxDialog.alert(response);
					}
					else 
					{
						alert(response);
					}
					location.reload();
				}
			});
		}
		else
		{
			if ($(window).width() < 768) {
				mcxDialog.alert("Πρέπει να επιλέξεις παραλήπτες πρώτα");
			}
			else 
			{
				alert("Πρέπει να επιλέξεις παραλήπτες πρώτα");
			}
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
