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
<h2>Μη Δημοσιευμένες Ιστορίες</h2>

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

$stmt = $conn->prepare("SELECT * FROM stories WHERE status = 0 AND publication_date IS NOT NULL");
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

$stmt = $conn->prepare("SELECT * FROM stories WHERE status = 0 AND publication_date IS NOT NULL LIMIT $from, $perPage");
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
		$publication_date = $row['publication_date'];
		$id = $row['id'];
							
		$stmt2 = $conn->prepare("SELECT name, lastname FROM users WHERE id=?");
		$stmt2->execute(array($author_id));
		$author = $stmt2->fetchAll();

		$author_name = $author[0][0] . " " . $author[0][1];
 				
		echo	"<tr>";
		echo	'<td width = "20%">'.$title.'</td>';
		echo	'<td width = "20%">'.$description.'</td>';
		echo	'<td width = "20%">'.$author_name.'</td>';
		echo	'<td width = "20%">'.$publication_date.'</td>';
		echo	'<td width = "20%">'; 
		echo    '<form action="story_overview.php?rejected_story='.$id.'" method="post">';
		echo		'<button type="submit" class="btn btn-primary">Λεπτομέρειες</button>';
		echo	"</form>";
		echo	"</td>";
	}
}
else
{

		echo    '<tr><td colspan = "4"> Δεν υπάρχουν μη δημοσιευμένες ιστορίες</td></tr>';
}

?>

	</tbody>
    </table>
	</div>
</div>

<br><br><br>
<footer class="container-fluid text-center">
  Footer Text
</footer>


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

</body>
</html>
