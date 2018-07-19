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

<br><br><br><br>
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

$story_id = 0;
if(isset($_GET['story'])) {
	$story_id = $_GET['story'];
}

if(isset($_GET['published_story'])) {
	$story_id = $_GET['published_story'];
}

if(isset($_GET['rejected_story'])) {
	$story_id = $_GET['rejected_story'];
}

$stmt = $conn->prepare("SELECT * FROM stories WHERE id = ?");
$stmt->execute(array($story_id));
$result = $stmt->fetchAll();

$stmt5 = $conn->prepare("SELECT * from images WHERE storyID = ?");   
$stmt5->execute(array($story_id));		// αναζητώ αν υπάρχουν αποθηκευμένες εικόνες για την συγκεκριμένη ιστορία													
$result5 = $stmt5->fetchAll();

if(sizeof($result)>0) {
	foreach($result as $row) 
	{
		$title=$row['title'];
		$author_id=$row['userID'];
		$description=$row['description'];
		$body = $row['body'];
		$definition = $row['definition'];
							
		$stmt2 = $conn->prepare("SELECT name, lastname FROM users WHERE id=?");
		$stmt2->execute(array($author_id));
		$author = $stmt2->fetchAll();

		if(sizeof($author) > 0) {
			$author_name = $author[0][0] . " " . $author[0][1];
		}

		echo "<h2>Σύνοψη Ιστορίας</h2>";
		echo "<br>";
		echo '<div class="container">';
			echo "<br>";				 
			echo  '<form id = "form" name = "form">';

			echo		'<div class="form-group">';
			echo				'<label for="text">Συντάκτης</label>';
			echo				'<input class="form-control" id="owner" name="owner" value="'.$author_name.'" disabled="disabled">';
			echo		"</div>";
			echo		'<div class="form-group">';
			echo				'<label for="text">Προβάλλεται σε</label>';
			echo				'<table width="100%" id="myTable33">';					
								$stmt2 = $conn->prepare("SELECT * FROM departments");
								$stmt2->execute();
								$result2 = $stmt2->fetchAll();
								$arrayOfDepartmentIds = array();
								$array_counter = 0;
	
								foreach($result2 as $row2) 
								{
									$did = $row2['id'];
									$dname = $row2['dname'];
									$published = "published_department".$did;
									
									$arrayOfDepartmentIds[$array_counter] = $did;
									$array_counter = $array_counter + 1;
									
									if($row[$published] == 1) {
										echo '<tr>';
										echo 	'<td width="95%"><input class="form-control" id="owner" name="owner" value="'.$dname.'" disabled="disabled"></td>';
										echo 	'<td align="center" width = "5%"><input type="checkbox" id="'.$published.'" checked></td>';
										echo '</tr>';
									}
									else
									{
										echo '<tr>';
										echo 	'<td width="95%"><input class="form-control" id="owner" name="owner" value="'.$dname.'" disabled="disabled"></td>';
										echo 	'<td align="center" width = "5%"><input type="checkbox" id="'.$published.'"></td>';
										echo '</tr>';																			
									}
								} 			
			echo				'</table>';
			echo		"</div>";			
			echo		'<div class="form-group">';
			echo			'<label for="text">Τίτλος:</label>';
			echo			'<input class="form-control" id="title" name="title" value="'.$title.'">';
			echo		"</div>";
			echo		'<div class="form-group">';
			echo			'<label for="text">Σύντομη Περιγραφή:</label>';
			echo			'<textarea class="form-control" id="description" style="height:80px;" name = "description">'.htmlspecialchars($description).'</textarea>';
			echo		"</div>";
			echo		'<div class="form-group">';
			echo			'<label for="text">Περιεχόμενο:</label>';
			echo			'<textarea class="form-control" style="height:190px;" id="body" name = "body">'.htmlspecialchars($body).'</textarea>';
			echo		"</div>";
			echo		'<div class="form-group">';
			echo			'<label for="text">Κατηγορία Ιστορίας:</label>';
			echo				  '<select class="selectpicker" data-width="100%"  name="definition" id = "definition">';
								  if($definition == 0) {
			echo						'<option value="0" selected>Εσωτερική (ορατή μόνο από τους εγγεγραμμένους χρήστες)</option>';				
			echo						'<option value="1">Δημόσια (ορατή από όλους τους χρήστές)</option>';
								  }
								  else
								  {
			echo						'<option value="0">Ιστορία Εσωτερική Πανεπιστημίου Δυτικής Μακεδονίας</option>';				
			echo						'<option value="1" selected>Δημόσια Ιστορία</option>';									  								  
								  }
			echo				  '</select>';
			echo		"</div><br>";
			
			if(isset($_GET['story'])) {
				echo '<label for="text">Σχόλια (προαιρετικά):</label><br>';
				echo '<textarea placeholder="Προαιρετικά σχόλια για τον απόφοιτο στην περίπτωση που απορριφθεί η Ιστορία ..." class="form-control" style="height:50px;" id="comments" name = "comments"></textarea><br>';
			}
			
			echo '<label for="text">Εικόνες Ιστορίας:</label><br>';
			if (sizeof($result5)> 0) {  //αν υπάρχουν εικόνες τότε τις εμφανίζω την μια διπλα στην αλλη
				foreach($result5 as $row5) {
					$images_path3 = $row5['images_path'];
					$image_id2 = $row5['id']; // αποθηκεύω το id της εικόνας από την βάση στο id του tag <img>					
					echo '<img id = "image'.$image_id2.'" onclick="javascript:openModal(this.id);" onmouseover="" style="cursor: pointer;" src ="../content_images/'.$images_path3.'" width=100 height=100></img>';   
					echo '<img id = "'.$image_id2.'" src="../assets/delete_images2.png" width=15 height=15 onclick="javascript:delete_images(this.id);" onmouseover="" style="cursor: pointer; top:-2; left:1; position: absolute;"/>';
					// περνάω στην συνάρτηση το <tag> id και παράλληλα το id της εικόνας
					echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
				}
			}
			else
			{
				echo	'<p style = "color:black"> Δεν υπάρχουν διαθέσιμες εικόνες</p>';
			}
			
			echo		"<br><br>";
			if(isset($_GET['published_story'])) {
				echo '<div style="overflow-x: auto;">';
				echo	'<table width="100%" id="myTable33">';
				echo		'<tr>';
				echo			'<td colspan="2"><button type="button" class="btn btn-link" data-toggle="modal" data-target="#myModal2">Eπεξήγηση επιλογών</button></td>';
				echo			'<td colspan="4"></td>';
				echo		'</tr>';
				echo		'<tr>';
				echo			'<td width="10%"><a href = "admin_story_process.php"><span id = "arrow" class="glyphicon">&#xe091;</span></a></td>';
				echo			'<td width="30%"><input id = "submit" type="button" value="Δημοσίευση" class="btn btn btn-success" disabled><span class="glyphicon glyphicon-ok" style="color:#5cb85c"></span></td>';
				echo			'<td width="30%"><input id = "submit2" type="button" value="Απόρριψη" class="btn btn-warning"><span class="glyphicon glyphicon-remove" style="color:#ff9933"></span></td>';
				echo			'<td width="10%"><input id = "submit3" type="button" value="Αποθήκευση" class="btn btn-primary"></td>';				
				echo			'<td width="10%"><input id = "submit4" type="button" value="Αποθήκευση & Ανανέωση" class="btn btn-info"></td>';								
				echo			'<td width="10%"><input id = "submit5" type="button" value="Διαγραφή" class="btn btn-danger"></td>';												
				echo		'</tr>';
				echo	'</table>';
				echo '</div>';
			}
			else if(isset($_GET['story'])) {
				
				echo '<div style="overflow-x: auto;">';
				echo	'<table width="100%" id="myTable33">';
				echo		'<tr>';
				echo			'<td colspan="2"><button type="button" class="btn btn-link" data-toggle="modal" data-target="#myModal2">Eπεξήγηση επιλογών</button></td>';
				echo			'<td colspan="4"></td>';
				echo		'</tr>';
				echo		'<tr>';
				echo			'<td width="10%"><a href = "admin_story_approval.php"><span id = "arrow" class="glyphicon">&#xe091;</span></a></td>';
				echo			'<td width="30%"><input id = "submit" type="button" value="Δημοσίευση" class="btn btn btn-success" ><span class="glyphicon glyphicon-ok" style="color:#5cb85c"></span></td>';
				echo			'<td width="30%"><input id = "submit2" type="button" value="Απόρριψη" class="btn btn-warning"><span class="glyphicon glyphicon-remove" style="color:#ff9933"></span></td>';
				echo			'<td width="10%"><input id = "submit3" type="button" value="Αποθήκευση" class="btn btn-primary"></td>';				
				echo			'<td width="10%"><input id = "submit4" type="button" value="Αποθήκευση & Ανανέωση" class="btn btn-info" disabled></td>';								
				echo			'<td width="10%"><input id = "submit5" type="button" value="Διαγραφή" class="btn btn-danger"></td>';												
				echo		'</tr>';
				echo	'</table>';
				echo '</div>';	
			}
			else
			{
				echo '<div style="overflow-x: auto;">';
				echo	'<table width="100%" id="myTable33">';
				echo		'<tr>';
				echo			'<td colspan="2"><button type="button" class="btn btn-link" data-toggle="modal" data-target="#myModal2">Eπεξήγηση επιλογών</button></td>';
				echo			'<td colspan="4"></td>';
				echo		'</tr>';
				echo		'<tr>';
				echo			'<td width="10%"><a href = "admin_story_rejected.php"><span id = "arrow" class="glyphicon">&#xe091;</span></a></td>';
				echo			'<td width="30%"><input id = "submit" type="button" value="Δημοσίευση" class="btn btn btn-success"><span class="glyphicon glyphicon-ok" style="color:#5cb85c"></span></td>';
				echo			'<td width="30%"><input id = "submit2" type="button" value="Απόρριψη" class="btn btn-warning" disabled><span class="glyphicon glyphicon-remove" style="color:#ff9933"></span></td>';
				echo			'<td width="10%"><input id = "submit3" type="button" value="Αποθήκευση" class="btn btn-primary"></td>';				
				echo			'<td width="10%"><input id = "submit4" type="button" value="Αποθήκευση & Ανανέωση" class="btn btn-info" disabled></td>';								
				echo			'<td width="10%"><input id = "submit5" type="button" value="Διαγραφή" class="btn btn-danger"></td>';												
				echo		'</tr>';
				echo	'</table>';
				echo '</div>';					
			}
		echo "<br>";	
		echo "</div>";
					
	}
}
else
{
header('Location: ../notFound.php');
}

