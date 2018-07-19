<?php

include ("../connectPDO.php");

if(isset($_POST['input'])) {
	$input = $_POST['input'];
	$input = $input . '%';
}

if (empty($_POST['input'])) {
	$input = "";
}

$stmt = $conn->prepare("SELECT * FROM `users` WHERE ((`lastname` LIKE ?) OR (`name` LIKE ?)) AND (role = 1 OR role = 3)");
$stmt->execute(array($input,$input));
$result = $stmt->fetchAll();

if(sizeof($result)>0) {
	echo '<table class="table" style="width:100%">';
	foreach($result as $row) 
	{
		$name = $row['name'];
		$lastname = $row['lastname'];
		$cell_phone = $row['cell_phone'];
		$id = $row['id'];
				
		$fullname = $lastname . " " . $name;
		if(empty($cell_phone)) {
			$cell_phone = "Δεν υπάρχει διαθέσιμο κινητό";
			echo '<tr><td>' . $fullname . '</td> <td>' . $cell_phone . '</td></tr>';
		}
		else
		{
			echo '<tr><td>' . $fullname . '</td> <td id='.$id.' ondblclick="myFunction(this.id);">' . $cell_phone . '</td></tr>';			
		}
	}
	echo '</table>';
}
else
{
	echo "";
}	

?>