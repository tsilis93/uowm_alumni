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

<br><br><br><br><br>
<div class = "container">     
	<h2 align="center">Λογαριασμοί αποφοίτων που έχω<br>επιλέξει να ακολουθώ</h2><hr>
<form action="sent_email_form.php" method="post" style="overflow-x:auto;">			
	<table class="table" id = "myTable2" width="100%">
		<thead>
	      <tr>
			<th class="text-center">Επώνυμο</th>
			<th class="text-center">Όνομα</th>
			<th class="text-center">Σχολή</th>
			<th class="text-center">Προβολή</th>
			<th class="text-center">Επιλογή</th>
			<th class="text-center"></th>
		  </tr>
		</thead>
	<tbody>
<?php

session_start();
include ("../connectPDO.php");

if(isset($_SESSION['student'])) {
	$alumni_id = $_SESSION['student'];
}
else
{
header('Location: ../register_login_form.php');
}

$stmt = $conn->prepare("SELECT * FROM user_relationship WHERE alumni_id = ?");
$stmt->execute(array($alumni_id));
$result = $stmt->fetchAll();

if(sizeof($result)>0) {

	foreach($result as $row) {
		$friend_alumni_id = $row['friend_alumni_id'];

		$stmt2 = $conn->prepare("SELECT * FROM users WHERE id = ?");
		$stmt2->execute(array($friend_alumni_id));
		$result2 = $stmt2->fetchAll();
		
		foreach($result2 as $row2) {
			$did = $row2['department_id'];
			$lastname = $row2['lastname'];
			$name = $row2['name'];
			
			$stmt3 = $conn->prepare("SELECT * FROM departments WHERE id = ?");
			$stmt3->execute(array($did));
			$result3 = $stmt3->fetchAll();
					
			foreach($result3 as $row3) {
				$dname = $row3['dname'];
			}
			echo	"<tr>";
			echo		'<td class="text-center">'.$lastname.'</td>';
			echo		'<td class="text-center">'.$name.'</td>';
			echo		'<td class="text-center">'.$dname.'</td>';
			echo		'<td class="text-center"><button type="button" id="'.$friend_alumni_id.'" data-toggle="modal" data-target="#myModal2"><span class="glyphicon glyphicon-folder-open"></span></button></td>'; 
			echo		'<td class="text-center"><input type="checkbox" class = "messageCheckbox" name="recipients[]" value="'.$friend_alumni_id.'"></td>';			
			echo		'<td class="text-center"><button id = "'.$friend_alumni_id.'" class="btn btn-link" onclick="javascript:unfollow_alumni(this.id); return false;"><font color="red">Unfollow<font></button></td>';
			echo	"</tr>";		
		}
		echo '<tr><td colspan = "6" style="text-align:right"><input id = "submit" type="submit" value="Αποστολή Email" class="btn btn-primary"></td></tr>';
	}
}
else
{
	echo  '<tr align = "center"><td colspan = "6"> Δεν έχετε επιλέξει να ακολουθήσετε κάποιον απόφοιτο</td></tr>'; 
}

?>
    </tbody>
</table>
</form>
</div>

<br><br><br><br><br><br><br><br><br><br><br>
<footer class="container-fluid text-center">
  Footer Text
</footer>

<div class="modal fade" id="myModal2" role="dialog">
    
	<div class="modal-dialog modal-lg">
    
		  <div class="modal-content">
				
				<div class="modal-header">
				  <button type="button" class="close" data-dismiss="modal">&times;</button>
				  <h1 align = "center">Στοιχεία Αποφοίτου</h1>
				</div>
				<div class="modal-body edit-content">
				  
					
				</div>
				
				<div class="modal-footer">
				
				</div>  
				
		  </div>
      
    </div>
	
</div>

<script>
$(document).ready(function() {
		
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
	
	$('#myModal2').on('show.bs.modal', function(e) {
            
        var $modal = $(this);
        var $esseyId = e.relatedTarget.id;
            
		$.get("../alumni_details.php", {
			id: $esseyId
		}, function(data) {
			$modal.find('.edit-content').html(data);
        });
			
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

<script type="text/javascript">

function unfollow_alumni(id)
{
	var w = Math.max(document.documentElement.clientWidth, window.innerWidth || 0);  //συναρτηση υπολογισμου του πλατους της οθόνης

	if (w < 768) 
	{	
		mcxDialog.confirm("Θέλετε σίγουρα να διαγράψετε τον απόφοιτο από τους φίλους σας?", {
			sureBtnText: "OK",  
			sureBtnClick: function(){  
				
				$.post("unfollow_alumni.php", {
					alumni_id: id
				}, function(data) { 
					mcxDialog.alert("Σταματήσατε να ακολουθάτε τον απόφοιτο με επιτύχια");
					location.reload();
				});
				
			}
		});
	}
	else 
	{		
	
		var response = confirm("Θέλετε σίγουρα να διαγράψετε τον απόφοιτο από τους φίλους σας?");
			
		if (response == true) {

			$.post("unfollow_alumni.php", {
				alumni_id: id
			}, function(data) { 
				alert("Σταματήσατε να ακολουθάτε τον απόφοιτο με επιτύχια");
				location.reload();
			}); 

		} 
	} 
	
}

</script>

</body>
</html>