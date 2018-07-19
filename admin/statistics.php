<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>UOWM Alumni</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  
  <link rel="stylesheet" href="../css/admin.css">
  
  <link rel="stylesheet" type="text/css" href="../phones_specs/css/dialog-mobile.css"/> <!-- css & javascript για τις ειδοποιήσεις (alerts) στα κινητα -->
  <script type="text/javascript" src="../phones_specs/mcx-dialog.js"></script>  
  
</head>
<body>

<header>

</header>

<br><br><br><br>

<?php	

session_start();
include ("../connectPDO.php");
include ("../getPublicationId.php");

if(isset($_SESSION['name'])) {
	$admin_id = $_SESSION['name'];
}
else
{
header('Location: ../register_login_form.php');
}

$stmt = $conn->prepare("SELECT * FROM users WHERE role != 2");  
$stmt->execute();
$result = $stmt->fetchAll();
			
$total_website_sum = sizeof($result); //παρε τον αριθμό των εγγεγραμμένων αποφοίτων
/*------------------------------------------*/

$category_option_table = array(); //πινακας με τα id κατηγοριων
$category_name_table = array(); //πινακας ονοματων κατηγοριων
$counter = 0;

$stmt = $conn->prepare("SELECT * FROM newsletter_categories");	/* παρε τον αριθμό των κατηγοριων και τα ονόματα τους */
$stmt->execute();
$result = $stmt->fetchAll();

foreach($result as $row) {
	$option = "option_id".$row['id'];
	$name = $row['category_name'];	
	$category_option_table[$counter] = $option;	//τα id των κατηγοριων με το πρόθεμα "option_id" για καλυτερη εξυπηρετηση μας στην ευρεση πληροφοριων
	$category_name_table[$counter] = $name; //τα ονοματα αποθηκευονται σε πινακα
	$counter = $counter + 1;
}
$sizeofCategoryOptionTable = count($category_option_table);	//ο αριθμός των κατηγοριων	

$newsletter_alumni_id_table = array(); // πίνακας που αποθηκευονται τα id των αποφοίτων που έχουν επιλέξει να δεχονται έστω 1 newsletter
$counter2 = 0;

echo '<div align="center">';     
echo	'<h2>Newsletter Statistics</h2>';
echo '</div>';

