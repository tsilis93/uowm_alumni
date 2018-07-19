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
	$query = $query . " AND id < ? ORDER BY `id` DESC LIMIT 5";		//όταν εχουμε προσαρμοσμένο query
}
else
{
	$query = $query . " WHERE id < ? ORDER BY `id` DESC LIMIT 5";		//όταν το query ειναι ολοι οι αποφοιτοι	
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
		echo	'<td class="text-center"><input class = "messageCheckbox" name="recipients[]" type="checkbox" value="'.$id.'"></td>';
		echo	'<td class="text-center"><button id = "'.$id.'" class="btn btn-link" onclick="javascript:delete_alumni(this.id); return false;"><font color="red">Απεγγραφή<font></button></td>';
		echo	'<td class="text-center"><button id = "'.$id.'" class="btn btn-default" onclick="javascript:edit_alumni(this.id); return false;">Επεξεργασία</button></td>';
		echo "</tr>";						
	}
}
else
{
	echo '<tr><td style ="text-align:center" colspan = "7"> Δεν υπάρχουν επιπλέον φοιτητές που να πληρούν τις προδιαγραφές </td></tr>';
}

?>