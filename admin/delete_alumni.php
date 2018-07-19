<?php

session_start();
include ("../connectPDO.php");

if(isset($_SESSION['name'])) {
	$admin_id = $_SESSION['name'];
}
$alumni_id = $_POST['alumni_id'];
$active = 0;

$sql = "UPDATE alumni_users SET active = ? WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->execute(array($active, $alumni_id));


/*
									Κώδικας Διαγραφής χρήστη 
                                    ------------------------
(δεν επηρεάζει ολους τους πινακες στην βάση που εμπλέκεται ο χρήστης!! Προστέθηκαν και αλλοι πίνακες) 
*/


/*
// διαδικασια τροποποίησης των δημοσιευσεων που εχει ανεβασει ο αποφοιτος στην αρχική σελιδα του ιστοχώρου
$sql = "SELECT * FROM univsitecontents WHERE userID = ? AND status = 1";
$stmt = $conn->prepare($sql);
$stmt->execute(array($alumni_id));
$result = $stmt->fetchAll();

if(sizeof($result)>0) {
	foreach ($result as $row) {
		$univsitecontent_id = $row['id'];
		
		$sql = "UPDATE univsitecontents SET userID = ?, adminID = ? WHERE id = ?";
		$stmt = $conn->prepare($sql);
		$stmt->execute(array($userID, $admin_id, $univsitecontent_id));
	}
}

// διαγραφή όλων των δημοσιευσεων του αποφοιτου που δεν έχουν δημοσιευτεί και των εικόνων που περιλαμβάνουν για την αρχική σελιδα του ιστοχώρου
$sql = "SELECT * FROM univsitecontents WHERE userID = ? AND status = 0";        
$stmt = $conn->prepare($sql);
$stmt->execute(array($alumni_id));
$result = $stmt->fetchAll();

if(sizeof($result)>0) {
	foreach ($result as $row) {
		$univsitecontent_id = $row['id'];
		
		$sql = "DELETE FROM univsitecontents WHERE id = ?";
		$stmt = $conn->prepare($sql);
		$stmt->execute(array($univsitecontent_id));
		
		$sql = "SELECT images_path FROM images WHERE univsitecontentID = ?";
		$stmt = $conn->prepare($sql);
		$stmt->execute(array($univsitecontent_id));
		$results = $stmt->fetchAll();

		if(sizeof($results)>0) {
			$image_path = $results[0][0];

			$image_file = "../content_images/". $image_path;
			if (file_exists($image_file)) {
				unlink($image_file)
			}	

			$sql2 = "DELETE FROM images WHERE univsitecontentID = ?";
			$stmt = $conn->prepare($sql2);
			$stmt->execute(array($univsitecontent_id));
			
		}		
	}
}


// διαδικασια τροποποίησης των δημοσιευσεων που εχει ανεβασει ο αποφοιτος στην αρχική σελιδα του τμήματος του
$sql = "SELECT * FROM dsitecontents WHERE userID = ? AND status = 1";
$stmt = $conn->prepare($sql);
$stmt->execute(array($alumni_id));
$result = $stmt->fetchAll();

if(sizeof($result)>0) {
	foreach ($result as $row) {
		$dsitecontent_id = $row['id'];
		
		$sql = "UPDATE dsitecontents SET userID = ?, adminID = ? WHERE id = ?";
		$stmt = $conn->prepare($sql);
		$stmt->execute(array($userID, $admin_id, $dsitecontent_id));
	}
}

// διαγραφή όλων των δημοσιευσεων του αποφοιτου που δεν έχουν δημοσιευτεί και των εικόνων που περιλαμβάνουν για την αρχική σελιδα του τμήματος του
$sql = "SELECT * FROM dsitecontents WHERE userID = ? AND status = 0";        
$stmt = $conn->prepare($sql);
$stmt->execute(array($alumni_id));
$result = $stmt->fetchAll();

if(sizeof($result)>0) {
	foreach ($result as $row) {
		$dsitecontent_id = $row['id'];
		
		$sql = "DELETE FROM dsitecontents WHERE id = ?";
		$stmt = $conn->prepare($sql);
		$stmt->execute(array($dsitecontent_id));
		
		$sql = "SELECT images_path FROM images WHERE dsitecontentID = ?";
		$stmt = $conn->prepare($sql);
		$stmt->execute(array($dsitecontent_id));
		$results = $stmt->fetchAll();

		if(sizeof($results)>0) {
			$image_path = $results[0][0];

			$image_file = "../content_images/". $image_path;
			if (file_exists($image_file)) {
				unlink($image_file)
			}	

			$sql2 = "DELETE FROM images WHERE dsitecontentID = ?";
			$stmt = $conn->prepare($sql2);
			$stmt->execute(array($dsitecontent_id));
			
		}		
	}
}

// διαδικασια τροποποίησης των ιστοριων που εχει ανεβασει ο αποφοιτος
$sql = "SELECT * FROM stories WHERE userID = ? AND status = 1";
$stmt = $conn->prepare($sql);
$stmt->execute(array($alumni_id));
$result = $stmt->fetchAll();

if(sizeof($result)>0) {
	foreach ($result as $row) {
		$story_id = $row['id'];
		
		$sql = "UPDATE stories SET userID = ?, adminID = ? WHERE id = ?";
		$stmt = $conn->prepare($sql);
		$stmt->execute(array($userID, $admin_id, $story_id));
	}
}

// διαγραφή όλων των ιστοριών του αποφοιτου που δεν έχουν δημοσιευτεί και των εικόνων που περιλαμβάνουν
$sql = "SELECT * FROM stories WHERE userID = ? AND status = 0";        
$stmt = $conn->prepare($sql);
$stmt->execute(array($alumni_id));
$result = $stmt->fetchAll();

if(sizeof($result)>0) {
	foreach ($result as $row) {
		$story_id = $row['id'];
		
		$sql = "DELETE FROM stories WHERE id = ?";
		$stmt = $conn->prepare($sql);
		$stmt->execute(array($story_id));
		
		$sql = "SELECT images_path FROM images WHERE storyID = ?";
		$stmt = $conn->prepare($sql);
		$stmt->execute(array($story_id));
		$results = $stmt->fetchAll();

		if(sizeof($results)>0) {
			$image_path = $results[0][0];

			$image_file = "../content_images/". $image_path;
			if (file_exists($image_file)) {
				unlink($image_file)
			}	

			$sql2 = "DELETE FROM images WHERE storyID = ?";
			$stmt = $conn->prepare($sql2);
			$stmt->execute(array($story_id));
			
		}		
	}
}

// διαγραφή της καταχώρησης που αφορά τις επιλογες του χρήστη για τα newsletter
$sql = "DELETE FROM newsletter WHERE alumni_id = ?";        
$stmt = $conn->prepare($sql);
if($stmt->execute(array($alumni_id))) {	}

// διαγραφή πιθανών αποθηκευμένων ειδοποιήσεων απο τον πίνακα ειδοποιήσεων που αφορούν τον απόφοιτο
$sql = "DELETE FROM notifications WHERE alumni_id = ?";        
$stmt = $conn->prepare($sql);
if($stmt->execute(array($alumni_id))) {	}

// διαδικασια διαγραφής της εικόνας προφιλ και της καταχώρησης της στο πινακα των εικόνων στην περιπτωση που ο χρήστης εχει ανεβασει.
$sql = "SELECT images_path FROM images WHERE userID = ?";
$stmt = $conn->prepare($sql);
$stmt->execute(array($alumni_id));
$results = $stmt->fetchAll();

if(sizeof($results)>0) {
	$image_path = $results[0][0];
	if($image_path == 'user.png') {

	}
	else
	{
		$image_file = "../users_images/". $image_path;
		if (file_exists($image_file)) {
			unlink($image_file)
		}	
	}
	$sql = "DELETE FROM images WHERE userID = ?";        
	$stmt = $conn->prepare($sql);
	if($stmt->execute(array($alumni_id))) {	}
	
}

// διαγραφή της καταχώρησης βιογραφικού απο τον πίνακα βιογραφικών και του pdf αρχείου
$sql = "SELECT pdf_src FROM alumni_cv WHERE alumni_id = ?";
$stmt = $conn->prepare($sql);
$stmt->execute(array($alumni_id));
$cv = $stmt->fetchAll();

if(sizeof($cv)>0) {
	$filename = "../cv_files/" . $cv[0][0];

	if (file_exists($filename)) {
		unlink($filename)
	}
	
	$sql = "DELETE FROM alumni_cv WHERE alumni_id = ?";        
	$stmt = $conn->prepare($sql);
	if($stmt->execute(array($alumni_id))) {  }
	
}

//διαγραφή της καταχώρησης του αποφοίτου από τον πίνακα αποφοίτων
$sql = "DELETE FROM alumni_users WHERE id = ?";        
$stmt = $conn->prepare($sql);
if($stmt->execute(array($alumni_id)))
{
	echo "Ο απόφοιτος διαγράφηκε με επιτυχία";
}


*/
?>