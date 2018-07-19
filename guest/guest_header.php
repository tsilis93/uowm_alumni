<?php	
session_start();
include ("../connectPDO.php");

echo   '<div class="navbar navbar-default navbar-fixed-top" role="navigation">';

	echo      '<div class="navbar-header">';
	echo            '<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">';
	echo              '<span class="sr-only">Toggle navigation</span>';
	echo              '<span class="icon-bar"></span>';
	echo              '<span class="icon-bar"></span>';
	echo              '<span class="icon-bar"></span>';
	echo            '</button>';
	echo			'<a href ="index.php"><img src="../assets/UOWM-logo.png" alt="UOWM-logo"  height = "58px"></a>';
	echo  	'</div>';

$stmt = $conn->prepare("SELECT * FROM faculties");
$stmt->execute();
$result = $stmt->fetchAll();
$i = 0;
$faculties = sizeof($result);
					
$stmt = $conn->prepare("SELECT * FROM departments");
$stmt->execute();
$result2 = $stmt->fetchAll();
  
	echo	'<div class="navbar-collapse collapse">';
	echo		'<ul class="nav navbar-nav navbar-right">';

		echo         '<li class="dropdown">';
		echo		 '<a href="#" data-toggle="dropdown" class="dropdown-toggle"><font color="white">Σχολές & Τμήματα</font><span class="caret"></span></a>';
		echo            '<ul class="dropdown-menu">';
						  foreach($result as $row) {
							$i = $i + 1;  
							echo '<li style="color:black;" class="dropdown-header"><b>'.$row['facultyname'].'</b></li>';
							foreach ($result2 as $row2) {
								if($row['id'] == $row2['facultyid'] ) {
									echo '<li><a href="department_index.php?id='.$row2['id'].'"><font color="black">'.$row2['dname'].'</font></a></li>';
								}
							}
							if($i == $faculties) {
								
							}
							else
							{
								echo '<li class="divider"></li>';
							}
						  }
		echo			'</ul>';
		echo		'</li>';
		
		echo		'<li><a href="index.php#home"><font color="white">Αρχική</font></a></li>';
		echo		'<li><a href="index.php#about"><font color="white">Σχετικά</font></a></li>';
		echo		'<li><a href="index.php#action"><font color="white">Δράσεις - Ανακοινώσεις</font></a></li>';
		echo		'<li><a href="index.php#communicate"><font color="white">Επικοινωνία</font></a></li>';
		
		echo		'<li><a href="https://career.uowm.gr/erotimatologio-apofiton-panepistimiou-ditikis-makedonias/" target="_blank"><font color="white">Ερωτηματολόγιο</font></a></li>';	

		
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
			echo	'<li><a href="../alumni/alumni_index.php"><font color="white">'.$user.'<span class="glyphicon glyphicon-user" style="color:white"></span></font></a></li>';
		}
		else
		{
			echo		'<li><a href="../register_login_form.php"><font color="white">Σύνδεση-Εγγραφή</font></a></li>';	
		}

		echo		'<li><a href="all_students.php"><font color="white"><span class="glyphicon glyphicon-search" style="color:white"></span></font></a></li>';
		echo	'<li>&nbsp;&nbsp;</li>';	
	echo		'</ul>';

	echo    '</div>';
	
echo  '</div>';						

?>	