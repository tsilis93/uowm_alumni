<?php

session_start();

if(isset($_SESSION['student'])) {
	$alumni_id = $_SESSION['student'];
}

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
	
	$stmt4 = $conn->prepare("SELECT * FROM user_relationship WHERE alumni_id = ?");
	$stmt4->execute(array($alumni_id));
	$result4 = $stmt4->fetchAll();
	$friend_counter = 0;
	$friend_alumni_id = array();
				
	if(sizeof($result4)>0) {	
		foreach ($result4 as $row4) {
			$friend_alumni_id[$friend_counter] = $row4['friend_alumni_id'];
			$friend_counter = $friend_counter + 1; 		
		}
	}

	foreach($result as $row) {
		$id = $row['id'];
		$name = $row['name'];
		$lastname = $row['lastname'];
		$departmentID = $row['department_id'];
		
		$stmt3 = $conn->prepare("SELECT * FROM departments WHERE id = ?");
		$stmt3->execute(array($departmentID));
		$result3 = $stmt3->fetchAll();
						
		foreach($result3 as $row3) {
			$dname = $row3['dname'];
		}		
		
		echo '<tr class="post-id" id="'.$id.'">';
		echo	"<td class='text-center'>$lastname</td>";
		echo	"<td class='text-center'>$name</td>";
		echo	"<td class='text-center'>$dname</td>";
		echo	'<td class="text-center"><button type="button" id="'.$id.'" data-toggle="modal" data-target="#myModal2"><span class="glyphicon glyphicon-folder-open"></span></button></td>';
		if($alumni_id == $id) {
			echo	'<td class="text-center"><input class = "messageCheckbox" name="recipients[]" type="checkbox" value="'.$id.'" disabled></td>';
		}
		else
		{
			echo	'<td class="text-center"><input class = "messageCheckbox" name="recipients[]" type="checkbox" value="'.$id.'"></td>';			
		}		
		if(in_array($id,$friend_alumni_id)) 
		{
			echo		'<td class="text-center"><button id = "'.$id.'" class="btn btn-link" onclick="javascript:unfollow_alumni(this.id); return false;"><font color="red">Unfollow<font></button></td>';
		}
		else
		{
			if($alumni_id == $id) {
				echo		'<td class="text-center"></td>';
			}
			else
			{
				echo		'<td class="text-center"><button id = "'.$id.'" class="btn btn-link" onclick="javascript:follow_alumni(this.id); return false;"><font color="green">Follow<font></button></td>';
			}
		}
		echo "</tr>";						
	}
}
else
{
	echo '<tr><td style ="text-align:center" colspan = "6"> Δεν υπάρχουν επιπλέον φοιτητές που να πληρούν τις προδιαγραφές </td></tr>';
}

?>