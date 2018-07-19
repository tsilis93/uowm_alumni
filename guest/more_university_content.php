<?php
session_start();			
include ("../connectPDO.php");

$id = 0;
if(isset($_GET['id'])){
	$id =  $_GET['id'];
}

						// Το περιεχόμενο με το μικρότερο publication_date έχει και το μικρότερο publication_id
$stmt = $conn->prepare("SELECT * FROM contents WHERE publication_id < ? AND status = 1 AND published_index_page = 1 ORDER BY publication_id DESC LIMIT 4");
$stmt->execute(array($id));
$result = $stmt->fetchAll();
include ("../links.php");

$row_count = $_GET['row_count'];		
if (sizeof($result)> 0) {
	foreach($result as $row) {
		
		$contentid = $row['id'];
		$timestamp  = strtotime($row['publication_date']); 
		$date = date('d-m-Y',$timestamp);
		$body = makeLinks($row['body']);
		$title = $row['title'];
		$description = $row['description'];
		$publication_id = $row['publication_id'];
		
		$stmt4 = $conn->prepare("SELECT * from images WHERE contentID = ?");   
		$stmt4->execute(array($contentid));		// αναζητώ αν υπάρχουν αποθηκευμένες εικόνες για την συγκεκριμένη δημοσίευσή													
		$result4 = $stmt4->fetchAll();		
		
		echo '<tr class="post-id" id="'.$publication_id.'">';
		echo 	'<td class="more" width="25%">'.$title.'</td>';
		echo 	'<td class="more" width="25%">'.$description.'</td>';
		echo 	'<td width="25%">'.$date.'</td>';
		echo 	'<td width="25%"><a href="#a'.$row_count.'" class="btn btn-info" data-toggle="collapse">Λεπτομέρειες</a></td>';
		echo "<tr>";
		echo '<td style ="text-align:left" colspan = "4">';
			echo	'<div id="a'.$row_count.'" class="collapse">';
			echo		$body;
			if (sizeof($result4)> 0) {  //αν υπάρχουν εικόνες τότε τις εμφανίζω την μια διπλα στην αλλη
				echo "<br><br>";
				foreach($result4 as $row4) {
					$images_path = $row4['images_path'];
					$image_id = $row4['id']; // αποθηκεύω το id της εικόνας από την βάση στο id του tag <img>
					echo '<img id = "'.$image_id.'" onclick="javascript:openModal(this.id);" src ="../content_images/'.$images_path.'" width=100 height=100>';  //allakse to assets se images 
					echo "&nbsp;&nbsp;";			// περνάω στην συνάρτηση το <tag> id και παράλληλα το id της εικόνας
				}
			}	
			echo	'</div>';
			echo "</td>";
		echo "</tr>";
		
		
		echo "</tr>";
		$row_count = $row_count + 1;
	}
} 
else
{  
		echo '<tr><td colspan = "5"> Δεν υπάρχουν διαθέσιμες Εκδηλώσεις – Δράσεις – Ανακοινώσεις </td></tr>';
}

?>	