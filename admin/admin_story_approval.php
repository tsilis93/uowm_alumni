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

<br><br><br><br>
<h2>Ιστορίες προς Έγκριση</h2>

<br>
  <div class="construct">
    <div  style="overflow-x:auto;">       
    <table style="width:100%" class = "table" id="cssTable">
	<thead>
	<tr>
		<th class="text-center">Τίτλος</th>
		<th class="text-center">Σύντομη Περιγραφή</th>
		<th class="text-center">Συντάκτης</th>
		<th class="text-center">Περισσότερα</th>
		<th class="text-center">Έγκριση</th> 
		<th class="text-center">Απόρριψη</th>
		<th class="text-center"></th>
	</tr>
	</thead>
	<tbody>
				
<?php
session_start();

include ("../connectPDO.php");

include('../Pager.php');
require_once '../Pager/Pager.php';				

if(isset($_SESSION['name'])) {
	$admin_id = $_SESSION['name'];
}
else
{
header('Location: ../register_login_form.php');
}

$stmt = $conn->prepare("SELECT * FROM stories WHERE status = 0 AND publication_date IS NULL order by id desc");
$stmt->execute();
$result = $stmt->fetchAll();

$totalRows = $stmt->rowCount();
$pager_options = array(
	'mode'       => 'Sliding',
	'perPage'    => 5,
	'delta'      => 4,
	'totalItems' => $totalRows,
);

$pager = @Pager::factory($pager_options);
echo $pager->links;
list($from, $to) = $pager->getOffsetByPageId();
$from = $from - 1;
$perPage = $pager_options['perPage'];

$stmt = $conn->prepare("SELECT * FROM stories WHERE status = 0 AND publication_date IS NULL order by id desc LIMIT $from, $perPage");
$stmt->execute();
$result = $stmt->fetchAll();

$row_count = 1;					
if(sizeof($result)>0) {
	foreach($result as $row)
	{
		$title=$row['title'];
		$author_id=$row['userID'];
		$description=$row['description'];
		$body = $row['body'];
		$id = $row['id'];
					
		$stmt2 = $conn->prepare("SELECT name, lastname FROM users WHERE id=?");
		$stmt2->execute(array($author_id));
		$author = $stmt2->fetchAll();

		$author_name = $author[0][0] . " " . $author[0][1];
 				
		echo	"<tr>";
		echo	'<td width = "(100/7)%">'.$title.'</td>';
		echo	'<td width = "(100/7)%">'.$description.'</td>';
		echo	'<td width = "(100/7)%">'.$author_name.'</td>';
		echo	'<td width = "(100/7)%">'; 
		echo    '<form action="story_overview.php?story='.$id.'" method="post">';
		echo		'<button type="submit" class="btn btn-primary">Λεπτομέρειες</button>';
		echo	"</form>";
		echo	"</td>";
		echo	'<td width = "(100/7)%">';
		echo	'<form action="story_approval.php?approve='.$id.'" method="post">'; 
		echo		'<input type="image" name="submit" src="../assets/approve1.png" height="25" width="25" border="0" alt="Approve" onclick = "myFunction()" onmouseover="" style="cursor: pointer;"/>';  
		echo	'</form>'; 
		echo	"</td>";
		echo	'<form action="story_approval.php?delete='.$id.'" method="post">'; 
		echo		'<td width = "(100/7)%">';
		echo			'<input type="image" name="submit" src="../assets/delete.png" height="25" width="25" border="0" alt="Delete" onclick = "myFunction2()" onmouseover="" style="cursor: pointer;" />';
		echo		"</td>";
		echo		'<td width = "(100/7)%"><a href="#s'.$row_count.'" class="btn btn-info" data-toggle="collapse">Σχόλια</a></td>';
		echo		"<tr>";
		echo			'<td colspan = "7"><div id="s'.$row_count.'" class="collapse"> <textarea placeholder="Προαιρετικά σχόλια για τον απόφοιτο στην περίπτωση που απορριφθεί την Ιστορία ..." class="form-control" style="height:50px;" id="comment" name = "comment"></textarea> </div></td>';
		echo		"</tr>";
		echo	"</form>";
		echo	"</tr>";
		$row_count = $row_count + 1;	
	}
}
else
{

		echo    '<tr><td colspan = "7"> Δεν υπάρχουν Ιστορίες σε εκκρεμότητα </td></tr>';
}

?>

	</tbody>
    </table>
	</div>
</div>

<br><br><br>
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

function myFunction() {

	var w = Math.max(document.documentElement.clientWidth, window.innerWidth || 0);  //συναρτηση υπολογισμου του πλατους της οθόνης
	
	if (w < 768) {
		mcxDialog.alert("Ο απόφοιτος θα ενημερώθεί για την έγκριση της Ιστορίας του.");
	}
	else 
	{
		alert("Ο απόφοιτος θα ενημερώθεί για την έγκριση της Ιστορίας του.");
	}
	return true;
}

function myFunction2() {
	
	var w = Math.max(document.documentElement.clientWidth, window.innerWidth || 0);  //συναρτηση υπολογισμου του πλατους της οθόνης
	
	if (w < 768) {
		mcxDialog.alert("Ο απόφοιτος θα ενημερώθεί για την απόρριψη της Ιστορίας του.");
	}
	else 
	{
		alert("Ο απόφοιτος θα ενημερώθεί για την απόρριψη της Ιστορίας του.");
	}
	return true;
}

</script>		

</body>
</html>
