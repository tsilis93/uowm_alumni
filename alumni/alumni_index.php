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

<br><br><br>

<?php

session_start();
include ("../connectPDO.php");


if(isset($_SESSION['student'])) {
	$alumni_id = $_SESSION['student'];

	$stmt = $conn->prepare("SELECT * from users WHERE id = ?");   
	$stmt->execute(array($alumni_id));															
	$result = $stmt->fetchAll();

	foreach($result as $row) {
		echo "<br>";
		echo '<p id = "hi">Καλώς ήρθες '.$row['name'].'</p>';
		
		$name = $row['name'];
		$lastname = $row['lastname'];
		$fathers_name = $row['fathers_name'];
		$birthday_date = $row['birthday_date'];
		$phone = $row['phone'];
		$phone2 = $row['cell_phone'];
		$email = $row['email'];
		$registration_year = $row['registration_year'];
		$graduation_year = $row['graduation_year'];
		$graduation_date = $row['graduation_date'];
		$aem = $row['aem'];
		$grade = $row['degree_grade'];
		$thesis_diploma = $row['diploma_thesis_topic'];
		$facebook = $row['facebook'];
		$twitter = $row['twitter'];
		$instagram = $row['instagram'];
		$linkedin = $row['linkedin'];
		$google = $row['google'];
		$youtube = $row['youtube'];
		$job_city = $row['job_city'];
		$residence_city = $row['residence_city'];
		$workpiece = $row['Workpiece'];
		$job = $row['job'];
		$site = $row['social'];
		$departmentID = $row['department_id'];
		$postgraduate = $row['metaptuxiako'];
		$postgraduate2 = $row['didaktoriko'];		
	}
	
	if($postgraduate == ""){
		$postgraduate = "";
	}
	else {
		$postgraduate = $postgraduate .'&#13;---------------------------&#10;'.$postgraduate2;
	}
	
	if($job == 0) {
		$job = "";
	}
	else if($job == 1) {
		$job = "Ιδιωτικός Υπάλληλος";
	}
	else if($job == 2) {
		$job = "Δημόσιος Υπάλληλος";
	}
	else
	{
		$job = "Ελεύθερος επαγγελματίας";
	}

	$stmt2 = $conn->prepare("SELECT * from departments WHERE id = ?");
	$stmt2->execute(array($departmentID));
	$result2 = $stmt2->fetchAll();

	if (sizeof($result2)> 0) {
		foreach($result2 as $row2) {
			$department = $row2['dname'];
			$facultyid = $row2['facultyid'];
		} 
	}

	$stmt3 = $conn->prepare("SELECT * from faculties WHERE id = ?");
	$stmt3->execute(array($facultyid));
	$result3 = $stmt3->fetchAll();

	if (sizeof($result3)> 0) {
		foreach($result3 as $row3) {
			$faculty = $row3['facultyname'];
		} 
	}

	$stmt4 = $conn->prepare("SELECT * from images WHERE userID = ?");
	$stmt4->execute(array($alumni_id));
	$result4 = $stmt4->fetchAll();
	$images_path = "";

	if (sizeof($result4)> 0) {
		foreach($result4 as $row4) {
			$images_path = $row4['images_path'];		
		}
		if(file_exists("../users_images/".$images_path)) {
			
		}
		else
		{
			$images_path = "user.png";
		}
	}
	else
	{
		$images_path = "user.png";
	}


	$timestamp  = strtotime($birthday_date); 
	$bdate =  date('d-m-Y',$timestamp);
	
	$graduation_date = strtotime($graduation_date);
	$graduation_date = date('d-m-Y',$graduation_date);
	
	$empty_string = "";	
	
	$stmt3 = $conn->prepare("SELECT * from alumni_cv WHERE alumni_id = ?");
	$stmt3->execute(array($alumni_id));
	$result3 = $stmt3->fetchAll();

	if (sizeof($result3)> 0) {
		foreach($result3 as $row3) {
			$cv_pdf = $row3['original_name'];
		} 
	}
	else
	{
		$cv_pdf = "";
	}
	
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
	"blank" => "Παρακαλώ επιλέξτε μία φωτογραφία profil για υποβολή",
	"size"	=> "Η φωτογραφία έχει μέγεθος μεγαλύτερο από 1 MB, γι αυτό και απορρίφθηκε",
	"error"	=> "Η φωτογραφία δεν αποθηκεύτηκε στην βάση παρακαλώ δοκιμάστε ξανά ή ενημερώστε τους διαχειριστές",
);
if($var == 0) {
	if($_GET["Failed"] == 'true') {
		$message = $reasons[$_GET["reason"]];
		echo "<script type='text/javascript'> var w = Math.max(document.documentElement.clientWidth, window.innerWidth || 0); if (w < 768) {  mcxDialog.alert('$message'); } else { alert('$message'); }</script>";
	}
	else
	{
		$message = "Η φωτογραφία profil ανέβηκε με επιτυχία";
		echo "<script type='text/javascript'> var w = Math.max(document.documentElement.clientWidth, window.innerWidth || 0); if (w < 768) {  mcxDialog.alert('$message'); } else { alert('$message'); }</script>";
	}
}

