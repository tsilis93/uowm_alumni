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

  <link rel='stylesheet prefetch' href='https://netdna.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.css'>
  <link rel='stylesheet prefetch' href='https://fonts.googleapis.com/css?family=Dosis|Candal'>  

  <link rel="stylesheet" type="text/css" href="../phones_specs/css/dialog-mobile.css"/> <!-- css & javascript για τις ειδοποιήσεις (alerts) στα κινητα -->
  <script type="text/javascript" src="../phones_specs/mcx-dialog.js"></script>  
  
    
</head>

<body>

<header>

</header>

<br><br><br><br>
<div align="center">     
	<h2>Newsletter</h2><br>
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

echo '<div class="container">';
echo		'<div align="center">';
echo			'<h3 id="login">Λίστα Κατηγοριών Newsletter</h3><hr>';
echo		'</div>';
echo			'<p id="login"><span class="label label-info"><b>INFO</span> Μπορείτε να τροποποιήσετε τις κατηγορίες και να αποθηκεύσετε τις αλλαγές χρησιμοποιώντας το "check" button και να τις διαγράψετε με το "x" button.</b> </p><br>';

echo	        '<form id="taskEntryForm">';
echo				'<label for="text">Προσθήκη Κατηγορίας:</label>';
echo            	'<input class="form-control" id="taskInput" />';
echo	        '</form><br>';


$stmt = $conn->prepare("SELECT * FROM newsletter_categories");
$stmt->execute();
$result = $stmt->fetchAll();

echo '<div style="overflow-x:auto;">';
echo	'<table class = "table" width="100%" id="myTable4">';
echo		'<thead>';
echo		  '<tr>';
echo		 	 '<th>Όνομα Κατηγορίας</th>';
echo			 '<th>Αποθήκευση Αλλαγών</th>';
echo		     '<th>Διαγραφή Κατηγορίας</th>';
echo		  '</tr>';
echo		'</thead>';
echo		'<tbody>';
foreach($result as $row) {
		$category_name = $row['category_name'];
		$id = $row['id'];
echo		'<tr style="background-color: #dddddd;">';		
echo				'<td width = "80%"><input class="form-control" id="category'.$id.'" value="'.$category_name.'"></td>';
echo				'<td align="center" width = "10%"><img id = "'.$id.'" src="../assets/approve1.png" alt="save" width="25" height="25" onclick="saveChanges_newsletter_category(this.id)" onmouseover="" style="cursor: pointer;""></td>';
echo				'<td align="center" width = "10%"><img id = "'.$id.'" src="../assets/delete.png" alt="delete" width="25" height="25" onclick="delete_newsletter_category(this.id)" onmouseover="" style="cursor: pointer;""></td>';
echo		'</tr>';	
}
echo		'</tbody>';
echo	'</table>';
echo '</div>';

echo '</div>';

echo "<br><br><br>";

echo '<div class="container">';
echo		'<div align="center">';
echo			'<h3 id="login">Λίστα Διαθέσιμων Newsletter</h3><hr>';
echo		'</div>';

echo			'<p id="login"><span class="label label-info"><b>INFO</span> Μπορείτε να τροποποιήσετε τα δεδομένα των newsletter και να αποθηκεύσετε τις αλλαγές χρησιμοποιώντας το "check" button, μπορείτε να τα διαγράψετε με το "x" button και μπορείτε να τα κλωνοποιήσετε με το "copy" button</b> </p><br>';

$stmt = $conn->prepare("SELECT * FROM newsletter_content ORDER BY date_created DESC, id DESC");
$stmt->execute();
$result = $stmt->fetchAll();

