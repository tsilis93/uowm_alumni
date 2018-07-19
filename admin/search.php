<?php
													//έγγραφο αναζήτησης αποφοίτων
	session_start();

	$query_found = false;		
	include ("../connectPDO.php");
		
	$elements[1] = "name";		//αρχικοποίηση πίνακα δεδομένων
	$elements[2] = "lastname";
	$elements[3] = "registration_year";
	$elements[4] = "graduation_year";
	$elements[5] = "degree_grade";
	$elements[6] = "residence_city";
	$elements[7] = "job_city";
	
	$telestis[1] = "=";			// αρχικοποίηση πίνακα τελεστών
	$telestis[2] = "!=";
	$telestis[3] = "<";
	$telestis[4] = ">";
	$telestis[5] = ">=";
	$telestis[6] = "<=";
	$telestis[7] = "LIKE";
	
	$prosthiki[1] = "AND";		// αρχικοποίηση πίνακα προσθήκης νέου κριτηρίου
	$prosthiki[2] = "OR";
	
	if(isset($_POST['All'])) {    //εμφανίζει όλους τους αποφοίτους
				
		$query = "SELECT * from users"; //θελουμε όλους τους αποφοίτους για το csv αρχειο
		
		$query2 = "SELECT * from users WHERE (role = 1 OR role = 3) ORDER BY `id` DESC LIMIT 10"; //θελουμε να προβάλουμε σταδιακα τους αποφοίτους
		$stmt = $conn->prepare($query2);
		$stmt->execute();
		$result = $stmt->fetchAll();
		
		$_SESSION['params'] = array(); //αποθηκευση πινακα με τις απαιτούμενες παραμετρους για την αναζήτηση
		$_SESSION['csv_query'] = $query;
		$_SESSION['query'] = $query2;
		
		$query_found = true;
	}	
	
	if(isset($_POST['someKeyName'])) {    	//εμφανίζει τους αποφοίτους με βάση τα κριτιρια του χρήστη
		
		$query_end = ""; //αρχικοποίηση του τελους του query που θα αποθηκευτεί για το csv αρχείο
		$query_end2 = " AND (role = 1 OR role = 3) ORDER BY `id` DESC LIMIT 10"; //αρχικοποίηση του τελους του query για την προβολή των αποφοίτων. θελουμε να προβάλουμε σταδιακα τους αποφοίτους 	
		$data2 = 0;							
		if(isset($_POST['departments'])) {				//στην περίπτωση που ο χρήστης επιλέξει τμήμα σαν κριτήριο το query_end αλλάζει
			$departments = $_POST['departments'];		 
			$data2 = count($departments);
			
			if($data2 == 1) {											//πραγματοποιούνται και οι κατάλληλες αλλαγες
				$query_end = " AND department_id = ?";
				$query_end2 = " AND department_id = ? ORDER BY `id` DESC LIMIT 10";
			}
			else
			{
				$query_end = " AND (department_id = ?";
				$query_end2 = " AND (department_id = ?";
				for($i=1; $i<$data2; $i++) {							 
					if($i == $data2-1) {
						$query_end = $query_end . "  OR department_id = ?)";
						$query_end2 = $query_end2 . "  OR department_id = ?) AND (role = 1 OR role = 3) ORDER BY `id` DESC LIMIT 10";
					}
					else
					{
						$query_end = $query_end . "  OR department_id = ?";
						$query_end2 = $query_end2 . "  OR department_id = ?";
					}
				}
			}
		}
		
		$phparray = $_POST['someKeyName'];  // τα δεδομένα του χρήστη από την φόρμα αναζήτησης
		$data = count($phparray);
		$rows = $data/4;
												
		$query = "SELECT * FROM users WHERE (";
		$query2 = "SELECT * FROM users WHERE (";
		$el = 0;
		$field;
		$tel;
		$comparison;					//διαδικασία δημιουργιας του query για κάθε γραμμή που εχει προσθέσει στον πίνακα κριτηρίων
		for($row=0; $row<$rows; $row++) {    
			$field = $elements[$phparray[$el]];  //$field => πεδίο αναζήτησης (ονομα, επώνυμο, ετος αποφοίτης ...)
			$el = $el+1;
			$tel = $telestis[$phparray[$el]];   //$tel => πεδίο σύγκρισης (=, >, <, != ...)
			$el = $el+1;
			if($phparray[$el] != 3) {			// αν στην τελευταία στήλη εντοπιστεί το "τελος αναζήτησης" η διαδικασία δημιουργιας ολοκληρώθηκε
				$prosthese = $prosthiki[$phparray[$el]];	//$prosthese => πεδιο προσθήκης κριτηρίου (AND, OR)
			}
			else
			{
				$prosthese = ")";
			}
			$el = $el + 1;
			//$comparison = "'" . $phparray[$el] . "'";  	
			$comparison = "?";							//$comparison => πεδίο με το περιεχόμενο της αναζήτησης (Βασίλης, Τασος, 2011 ...)
			$el = $el+1;
			$query = $query . $field . " " . $tel . " " . $comparison . " " . $prosthese . " "; //δημιουργια του query
			$query2 = $query2 . $field . " " . $tel . " " . $comparison . " " . $prosthese . " ";
		}
		
		$query = $query . $query_end; //προσθήκη της αρχικοποίησης του τέλους του query
		$query2 = $query2 . $query_end2;
				
		$phpcounter = 3; //μετρητης για να παρουμε τις πληροφορίες που μας εχει δώσει ο χρήστης	(οι πληροφορίες βρίσκονται στην 3η θέση και μετα απο κάθε 4 θέσεις του γενικού πίνακα δεδομένων)
		$params = array(); //πίνακας παραμέτρων για την συνάρτηση execute
		
		for($row=0; $row<$rows; $row++) {  //bind params των δεδομένων του χρήστη που αφορούν τα κριτήρια
			$params[$row] = $phparray[$phpcounter];
			$phpcounter = $phpcounter + 4;
		}
		$params_size = count($params);
		for($i=0; $i<$data2; $i++) {							 
			$params[$params_size] = $departments[$i];
			$params_size = $params_size + 1;
		}		
		
			
		$stmt = $conn->prepare($query2);
		$stmt->execute($params);
		$result = $stmt->fetchAll();
		
		$_SESSION['params'] = $params;
		$_SESSION['csv_query'] = $query; //αποθηκευση της επιλογης του διαχειριστή για το csv αρχείο
		$_SESSION['query'] = $query2; //αποθηκευση της επιλογής του διαχειριστή για την προβολη των αποφοίτων στην σελίδα και ανακτησης της επιλογής		
		
		$query_found = true;
	}
	
	if(isset($_POST['unique_key'])) {
		
		$past_query = "";
		if(isset($_SESSION['query'])) {
			$past_query = $_SESSION['query'];
		}
		if(isset($_SESSION['params'])) {
			$past_params = $_SESSION['params'];
		}
		if(isset($_SESSION['csv_query'])) {
			$query = $_SESSION['csv_query'];
		}
		
		if($past_query != "") {
			$stmt = $conn->prepare($past_query);
			$stmt->execute($past_params);
			$result = $stmt->fetchAll();
			
			$query_found = true;
		}
	}

