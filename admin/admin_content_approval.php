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
  
  <link rel="stylesheet" href="../css/admin.css"> 
  
</head>
<body>

<header>

</header>

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
	
?>

<br><br><br><br>
<h2>Ανακοινώσεις προς Έγκριση</h2>

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
				
$stmt2 = $conn->prepare("SELECT * FROM contents WHERE status = 0 AND publication_date is NULL");
$stmt2->execute();
$result = $stmt2->fetchAll();

$totalRows = $stmt2->rowCount();
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

$stmt2 = $conn->prepare("SELECT * FROM contents WHERE status = 0 AND publication_date is NULL LIMIT $from, $perPage");
$stmt2->execute();
$result = $stmt2->fetchAll();	

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
		echo		'<td width = "(100/6)%">'.$title.'</td>';
		echo		'<td width = "(100/6)%">'.$description.'</td>';
		echo		'<td width = "(100/6)%">'.$author_name.'</td>';
		echo		'<td width = "(100/6)%">'; 
		echo    		'<form action="content_overview.php?content='.$id.'" method="post">';
		echo				'<button type="submit" class="btn btn-primary">Λεπτομέρειες</button>';
		echo			"</form>";
		echo		"</td>";
		echo		'<td width = "(100/6)%">';
		echo			'<form action="content_approval.php?approve='.$id.'" method="post">'; 
		echo				'<input type="image" name="submit" src="../assets/approve1.png" height="25" width="25" border="0" alt="Approve" onclick = "myFunction()" onmouseover="" style="cursor: pointer;"/>';  
		echo			'</form>'; 
		echo		"</td>"; 
		echo		'<td width = "(100/6)%">';
		echo			'<form action="story_approval.php?delete='.$id.'" method="post">';
		echo				'<input type="image" name="submit" src="../assets/delete.png" height="25" width="25" border="0" alt="Delete" onclick = "myFunction2()" onmouseover="" style="cursor: pointer;" />';
		echo			"</form>";		
		echo		"</td>";
		echo	"</tr>";
	}
}
else
{

		echo    '<tr><td colspan = "6"> Δεν υπάρχουν Ανακοινώσεις σε εκκρεμότητα </td></tr>';
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

<script type="text/javascript">
function myFunction() {
	var w = Math.max(document.documentElement.clientWidth, window.innerWidth || 0);  //συναρτηση υπολογισμου του πλατους της οθόνης
	
	if (w < 768) {
		mcxDialog.alert("Ο απόφοιτος θα ενημερώθεί για την έγκριση της ανακοίνωσης του.");
	}
	else 
	{
		alert("Ο απόφοιτος θα ενημερώθεί για την έγκριση της ανακοίνωσης του.");
	}
	return true;
}

function myFunction2() {
	var w = Math.max(document.documentElement.clientWidth, window.innerWidth || 0);  //συναρτηση υπολογισμου του πλατους της οθόνης
	
	if (w < 768) {
		mcxDialog.alert("Ο απόφοιτος θα ενημερώθεί για την απόρριψη της ανακοίνωσης του.");
	}
	else 
	{
		alert("Ο απόφοιτος θα ενημερώθεί για την απόρριψη της ανακοίνωσης του.");
	}
	return true;
}	
</script>		

</body>
</html>
