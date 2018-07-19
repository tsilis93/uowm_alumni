<?php

session_start();
include ("../connectPDO.php");
$last_id = 0;
if(isset($_GET['last_id']))
{
	$last_id = $_GET['last_id'];
}
$id =  $_GET['id'];
						// Το περιεχόμενο με το μικρότερο publication_date έχει και το μικρότερο publication_id
$department = "published_department" . $id;  //ελεγχω αν η σημαια δημοσίευσης του τμήματος με id = $id ειναι 1

$stmt = $conn->prepare("SELECT * FROM contents WHERE publication_id < ? AND status = 1 AND ".$department." = 1 ORDER BY publication_id DESC LIMIT 4");
$stmt->execute(array($last_id));
$result = $stmt->fetchAll();
include ("../links.php");

$row_count = $_GET['row_count'];	//αποθηκεύω τον αριθμό των στηλών		
if (sizeof($result)> 0) {
	foreach($result as $row) {
		
		$contentid = $row['id'];
		$timestamp  = strtotime($row['publication_date']); 
		$date = date('d-m-Y',$timestamp);
		$body = makeLinks($row['body']);
		$title = $row['title'];
		$description = $row['description'];
		$publication_id = $row['publication_id'];

		$stmt5 = $conn->prepare("SELECT * from images WHERE contentID = ?");   
		$stmt5->execute(array($contentid));		// αναζητώ αν υπάρχουν αποθηκευμένες εικόνες για την συγκεκριμένη δημοσίευσή													
		$result5 = $stmt5->fetchAll();		
		
		echo '<tr class="post-id" id="'.$publication_id.'">';
		echo '<td class="more" width="25%">'.$title.'</td>';
		echo '<td class="more" width="25%">'.$description.'</td>';
		echo '<td width="25%">'.$date.'</td>';
		echo '<td width="25%"><a href="#d'.$row_count.'" class="btn btn-info" data-toggle="collapse">Λεπτομέρειες</a></td>'; 
		echo "<tr>";
			echo '<td style ="text-align:left" colspan = "4">';
			echo '<div id="d'.$row_count.'" class="collapse">';
			echo	$body;
			if (sizeof($result5)> 0) {  //αν υπάρχουν εικόνες τότε τις εμφανίζω την μια διπλα στην αλλη
				echo "<br><br>";
				foreach($result5 as $row5) {
					$images_path2 = $row5['images_path'];
					$image_id = $row5['id']; // αποθηκεύω το id της εικόνας από την βάση στο id του tag <img>
					echo '<img id = "'.$image_id.'" onclick="javascript:openModal(this.id);" onmouseover="" style="cursor: pointer;" src ="../content_images/'.$images_path2.'" width=100 height=100>';  //allakse to assets se images 
					echo "&nbsp;&nbsp;";			// περνάω στην συνάρτηση το <tag> id και παράλληλα το id της εικόνας
				}
			}			
			echo "</div>";
			echo "</td>";
		echo "</tr>";
		
		
		echo "</tr>";
		$row_count = $row_count + 1;
	}
}
else
{			
	echo '<tr><td colspan = "4"> Δεν υπάρχουν διαθέσιμες Ανακοινώσεις </td></tr>';
}


?>
