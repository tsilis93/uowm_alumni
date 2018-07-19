<?php

include ("../connectPDO.php");

if(isset($_POST['value'])) {
	$input = $_POST['value'];
	$input = $input . '%';
}

if(isset($_POST['alumni_id'])) {
	$alumni_id = $_POST['alumni_id'];
}

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

echo	'<table id = "myTable2" width="100%">';
echo		'<thead>';
echo	      	'<tr>';
echo				'<th>Επώνυμο</th>';
echo				'<th>Όνομα</th>';
echo				'<th>Σχολή</th>';
echo				'<th>Επιλογή</th>';
echo			'</tr>';
echo		'</thead>';
echo		'<tbody>';

$stmt = $conn->prepare("SELECT * FROM `users` WHERE (`lastname` LIKE ?) OR (`name` LIKE ?) AND (role = 1 OR role = 3)");
$stmt->execute(array($input,$input));
$result = $stmt->fetchAll();

if(sizeof($result)>0) {
	foreach($result as $row) {
		$did = $row['department_id'];
		$lastname = $row['lastname'];
		$name = $row['name'];
		$id = $row['id'];
												
		$stmt3 = $conn->prepare("SELECT * FROM departments WHERE id = ?");
		$stmt3->execute(array($did));
		$result3 = $stmt3->fetchAll();
							
		foreach($result3 as $row3) {
			$dname = $row3['dname'];
		}	
		echo	"<tr>";
		echo		'<td>'.$lastname.'</td>';
		echo		'<td>'.$name.'</td>';
		echo		'<td>'.$dname.'</td>';
		if($id != $alumni_id) {
			if(in_array($id,$friend_alumni_id)) 
			{
				echo		'<td><button id = "'.$id.'" class="btn btn-link" onclick="javascript:unfollow_alumni(this.id); return false;"><font color="red">Unfollow<font></button></td>';
			}
			else
			{
				echo		'<td><button id = "'.$id.'" class="btn btn-link" onclick="javascript:follow_alumni(this.id); return false;"><font color="green">Follow<font></button></td>';
			}
		}
		else
		{
			echo		'<td>Καμία Επιλογή</td>';
		}
		echo	"</tr>";
	}
}
else
{
	echo  '<tr align = "center"><td colspan = "4"> Δεν υπάρχει απόφοιτος που να έχει το παραπάνω όνομα ή επώνυμο</td></tr>'; 	
}	
