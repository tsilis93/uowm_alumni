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
  
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.4/css/bootstrap-select.min.css">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.4/js/bootstrap-select.min.js"></script>
  
  <link rel="stylesheet" type="text/css" href="../phones_specs/css/dialog-mobile.css"/> <!-- css & javascript για τις ειδοποιήσεις (alerts) στα κινητα -->
  <script type="text/javascript" src="../phones_specs/mcx-dialog.js"></script> 


</head>
<body>

<header>

</header>

<br><br><br>
  <h2>Δημιουργία Τμήματος</h2><br>
<div class="container">  
<br>
<form action="create_department.php" method="post" enctype="multipart/form-data" id = "main" onSubmit="return validate()"> 
  
  <div class="form-group">
    <label for="text">Όνομα:</label>
   <input class="form-control" id="title" name="title" onkeyup='saveValue(this);'> 
  </div>
  <div class="form-group">
    <label for="text">Τίτλος καλωσορίσματος:</label>
    <textarea placeholder="π.χ. Καλώς ήρθατε στην σελίδα των αποφοίτων του τμήματος ..." class="form-control" id="welcome" style="height:80px;" name = "welcome" onkeyup='saveValue(this);'></textarea>
  </div>
  <div class="form-group">
	<label for="text">Σχετικά με τον ιστότοπο:</label>
	<textarea class="form-control" style="height:190px;" id="description" name = "description" onkeyup='saveValue(this);'></textarea>
  </div>
  <div class="form-group">
	<label for="text">Χρώμα μενού επιλογών χρήστη (header):</label><br>
	<input type="color" name="color" style="width:70px; height:30px;">
  </div>
  <div class="form-group">
	<label for="text">Εικόνες για Image Slider: </label> (2 εικόνες)
	<input id="fileupload" name="files[]" type="file" multiple="multiple"/>
	<hr>
	<div id="dvPreview"></div><br>
  </div>

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

$stmt = $conn->prepare("SELECT * FROM faculties");
$stmt->execute();
$result = $stmt->fetchAll();

echo '<div class="form-group" id = "bloc3">';
echo	'<label for="text">Επιλέξτε σε ποια Σχολή θα ανήκει το Τμήμα:</label><br>';
echo	'<select class="selectpicker" data-width="75%"  name="where" id = "where">';
echo    '<option value="" disabled selected>--Επιλογή--</option>';	

foreach($result as $row) 
{
  echo  '<option value="'.$row['id'].'">'.$row['facultyname'].'</option>';
}  
?>
		</select>
   </div>
   
   <div class="form-group" id = "bloc4">
	<input type="submit" class="btn btn-primary" value ="Δημιουργία" id = "submit"></input>
   </div>
   
</form>
<br>


</div>

<br><br><br>
<footer class="container-fluid text-center">
  Footer Text
</footer>

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
	
	document.getElementById("title").value = getSavedValue("title");  //επαναφορά των επιλογών του χρήστη στα input fields σε περίπτωση που δεν 
	document.getElementById("description").value = getSavedValue("description"); // καταχωρηθούν οι εικόνες
	document.getElementById("welcome").value = getSavedValue("welcome"); 
	
};
</script>

<script type="text/javascript">
function validate()  
{
	var title = $("#title").val();
	var description = $("#description").val();
	var welcome = $("#welcome").val();
	var facultyname = $("#where").val();
	
	var w = Math.max(document.documentElement.clientWidth, window.innerWidth || 0);  //συναρτηση υπολογισμου του πλατους της οθόνης

	if (title == '' || description == '' || welcome == '' || facultyname == null) {
		if (w < 768) {
			mcxDialog.alert("Παρακαλώ συμπληρώστε όλα τα πεδία προτού προχωρήσετε στην δημοσίευση");
		}
		else 
		{
			alert("Παρακαλώ συμπληρώστε όλα τα πεδία προτού προχωρήσετε στην δημοσίευση");
		}		
		return false;
	}
	return true;
}

</script>

<script type="text/javascript">
function saveValue(e){  //συνάρτηση αποθήκευσης των επιλογών του χρήστη
    
	var id = e.id;  // get the sender's id to save it . 
    var val = e.value; // get the value. 
    localStorage.setItem(id, val);// Every time user writing something, the localStorage's value will override . 
}

function getSavedValue(v){  // συνάρτηση ανάκτησης των επιλογών του χρήστη
    
	if (localStorage.getItem(v) === null) {
        return "";// You can change this to your defualt value. 
    }
    return localStorage.getItem(v);
}
</script>

</body>
</html>