echo "<br><br>";
echo '<div class="container">';
echo '<h2 align="center">Προσωπικά Στοιχεία</h2><hr>';	
echo "<br>";

		echo '<form action="upload_image.php" method="post" enctype="multipart/form-data">';

		echo '<table id="myTable" width="100%">';
		echo	"<tbody>";
		echo		"<tr>";
		echo			'<td style="text-align: center;" rowspan="2"><img id = "image" src ="../users_images/'.$images_path.'" width=180 height=180><input name="myPhoto" class="btn" type="file" id = "myPhoto"/><input id ="submit2" type="submit" value="Upload" class="btn btn-info"><a href="#" id="clear"> Clear</a></td>';
		echo			'<td><label for="text">Όνομα</label><input class="form-control fontsize" id="owner" name="owner" value="'.$name.'" readonly="readonly"></td>'; 
		echo			'<td><label for="text">Επώνυμο</label><input class="form-control fontsize" id="owner" name="owner" value="'.$lastname.'" readonly="readonly"></td>';
		echo			'<td><label for="text">Πατρώνυμο</label><input class="form-control fontsize" id="owner" name="owner" value="'.$fathers_name.'" readonly="readonly"></td>';
		echo		"</tr>";
		echo		"<tr>";
		if($bdate == "01-01-1970") {
			$bdate = "";
		}
		echo			'<td><label for="text">Ημερομηνία Γέννησης</label><input class="form-control fontsize" id="owner" name="owner" value="'.$bdate.'" readonly="readonly"></td>'; 
		echo			'<td><label for="text">Τηλέφωνο</label><textarea class="form-control custom-control" style="resize:none; font-size:12px;" readonly="readonly">'.$phone .'&#13;&#10;'. $phone2.'</textarea></td>'; 
		echo			'<td><label for="text">Email</label><input class="form-control fontsize" id="owner" name="owner" value="'.$email.'" readonly="readonly"></td>';
		echo		"</tr>";
		echo		"<tr>";
		echo		  '<td><label for="text">Σχολή</label><input class="form-control fontsize" id="owner" name="owner" value="'.$faculty.'" readonly="readonly"></td>';
		echo		  '<td><label for="text">Τμήμα</label><textarea class="form-control custom-control" style="resize:none; font-size:12px;" readonly="readonly">'.$department.'</textarea></td>';
		echo		  '<td><label for="text">Έτος Εισαγωγής</label><input class="form-control fontsize" id="owner" name="owner" value="'.$registration_year.'" readonly="readonly"></td>';
		echo		  '<td><label for="text">Ημερομηνία Αποφοίτησης</label><input class="form-control fontsize" id="owner" name="owner" value="'.$graduation_date.'" readonly="readonly"></td>';
		echo		"</tr>";
		echo		"<tr>";
		echo		  '<td><label for="text">Αριθμός Μητρώου Φοιτητή</label><input class="form-control fontsize" id="owner" name="owner" value="'.$aem.'" readonly="readonly"></td>';
		echo		  '<td><label for="text">Βαθμός Πτυχίου</label><input class="form-control fontsize" id="owner" name="owner" value="'.$grade.'" readonly="readonly"></td>';
		echo		  '<td><label for="text">Θέμα Διπλωματικής</label><textarea class="form-control custom-control" style="resize:none; font-size:12px;" readonly="readonly">'.$thesis_diploma.'</textarea></td>';
		echo		  '<td><label for="text">Μεταπτυχιακά - Διδακτορικά</label><textarea class="form-control custom-control" style="resize:none; font-size:12px;" readonly="readonly">'.$postgraduate.'</textarea></td>';
		echo		"</tr>";
		echo		"<tr>";
		echo		  '<td><label for="text">Facebook</label><input class="form-control fontsize" id="owner" name="owner" value="'.$facebook.'" readonly="readonly"></td>';
		echo		  '<td><label for="text">Twitter</label><input class="form-control fontsize" id="owner" name="owner" value="'.$twitter.'" readonly="readonly"></td>';
		echo		  '<td><label for="text">Instagram</label><input class="form-control fontsize" id="owner" name="owner" value="'.$instagram.'" readonly="readonly"></td>';
		echo		  '<td><label for="text">Linkedin</label><input class="form-control fontsize" id="owner" name="owner" value="'.$linkedin.'" readonly="readonly"></td>';	
		echo		"</tr>";
		echo		"<tr>";
		echo		  '<td><label for="text">Google+</label><input class="form-control fontsize" id="owner" name="owner" value="'.$google.'" readonly="readonly"></td>';
		echo		  '<td><label for="text">Youtube</label><input class="form-control fontsize" id="owner" name="owner" value="'.$youtube.'" readonly="readonly"></td>';
		echo		  '<td><label for="text">Άλλο</label><input class="form-control fontsize" name="optional" value="'.$site.'" id = "site" readonly="readonly"></td>';
		echo		  '<td><label for="text" id = "header">cv_pdf&nbsp;</label><span class="glyphicon" style="color:blue" onmouseOver = "mouseOver()" onmouseout="mouseOut()">&#xe086;</span><div id = "info"></div><input class="form-control fontsize" id="owner" name="owner" value="'.$cv_pdf.'" readonly="readonly"></td>';		
		echo		"</tr>";
		echo		"<tr>";
		echo		  '<td><label for="text">Πόλη Διαμονής</label><input class="form-control fontsize" id="owner" name="owner" value="'.$residence_city.'" readonly="readonly"></td>';
		echo		  '<td><label for="text">Πόλη Εργασίας</label><input class="form-control fontsize" id="owner" name="owner" value="'.$job_city.'" readonly="readonly"></td>';
		echo		  '<td><label for="text">Επάγγελμα</label><input class="form-control fontsize" id="owner" name="owner" value="'.$job.'" readonly="readonly"></td>';
		echo		  '<td><label for="text">Αντικείμενο Εργασίας</label><textarea class="form-control custom-control" style="resize:none; font-size:12px;" readonly="readonly">'.$workpiece.'</textarea></td>';
		echo		"</tr>";
		echo		"<tr>";
		echo		  '<td style="text-align: center;" colspan="3"></td>';
		echo		  '<td><input id ="submit"  type="button" value="Επεξεργασία / Προσθήκη Στοιχείων" class="btn btn-primary"></td>';
		echo		"</tr>";

		echo	"</tbody>";

		echo "</table>";
					
		echo "</form>";
		