?>

<div id="myModal" class="modal fade" role="dialog"> <!-- modal για να εμφανίζονται σε μεγαλύτερο μεγεθος οι εικόνες -->
    <button type="button" class="close" data-dismiss="modal">&times;</button>
	<img id="modalImageId" src="" class="modal-content">
</div>

<div id="myModal2" class="modal fade" role="dialog"> <!-- modal για την επεξήγηση των εικονιδίων -->
  <div class="modal-dialog">

    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
		<h4 class="modal-title" align="center">Eπεξήγηση Επιλογών</h4>
      </div>
      <div class="modal-body"> 
		<table class="betta">
			<tr>
				<th align="center">Επιλογή</th>
				<th align="center">Επεξήγηση</th>
			</tr>
			<tr>
				<td align="center"><b>Δημοσίευση:</b></td>
				<td>Η ιστορία γίνεται published (status = 1) και η ημερομηνία δημοσίευσης αρχικοποιείται με την τωρινή ημερομηνία</td>
			</tr>			
			<tr>
				<td align="center"><b>Απόρριψη:</b></td>
				<td>Αν η ιστορία βρίσκεται σε εκκρεμότητα (status = 0) και ημερομηνία δημοσίευσης κενή τότε απορρίπτεται και ενημερώνεται ο απόφοιτος. Αν η ιστορία είναι published (status = 1) τότε η κατάσταση της γίνεται unpublished (status = 0) αλλά η ημερομηνία δημοσίευσης δεν μεταβάλεται </td>
			</tr>			
			<tr>
				<td align="center"><b>Αποθήκευση:</b></td>
				<td>Αποθηκεύει τις αλλαγές στα στοιχεία της ιστορίας.</td>
			</tr>
			<tr>
				<td align="center"><b>Αποθήκευση & Ανανέωση:</b></td>
				<td>Αποθηκεύει τις αλλαγές στα στοιχεία της δημοσιευμένης ιστορίας και τροποποιεί την ημερομηνία δημοσίευσης. Έτσι ως νεώτερη, προβάλλεται πρώτη στην αρχική σελίδα του αντίστοιχου τμήματος.</td>
			</tr>
			<tr>
				<td align="center"><b>Διαγραφή:</b> </td>
				<td>Διαγράφει την ιστορία</td>
			</tr>
		</table>      
	  </div>
    </div>

  </div>