echo '<div style="overflow-x:auto;">';
echo	'<table id="myTable2" width="100%">';
echo		'<tbody>';
if(sizeof($result)>0) {
	foreach($result as $row) {
			$id = $row['id'];
			$title = $row['titlos'];
			$body_html = $row['body_html'];
			$date_created = $row['date_created'];
			$date = new DateTime($date_created);			/* Τα σχολιασμένα τμήματα είναι αν θέλουμε να κρύβουμε τις πληροφορίες 
																και να τις εμφανίζουμε με ένα button */
		echo		'<tr>';	
		//echo			'<td colspan="4"><b>Τίτλος: ' .$title. '</b></td>';
		echo			'<td width="60%" colspan="2"><b>Τίτλος: ' .$title. '</b></td>';
		echo			'<td align="center" width="20%"><a href="#a'.$id.'" class="btn btn-info" data-toggle="collapse">Λεπτομέρειες</a></td>';
		echo			'<td align="center" width="20%"><button class="btn btn-primary" id="'.$id.'" onclick="sendNewsletterEmail(this.id)">Αποστολή</button></td>';
		echo			'<tr>';
		
		echo				'<td colspan="4">';				//εδω πρεπει να γίνει σχολιασμός για τo button
		echo					'<div id="a'.$id.'" class="collapse">';
		echo						'<table width="100%"><tr>';	
		
		echo						'<td width = "70%">';
		echo							'<label for="text">Τίτλος:</label><input class="form-control" id="titlos'.$id.'" value="'.$title.'"><br>';
		//echo							'<label for="text">Περιεχόμενο:</label><textarea rows="4" id = "body'.$id.'" class="form-control" style="resize:none;" >'.$body.'</textarea><br>';
									
		echo							'<label for="text">Περιεχόμενο:</label>';
										echo	'<div class="html_editor'.$id.' editors">';
										echo  		'<div class="toolbar">';
										echo			 '<a href="#" class="whatever" data-command="undo"><i class="fa fa-undo"></i></a>';
										echo		 	'<a href="#" class="whatever" data-command="redo"><i class="fa fa-repeat"></i></a>';
										echo	 	 	'<div class="fore-wrapper"><i class="fa fa-font" style="color:#C96;"></i>';
										echo	     	'<div class="fore-palette"></div>';
										echo		'</div>';
										echo		'<div class="back-wrapper"><i class="fa fa-font" style="background:#C96;"></i>';
										echo    		'<div class="back-palette"></div>';
										echo 		'</div>';
										echo		'<a href="#" class="whatever" data-command="bold"><i class="fa fa-bold"></i></a>';
										echo		'<a href="#" class="whatever" data-command="italic"><i class="fa fa-italic"></i></a>';
										echo		'<a href="#" class="whatever" data-command="underline"><i class="fa fa-underline"></i></a>';
										echo		'<a href="#" class="whatever" data-command="strikeThrough"><i class="fa fa-strikethrough"></i></a>';
										echo		'<a href="#" class="whatever" data-command="justifyLeft"><i class="fa fa-align-left"></i></a>';
										echo		'<a href="#" class="whatever" data-command="justifyCenter"><i class="fa fa-align-center"></i></a>';
										echo		'<a href="#" class="whatever" data-command="justifyRight"><i class="fa fa-align-right"></i></a>';
										echo		'<a href="#" class="whatever" data-command="justifyFull"><i class="fa fa-align-justify"></i></a>';
										echo		'<a href="#" class="whatever" data-command="indent"><i class="fa fa-indent"></i></a>';
										echo		'<a href="#" class="whatever" data-command="outdent"><i class="fa fa-outdent"></i></a>';
										echo		'<a href="#" class="whatever" data-command="insertUnorderedList"><i class="fa fa-list-ul"></i></a>';
										echo		'<a href="#" class="whatever" data-command="insertOrderedList"><i class="fa fa-list-ol"></i></a>';
										echo		'<a href="#" class="whatever" data-command="createlink"><i class="fa fa-link"></i></a>';
										echo  		'<a href="#" class="whatever" data-command="unlink"><i class="fa fa-unlink"></i></a>';
										echo  		'<a href="#" class="whatever" data-command="insertimage"><i class="fa fa-image"></i></a>';
										echo		'<a href="#" class="whatever" data-command="p">P</a>';
										echo  		'<a href="#" class="whatever" data-command="subscript"><i class="fa fa-subscript"></i></a>';
										echo  		'<a href="#" class="whatever" data-command="superscript"><i class="fa fa-superscript"></i></a>';
										echo		'<a href="#" class="whatever" data-command="h1">H1</a>&nbsp;&nbsp;';
										echo		'<a href="#" class="whatever" data-command="h3">H3</a>';
										echo '</div>';

										echo	'<div id="editor'.$id.'" class="content_html_editor" contenteditable>';
													  $body_html = urldecode($body_html);
										echo		  $body_html;
										echo	'</div>';

										echo	'</div>';
				
		echo							'<br><label for="text">Κατηγορίες στις οποίες ανήκει το newsletter: </label><br>';
		echo							'<table class="table" width="100%">';						
										$stmt2 = $conn->prepare("SELECT * FROM newsletter_categories");
										$stmt2->execute();
										$result2 = $stmt2->fetchAll();
										
										$hasChange = false;
										foreach($result2 as $row2) {
											$category_id = $row2['id'];
											$category_name = $row2['category_name'];
											$value = "option_id" . $category_id;
											$value2 = $row[$value];
											if($value2 == 1) {
												echo  '<tr><input class="form-control myClass" value="'.$category_name.'" disabled></tr>';
												$hasChange = true;
											}
										}
										if($hasChange == false) {
												echo  '<tr><input class="form-control myClass" value="Δεν έχει επιλεγεί κατηγορία" disabled></tr>';											
										}
		echo							'</table>';
		echo							'<label for="text">Προσθήκη κατηγορίας στο newsletter: </label>';
		echo							'<select class="selectpicker" data-width="100%" id="categories'.$id.'" multiple>';
											foreach($result2 as $row2) {
												$category_id = $row2['id'];
												$category_name = $row2['category_name'];
												$value = "option_id" . $category_id;
												$value2 = $row[$value];
												if($value2 == 1) {
												
												}										
												else
												{	
													echo '<option value="'.$category_id.'">'.$category_name.'</option>';
												}
											}
			echo						'</select><br><br>';
			echo						'<label for="text">Ημερομηνία δημιουργίας:</label><input class="form-control myClass" id="date_created" value="'.$date->format("d-m-Y").'" disabled>';
									'</td>';
		echo						'<td align="center" width = "10%"><img id = "'.$id.'" src="../assets/approve1.png" alt="save" width="25" height="25" onclick="saveChanges_newsletter(this.id)" onmouseover="" style="cursor: pointer;""></td>';
		echo						'<td align="center" width = "10%"><img id = "'.$id.'" src="../assets/delete.png" alt="delete" width="25" height="25" onclick="delete_newsletter(this.id)" onmouseover="" style="cursor: pointer;""></td>';
		echo						'<td align="center" width = "10%"><img id = "'.$id.'" src="../assets/copy.png" alt="delete" width="25" height="25" onclick="copy_newsletter_content(this.id)" onmouseover="" style="cursor: pointer;""></td>';
		echo					'</tr></table>';		//εδω πρεπει να γινει σχολιασμός για το button
		echo					'</div>';	
		echo				'</td>';
		echo			'</tr>';
		echo		'</tr>';	
	}
}
else
{
	echo  '<tr><td class="text-center" colspan = "4"> Δεν υπάρχουν διαθέσιμα newsletter</td></tr>'; 
}
echo		'</tbody>';
echo	'</table>';
echo '</div>';
echo '<br>';
echo '</div>';


