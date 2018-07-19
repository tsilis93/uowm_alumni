<?php
session_start();
	// έγγραφο δημιουργίας πίνακα αναζήτησης
	$id = $_GET['id'];  //αριθμός γραμμής πινακα που εισέρχεται


echo "<tr>";
echo	'<td width="20%">';
echo		'<div id = "select1_'.$id.'" style="border-radius: 7px;">'; 
echo			'<select id = "select1-'.$id.'" data-width="100%">';
echo				'<option value="" disabled selected>Πεδία</option>';
echo				'<option value="1">Όνομα</option>';
echo				'<option value="2">Επώνυμο</option>';
echo				'<option value="3">Έτος Εγγραφής</option>';
echo				'<option value="4">Έτος Αποφοίτησης</option>';
echo				'<option value="5">Βαθμός Πτυχίου</option>';
echo				'<option value="6">Πόλη Διαμονής</option>';
echo				'<option value="7">Πόλη Εργασίας</option>';
echo			"</select>";
echo		'</div>';
echo	"</td>";

echo		'<td width="5%">';
echo			'<div id = "select2_'.$id.'" style="border-radius: 7px;">'; 
echo				'<select id = "select2-'.$id.'" data-width="100%">';
echo					'<option value="" disabled selected>Τελεστής</option>';
echo					'<option value="1"> = </option>';
echo					'<option value="2"> ! = </option>';
echo					'<option value="3"> < </option>';
echo					'<option value="4"> > </option>';
echo					'<option value="5"> > = </option>';
echo					'<option value="6"> < = </option>';
echo					'<option value="7"> LIKE (περιλαμβάνει) </option>';
echo				"</select>";
echo			'</div>';
echo		"</td>";

echo		'<td width="55%">';
echo		'<input type="text" id="input'.$id.'">';
echo		"</td>";

echo		'<td width="20%">';
echo			'<div id = "select3_'.$id.'" style="border-radius: 7px;">'; 
echo				'<select id = "select3-'.$id.'" data-width="100%">';
echo					'<option value="" disabled selected>Πρόσθεσε</option>';
echo					'<option value="1"> AND </option>';
echo					'<option value="2"> OR </option>';
echo					'<option value="3"><<Τέλος αναζήτησης>></option>';
echo				"</select>";
echo			'</div>';
echo		"</td>";
	
echo "</tr>";

?>