echo "</div>";

?>
<br><br><br><br>
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

	$('#submit').click(function() {  
		location.href = "myDetails.php";	
	});

	$("#clear").on("click",function(e) {
		var psrc = <?php echo json_encode($images_path); ?>;
		e.preventDefault();
		var imgPreview = document.getElementById("image");
		$("#myPhoto").val(''); 
		imgPreview.src = "../users_images/" + psrc;
		imgPreview.height = "180";
		imgPreview.width = "180";
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
    var psrc = <?php echo json_encode($images_path); ?>;
	var myPhoto = document.getElementById("myPhoto");  //συνάρτηση που κάνει προεπισκόπηση των εικόνων πρωτού ο χρήστης τις ανεβάσει 
    myPhoto.onchange = function () {
        if (typeof (FileReader) != "undefined") {
            var imgPreview = document.getElementById("image");
			imgPreview.src = "";
            var regex = /^([a-zA-Z0-9\s_\\.\-:])+(.jpg|.jpeg|.png)$/;
            for (var i = 0; i < myPhoto.files.length; i++) {
                var file = myPhoto.files[i];
                if (regex.test(file.name.toLowerCase())) {
                    var reader = new FileReader();
                    reader.onload = function (e) {
                        imgPreview.src = e.target.result;
						imgPreview.height = "180";
						imgPreview.width = "180";
                    }
                    reader.readAsDataURL(file);
                } else {
					if ($(window).width() < 768) {
						mcxDialog.alert("Το αρχείο " + file.name + " δεν είναι εικόνα (.jpg|.jpeg|.png)");
					}
					else 
					{
						alert("Το αρχείο " + file.name + " δεν είναι εικόνα (.jpg|.jpeg|.png)");
					}
					imgPreview.src = "../users_images/" + psrc;
                    return false;
                }
            }
			if(myPhoto.files.length == 0) {
				imgPreview.src = "../users_images/" + psrc;
				imgPreview.height = "180";
				imgPreview.width = "180";
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
function mouseOver()
{
	$('#info').html('<p> Για να ανεβάσετε βιογραφικό επιλέξτε από το μενού πλοήγησης "Περιεχόμενο Ιστοχώρου -> Ανέβασμα Βιογραφικού"</p>');
	$('#info').show();
}
function mouseOut() 
{
	$('#info').hide();
}

</script>

</body>
</html>
