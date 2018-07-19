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
  
  <link rel="stylesheet" href="../css/alumni_newsletter.css"> 
  
<link rel="stylesheet" type="text/css" href="../phones_specs/css/dialog-mobile.css"/> <!-- css & javascript για τις ειδοποιήσεις (alerts) στα κινητα -->
<script type="text/javascript" src="../phones_specs/mcx-dialog.js"></script>  
  
</head>
<body>
<header>

</header>
<?php 

session_start();
include ("../connectPDO.php");

$alumni_id = 0;
if(isset($_SESSION['student']))
{
	$alumni_id = $_SESSION['student'];
}
else
{
header('Location: ../register_login_form.php');
}

$newsletter_categories = array();
$categories_id = array();
$counter = 0;

echo '<br><br><br><br><br><br>';
echo '<div class="container">';
echo 	'<h2 id="tcenter">Εγγραφή σε Newsletter</h2><hr>';
echo	'<label>Θέλω να γραφτώ / ξεγραφτώ στα newsletter σχετικά με:</label>';
echo	'<table style="width:100%;">';

$stmt = $conn->prepare("SELECT * from newsletter_categories");  
$stmt->execute();
$result = $stmt->fetchAll();

if (sizeof($result)>0) {   //αν υπάρχει κατηγορία
	foreach($result as $row) {
		$cname = $row['category_name'];
		$id = $row['id'];
		$newsletter_categories[$counter] = $cname;
		$categories_id[$counter] = $id;
		$counter = $counter + 1;
	}

	$stmt = $conn->prepare("SELECT * from newsletter WHERE alumni_id = ?");  
	$stmt->execute(array($alumni_id));
	$result2 = $stmt->fetchAll();
	
	if(sizeof($result2)>0) {
		foreach($result2 as $row2) {
			for($i=0; $i<count($newsletter_categories); $i++) {
				$option = "option_id".$categories_id[$i];
				echo '<tr>';
				echo	'<td style="width:70%">'.$newsletter_categories[$i].'</td>';
				if($row2[$option] == 1) {
					echo	'<td style="width:30%"><input type="checkbox" id="'.$option.'" checked></td>';
				}
				else
				{
					echo	'<td style="width:30%"><input type="checkbox" id="'.$option.'"></td>';
				}
				echo '</tr>';
			}
		}
	}
}
else
{
	echo '<tr>';
	echo	'<td colspan="2">Δεν υπάρχει διαθέσιμη κατηγορία για newsletter</td>';
	echo '</tr>';
}

echo '</table>';
echo "<br>";
echo '</div>';	


?>
 
<br><br><br><br><br><br><br><br><br><br><br><br>
<footer class="container-fluid text-center">
  Footer Text
</footer>
 
<script>
$(document).ready(function(){
	
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
	
	$(":input").change(function(){ 
		
		var current_id = $(this).attr('id');
		
		var newsletter_categories_table = <?php echo(json_encode($categories_id)); ?>;	//παρε τις επιλογες που εχει ο χρήστης
		var table_length = newsletter_categories_table.length; //μεγεθος παραπάνω πίνακα
		if(table_length > 0) {
			for(var i=0; i<table_length; i++) {
				if(current_id == "option_id"+newsletter_categories_table[i]) {		//αν όντως εχει επιλέξει μια από τις επιλογες
						unsaved = false;	//τότε δεν χρειαζεται να τον προειδοποιήσουμε για μη αποθηκευμένα δεδομένα
						newsletter_found_option = true; //σηκώνουμε την σημαια 
						
						var option = newsletter_categories_table[i];
						if(this.checked) 
						{	
							var action = 1; 
							$.post("update_newsletter.php", {
								option: option,
								action: action,
							}, function() {
								if ($(window).width() < 768) {
									mcxDialog.alert('Εγγραφήκατε στα newsletter με επιτυχία');
								}
								else 
								{
									alert('Εγγραφήκατε στα newsletter με επιτυχία');
								}
							});
						}
						else
						{
							var action = 0; 
							$.post("update_newsletter.php", {
								option: option,
								action: action,
							}, function() {
								if ($(window).width() < 768) {
									mcxDialog.alert('Απεγγραφήκατε από τα newsletter με επιτυχία');
								}
								else 
								{
									alert('Απεγγραφήκατε από τα newsletter με επιτυχία');
								}
							});										
						}
				}
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