echo '<div class="container">';
echo '<form id = "form" name = "form">';
echo	'<br>';
echo	'<table id = "myTable" class = "table" width="100%">';
echo		'<thead>';
echo			'<tr>';
echo				'<th>Εγγεγραμμένοι απόφοιτοι που έχουν επιλέξει να δέχονται newsletter</th>';
echo				'<th>Αριθμός</th>';
echo				'<th>Ποσοστό</th>';
echo			'</tr>';
echo		'</thead>';	
echo		'<tbody>';

		$query = "SELECT * FROM newsletter WHERE ";		//υπολογίζεται ο συνολικός αριθμός των αποφοίτων που δεχονται να λαμβάνουν γενικα newsletter
		for($i=0; $i<$sizeofCategoryOptionTable; $i++) {
			if($i == $sizeofCategoryOptionTable-1) {
				$query = $query . $category_option_table[$i]." != 0";
			}
			else
			{
				$query = $query . $category_option_table[$i]." != 0 OR ";
			}
		}
		$stmt = $conn->prepare($query);
		$stmt->execute();
		$result = $stmt->fetchAll();
			
		$sumAlumni = sizeof($result);
		
		if(sizeof($result)>0) {
			foreach($result as $row) {	//παίρνω τα id από τους απόφοιτους που δεχονται newsletter
				$newsletter_alumni_id_table[$counter2] = $row['alumni_id'];
				$counter2 = $counter2 + 1;
			}
		}
		
		$table = array(); //πίνακα υπολογισμού του αριθμού των αποφοίτων που δεχονται newsletter ανα τμήμα
		$departments_name = array(); // πίνακας με τα ονοματα των τμημάτων
		$dnamecounter = 1; //μετρητής τμημάτων
		$stmt = $conn->prepare("SELECT * FROM departments");
		$stmt->execute();
		$result = $stmt->fetchAll();	//αρχικοποιήση του πίνακα υπολογισμού του αριθμού των αποφοίτων που δεχονται newsletter ανα τμήμα
		for($i=1; $i<=sizeof($result); $i++) {
			$table[$i] = 0;
		}
		foreach($result as $row) {
			$dname = $row['dname'];
			$departments_name[$dnamecounter] = $dname;
			$dnamecounter = $dnamecounter + 1;
		}
		for($i=0; $i<count($newsletter_alumni_id_table); $i++) { //για όλους τους αποφοίτους που θέλουν να λαμβάνουν newsletter
			$stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
			$stmt->execute(array($newsletter_alumni_id_table[$i]));
			$result = $stmt->fetchAll();
			foreach($result as $row) {
				$did = $row['department_id']; //παρε το τμήμα που αποφοίτησαν
				$table[$did] = $table[$did] + 1; // και αυξησε το αντίστοιχο αθροισμα κατα 1
			}
		}
		
		for($i=1; $i<=count($table); $i++) {  //για όλα τα τμήματα εμφάνισε τα αποτελέσματα
			echo	'<tr>';
			echo		'<td width="80%">'.$departments_name[$i].'</td>';
			echo		'<td width="10%"><input value="'.$table[$i].'" class = "statistics" disabled="disabled"></td>';
			$rate = $table[$i]/$total_website_sum * 100;
			echo		'<td width="10%"><input value="'.$rate.'%" class = "statistics" disabled="disabled"></td>';
			echo	'</tr>';
		}
		echo	'<tr>';
		echo		'<td width="80%">Συνολικός αριθμός</td>'; //εμφάνισε τον συνολικό αριθμό
		echo		'<td width="10%"><input value="'.$sumAlumni.'" class = "statistics" disabled="disabled"></td>';
		$total_rate = $sumAlumni / $total_website_sum * 100;
		echo		'<td width="10%"><input value="'.$total_rate.'%" class = "statistics" disabled="disabled"></td>';
		echo	'</tr>';
				
	
echo	'</tbody>';
echo	'<thead>';
echo		'<tr>';
echo			'<th colspan="3">Αριθμός αποφοίτων που έχουν επιλέξει να δέχονται newsletter ανα κατηγορία</th>';
echo		'</tr>';
echo	'</thead>';
echo	'<tbody>';
	
		for($i=0; $i<$sizeofCategoryOptionTable; $i++) {	//υπολογίζεται ο συνολικός αριθμός των αποφοίτων που δεχονται να λαμβάνουν newsletter ανα κατηγορία
			$stmt = $conn->prepare("SELECT * FROM newsletter WHERE ". $category_option_table[$i]." != 0");
			$stmt->execute();
			$result = $stmt->fetchAll();
							
			$alumniPerCategory = sizeof($result);			
		
		echo	'<tr>';
		echo		'<td colspan="2" width="90%">'.$category_name_table[$i].'</td>';
		echo		'<td width="10%"><input value="'.$alumniPerCategory.'" class = "statistics" disabled="disabled"></td>';
		echo	'</tr>';
		}