</div>

<br><br><br>
<footer class="container-fluid text-center">
  Footer Text
</footer>




<script>
$(document).ready(function() {
	
	var unsaved = false;

	$.post("admin_header.php", {	
	}, function(data) {
		$('header').html(data);
	});
	
	$.post("../footer.php", {	
	}, function(data) {
		$('footer').html(data);
	});	
	
	$("#submit").click(function() {
		var title = $("#title").val();
		var description = $("#description").val();
		var body = $("#body").val();
		var editor = $("#owner").val();
		var story_id = <?php echo(json_encode($story_id)); ?>;
		var definition = $("#definition").val();
	
		if (title == '' || description == '' || body == '') {
			if ($(window).width() < 768) {
				mcxDialog.alert("Παρακαλώ συμπληρώστε όλα τα πεδία προτού προχωρήσετε στην δημοσίευση");
			}
			else 
			{
				alert("Παρακαλώ συμπληρώστε όλα τα πεδία προτού προχωρήσετε στην δημοσίευση");
			}
		}
		else
		{
	
			$.post("update_approve_story.php", {
				title1: title,
				description1: description,
				body1: body,
				editor1: editor,
				story_id1: story_id,
				definition1: definition
			}, function(data) { 
				if ($(window).width() < 768) {
					mcxDialog.alert("Ο απόφοιτος θα ενημερωθεί για την έγκριση της Ιστορία του");
				}
				else 
				{
					alert("Ο απόφοιτος θα ενημερωθεί για την έγκριση της Ιστορία του");
					//alert(data);
				}			

			});

			var trans = <?php $value = 0; if(isset($_GET['story'])) { $value = 1; } elseif (isset($_GET['published_story'])) { $value = 2; } elseif (isset($_GET['rejected_story'])) { $value = 3; }  echo(json_encode($value)); ?>;			
		
			if(trans == 1) 
			{
				header('Location: admin_story_approval.php');
			}
			else if (trans == 2) 
			{
				header('Location: admin_story_process.php');
			}
			else 
			{
				header('Location: admin_story_rejected.php');
			}
		}
	});
	
	$("#submit2").click(function() {
		var comments = $("#comments").val();
		var story_id = <?php echo(json_encode($story_id)); ?>;
		var editor = $("#owner").val();

		$.post("reject_story.php", {
				comments1: comments,
				editor1: editor,				
				story_id1: story_id
			}, function(data) {
				if ($(window).width() < 768) {
					mcxDialog.alert("Ο απόφοιτος θα ενημερωθεί για την απόρριψη της Ιστορίας του");
				}
				else 
				{
					alert("Ο απόφοιτος θα ενημερωθεί για την απόρριψη της Ιστορίας του");
				}				
		});

		var trans = <?php $value = 0; if(isset($_GET['story'])) { $value = 1; } elseif (isset($_GET['published_story'])) { $value = 2; } elseif (isset($_GET['rejected_story'])) { $value = 3; }  echo(json_encode($value)); ?>;			
		
		if(trans == 1) 
		{
			header('Location: admin_story_approval.php');
		}
		else if (trans == 2) 
		{
			header('Location: admin_story_process.php');
		}
		else 
		{
			header('Location: admin_story_rejected.php');
		} 
	});
	
	$("#submit3").click(function() {
		
		var title = $("#title").val();
		var description = $("#description").val();
		var body = $("#body").val();
		var story_id = <?php echo(json_encode($story_id)); ?>; 
		var definition = $("#definition").val();		
	
		$.post("save_changes_story.php", {
			title: title,
			description: description,
			body: body,
			story_id: story_id,
			definition: definition			
		}, function(data) { 
			if ($(window).width() < 768) {
				mcxDialog.alert("Οι αλλαγές αποθηκεύτηκαν με επιτυχία");
			}
			else 
			{
				alert("Οι αλλαγές αποθηκεύτηκαν με επιτυχία");
			}
		});

		var trans = <?php $value = 0; if(isset($_GET['story'])) { $value = 1; } elseif (isset($_GET['published_story'])) { $value = 2; } elseif (isset($_GET['rejected_story'])) { $value = 3; }  echo(json_encode($value)); ?>;			
		
		if(trans == 1) 
		{
			header('Location: admin_story_approval.php');
		}
		else if (trans == 2) 
		{
			header('Location: admin_story_process.php');
		}
		else 
		{
			header('Location: admin_story_rejected.php');
		} 
					
	});
	
	$("#submit4").click(function() {
		
		var title = $("#title").val();
		var description = $("#description").val();
		var body = $("#body").val();
		var story_id = <?php echo(json_encode($story_id)); ?>;
		var refresh = true;
		var definition = $("#definition").val();		
		
		$.post("save_changes_story.php", {
			title: title,
			description: description,
			body: body,
			refresh: refresh,
			story_id: story_id,
			definition: definition
		}, function(data) { 
			if ($(window).width() < 768) {
				mcxDialog.alert("Οι αλλαγές αποθηκεύτηκαν με επιτυχία");
			}
			else 
			{
				alert("Οι αλλαγές αποθηκεύτηκαν με επιτυχία");
			}
		});

		var trans = <?php $value = 0; if(isset($_GET['story'])) { $value = 1; } elseif (isset($_GET['published_story'])) { $value = 2; } elseif (isset($_GET['rejected_story'])) { $value = 3; }  echo(json_encode($value)); ?>;			
		
		if(trans == 1) 
		{
			header('Location: admin_story_approval.php');
		}
		else if (trans == 2) 
		{
			header('Location: admin_story_process.php');
		}
		else 
		{
			header('Location: admin_story_rejected.php');
		} 			
				
	});
	
	$("#submit5").click(function() {
		
		var story_id = <?php echo(json_encode($story_id)); ?>;
		
		$.post("delete_story.php", {
			story_id: story_id
		}, function(data) { 
			if ($(window).width() < 768) {
				mcxDialog.alert("Η Ιστορία διαγράφηκε με επιτυχία");
			}
			else 
			{
				alert("Η Ιστορία διαγράφηκε με επιτυχία");
			}
		});

		var trans = <?php $value = 0; if(isset($_GET['story'])) { $value = 1; } elseif (isset($_GET['published_story'])) { $value = 2; } elseif (isset($_GET['rejected_story'])) { $value = 3; }  echo(json_encode($value)); ?>;			
		
		if(trans == 1) 
		{
			header('Location: admin_story_approval.php');
		}
		else if (trans == 2) 
		{
			header('Location: admin_story_process.php');
		}
		else 
		{
			header('Location: admin_story_rejected.php');
		} 			
			
	});
	
	$(":input").change(function(){ 
		var current_id = $(this).attr('id');
		
		var arrayOfDepartmentIds = <?php echo(json_encode($arrayOfDepartmentIds)); ?>;
		var table_length = arrayOfDepartmentIds.length;
		var story_id = <?php echo(json_encode($story_id)); ?>;
		var published_change = false;
		
		for(var i=0; i<table_length; i++) {
			if(current_id == "published_department"+arrayOfDepartmentIds[i]) {
								
				var option = arrayOfDepartmentIds[i];
				if(this.checked)
				{
					var action = 1; 
					$.post("update_published_story.php", {
						option: option,
						action: action,
						story_id: story_id
					}, function() {
						if ($(window).width() < 768) {
							mcxDialog.alert('Η προσθήκη στα σημεία προβολής έγινε με επιτυχία');
						}
						else 
						{
							alert('Η προσθήκη στα σημεία προβολής έγινε με επιτυχία');
						}
					});	
				}
				else
				{
					var action = 0; 
					$.post("update_published_story.php", {
						option: option,
						action: action,
						story_id: story_id
					}, function() {
						if ($(window).width() < 768) {
							mcxDialog.alert('Η αφαίρεση από τα σημεία προβολής έγινε με επιτυχία');
						}
						else 
						{
							alert('Η αφαίρεση από τα σημεία προβολής έγινε με επιτυχία');
						}
					});										
				}
				published_change = true;
			}
		}
		if(published_change == false) {
			unsaved = true;
		}

	});
	
	function unloadPage(){ 
		if(unsaved == true){
			return "You have unsaved changes on this page. Do you want to leave this page and discard your changes or stay on this page?";
		}
	}
	window.onbeforeunload = unloadPage;	
	
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
	function openModal(id) {
		var imgsrc = document.getElementById(id).src;  //χρησιμοποιώ το id της εικόνας και βρίσκω το src το οποίο δίνω σαν src στην εικόνα του modal
		$('#modalImageId').attr('src',imgsrc); 
		$('#myModal').modal('show');  //εμφανίζω το modal
	}
	
	function delete_images(id) {
		
		var w = Math.max(document.documentElement.clientWidth, window.innerWidth || 0);  //συναρτηση υπολογισμου του πλατους της οθόνης
		if (w < 768) {

			mcxDialog.confirm("Θέλετε σίγουρα να διαγράψετε την εικόνα?", {
				sureBtnText: "OK",  
				sureBtnClick: function(){  
					
					$.post("delete_image.php", {
						photoid: id
					}, function(data) { 
						mcxDialog.alert("Η εικόνα διαγράφηκε με επιτυχία");
					});
					
					var imageOne = document.getElementById('image'+id);
					var imageTwo = document.getElementById(id);
					imageOne.style.display='none';
					imageTwo.style.display='none';
					
				}
			});		
		
		
		
		}
		else
		{
			var response = confirm("Θέλετε σίγουρα να διαγράψετε την εικόνα?");
		
			if (response == true) {

				$.post("delete_image.php", {
					photoid: id
				}, function(data) { 
					alert("Η εικόνα διαγράφηκε με επιτυχία");
					//alert(data);
				}); 
				
				var imageOne = document.getElementById('image'+id);
				var imageTwo = document.getElementById(id);
				imageOne.style.display='none';
				imageTwo.style.display='none';
			}
		}			
	}
</script>	

</body>
</html>