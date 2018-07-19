<?php	
session_start();
include ("../connectPDO.php");

if((isset($_SESSION['name'])) )  // αν ο χρήστης ειναι συνδεδεμένος
{
	$userid = $_SESSION['name'];
	
	$statement = $conn->prepare("SELECT * FROM users WHERE id = ?");
	$statement->execute(array($userid));
	$result = $statement->fetchAll();
	
	foreach($result as $row) {
		$user =  $row['name'];
		$urole = $row['role'];
	}
}

echo   '<div class="navbar navbar-default navbar-fixed-top" role="navigation">';

	echo      '<div class="navbar-header">';
	echo            '<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">';
	echo              '<span class="sr-only">Toggle navigation</span>';
	echo              '<span class="icon-bar"></span>';
	echo              '<span class="icon-bar"></span>';
	echo              '<span class="icon-bar"></span>';
	echo            '</button>';
	echo			'<a href ="../guest/index.php"><img src="../assets/UOWM-logo.png" alt="UOWM-logo"  height = "58px"></a>';
	echo  	'</div>';  
	
	echo	'<div class="navbar-collapse collapse">';
	echo		'<ul class="nav navbar-nav navbar-right">';

//echo			'<li><a href="admin_index.php"><span class="glyphicon glyphicon-home" style="color:white; font-size: 18px;"></span></a></li>';

echo			'<li class="dropdown">';
echo		 		'<a href="#" data-toggle="dropdown" class="dropdown-toggle"><span class="glyphicon glyphicon-home" style="color:white; font-size: 18px;"></span><span class="caret"></span></a>';
echo				'<ul class="dropdown-menu">';
echo					'<li><a href="admin_index.php"><font color="black">Αρχική Διαχειριστή</font></a></li>';
echo 					'<li class="divider"></li>';
echo					'<li><a href="../"><font color="black">Κεντρική Σελίδα</font></a></li>';
echo				"</ul>";
echo			"</li>";
			
echo			'<li class="dropdown">';
echo		 		'<a href="#" data-toggle="dropdown" class="dropdown-toggle"><font color="white">Επικοινωνία</font><span class="caret"></span></a>';
echo				'<ul class="dropdown-menu">';
echo					'<li><a href="pending_alumni.php"><font color="black">Λογαριασμοί Χρηστών σε εκκρεμότητα</font></a></li>';
echo 					'<li class="divider"></li>';
echo					'<li><a href="new_sms.php"><font color="black">Δημιουργία SMS</font></a></li>';
echo 					'<li class="divider"></li>';
echo					'<li><a href="new_newsletter.php"><font color="black">Διαχείριση Newsletter</font></a></li>';
echo				"</ul>";
echo			"</li>";
			
			
echo			'<li class="dropdown">';
echo				'<a href="#" class="dropdown-toggle" data-toggle="dropdown"><font color="white">Περιεχόμενο<br>Ιστοχώρου</font><span class="caret"></span></a>';
echo				'<ul class="dropdown-menu">';
echo 					'<li style="color:black; text-align:center;" class="dropdown-header"><b>ΙΣΤΟΡΙΕΣ</b></li>';
echo 					'<li class="divider"></li>';
echo					'<li><a href="create_story_admin.php"><font color="black">Δημιουργία Ιστορίας</font></a></li>';	
echo 					'<li class="divider"></li>';
echo					'<li><a href="admin_story_approval.php"><font color="black">Ιστορίες προς Εγκρισης</font></a></li>';
echo 					'<li class="divider"></li>';
echo					'<li><a href="admin_story_process.php"><font color="black">Δημοσιευμένες Ιστορίες</font></a></li>';
echo 					'<li class="divider"></li>';
echo					'<li><a href="admin_story_rejected.php"><font color="black">Μη Δημοσιευμένες Ιστορίες</font></a></li>';				
echo 					'<li class="divider"></li>';
echo 					'<li style="color:black; text-align:center;" class="dropdown-header"><b>ΑΝΑΚΟΙΝΩΣΕΙΣ</b></li>';
echo 					'<li class="divider"></li>';
echo					'<li><a href="create_content_admin.php"><font color="black">Δημιουργία Ανακοίνωσης</font></a></li>';
echo 					'<li class="divider"></li>';
echo					'<li><a href="admin_content_approval.php"><font color="black">Ανακοίνωση προς Εγκρισης</font></a></li>';
echo 					'<li class="divider"></li>';
echo					'<li><a href="admin_content_process.php"><font color="black">Δημοσιευμένες Ανακοινώσεις</font></a></li>';
echo 					'<li class="divider"></li>';
echo					'<li><a href="admin_content_rejected.php"><font color="black">Μη Δημοσιευμένες Ανακοινώσεις</font></a></li>';
echo				"</ul>";
echo			"</li>";
			
echo			'<li class="dropdown">';
echo				'<a href="#" class="dropdown-toggle" data-toggle="dropdown"><font color="white">Διαχείριση Χρηστών</font><span class="caret"></span></a>';
echo				'<ul class="dropdown-menu">';
echo					'<li><a href="create_alumni.php"><font color="black">Δημιουργία Χρηστών</font></a></li>';
echo 					'<li class="divider"></li>';
echo					'<li><a href="pending_alumni_registry.php"><font color="black">Εκκρεμείς Εγγραφές Χρηστών</font></a></li>';
if($urole == 2) {	
echo 					'<li class="divider"></li>';
echo					'<li><a href="change_admin_username_password.php"><font color="black">ADMIN (Αλλαγή Username - Password)</font></a></li>';	
}
echo				"</ul>";
echo			"</li>";
			
			
echo			'<li><a href="admin_alumni_search.php"><font color="white">Αναζήτηση<br>Απόφοιτου</font></a></li>';
			
echo			'<li><a href="statistics.php"><font color="white">Στατιστικά</font></a></li>';
			
echo			'<li><a href="view_notifications.php"><font color="white">Ειδοποιήσεις</font><img id="notification" src="../assets/white.png" width="15" height="15"/></a></li>';		
	
echo			'<li class="dropdown">';
echo				'<a href="#" class="dropdown-toggle" data-toggle="dropdown"><img src="../assets/more.png" height="30" width="30"><span class="caret"></span></a>';
echo				'<ul class="dropdown-menu">';
echo					'<li><a href="new_department.php"><font color="black">Δημιουργία Νέου Τμήματος</font></a></li>';
echo 					'<li class="divider"></li>';
echo					'<li><a href="new_admin.php"><font color="black">Δημιουργία Διαχειριστή</font></a></li>';
echo				"</ul>";
echo			"</li>";
			


if((isset($_SESSION['name'])) )  // αν ο χρήστης ειναι συνδεδεμένος
{
	echo	'<li><a href="../admin_logout.php"><font color="white">Αποσύνδεση<br>'.$user.' </font></a></li>';
}
else
{
	echo	'<li><a href="../register_login_form.php"><font color="white">Σύνδεση-Εγγραφή</font></a></li>';
}

echo		"</ul>";
echo	"</div>";
	
echo  "</div>";
