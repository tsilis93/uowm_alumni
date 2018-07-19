<?php	
session_start();
include ("../connectPDO.php");

echo      '<div class="navbar navbar-default navbar-fixed-top" role="navigation">';
//echo		'<div class="container">';
echo          '<div class="navbar-header">';
echo            '<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">';
echo              '<span class="sr-only">Toggle navigation</span>';
echo              '<span class="icon-bar"></span>';
echo              '<span class="icon-bar"></span>';
echo              '<span class="icon-bar"></span>';
echo            '</button>';
echo			'<a href ="../guest/index.php"><img src="../assets/UOWM-logo.png" alt="UOWM-logo"  height = "58px"></a>';
echo          '</div>';
	
echo	'<div class="navbar-collapse collapse">';
echo		'<ul class="nav navbar-nav navbar-right">';

//echo			'<li><a href="alumni_index.php"><font color="white">Αρχική</font></a></li>';
echo			'<li class="dropdown">';
echo		 		'<a href="#" data-toggle="dropdown" class="dropdown-toggle"><span class="glyphicon glyphicon-home" style="color:white; font-size: 18px;"></span><span class="caret"></span></a>';
echo				'<ul class="dropdown-menu">';
echo					'<li><a href="alumni_index.php"><font color="black">Αρχική Απόφοιτου</font></a></li>';
echo 					'<li class="divider"></li>';
echo					'<li><a href="../"><font color="black">Κεντρική Σελίδα</font></a></li>';
echo				"</ul>";
echo			"</li>";

echo			'<li><a href="alumni_change_password.php"><font color="white">Αλλαγή Κωδικού<br>Πρόσβασης</font></a></li>';

echo			'<li class="dropdown">';
echo				'<a href="#" class="dropdown-toggle" data-toggle="dropdown"><font color="white">Περιεχόμενο<br>Ιστοχώρου</font><span class="caret"></span></a>';
echo				'<ul class="dropdown-menu">';
echo					'<li><a href="alumni_cv_upload.php"><font color="black">Ανέβασμα Βιογραφικού</font></a></li>';
echo 					'<li class="divider"></li>';
echo 					'<li style="color:black; text-align:center;" class="dropdown-header"><b>ΙΣΤΟΡΙΕΣ</b></li>';
echo 					'<li class="divider"></li>';
echo					'<li><a href="create_story_alumni.php"><font color="black">Δημιουργία Ιστορίας</font></a></li>';	
echo 					'<li class="divider"></li>';
echo					'<li><a href="alumni_story_process.php"><font color="black">Οι Ιστορίες μου</font></a></li>';
echo 					'<li class="divider"></li>';
echo 					'<li style="color:black; text-align:center;" class="dropdown-header"><b>ΑΝΑΚΟΙΝΩΣΕΙΣ</b></li>';
echo 					'<li class="divider"></li>';
echo					'<li><a href="create_content_alumni.php"><font color="black">Δημιουργία Ανακοίνωσης</font></a></li>';
echo 					'<li class="divider"></li>';
echo					'<li><a href="alumni_content_process.php"><font color="black">Οι Ανακοινώσεις μου</font></a></li>';
echo				"</ul>";
echo			"</li>";						

echo			'<li class="dropdown">';
echo				'<a href="#" class="dropdown-toggle" data-toggle="dropdown"><font color="white">Αναζήτηση Απόφοιτου</font><span class="caret"></span></a>';
echo				'<ul class="dropdown-menu">';
echo					'<li><a href="myYearSearch.php"><font color="black">Το Ετος μου</font></a></li>';
echo 					'<li class="divider"></li>';
echo					'<li><a href="graduateSearch.php"><font color="black">Ίδιο Έτος Αποφοίτησης</font></a></li>';
echo 					'<li class="divider"></li>';
echo					'<li><a href="myFriends.php"><font color="black">Οι Απόφοιτοι που Ακολουθώ</font></a></li>';
echo 					'<li class="divider"></li>';
echo					'<li><a href="alumni_search.php"><font color="black">Προσαρμοσμένη Αναζήτηση</font></a></li>';
echo				"</ul>";
echo			"</li>";
						
echo			'<li><a href="newsletter.php"><font color="white">Newsletter</font></a></li>';
			
echo			'<li><a href="view_notifications.php"><font color="white">Ειδοποιήσεις</font><img id="notification" src="../assets/white.png" width="15" height="15"/></a></li>';		

if((isset($_SESSION['student'])) )  // αν ο χρήστης ειναι συνδεδεμένος
{
	$userid = $_SESSION['student'];
	$statement = $conn->prepare("SELECT name as user_name FROM users WHERE id = ?");
	$statement->execute(array($userid));
	$name = $statement->fetch(PDO::FETCH_OBJ);
	$user =  $name->user_name;
	echo	'<li><a href="../alumni_logout.php"><font color="white">Αποσύνδεση<br>'.$user.' </font></a></li>';
}
else
{
	echo	'<li><a href="../register_login_form.php"><font color="white">Σύνδεση</font></a></li>';
}	
			
echo		"</ul>";
echo	"</div>";
	
echo  "</div>";
