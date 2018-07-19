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
<h2>Δημοσιευμένες Ανακοινώσεις</h2>
<br>
  <div class="construct"><br>
	<div style="overflow-x:auto;">
    <table style="width:100%" class = "table" id="cssTable">
	<thead>
	<tr>
		<th class="text-center">Τίτλος</th>
		<th class="text-center">Σύντομη Περιγραφή</th>
		<th class="text-center">Συντάκτης</th>
		<th class="text-center">Ημερομηνία Δημοσίευσης</th>
		<th class="text-center"></th>
	</tr>
	</thead>
	<tbody>  
<?php
				
$stmt2 = $conn->prepare("SELECT * FROM contents WHERE status = 1");
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

$stmt2 = $conn->prepare("SELECT * FROM contents WHERE status = 1 ORDER BY publication_date DESC LIMIT $from, $perPage");
$stmt2->execute();
$result = $stmt2->fetchAll();

				
if(sizeof($result)>0) {
	foreach($result as $row)
	{
		$title=$row['title'];
		$description=$row['description'];
		$author_id=$row['userID'];
		$publication_date = $row['publication_date'];
		$id = $row['id'];
		
		$stmt2 = $conn->prepare("SELECT name, lastname FROM users WHERE id=?");
		$stmt2->execute(array($author_id));
		$author = $stmt2->fetchAll();

		if(sizeof($author) > 0) {
			$author_name = $author[0][0] . " " . $author[0][1];
		}
					
 				
		echo	"<tr>";
		echo	'<td width = "20%">'.$title.'</td>';
		echo	'<td width = "20%">'.$description.'</td>';
		echo	'<td width = "20%">'.$author_name.'</td>';
		echo	'<td width = "20%">'.$publication_date.'</td>'; 
		echo	'<td width = "20%">'; 
		echo    '<form action="content_overview.php?published_content='.$id.'" method="post">';
		echo		'<button type="submit" class="btn btn-primary">Επεξεργασία</button>';
		echo	"</form>";
		echo	"</td>";		
		echo	"</tr>";
	}
}
else
{

		echo    '<tr><td colspan = "5"> Δεν υπάρχουν Ανακοινώσεις που έχουν δημοσιευτεί </td></tr>';
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

</body>
</html>