if($query_found == true) {
	
	echo	'<form action="sent_email_form.php" method="post">';	
		
	echo   '<table class="table">';
	echo   "<thead>";
	echo     "<tr>";
	echo        '<th class="text-center">Επώνυμο</th>';
	echo        '<th class="text-center">Όνομα</th>';
	echo		'<th class="text-center">Πατρώνυμο</th>';
	echo		'<th class="text-center">Προβολή</th>';
	echo		'<th class="text-center">Επιλογή</th>';
	echo		'<th class="text-center" colspan="2">Περισσότερα</th>';
	echo      "</tr>";
	echo    "</thead>";
	echo    '<tbody id = "post_data">';

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
			echo	'<td class="text-center"><a class="btn btn-link" href="edit_alumni.php?id='.$id.'"><button type="button">Επεξεργασία</button></a>​</td>';		
			echo "</tr>";						
		}
	}
	else
	{
		echo '<tr><td style ="text-align:center" colspan = "7"> Δεν υπάρχουν φοιτητές που να πληρούν τις προδιαγραφές </td></tr>';
	}


	echo "</tbody>";
	echo "</table>";

	echo	'<div class="ajax-load text-center" style="display:none">';
	echo		'<i class="fa fa-spinner fa-spin" style="font-size:30px"></i>';
	echo		'<p style = "color:black; text-align:center">Loading More Alumni</p>';
	echo	'</div>';

	echo   '<table class="table" width="100%">';
	echo    "<tbody>";
		echo '<tr>';
		echo	'<td colspan = "6"><button type="button" onclick="loadMoreAlumni(\''.$query.'\')" class="btn btn-info">Περισσότεροι Απόφοιτοι</button></td>';
		echo 	'<td class="text-center"><input id = "submit" type="submit" value="Αποστολή Email" class="btn btn-primary"></td>';
		echo 	'<td class="text-center"><button type="button" onclick="myFunction(\''.$query.'\')" class="btn btn-primary">Αποθήκευση CSV</button></td>';
		echo '</tr>';
	echo	'</tbody>';
	echo	'</table>';	

	echo "</form>";

}

?>
		
		