echo "<br><br><br>";

echo '<div class="container">';
echo		'<div align="center">';
echo			'<h3 id="login">Δημιουργία νέου Newsletter</h3><hr>';
echo		'</div>';


echo	'<label for="text">Τίτλος:</label><input class="form-control" id="title"><br>';

echo	'<label for="text">Περιεχόμενο:</label>';
echo	'<div class="html_editor">';
echo  		'<div class="toolbar">';
echo			 '<a href="#" class="whatever" data-command="undo"><i class="fa fa-undo"></i></a>';
echo		 	'<a href="#" class="whatever" data-command="redo"><i class="fa fa-repeat"></i></a>';
echo	 	 	'<div class="fore-wrapper"><i class="fa fa-font" style="color:#C96;"></i>';
echo	     	'<div class="fore-palette"></div>';
echo		'</div>';
echo		'<div class="back-wrapper"><i class="fa fa-font" style="background:#C96;"></i>';
echo    		'<div class="back-palette"></div>';
echo 		'</div>';
echo		'<a href="#" class="whatever" data-command="bold"><i class="fa fa-bold"></i></a>';
echo		'<a href="#" class="whatever" data-command="italic"><i class="fa fa-italic"></i></a>';
echo		'<a href="#" class="whatever" data-command="underline"><i class="fa fa-underline"></i></a>';
echo		'<a href="#" class="whatever" data-command="strikeThrough"><i class="fa fa-strikethrough"></i></a>';
echo		'<a href="#" class="whatever" data-command="justifyLeft"><i class="fa fa-align-left"></i></a>';
echo		'<a href="#" class="whatever" data-command="justifyCenter"><i class="fa fa-align-center"></i></a>';
echo		'<a href="#" class="whatever" data-command="justifyRight"><i class="fa fa-align-right"></i></a>';
echo		'<a href="#" class="whatever" data-command="justifyFull"><i class="fa fa-align-justify"></i></a>';
echo		'<a href="#" class="whatever" data-command="indent"><i class="fa fa-indent"></i></a>';
echo		'<a href="#" class="whatever" data-command="outdent"><i class="fa fa-outdent"></i></a>';
echo		'<a href="#" class="whatever" data-command="insertUnorderedList"><i class="fa fa-list-ul"></i></a>';
echo		'<a href="#" class="whatever" data-command="insertOrderedList"><i class="fa fa-list-ol"></i></a>';
echo		'<a href="#" class="whatever" data-command="createlink"><i class="fa fa-link"></i></a>';
echo  		'<a href="#" class="whatever" data-command="unlink"><i class="fa fa-unlink"></i></a>';
echo  		'<a href="#" class="whatever" data-command="insertimage"><i class="fa fa-image"></i></a>';
echo		'<a href="#" class="whatever" data-command="p">P</a>';
echo  		'<a href="#" class="whatever" data-command="subscript"><i class="fa fa-subscript"></i></a>';
echo  		'<a href="#" class="whatever" data-command="superscript"><i class="fa fa-superscript"></i></a>';
echo		'<a href="#" class="whatever" data-command="h1">H1</a>&nbsp;&nbsp;';
echo		'<a href="#" class="whatever" data-command="h3">H3</a>';
echo '</div>';