echo	'</tbody>';
echo '</table>';
echo '</form>';
echo '</div>';
echo '<br><br>';
echo '<div align="center">';     
echo	'<h2>General Statistics</h2>';
echo '</div>';
echo '<div class="container">';
echo '<form id = "form" name = "form">';
echo	'<br>';
echo	'<table id = "myTable" class = "table" width="100%">';
echo		'<thead>';
echo			'<tr>';
echo				'<th>Εγγεγραμμένοι απόφοιτοι:</th>';
echo				'<th>Αριθμός</th>';
echo				'<th>Ποσοστό</th>';
echo			'</tr>';
echo		'</thead>';		
echo		'<tbody>';

		$stmt = $conn->prepare("SELECT * FROM users WHERE role != 2");	//για όλους τους χρήστες
		$stmt->execute();
		$result = $stmt->fetchAll();
			
		$total_website_sum = sizeof($result); //ξαναυπολόγισε τον αριθμό των εγγεγραμμένων
			
		$stmt = $conn->prepare("SELECT * FROM departments"); // για όλα τα τμήματα
		$stmt->execute();
		$result = $stmt->fetchAll();
			
		if(sizeof($result)>0) {
			foreach ($result as $row) {
				$id = $row['id'];
				$dname = $row['dname'];
					
				$stmt2 = $conn->prepare("SELECT * FROM users WHERE department_id = ?");  //μέτρησε ποσοι απόφοιτοι ανήκουν στο τμήμα $id
				$stmt2->execute(array($id));
				$result2 = $stmt2->fetchAll();

				if(sizeof($result2)>0) {
					$AlumniPerDepartment = sizeof($result2);
				}
				else
				{
					$AlumniPerDepartment = 0;
				}
				echo	'<tr>';		//εμφάνισε αποτελέσματα
				echo		'<td width="80%">'.$dname.'</td>';
				echo		'<td width="10%"><input value="'.$AlumniPerDepartment.'" class = "statistics" disabled="disabled"></td>';
				$rate = $AlumniPerDepartment / $total_website_sum * 100;
				echo		'<td width="10%"><input value="'.$rate.'%" class = "statistics" disabled="disabled"></td>';
				echo	'</tr>';
			}		
		}
		echo	'<tr>';			//εμφάνισε συνολικα αποτελέσματα
		echo		'<td width="80%">Συνολικός αριθμός</td>';
		echo		'<td width="10%"><input value="'.$total_website_sum.'" class = "statistics" disabled="disabled"></td>';
		$rate = $total_website_sum / $total_website_sum * 100;
		echo		'<td width="10%"><input value="'.$rate.'%" class = "statistics" disabled="disabled"></td>';
		echo	'</tr>';


echo	'</tbody>';
echo '</table>';
echo '</form>';
echo '</div>';

 //ΔΙΑΔΙΚΑΣΙΑ ΠΟΥ ΑΦΟΡΑ ΤΙΣ ΠΛΗΡΟΦΟΡΙΕΣ ΓΙΑ ΤΙΣ ΣΕΛΙΔΕΣ ΤΩΝ ΤΜΗΜΑΤΩΝ
$stories_per_department = array(); //πινακας που αποθηκευονται ο αριθμός των ιστοριων ανα τμήμα
$content_per_department = array(); //πινακας που αποθηκευονται ο αριθμός των ανακοινωσεων ανα τμήμα
$lastDate_content_per_department = array(); //πίνακας που αποθηκευονται οι ημερομηνίες των τελευταίων καταχωρήσεων που αφορούν ανακοινώσεις
$lastDate_story_per_department = array(); //πίνακας που αποθηκευονται οι ημερομηνίες των τελευταίων καταχωρήσεων που αφορούν ιστορίες
$alumni_number_story_perDepartment = array(); //πινακας που αποθηκευεται ο αριθμός των ιστοριων που ανήκουν σε αποφοίτους ανα τμήμα
$admin_number_story_perDepartment = array(); //πινακας που αποθηκευεται ο αριθμός των ιστοριων που ανήκουν σε διαχειριστές ανα τμήμα
$alumni_number_content_perDepartment = array(); //πινακας που αποθηκευεται ο αριθμός των ανακοινωσεων που ανήκουν σε αποφοίτους ανα τμήμα
$admin_number_content_perDepartment = array(); //πινακας που αποθηκευεται ο αριθμός των ανακοινωσεων που ανήκουν σε διαχειριστές ανα τμήμα

$stmt = $conn->prepare("SELECT * FROM departments");
$stmt->execute();
$result = $stmt->fetchAll();
			
