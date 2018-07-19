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
  
   <link rel="stylesheet" href="../css/alumni_create.css">
   
  <link rel="stylesheet" type="text/css" href="../phones_specs/css/dialog-mobile.css"/> <!-- css & javascript για τις ειδοποιήσεις (alerts) στα κινητα -->
  <script type="text/javascript" src="../phones_specs/mcx-dialog.js"></script> 
  
</head>
<body>

<header>

</header>

<br><br><br><br><br>
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
	
$content_id = 0;
if(isset($_GET['content'])) {
	$content_id = $_GET['content'];
}

$stmt = $conn->prepare("SELECT * FROM contents WHERE id = ? AND userID = ?");
$stmt->execute(array($content_id, $alumni_id));
$result = $stmt->fetchAll();

$stmt5 = $conn->prepare("SELECT * from images WHERE contentID = ?");   
$stmt5->execute(array($content_id));		// αναζητώ αν υπάρχουν αποθηκευμένες εικόνες για την συγκεκριμένη δημοσίευσή													
$result5 = $stmt5->fetchAll();

if(sizeof($result)>0) {
	foreach($result as $row) 
	{
		$title=$row['title'];
		$description=$row['description'];
		$body = $row['body'];
		
		
		echo	'<div class="container">';
		echo	'<h2 align="center">Σύνοψη Δημοσίευσης</h2><hr>';
		echo	  '<div class="form-group">';
		echo			'<label for="text">Τίτλος:</label>';
		echo			'<input class="form-control" id="title" name="title" value="'.$title.'">';
		echo	  "</div>";
		echo	  '<div class="form-group">';
		echo			'<label for="text">Σύντομη Περιγραφή:</label>';
		echo			'<textarea class="form-control" id="description" style="height:80px;" name = "description">'.htmlspecialchars($description).'</textarea>';
		echo	  "</div>";
		echo	  '<div class="form-group">';
		echo			'<label for="text">Περιεχόμενο:</label>';
		echo			'<textarea class="form-control" style="height:190px;" id="body" name = "body">'.htmlspecialchars($body).'</textarea>';
		echo	  "</div>";
					
		echo '<label for="text">Εικόνες Δημοσίευσης:</label><br>';
		if (sizeof($result5)> 0) {  //αν υπάρχουν εικόνες τότε τις εμφανίζω την μια διπλα στην αλλη
			foreach($result5 as $row5) {
				$images_path3 = $row5['images_path'];
				$image_id2 = $row5['id']; // αποθηκεύω το id της εικόνας από την βάση στο id του tag <img>
				echo '<img id = "image'.$image_id2.'" onclick="javascript:openModal(this.id);" onmouseover="" style="cursor: pointer;" src ="../content_images/'.$images_path3.'" width=100 height=100>';  //allakse to assets se images 
				echo '<img id = "'.$image_id2.'" src="../assets/delete_images2.png" width=15 height=15 onclick="javascript:delete_images(this.id);" onmouseover="" style="cursor: pointer; top:-2; left:1; position: absolute;"/>';
				// περνάω στην συνάρτηση το <tag> id και παράλληλα το id της εικόνας
				echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
			}
		}
		else
		{
			echo	'<p style = "color:black"> Δεν υπάρχουν διαθέσιμες εικόνες</p>';
		}
			
		echo	"<br><br>";
		echo	'<form action="../upload_content_images.php?content='.$content_id.'" method="post" enctype="multipart/form-data">';
		echo	  	'<label for="text"><span class="label label-info"><b>INFO</span> Για να επιλέξετε παραπάνω από μία εικόνες κρατήστε πατημένο το πλήκτρο control</label><br><br>';		
		echo		'<label for="text">Προσθέστε Εικόνες: </label> &nbsp;&nbsp;<a href="#" id="clear"> Clear <img src="../assets/delete.png" height="15" width="15"></a><br>';
		echo		'<div class="form-group">';
		echo			'<input id="fileupload" name="files[]" type="file" multiple="multiple"/><br>';
		echo			'<input type="submit" class="btn btn-info" value="Ανέβασμα"</input>';
		echo			'<hr>';
		echo			'<div id="dvPreview"></div><br>';
		echo		'</div>';
		echo	'</form>';	

		echo '<div style="overflow-x: auto;">';
		echo	'<table width="100%" id="myTable33">';
		echo		'<tr>';
		echo			'<td colspan="2"><button type="button" class="btn btn-link" data-toggle="modal" data-target="#myModal2">Eπεξήγηση επιλογών</button></td>';
		echo			'<td colspan="3"></td>';
		echo		'</tr>';			
		echo		'<tr>';
		echo			'<td width="10%"><a href = "alumni_content_process.php"><span id = "arrow" class="glyphicon">&#xe091;</span></a></td>';
		echo 			'<td width="10%"></td>';
		echo			'<td width="60%"></td>';
		echo			'<td width="10%"><input id = "submit" type="button" value="Αποθήκευση" class="btn btn-primary"></td>';				
		echo			'<td width="10%"><input id = "submit2" type="button" value="Διαγραφή" class="btn btn-danger"></td>';
		echo		'</tr>';			
		echo	'</table>';
		echo '</div>';					
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
		<h4 class="modal-title" align="center">Eπεξήγηση των επιλογών</h4>
      </div>
      <div class="modal-body"> 
		<table class="betta">
			<tr>
				<th align="center">Επιλογή</th>
				<th align="center">Επεξήγηση</th>
			</tr>
			<tr>
				<td align="center"><b>Αποθήκευση:</b></td>
				<td>Αποθηκεύει την ανακοίνωση.</td>
			</tr>
			<tr>
				<td align="center"><b>Διαγραφή:</b></td>
				<td>Διαγράφει την ανακοίνωση</td>
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

	$("#submit").click(function() {
		var title = $("#title").val();
		var description = $("#description").val();
		var body = $("#body").val();
		var content_id = <?php echo(json_encode($content_id)); ?>; 
	
		$.post("save_changes_content.php", {
			title: title,
			description: description,
			body: body,
			content_id: content_id
		}, function(data) { 
			if ($(window).width() < 768) {
				mcxDialog.alert("Οι αλλαγές αποθηκεύτηκαν με επιτυχία");
			}
			else 
			{
				alert("Οι αλλαγές αποθηκεύτηκαν με επιτυχία");
			}
		});
		
	});
	
	$("#submit2").click(function() {
		
		var content_id = <?php echo(json_encode($content_id)); ?>;
		
		$.post("delete_content.php", {
			content_id: content_id
		}, function(data) { 
			if ($(window).width() < 768) {
				mcxDialog.alert("Η ανακοίνωση διαγράφηκε με επιτυχία");
			}
			else 
			{
				alert("Η ανακοίνωση διαγράφηκε με επιτυχία");
			}
		});			
		
		
	});
	
	$("#clear").on("click",function(e) {
		e.preventDefault();
		var dvPreview = document.getElementById("dvPreview");
		$("#fileupload").val(''); 
		dvPreview.innerHTML = "";
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

<script language="javascript" type="text/javascript">
window.onload = function () {
    
	var fileUpload = document.getElementById("fileupload");  //συνάρτηση που κάνει προεπισκόπηση των εικόνων πρωτού ο χρήστης τις ανεβάσει 
    fileUpload.onchange = function () {
        if (typeof (FileReader) != "undefined") {
            var dvPreview = document.getElementById("dvPreview");
            dvPreview.innerHTML = "";
            var regex = /^([a-zA-Z0-9\s_\\.\-:])+(.jpg|.jpeg|.gif|.png|.bmp)$/;
            for (var i = 0; i < fileUpload.files.length; i++) {
                var file = fileUpload.files[i];
                if (regex.test(file.name.toLowerCase())) {
                    var reader = new FileReader();
                    reader.onload = function (e) {
                        var img = document.createElement("IMG");
                        img.height = "100";
                        img.width = "100";
                        img.src = e.target.result;
                        dvPreview.appendChild(img);
                    }
                    reader.readAsDataURL(file);
                } else {
					if ($(window).width() < 768) {
						mcxDialog.alert("Το αρχείο " + file.name + " δεν είναι εικόνα.");
					}
					else 
					{
						alert("Το αρχείο " + file.name + " δεν είναι εικόνα.");
					}
                    dvPreview.innerHTML = "";
                    return false;
                }
            }
        } else {
			if ($(window).width() < 768) {
				mcxDialog.alert("This browser does not support HTML5 FileReader.");
			}
			else 
			{
				alert("This browser does not support HTML5 FileReader.");
			}
        }
    }

};
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