echo	'<div id="editor" contenteditable>';
echo		  '<h1>A WYSIWYG HTML Editor.</h1>';
echo		  '<p>Προσπαθήστε να κάνετε κάποιες αλλαγές. Προσθέστε κάποιο κείμενο ή επιλέξτε κάποια εικόνα.</p>';
echo	'</div>';

echo	'</div>';
echo	'<br>';
echo	'<label for="text">Προσθήκη κατηγορίας στο newsletter: </label>';
echo	'<select class="selectpicker" data-width="100%" id="categories" multiple>';
			foreach($result2 as $row2) {
				$category_id = $row2['id'];
				$category_name = $row2['category_name'];
				echo '<option value="'.$category_id.'">'.$category_name.'</option>';
			}
echo	'</select><br><br>';

echo	'<table width="100%"><tr>';	
echo		'<td width = "80%"></td>';
echo 		'<td width = "20%"><button class="btn btn-info" onclick="get_editor_content()">Αποθήκευση</button></td>';
echo	'</tr></table>';
echo    '<br>';
echo '</div>';
?>


</div>
<br><br><br>
<footer class="container-fluid text-center">
  Footer Text
</footer>

<script>
$(document).ready(function() {
		
	var colorPalette = ['000000', 'FF9966', '6699FF', '99FF66', 'CC0000', '00CC00', '0000CC', '333333', '0066FF', 'FFFFFF'];
	var forePalette = $('.fore-palette');
	var backPalette = $('.back-palette');

	for (var i = 0; i < colorPalette.length; i++) {
		forePalette.append('<a href="#" class="whatever" data-command="forecolor" data-value="' + '#' + colorPalette[i] + '" style="background-color:' + '#' + colorPalette[i] + ';" class="palette-item"></a>');
		backPalette.append('<a href="#" class="whatever" data-command="backcolor" data-value="' + '#' + colorPalette[i] + '" style="background-color:' + '#' + colorPalette[i] + ';" class="palette-item"></a>');
	}

	$('.toolbar a').click(function(e) {
		var command = $(this).data('command');
		if (command == 'h1' || command == 'h3' || command == 'p') {
		  document.execCommand('formatBlock', false, command);
		}
		if (command == 'forecolor' || command == 'backcolor') {
		  document.execCommand($(this).data('command'), false, $(this).data('value'));
		}
		if (command == 'createlink' || command == 'insertimage') {
		  url = prompt('Enter the link here: ', 'http:\/\/');
		  document.execCommand($(this).data('command'), false, url);
		} else document.execCommand($(this).data('command'), false, null);
	});	
	
	$(".whatever").click(function(e) {
		e.preventDefault();	
	});
		
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
			
			$.post("new_newsletter_category.php", {
				text: text,
			}, function(data) { 
				//alert(data);
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

function delete_newsletter_category(id)
{
	var w = Math.max(document.documentElement.clientWidth, window.innerWidth || 0);  //συναρτηση υπολογισμου του πλατους της οθόνης
	
	if (w < 768) 
	{	
		mcxDialog.confirm("Θέλετε σίγουρα να διαγράψετε την κατηγορία?", {
			sureBtnText: "OK",  
			sureBtnClick: function(){  
				
				$.post("delete_newsletter_category.php", {
					id: id
				}, function(data) { 
					location.reload();
				});
				
			}
		});
	}
	else 
	{
		var response = confirm("Θέλετε σίγουρα να διαγράψετε την κατηγορία?");
		
		if (response == true) {

			$.post("delete_newsletter_category.php", {
				id: id
			}, function(data) { 
				//alert(data);
				location.reload();
			}); 

		}
	}
}

function saveChanges_newsletter_category(id) {
	
	var textarea = document.getElementById("category"+id);
	var text = textarea.value;

	var w = Math.max(document.documentElement.clientWidth, window.innerWidth || 0);  //συναρτηση υπολογισμου του πλατους της οθόνης
	
	if (w < 768) 
	{	
		mcxDialog.confirm("Θέλετε να αποθηκεύσετε τις αλλαγές?", {
			sureBtnText: "OK",  
			sureBtnClick: function(){  
				
				$.post("saveChanges_newsletter_category.php", {
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

			$.post("saveChanges_newsletter_category.php", {
				id: id,
				text: text
			}, function(data) { 
				location.reload();
			}); 

		}
	}
	
}

function get_editor_content() {
	
	var title = $('#title').val();
	var html_text = $('#editor').html();
	var categories = $('#categories').val();
		
	$.post("createNewNewsletter.php", {
		html_text: html_text,
		title: title,
		categories: categories
	}, function(data) { 
		location.reload();
	}); 	

}

function copy_newsletter_content(id) {
	
	$.post("createDublicateNewsletter.php", {
		id: id
	}, function(data) {
		//alert(data); 
		location.reload();
	});
}

function delete_newsletter(id) {
	
	var w = Math.max(document.documentElement.clientWidth, window.innerWidth || 0);  //συναρτηση υπολογισμου του πλατους της οθόνης
	
	if (w < 768) 
	{	
		mcxDialog.confirm("Θέλετε να διαγράψετε το newsletter?", {
			sureBtnText: "OK",  
			sureBtnClick: function(){  
				
				$.post("delete_newsletter.php", {
					id: id
				}, function(data) { 
					location.reload();
				});
				
			}
		});
	}
	else 
	{
		var response = confirm("Θέλετε να διαγράψετε το newsletter?");
		
		if (response == true) {

			$.post("delete_newsletter.php", {
				id: id
			}, function(data) { 
				location.reload();
			});

		}
	}	
	
}

function saveChanges_newsletter(id) {
	
	var titlos = $("#titlos"+id).val();
	var html_text = $('#editor'+id).html();
	var categories = $('#categories'+id).val();
	
	var w = Math.max(document.documentElement.clientWidth, window.innerWidth || 0);  //συναρτηση υπολογισμου του πλατους της οθόνης
	
	if (w < 768) 
	{	
		mcxDialog.confirm("Θέλετε να αποθηκεύσετε τις αλλαγές?", {
			sureBtnText: "OK",  
			sureBtnClick: function(){  
				
				$.post("saveChanges_newsletter.php", {
					html_text: html_text,
					title: titlos,
					categories: categories, 
					id: id
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

			$.post("saveChanges_newsletter.php", {
				html_text: html_text,
				title: titlos,
				categories: categories,
				id: id
			}, function(data) { 
				location.reload();
			}); 

		}
	}
	
}

function sendNewsletterEmail(id) {
	
	var w = Math.max(document.documentElement.clientWidth, window.innerWidth || 0);  //συναρτηση υπολογισμου του πλατους της οθόνης
	
	if (w < 768) 
	{	
		$.post("sendNewsletterEmail.php", {
			id: id
		}, function(data) {
			mcxDialog.alert("Τα newsletter θα σταλούν στους ενδιαφερόμενους");
			location.reload();
		});	
		
	}
	else
	{
		$.post("sendNewsletterEmail.php", {
			id: id
		}, function(data) {
			alert("Τα newsletter θα σταλούν στους ενδιαφερόμενους");
			location.reload();
		});		
		
	}
		
}

</script>

</body>
</html>
	