if(sizeof($result)>0) {
	foreach ($result as $row) {	//για κάθε τμήμα
		$id = $row['id'];
		$published = "published_department".$id; //παιρνουμε το id σε μία ευνοϊκή μορφη
		
		
		$stmt2 = $conn->prepare("SELECT * FROM stories WHERE ".$published." = 1 AND status = 1");
		$stmt2->execute();
		$result2 = $stmt2->fetchAll();
		
		$stories_per_department[$id] = sizeof($result2); // παιρνουμε τον αριθμό των ιστοριων που αφορούν το τμήμα $id

		if(sizeof($result2)>0) { // αν είναι μεγαλύτερος του 0
			$pid = getStoryMaxPublicationidPerDepartment($id); //παρε το μεγαλύτερο publication_id από τις δημοσιευμένες ιστορίες για το τμήμα $id
			$statement = $conn->prepare("SELECT publication_date as pdate FROM stories WHERE publication_id = ? AND ".$published." = 1 AND status = 1");
			$statement->execute(array($pid));
			$max = $statement->fetch(PDO::FETCH_OBJ);	//(όσο μεγαλύτερο το publication_id τόσο πιο νέα είναι η ιστορία)
			$date = $max->pdate;
			$lastDate_story_per_department[$id] = $date;  //παρε την ημερομηνία δημοσιευσης
				

			$count_alumni_counter = 0; //μετρητής για να υπολογίσουμε ποσες ιστορίες ανήκουν σε αποφοίτους
			$count_admin_counter = 0; //μετρητής για να υπολογίσουμε ποσες ιστορίες ανήκουν σε διαχειριστές			
			foreach($result2 as $row2) {
				$userID = $row2['userID'];
										
				$stmt4 = $conn->prepare("SELECT role as user_role FROM users WHERE id = ?");
				$stmt4->execute(array($userID));	
				$result4 = $stmt4->fetch(PDO::FETCH_OBJ);
				$role = $result4->user_role;	//για το userID της εκάστοτε ιστορίας παρε τον ρόλο του χρήστη
				
				if($role == 1) {	//αν ο ρολος αποφοιτος 
					$count_alumni_counter = $count_alumni_counter + 1;
				}
				else
				{	//αν ο ρολος διαχειριστής (περιλαμβάνει διαχειριστή-αποφοιτο)
					$count_admin_counter = $count_admin_counter + 1;					
				}
			}
			$alumni_number_story_perDepartment[$id] = $count_alumni_counter;	//αποθηκευση στην αντίστοιχη θέση
			$admin_number_story_perDepartment[$id] = $count_admin_counter;

		}
		else //αν δεν υπάρχουν ιστορίες
		{
			$lastDate_story_per_department[$id] = null; //στην ημερομηνία βαλε null
			$alumni_number_story_perDepartment[$id] = 0; //στο πληθος των αποφοίτων βαλε 0
			$admin_number_story_perDepartment[$id] = 0; //στο πληθος των διαχειριστων βαλε 0
		}		
		
		$stmt2 = $conn->prepare("SELECT * FROM contents WHERE ".$published." = 1 AND status = 1");
		$stmt2->execute();
		$result2 = $stmt2->fetchAll();
		
		$content_per_department[$id] = sizeof($result2); // παιρνουμε τον αριθμό των ανακοινωσεων που αφορούν το τμήμα $id
		
		if(sizeof($result2)>0) { // αν είναι μεγαλύτερος του 0
			$pid = getContentMaxPublicationidPerDepartment($id); //παρε το μεγαλύτερο publication_id από τις δημοσιευμένες ανακοινώσεις για το τμήμα $id
			$statement = $conn->prepare("SELECT publication_date as pdate FROM contents WHERE publication_id = ? AND ".$published." = 1 AND status = 1");
			$statement->execute(array($pid));
			$max = $statement->fetch(PDO::FETCH_OBJ);	//(όσο μεγαλύτερο το publication_id τόσο πιο νέα είναι η ανακοίνωση)
			$date = $max->pdate;
			$lastDate_content_per_department[$id] = $date; //παρε την ημερομηνία δημοσιευσης


			$count_alumni_counter = 0; //μετρητής για να υπολογίσουμε ποσες ανακοινώσεις ανήκουν σε αποφοίτους
			$count_admin_counter = 0; //μετρητής για να υπολογίσουμε ποσες ανακοινώσεις ανήκουν σε διαχειριστέ
			foreach($result2 as $row2) {
				$userID = $row2['userID'];
				
				$stmt4 = $conn->prepare("SELECT role as user_role FROM users WHERE id = ?");
				$stmt4->execute(array($userID));
				$result4 = $stmt4->fetch(PDO::FETCH_OBJ); 
				$role = $result4->user_role; //για το userID της εκάστοτε ανακοίνωσης παρε τον ρόλο του χρήστη
				
				if($role == 1) { //αν ο ρολος αποφοιτος
					$count_alumni_counter = $count_alumni_counter + 1;
				}
				else
				{	//αν ο ρολος διαχειριστής (περιλαμβάνει διαχειριστή-αποφοιτο)
					$count_admin_counter = $count_admin_counter + 1;					
				}
			}
			$alumni_number_content_perDepartment[$id] = $count_alumni_counter;	//αποθηκευση στην αντίστοιχη θέση
			$admin_number_content_perDepartment[$id] = $count_admin_counter;
		}
		else
		{
			$lastDate_content_per_department[$id] = null;	//στην ημερομηνία βαλε null
			$alumni_number_content_perDepartment[$id] = 0;	//στο πληθος των αποφοίτων βαλε 0
			$admin_number_content_perDepartment[$id] = 0;	//στο πληθος των διαχειριστων βαλε 0
		}
	}
}


