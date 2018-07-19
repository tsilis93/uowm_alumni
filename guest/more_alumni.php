<?php

session_start();
include ("../connectPDO.php");
$last_id = 0;
if(isset($_POST['last_id']))
{
	$last_id = $_POST['last_id'];
}

$query = "";
if(isset($_POST['previews_query'])) {  //βρες τα δεδομενα απο το αποθηκευμένο query 
	$query = $_POST['previews_query'];
}

$params = array();
if(isset($_SESSION['params'])) {
	$params = $_SESSION['params'];
}

if(count($params) > 0) {
	$query = $query . " AND id < ? AND (role = 1 OR role = 3) ORDER BY `id` DESC LIMIT 10";		//όταν εχουμε προσαρμοσμένο query
}
else
{
	$query = $query . " WHERE id < ? AND (role = 1 OR role = 3) ORDER BY `id` DESC LIMIT 10";		//όταν το query ειναι ολοι οι αποφοιτοι	
}


$count = count($params);

if($count == 0) {
	$params[0] = $last_id;
}
else
{
	$params[$count] = $last_id;
}

$stmt = $conn->prepare($query);
$stmt->execute($params);
$result = $stmt->fetchAll();

if(sizeof($result)>0) {

	foreach($result as $row) {
		$id = $row['id'];
		$name = $row['name'];
		$lastname = $row['lastname'];
		$father = $row['fathers_name'];
		
		echo '<tr class="post-id" id="'.$id.'">';
		echo	"<td class='text-center'>$lastname</td>";
		echo	"<td class='text-center'>$name</td>";
		echo	"<td class='text-center'>$father</td>";
		echo	'<td class="text-center"><button type="button" id="'.$id.'" data-toggle="modal" data-target="#myModal2"><span class="glyphicon glyphicon-folder-open"></span></button></td>';
		if((isset($_SESSION['name'])) || (isset($_SESSION['student'])))  // αν ο χρήστης ειναι συνδεδεμένος
		{
			if (sizeof($result2)>0) {
				foreach($result2 as $row2) {
					$src = $row2['pdf_src'];
					echo	'<td class="text-center"><a href="../cv_files/'.$src.'" onclick="window.open(this.href); return false;" onkeypress="window.open(this.href); return false;">Σύνδεσμος</a></td>';				
				}
			}
			else
			{
				echo '<td class="text-center">Μη διαθέσιμο pdf</td>';
			}
		}			
		echo "</tr>";						
	}
}
else
{
	echo '<tr><td style ="text-align:center" colspan = "4"> Δεν υπάρχουν επιπλέον φοιτητές που να πληρούν τις προδιαγραφές </td></tr>';
}

?>