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

<br><br><br>

<?php

session_start();
include ("../connectPDO.php");
if(isset($_SESSION['name'])) {
	$admin_id = $_SESSION['name'];

	$stmt = $conn->prepare("SELECT * from users WHERE id = ?");   
	$stmt->execute(array($admin_id));															
	$result = $stmt->fetchAll();

	foreach($result as $row) {
		$admin_name = $row['name'];
		
		echo "<br>";
		echo '<p style = "font-size: 150%; padding-left: 10px; color: white;">Καλώς ήρθες '.$admin_name.'</p>';
	}
}
else
{
header('Location: ../register_login_form.php');
}

$stmt = $conn->prepare("SELECT * from ekkremothtes ORDER BY id DESC");   
$stmt->execute();															
$result = $stmt->fetchAll();

	

echo '<div class="container">';
echo		'<div align="center">';
echo			'<h2 id="login">Λίστα Εκκρεμοτήτων</h2>';
echo		'</div><br>';
echo			'<p id="login"><span class="label label-info"><b>INFO </span>  Η λίστα εκκρεμοτήτων αφορά τους διαχειριστές της ιστοσελίδας και μπορεί να χρησιμοποιηθεί σαν σημειωματάριο.</b> </p>';
echo			'<p id="login"><span class="label label-info"><b>INFO 2</span> Μπορείτε να τροποποιήσετε τις εκκρεμότητες και να αποθηκεύσετε τις αλλαγές χρησιμοποιώντας το "check" button και να τις διαγράψετε με το "x" button.</b> </p>';
echo	        '<br><form id="taskEntryForm">';
echo				'<label for="text">Εκκρεμότητες:</label>';
echo            	'<input class="form-control" id="taskInput" placeholder="Έχω να κάνω..." />';
echo	        '</form>';
echo			'<br><br>';
echo			'<div style="overflow-x:auto;">';
echo				'<table style="width:100%" id="myTable2">';
echo					'<tbody>';
					if(sizeof($result)>0) {
						foreach($result as $row) {
							$author_id = $row['admin_id'];
							$content = $row['content'];
							$id = $row['id'];
							
							$stmt2 = $conn->prepare("SELECT * from users WHERE id = ?");   
							$stmt2->execute(array($author_id));															
							$result2 = $stmt2->fetchAll();

							foreach($result2 as $row2) {
								$admin_name2 = $row2['name'];
							}
						echo	'<tr>';	
						echo		'<td colspan="3"><b>Ο διαχειριστής ' .$admin_name2.' σημείωσε:</b></td>';	
						echo		'<tr>';
						echo			'<td width = "80%"><textarea id = "'.$id.'" rows="4" class="form-control" style="resize:none;">'.$content.'</textarea></td>';
						echo			'<td align="center" width = "10%"><img id = "'.$id.'" src="../assets/approve1.png" alt="save" width="25" height="25" onclick="saveChanges(this.id)" onmouseover="" style="cursor: pointer;""></td>';						
						echo			'<td align="center" width = "10%"><img id = "'.$id.'" src="../assets/delete.png" alt="delete" width="25" height="25" onclick="myFunction(this.id)" onmouseover="" style="cursor: pointer;""></td>';
						echo		'</tr>';
						echo	'</tr>';
						}
					}			

echo					'</tbody>';
echo				'</table>';
echo				'<br>';
echo			'</div>';	
echo	'</div>';
?>

<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
<footer class="container-fluid text-center"></footer>


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

	$('#taskEntryForm').submit(function () {
        if ($('#taskInput').val() !== "") 
		{
			var text = $('#taskInput').val();
			var admin_id = <?php echo json_encode($admin_id); ?>;
			
			$.post("new_ekkremothta.php", {
				text: text,
				admin_id: admin_id
			}, function(data) { 
				location.reload();
			});
        }
        return false;
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

function myFunction(id)
{
	var w = Math.max(document.documentElement.clientWidth, window.innerWidth || 0);  //συναρτηση υπολογισμου του πλατους της οθόνης
	
	if (w < 768) 
	{	
		mcxDialog.confirm("Θέλετε σίγουρα να διαγράψετε την εκκρεμότητα?", {
			sureBtnText: "OK",  
			sureBtnClick: function(){  
				
				$.post("delete_ekkremothta.php", {
					id: id
				}, function(data) { 
					location.reload();
				});
				
			}
		});
	}
	else 
	{
		var response = confirm("Θέλετε σίγουρα να διαγράψετε την εκκρεμότητα?");
		
		if (response == true) {

			$.post("delete_ekkremothta.php", {
				id: id
			}, function(data) { 
				location.reload();
			}); 

		}
	}
}

function saveChanges(id) {
	
	var textarea = document.getElementById(id);
	var text = textarea.value;
	var w = Math.max(document.documentElement.clientWidth, window.innerWidth || 0);  //συναρτηση υπολογισμου του πλατους της οθόνης
	
	if (w < 768) 
	{	
		mcxDialog.confirm("Θέλετε να αποθηκεύσετε τις αλλαγές?", {
			sureBtnText: "OK",  
			sureBtnClick: function(){  
				
				$.post("saveChangesekkremothta.php", {
					id: id,
					text: text
				}, function(data) { 
					location.reload();
				});
				
			}
		});
	}
	else 
	{
		var response = confirm("Θέλετε να αποθηκεύσετε τις αλλαγές?");
		
		if (response == true) {

			$.post("saveChangesekkremothta.php", {
				id: id,
				text: text
			}, function(data) { 
				location.reload();
			}); 

		}
	}
	
}

</script>

</body>
</html>
