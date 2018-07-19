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
  
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.4/css/bootstrap-select.min.css">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.4/js/bootstrap-select.min.js"></script>
  
    <link rel="stylesheet" type="text/css" href="../phones_specs/css/dialog-mobile.css"/> <!-- css & javascript για τις ειδοποιήσεις (alerts) στα κινητα -->
	<script type="text/javascript" src="../phones_specs/mcx-dialog.js"></script>


</head>
<body>

<header>

</header>

<br><br><br><br><br>

<div class="container">  
  <h2 align="center">Νεα Ανακοίνωση</h2><hr>
<form action="../create_content.php" method="post" enctype="multipart/form-data" id = "main" onSubmit="return validate()"> 
  
  <div class="form-group">
    <label for="text">Τίτλος:</label>
    <input class="form-control" id="title" name="title">
  </div>
  <div class="form-group">
    <label for="text">Σύντομη Περιγραφή:</label>
    <textarea class="form-control" id="description" style="height:80px;" name = "description"></textarea>
  </div>
  <div class="form-group">
	<label for="text">Περιεχόμενο:</label>
	<textarea class="form-control" style="height:190px;" id="body" name = "body"></textarea>
  </div><br>
  
  <label for="text"><span class="label label-info"><b>INFO</span> Για να επιλέξετε παραπάνω από μία εικόνες κρατήστε πατημένο το πλήκτρο control</label><br><br>
  <label for="text">Εικόνες: </label> (Προαιρετικά)  &nbsp;&nbsp;<a href="#" id="clear"> Clear <img src="../assets/delete.png" height="15" width="15"></a><br> 
  <div class="form-group">
	<input id="fileupload" name="files[]" type="file" multiple="multiple"/>
	<hr>
	<div id="dvPreview"></div><br>
  </div>

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

$stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute(array($alumni_id));
$result = $stmt->fetchAll();
	
foreach($result as $row) {
	$department_id = $row['department_id'];
}

$number = 0;

echo '<div class="form-group" id = "bloc3">';
echo	'<label for="text">Επιλέξτε που θα προβληθεί η ανακοίνωση:</label><br>';
echo	'<select class="selectpicker" data-width="75%"  name="where[]" id = "where" multiple>';
echo		'<option value="'.$number.'">Ανακοίνωση που αφορά την Κεντρική Σελίδα </option>';
	
$stmt = $conn->prepare("SELECT * FROM departments WHERE id = ?");
$stmt->execute(array($department_id));
$result = $stmt->fetchAll();
	
	foreach($result as $row) {
	
		echo  '<option value="'.$department_id.'">Ανακοίνωση που αφορά το '.$row['dname'].'</option>';
	}  
?>
		</select>
   </div>
   
   <div class="form-group" id = "bloc4">
	<input type="submit" class="btn btn-primary" value ="Δημοσίευση" id = "submit"></input>
   </div>
   
</form><br>


</div>

<br><br><br><br>
<footer class="container-fluid text-center">
  Footer Text
</footer>

<script>
$(document).ready(function () {
	
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

	if (localStorage.getItem("optiondiv") === null) 
	{
		// αν η μνήμη ειναι αδεια τοτε δεν κανει τπτ
	}
	else
	{
		localStorage.removeItem("optiondiv"); //ελευθερώνει την μνήμη απο τις δημοσιευσεις
	}	
							
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
function validate()  
{
	var title = $("#title").val();
	var description = $("#description").val();
	var body = $("#body").val();
	var department = $("#where").val();
	
	var w = Math.max(document.documentElement.clientWidth, window.innerWidth || 0);  //συναρτηση υπολογισμου του πλατους της οθόνης
		
	if (title == '' || description == '' || body == '' || department == '') {
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

</body>
</html>
