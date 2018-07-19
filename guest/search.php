<?php
session_start();
	//έγγραφο αναζήτησης για τα τμήματα
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
	
	if(isset($_POST['All'])) {
		$did = 0;		// το id του τμήματος στο οποίο γίνεται η αναζήτηση	
		if(isset($_POST['departmentId'])) {
			$did = $_POST['departmentId'];
		}
		
		$query = "SELECT * from users WHERE department_id = ?";
		$query2 = "SELECT * from users WHERE (role = 1 OR role = 3) AND department_id = ? ORDER BY `id` DESC LIMIT 10"; //θελουμε να προβάλουμε σταδιακα τους αποφοίτους
		
		$stmt = $conn->prepare($query2);
		$stmt->execute(array($did));
		$result = $stmt->fetchAll();
		
		$params = array();
		$params[0] = $did;
		$_SESSION['params'] = $params; //αποθηκευση πινακα με τις απαιτούμενες παραμετρους για την αναζήτηση
	}
	
	if(isset($_POST['someKeyName'])) {
		$did = 0;		// το id του τμήματος στο οποίο γίνεται η αναζήτηση	
		if(isset($_POST['departmentId'])) {
			$did = $_POST['departmentId'];
		}
		$phparray = $_POST['someKeyName'];  // τα δεδομένα του χρήστη από την φόρμα αναζήτησης
		$data = count($phparray);
		$rows = $data/4;
		
		/* // θα μπορούσε να παραληφθεί γιατί η γενική μορφή στο else περιλαμβάνει όλες τις περιπτώσεις
		
		if($rows == 1) {   
			$field = $elements[$phparray[0]];
			$tel = $telestis[$phparray[1]];
			$comparison = $phparray[3];

			$stmt = $conn->prepare("SELECT * FROM alumni_users WHERE $field $tel '$comparison' AND department_id = ?");
			$stmt->execute(array($did));
			$result = $stmt->fetchAll();
			
		}
		else  // δημιουργία ξεχωριστων query και σύνθεση σε 1
		{*/
	
		$query = "SELECT * FROM users WHERE (";
		$query2 = "SELECT * FROM users WHERE (";
		$query_end = " AND department_id = ?";
		$query_end2 = " AND department_id = ? AND (role = 1 OR role = 3) ORDER BY `id` DESC LIMIT 10";
		$el = 0;
		$field;
		$tel;
		$comparison;
		for($row=0; $row<$rows; $row++) {
			$field = $elements[$phparray[$el]];
			$el = $el+1;
			$tel = $telestis[$phparray[$el]];
			$el = $el+1;
			if($phparray[$el] != 3) {
				$prosthese = $prosthiki[$phparray[$el]];
			}
			else
			{
				$prosthese = ")";
			}
			$el = $el + 1;
			//$comparison = "'" . $phparray[$el] . "'";
			$comparison = "?";
			$el = $el+1;
			$query = $query . $field . " " . $tel . " " . $comparison . " " . $prosthese . " ";
			$query2 = $query2 . $field . " " . $tel . " " . $comparison . " " . $prosthese . " ";			
		}
		$query = $query . $query_end;
		$query2 = $query2 . $query_end2;
		//echo $query;     
		$phpcounter = 3; //μετρητης για να παρουμε τις πληροφορίες που μας εχει δώσει ο χρήστης	(οι πληροφορίες βρίσκονται στην 3η θέση και μετα απο κάθε 4 θέσεις του γενικού πίνακα δεδομένων)
		$params = array(); //πίνακας παραμέτρων για την συνάρτηση execute
		
		for($row=0; $row<$rows; $row++) {  //bind params των δεδομένων του χρήστη που αφορούν τα κριτήρια
			$params[$row] = $phparray[$phpcounter];
			$phpcounter = $phpcounter + 4;
		}
		$params_size = count($params);
		$params[$params_size] = $did;
		
		$stmt = $conn->prepare($query2);
		$stmt->execute($params);
		$result = $stmt->fetchAll();
		//}
		$_SESSION['params'] = $params;

	}	
		

echo '<table class="table">'; 
echo   "<thead>";
echo   "<tr>";
echo        '<th class="text-center">Επώνυμο</th>';
echo        '<th class="text-center">Όνομα</th>';
echo		'<th class="text-center">Πατρώνυμο</th>';
echo		'<th class="text-center"></th>';
echo   "</tr>";
echo   "</thead>";
echo    '<tbody id = "post_data">';

if(sizeof($result)>0) {
	foreach($result as $row) {	 
		$id = $row['id'];
		$lastname = $row['lastname'];
		$name = $row['name'];
		$father = $row['fathers_name'];
		
		echo '<tr class="post-id" id="'.$id.'">';
		echo	"<td style ='text-align:center'>$lastname</td>";
		echo	"<td style ='text-align:center'>$name</td>";
		echo	"<td style ='text-align:center'>$father</td>";
		echo	'<td><button type="button" id="'.$id.'" data-toggle="modal" data-target="#myModal"><span class="glyphicon glyphicon-folder-open"></span></button>';
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
