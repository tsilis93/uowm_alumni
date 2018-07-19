<?php
session_start();
include ("../connectPDO.php");
$id = 0;
if(isset($_POST['id'])){
	$id =  $_POST['id'];
}

$stmt = $conn->prepare("SELECT * from departments WHERE id = ?");
$stmt->execute(array($id));
$result = $stmt->fetchAll();

if(sizeof($result) == 0) {

//επέστρεψε τιποτα
	
}
else
{
	foreach($result as $row) {
		$dname = $row['dname'];
	}
	
	echo      '<div class="navbar navbar-default navbar-fixed-top" role="navigation">';
	//echo		'<div class="container">';
	echo          '<div class="navbar-header">';
	echo            '<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">';
	echo              '<span class="sr-only">Toggle navigation</span>';
	echo              '<span class="icon-bar"></span>';
	echo              '<span class="icon-bar"></span>';
	echo              '<span class="icon-bar"></span>';
	echo            '</button>';
	echo			'<a href ="index.php"><img src="../assets/UOWM-logo.png" alt="UOWM-logo"  height = "58px"></a>';
	echo			'<br>&nbsp;&nbsp;&nbsp;<font color="white">'.$dname.'</font>';
	echo          '</div>';

	echo	'<div class="navbar-collapse collapse">';
	echo		'<ul class="nav navbar-nav navbar-right">';
	echo 			"<li><a href='index.php'><font color='white'>Κεντρική Σελίδα</font></a></li>";	
	echo 			"<li><a href='department_index.php?id=".$id."#home'><font color='white'>Αρχική Τμήματος</font></a></li>";
	echo 			"<li><a href='department_index.php?id=".$id."#about'><font color='white'>Σχετικά</font></a></li>";
	echo 			"<li><a href='department_index.php?id=".$id."#action'><font color='white'>Ανακοινώσεις</font></a></li>";
	echo 			"<li><a href='department_index.php?id=".$id."#stories'><font color='white'>Ιστορίες</font></a></li>";
	echo 			"<li><a href='student_search_guest.php?id=".$id."'><font color='white'>Αναζήτηση Αποφοίτου</font></a></li>";
	
	if((isset($_SESSION['name'])) || (isset($_SESSION['student']))) { // αν ο χρήστης ειναι συνδεδεμένος
		// Δεν θα πρεπει να του δινεται η δυνατοτητα να κάνει εγγραφή ξανα
	}
	else
	{
		echo 	"<li><a href='sign_in.php?id=".$id."'><font color='white'>Εγγραφή</font></a></li>";
	}

	if((isset($_SESSION['name'])) )  // αν ο χρήστης ειναι συνδεδεμένος
	{
		$userid = $_SESSION['name'];
		$statement = $conn->prepare("SELECT name as user_name FROM users WHERE id = ?");
		$statement->execute(array($userid));
		$name = $statement->fetch(PDO::FETCH_OBJ);
		$user =  $name->user_name;
		echo	'<li><a href="../admin/admin_index.php"><font color="white">'.$user.' <span class="glyphicon glyphicon-user" style="color:white"></span></font></a></li>';
	}
	else if((isset($_SESSION['student'])) )
	{
		$userid = $_SESSION['student'];
		$statement = $conn->prepare("SELECT name as user_name FROM users WHERE id = ?");
		$statement->execute(array($userid));
		$name = $statement->fetch(PDO::FETCH_OBJ);
		$user =  $name->user_name;
		echo	'<li><a href="../admin/admin_index.php"><font color="white">'.$user.'<span class="glyphicon glyphicon-user" style="color:white"></span></font></a></li>';
	}
	echo	'<li>&nbsp;&nbsp;</li>';

	echo	"</ul>";
	echo	"</div>";

	echo	"</div>";		

}
?>