#!/usr/local/bin/php -q
<?php

include ("connectPDO.php");

$today = date("Y-m-d"); //παρε την σημερινη ημερομηνία
$todays_dateArray = date_parse_from_format('Y-m-d', $today); //ξεχωρισε την μερα και τον μηνα
$todays_day = $dateArray['day']; //μέρα
$todays_month = $dateArray['month']; //μήνας

$header = "From: <webmaster@alumni.uowm.gr>";

$graduation_text = "Συγχαρητήρια!!! Σαν σήμερα αποφοίτησατε από το "; //εδω ειναι το μνμ για την επετειο αποφοίτησης
$graduation_subject = "Επέτειος Αποφοίτησης";
$birthday_text = "Η ιστοσελίδα των αποφοίτων του Πανεπιστημίου Δυτικής Μακεδονίας σας εύχεται Χρόνια Πολλά!!!"; //εδω είναι το μνμ για τα χρονια πολλα
$birthday_subject = "Γενέθλιες Ευχές";


$stmt = $conn->prepare("SELECT * FROM users WHERE (role = 3 OR role = 1) AND active = 1"); 
$stmt->execute();														   
$result = $stmt->fetchAll();

if(sizeof($result)>0) {	//αν υπαρχουν απόφοιτοι 
	
	foreach($result as $row) {
								//παρε email, ημερομηνια αποφοίτησης και γενεθλίων
		$email = $row['email'];	
		$birthday = $row['birthday_date'];
		$graduation = $row['graduation_date'];
		
		$birthday_date = strtotime($birthday);	//ομοίως με την σημερινη ημερομηνία παίρνω μερα και μηνα γενεθλίων
		$birthdays_day = date('d',$birthday_date);
		$birthdays_month = date('m',$birthday_date);
														//αν η μερα γενεθλίων είναι η σημερινη μερα και ο μηνας γενεθλίων είναι ο τωρινος
		if(($todays_day == $birthdays_day) && ($todays_month == $birthdays_month)) {
			mail($email, $birthday_subject, $birthday_text, $header);	//στείλε email
		}
		
		$graduation_date = strtotime($graduation); //ομοίως με την σημερινη ημερομηνία παίρνω μερα και μηνα αποφοίτησης
		$graduations_day = date('d',$graduation_date);
		$graduations_month = date('m',$graduation_date);
																//αν η μερα αποφοίτησης είναι η σημερινη μερα και ο μηνας αποφοίτησης είναι ο τωρινος
		if(($todays_day == $graduations_day) && ($todays_month == $graduations_month)) {
			mail($email, $graduation_subject, $graduation_text, $header); //στείλε email
		}

	}

}
else
{
	//αν δεν υπάρχουν αποφοιτοι γενικα μην κανεις τπτ
}