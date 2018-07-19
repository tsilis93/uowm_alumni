<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>UOWM Alumni</title>
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
  <meta http-equiv="content-type" content="text/html; charset=UTF-8">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.4/css/bootstrap-select.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.4/js/bootstrap-select.min.js"></script>

		<link rel="stylesheet" href="../css/global_search.css">
		 <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	
  <link rel="stylesheet" type="text/css" href="../phones_specs/css/dialog-mobile.css"/> <!-- css & javascript για τις ειδοποιήσεις (alerts) στα κινητα -->
  <script type="text/javascript" src="../phones_specs/mcx-dialog.js"></script>	
  
</head>

<body> 

<header>

<!-- Σε αυτό το σημείο το navigation menu έρχεται με jquery -->

</header>
<br><br><br><br><br><br>

<div align="center">
<p id = "header">Αναζήτηση Αποφοίτων <span class="glyphicon" style="color:white" onmouseOver = "mouseOver()" onmouseout="mouseOut()">&#xe086;</span></p>
<div id = "info"></div>
</div>


<br>

<div class="container" align = "center">
<br>
<label for="text">Επιλέξτε Τμήμα:</label><br>
<select class="selectpicker" data-width="30%" name="department" id = "department" multiple>
<?php 
session_start();
include ("../connectPDO.php");

$stmt = $conn->prepare("SELECT * FROM departments");
$stmt->execute();
$result = $stmt->fetchAll();
foreach($result as $row) {
	
	echo '<option value="'.$row['id'].'">'.$row['dname'].'</option>';
	
}
 
?>
</select>

<br><br>
<label for="text">Επιλέξτε Κριτήρια:</label>
<table id="myTable" width="100%"> 
	<tr>
			<td width="20%">
				<div id = "select1_1" style="border-radius: 7px;">
					<select class="selectpicker" id = "select1-1" data-width="100%">
							<option value="" disabled selected>Πεδία</option>
							<option value="1">Όνομα</option>
							<option value="2">Επώνυμο</option>
							<option value="3">Έτος Εγγραφής</option>
							<option value="4">Έτος Αποφοίτησης</option>
							<option value="5">Βαθμός Πτυχίου</option>
							<option value="6">Πόλη Διαμονής</option>
							<option value="7">Πόλη Εργασίας</option>
					</select>
				</div>	
			</td>
			<td width="5%">
				<div id = "select2_1" style="border-radius: 7px;">
					<select class="selectpicker" id = "select2-1" data-width="100%">
							<option value="" disabled selected>Τελεστής</option>
							<option value="1"> = </option>
							<option value="2"> ! = </option>
							<option value="3"> < </option>
							<option value="4"> > </option>
							<option value="5"> > = </option>
							<option value="6"> < = </option>
							<option value="7"> LIKE (περιλαμβάνει) </option>
					</select>
				</div>	
			</td>
			<td width="55%">
				 <input type="text" id="input1" class="form-control">
			</td>
			<td width="20%">
				<div id = "select3_1" style="border-radius: 7px;">
					<select class="selectpicker" id = "select3-1" data-width="100%">
							<option value="" disabled selected>Πρόσθεσε</option>
							<option value="1"> AND </option>
							<option value="2"> OR </option>
							<option value="3"><<Τέλος αναζήτησης>></option>
					</select>
				</div>	
			</td>
	
	</tr>
	<br>
	
</table>

<br><br>
<table id="myTable3" width="100%"> 
	<tr>
		<td width="25%"><input class = "btn" type="button" id="btnSearchAll" value ="Όλοι οι Αποφοίτοι" /></td>
		<td width="25%"><input class = "btn" type="button" id="btnAdd" value ="Προσθήκη Κριτηρίου" /></td>
		<td width="25%"><input class = "btn" type="button" id="btnRem" value ="Διαγραφή Κριτηρίων" /></td>
		<td width="25%"><input class = "btn" type="button" id="btnSearch" value ="Αναζήτηση" /></td>
	</tr>
</table>
<br>
</div>

<br><br><br>
<div id = "results">

</div>



<div class="modal fade" id="myModal2" role="dialog">
    
	<div class="modal-dialog modal-lg">
    
		  <div class="modal-content">
				
				<div class="modal-header">
				  <button type="button" class="close" data-dismiss="modal">&times;</button>
				  <h1>Στοιχεία Αποφοίτου</h1>
				</div>
				<div class="modal-body edit-content">
				  
					
				</div>
				
				<div class="modal-footer">
					<?php
					if((isset($_SESSION['name'])) || (isset($_SESSION['student']) ) ) {
						
					}
					else
					{
						echo '<p style = "color: black; font-size: 120%;" >Για να δείτε τα στοιχεία με την σήμανση <span class="red-star">★</span> συνδεθείτε σαν απόφοιτος ή διαχειριστής. Μπορείτε επίσης να δείτε και το βιογραφικό του χρήστη</p>';
					}
					?>				
				</div>  
				
		  </div>
      
    </div>
	
</div>

<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
<footer class="container-fluid text-center">
  Footer Text
</footer>