echo '<br><br>';
echo '<div align="center">';     
echo	'<h2>Content Statistics</h2>';
echo '</div>';
echo '<div class="construct2">';
echo 	'<br><p align="center" id="login"><span class="label label-info"><b>INFO </span>  Οι χρήστες που είναι και διαχειριστές και απόφοιτοι θεωρούνται διαχειριστές στα στατιστικά.</b> </p>';

echo	'<form id = "form" name = "form">';
echo		'<br>';
echo		'<table id = "myTable44" class = "table" width="100%">';
echo			'<thead>';
echo				'<tr>';
echo					'<th></th>';
echo					'<th style="text-align:center" colspan="4">ΙΣΤΟΡΙΕΣ</th>';
echo					'<th style="text-align:center" colspan="4">ΑΝΑΚΟΙΝΩΣΕΙΣ</th>';
echo				'</tr>';
echo				'<tr>';
echo					'<th style="text-align:center">Τμήματα</th>';
echo					'<th style="text-align:center">Αριθμός</th>';
echo					'<th style="text-align:center">Αριθμός από Απόφοιτους</th>';
echo					'<th style="text-align:center">Αριθμός από Διαχειριστές</th>';
echo					'<th style="text-align:center">Ημερομηνία προσφατης ιστορίας</th>';
echo					'<th style="text-align:center">Αριθμός</th>';
echo					'<th style="text-align:center">Αριθμός από Απόφοιτους</th>';
echo					'<th style="text-align:center">Αριθμός από Διαχειριστές</th>';
echo					'<th style="text-align:center">Ημερομηνία προσφατης ανακοίνωσης</th>';
echo				'</tr>';
echo			'</thead>';		
echo			'<tbody>';
			
		$stmt = $conn->prepare("SELECT * FROM departments");
		$stmt->execute();
		$result = $stmt->fetchAll();
			
		if(sizeof($result)>0) {
			foreach ($result as $row) {
				$id = $row['id'];
				$dname = $row['dname'];
					

				echo	'<tr>';
				echo		'<td width="30%">'.$dname.'</td>';
				echo		'<td width="9%" style="text-align:center"><input value="'.$stories_per_department[$id].'" class = "statistics" disabled="disabled"></td>';
				echo		'<td width="9%" style="text-align:center"><input value="'.$alumni_number_story_perDepartment[$id].'" class = "statistics" disabled="disabled"></td>';
				echo		'<td width="9%" style="text-align:center"><input value="'.$admin_number_story_perDepartment[$id].'" class = "statistics" disabled="disabled"></td>';				
				$date = $lastDate_story_per_department[$id];
				if($date != null) {
					$date = new DateTime($date);
					$date = $date->format("d-m-Y");
				}
				echo		'<td width="9%" style="text-align:center">'.$date.'</td>';
				echo		'<td width="9%" style="text-align:center"><input value="'.$content_per_department[$id].'" class = "statistics" disabled="disabled"></td>';
				echo		'<td width="9%" style="text-align:center"><input value="'.$alumni_number_content_perDepartment[$id].'" class = "statistics" disabled="disabled"></td>';
				echo		'<td width="9%" style="text-align:center"><input value="'.$admin_number_content_perDepartment[$id].'" class = "statistics" disabled="disabled"></td>';				
				$date = $lastDate_content_per_department[$id];
				if($date != null) {
					$date = new DateTime($date);
					$date = $date->format("d-m-Y");
				}
				echo		'<td width="9%" style="text-align:center">'.$date.'</td>';
				echo	'</tr>';
			}		
		}
		//ΔΙΑΔΙΚΑΣΙΑ ΠΟΥ ΑΦΟΡΑ ΤΙΣ ΠΛΗΡΟΦΟΡΙΕΣ ΓΙΑ ΤΗΝ ΚΕΝΤΡΙΚΗ ΣΕΛΙΔΑ
		// ΕΠΑΝΑΛΑΜΒΆΝΟΥΜΕ ΤΗΝ ΔΙΑΔΙΚΑΣΙΑ ΠΟΥ ΠΡΑΓΜΑΤΟΠΟΙΗΣΑΜΕ ΓΙΑ ΤΑ ΤΜΗΜΑΤΑ ΑΛΛΑ ΤΡΟΠΟΠΟΙΟΥΜΕ THN ΠΑΡΑΜΕΤΡΟ (ID)
		$stmt = $conn->prepare("SELECT * FROM contents WHERE published_index_page = 1 AND status = 1");
		$stmt->execute();
		$result = $stmt->fetchAll();
		
		$index_content = sizeof($result);
		if(sizeof($result)>0) {
			$pid = getContentMaxPublicationidIndexPage();	//αντι για το id του τμήματος χρησιμοποιούμε την μεταβλητη που απευθυνεται σην κεντρικη σελιδα 
			$statement = $conn->prepare("SELECT publication_date as pdate FROM contents WHERE publication_id = ? AND published_index_page = 1 AND status = 1");
			$statement->execute(array($pid));
			$max = $statement->fetch(PDO::FETCH_OBJ);
			$date = $max->pdate;
			$date = new DateTime($date);
			$date = $date->format("d-m-Y");
			
			$count_alumni_counter = 0;
			$count_admin_counter = 0;
			foreach($result as $row) {
				$userID = $row['userID'];
				
				$stmt4 = $conn->prepare("SELECT role as user_role FROM users WHERE id = ?");
				$stmt4->execute(array($userID));
				$result4 = $stmt4->fetch(PDO::FETCH_OBJ);
				$role = $result4->user_role;
				
				if($role == 1) {
					$count_alumni_counter = $count_alumni_counter + 1;
				}
				else
				{
					$count_admin_counter = $count_admin_counter + 1;
				}
			}
			$alumni_number = $count_alumni_counter;
			$admin_number = $count_admin_counter;
		}
		else
		{
			$date = null;
			$alumni_number = 0;
			$admin_number = 0;
		}
		echo	'<tr>';
		echo		'<td width="30%">Κεντρική Σελίδα</td>';
		echo 		'<td colspan="4" width="30%" style="text-align:center">Δεν υπάρχουν Ιστορίες</td>';
		echo		'<td width="9%" style="text-align:center"><input value="'.$index_content.'" class = "statistics" disabled="disabled"></td>';
		echo		'<td width="9%" style="text-align:center"><input value="'.$alumni_number.'" class = "statistics" disabled="disabled"></td>';
		echo		'<td width="9%" style="text-align:center"><input value="'.$admin_number.'" class = "statistics" disabled="disabled"></td>';
		echo		'<td width="9%" style="text-align:center">'.$date.'</td>';  
		echo	'</tr>';

echo		'</tbody>';
echo		'</table>';
echo	'</form>';
echo	'</div>';

?>		
<br><br><br>
<footer class="container-fluid text-center">
  Footer Text
</footer>


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
