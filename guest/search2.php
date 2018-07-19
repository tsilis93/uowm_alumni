<?php
	session_start();

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
		
		$query = "SELECT * from users";
		$query2 = "SELECT * from users WHERE (role = 1 OR role = 3) ORDER BY `id` DESC LIMIT 10"; //θελουμε να προβάλουμε σταδιακα τους αποφοίτους
		
		$stmt = $conn->prepare($query2);
		$stmt->execute();
		$result = $stmt->fetchAll();
		
		$_SESSION['params'] = array(); //αποθηκευση πινακα με τις απαιτούμενες παραμετρους για την αναζήτηση
	}	
	
	if(isset($_POST['someKeyName'])) {    	
		
		$query_end = ""; 
		$query_end2 = " AND (role = 1 OR role = 3) ORDER BY `id` DESC LIMIT 10"; //αρχικοποίηση του τελους του query για την προβολή των αποφοίτων. θελουμε να προβάλουμε σταδιακα τους αποφοίτους 						
		$data2 = 0;							
		if(isset($_POST['departments'])) {	
			$departments = $_POST['departments'];
			$data2 = count($departments);
			
			if($data2 == 1) {											//πραγματοποιούνται και οι κατάλληλες αλλαγες
				$query_end = " AND department_id = ?";
				$query_end2 = " AND department_id = ? AND (role = 1 OR role = 3) ORDER BY `id` DESC LIMIT 10";
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
		$comparison;				//διαδικασία δημιουργιας του query για κάθε γραμμή που εχει προσθέσει στον πίνακα κριτηρίων
		for($row=0; $row<$rows; $row++) {    
			$field = $elements[$phparray[$el]];		//$field => πεδίο αναζήτησης (ονομα, επώνυμο, ετος αποφοίτης ...)
			$el = $el+1;
			$tel = $telestis[$phparray[$el]];		//$tel => πεδίο σύγκρισης (=, >, <, != ...)
			$el = $el+1;
			if($phparray[$el] != 3) {				// αν στην τελευταία στήλη εντοπιστεί το "τελος αναζήτησης" η διαδικασία δημιουργιας ολοκληρώθηκε
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
		
	}	
		
echo   '<table class="table" id="fixed_headers">';
echo   "<thead>";
echo     "<tr>";
echo        '<th class="text-center">Επώνυμο</th>';
echo        '<th class="text-center">Όνομα</th>';
echo		'<th class="text-center">Πατρώνυμο</th>';
echo		'<th class="text-center">Προβολή</th>';
if((isset($_SESSION['name'])) || (isset($_SESSION['student'])))  // αν ο χρήστης ειναι συνδεδεμένος
{
echo		'<th class="text-center">Βιογραφικό</th>';
}
echo      "</tr>";
echo    "</thead>";
echo    '<tbody id = "post_data">';

if(sizeof($result)>0) {
	foreach($result as $row) {	 
		$lastname = $row['lastname'];
		$name = $row['name'];
		$father = $row['fathers_name'];
		$id = $row['id'];
		
		$stmt2 = $conn->prepare("SELECT * from alumni_cv WHERE alumni_id = ?");
		$stmt2->execute(array($id));
		$result2 = $stmt2->fetchAll();
				
		echo '<tr class="post-id" id="'.$id.'">';
		echo	"<td class='text-center'>$lastname</td>";
		echo	"<td class='text-center'>$name</td>";
		echo	"<td class='text-center'>$father</td>";
		echo	'<td class="text-center"><button type="button" id="'.$id.'" data-toggle="modal" data-target="#myModal2"><span class="glyphicon glyphicon-folder-open"></span></button>';
		if((isset($_SESSION['name'])) || (isset($_SESSION['student'])))  // αν ο χρήστης ειναι συνδεδεμένος
		{
			if (sizeof($result2)>0) {
				foreach($result2 as $row2) {
					$src = $row2['pdf_src'];
					echo	'<td class="text-center"><a href="../cv_files/'.$src.'" onclick="window.open(this.href); return false;" onkeypress="window.open(this.href); return false;">Σύνδεσμος</a></td>';				
				}
			}
			else
			{
				echo '<td class="text-center">Μη διαθέσιμο pdf</td>';
			}
		}		
		echo "</tr>";						
	}

}
else
{
	echo '<tr><td style ="text-align:center" colspan = "4"> Δεν υπάρχουν φοιτητές που να πληρούν τις προδιαγραφές </td></tr>';
	//echo '<tr><td style ="text-align:center" colspan = "4">'.$query.'</td></tr>';
	//echo '<tr><td style ="text-align:center" colspan = "4">'.$query2.'</td></tr>';	
}

echo "</tbody>";
echo "</table>";

echo	'<div class="ajax-load text-center" style="display:none">';
echo		'<i class="fa fa-spinner fa-spin" style="font-size:30px"></i>';
echo		'<p style = "color:black; text-align:center">Loading More Alumni</p>';
echo	'</div>';

echo   '<table class="table">';
echo    "<tbody>";
	echo '<tr>';
	echo	'<td colspan = "5"><button type="button" onclick="loadMoreAlumni(\''.$query.'\')" class="btn btn-info">Περισσότεροι Απόφοιτοι</button></td>';
	echo '</tr>';
echo	'</tbody>';
echo	'</table>';	

?>
		
		