<script>
	$(document).ready(function() {
		//setup της σελίδας
		$.post("guest_header.php", {	
		}, function(data) {
			$('header').html(data);
		});	

		$.post("../footer.php", {	
		}, function(data) {
			$('footer').html(data);
		});		
	
		//διαδικασία δημιουργίας πίνακα αναζήτησης
		var id = 2;		//στην αρχή ο μετρητής είναι 2 γιατί ο πίνακας περιλαμβάνει ήδη ένα κριτήριο
		
		$('#btnAdd').click(function() {
			var rowCount = $('#myTable tr').length;  // υπολογίζει πόσα κριτήρια έχει ο πίνακας
			
			$.get("rows.php", {id: id}, function(data, status) {
				
				$('#myTable').append(data);
				
				if(rowCount >= 1) {  //ο πίνακας έχει τουλάχιστον 1 εγγραφή
					for(var i=1; i<=rowCount+1; i++) {
						$('#select1-'+ i +'').selectpicker();  //εφαρμόζω στυλιστικές συναρτήσεις
						$('#select2-'+ i +'').selectpicker();
						$('#select3-'+ i +'').selectpicker();
						$('#input'+ i +'').addClass("form-control");
					}
				}
				else  // ο πίνακας είναι άδειος
				{
					$('#select1-1').selectpicker();
					$('#select2-1').selectpicker();
					$('#select3-1').selectpicker();
					$('#input1').addClass("form-control");
				}
				
				id = id+1; //αυξάνω τον μετρητή για τις θέσεις του πίνακα
			})
		});
		
		
		$('#btnSearch').click(function() {
			var rowCount = $('#myTable tr').length;
			var flag = true; //σημαία για να καταλαβουμε αν έχουν συμπληρωθεί όλα τα απαιτούμενα δεδομένα
			
			var department = $("#department").val();
			var faculty = $("#faculty").val();
			var array = new Array();
			var elements = 0;  //μετρητής για τα στοιχεία που μπαινουν στον πίνακα
			
			for(var i=1; i<rowCount+1; i++) {
				for(var j=1; j<=4; j++) {
					var select;
					if(j == 4) {
						select = document.getElementById('input'+i+'').value;
					}
					else
					{
						select = $('#select'+ j +'-'+ i +'').val();
					}
					
					if(select == null) {
						flag = false;
						$('#select'+ j +'_'+ i +'').css("border","red solid 2px");
					}
					else
					{
						$('#select'+ j +'_'+ i +'').css("border","");
						array[elements++] = select;
					}
				}
			}
			if(flag == false) {
				if ($(window).width() < 768) {
					mcxDialog.alert("Ενα ή περισσότερα πεδία δεν έχούν συμπληρωθεί");
				}
				else 
				{
					alert("Ενα ή περισσότερα πεδία δεν έχούν συμπληρωθεί");
				}
			}
			else
			{
				if(array[elements-2] != 3 ) {
					i = rowCount;
					j = 3;
					$('#select'+ j +'_'+ i +'').css("border","red solid 1.5px");

					if ($(window).width() < 768) {
						mcxDialog.alert("Παρακαλώ προσθέστε ένα κριτήριο ακόμα ή επιλέξτε 'Τέλος Αναζήτησης' στο τελευταίο κελί");
					}
					else 
					{
						alert("Παρακαλώ προσθέστε ένα κριτήριο ακόμα ή επιλέξτε 'Τέλος Αναζήτησης' στο τελευταίο κελί");
					}				
				}
				else
				{
					var elementTocheck = 4*rowCount-2;   //εναλλακτικά elements-2
					var okflag = true;
					
					i = rowCount;
					j = 3;				
					while(elementTocheck > 0) {
						elementTocheck = elementTocheck - 4;
						i = i-1;
						if(array[elementTocheck] == 3) { // αν εντοπιστεί λάθος επιλογή ο χρήστης ενημερώνεται
							$('#select'+ j +'_'+ i +'').css("border","red solid 1.5px");
							okflag = false;
						}
					}
					if(okflag == false) {
						if ($(window).width() < 768) {
							mcxDialog.alert("Υπάρχει μια ή παραπάνω λάθος επιλογή στην 4η στήλη! Παρακαλώ διορθώστε και ξαναδοκιμάστε την αναζήτηση σας");
						}
						else 
						{
							alert("Υπάρχει μια ή παραπάνω λάθος επιλογή στην 4η στήλη! Παρακαλώ διορθώστε και ξαναδοκιμάστε την αναζήτηση σας");
						}
							
					}					
					if(okflag == true) {    // αν όλα είναι στην εντέλεια εκτελεί το query
						$.post("search2.php", {
							someKeyName: array,
							departments: department,
							faculties: faculty
						}, function(data, status) {
							$('#results').html(data);
							$('#results').css('box-shadow', '0 0 15px 10px rgba(0, 0, 0, 0.4)');
						});
					}
				}
			}
		});
		
		
		$('#btnRem').click(function() {  // διαγράφει όλα τα κριτήρια
			$('#myTable tr').remove();
			id = 1; // ο πίνακας είναι άδειος άρα πρέπει να βάλουμε ξανά κριτήρια
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
		
		$('#btnSearchAll').click(function() {  // επιστρέφει όλους τους απόφοιτους από όλα τα τμήματα
			var all = true;
			$.post("search2.php", {
				All: all
			}, function(data, status) {
				$('#results').html(data);
				$('#results').css('box-shadow', '0 0 25px 10px rgba(0, 0, 0, 0.3)');
			});
		});
										
	});
</script>


<script type="text/javascript">
function mouseOver()
{
	$('#info').html('Μπορείς να κάνεις αναζήτηση αποφοίτων για όλα τα Τμήματα');
	$('#info').show();
}
function mouseOut() 
{
	$('#info').hide();
}

function loadMoreAlumni(query)
{
	var last_id = $(".post-id:last").attr("id");
	$('.ajax-load').show();
	$.post("more_alumni.php", {last_id: last_id, previews_query: query}, function(data, status) {
		
		$('.ajax-load').show();	
		setTimeout(function(){ 
			$('.ajax-load').hide();
			$('#post_data').append(data);			
		}, 2000);
		
	});
}

</script>
<br><br><br><br>
</body>

</html>
