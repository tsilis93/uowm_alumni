<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>UOWM Alumni</title>
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
  <meta http-equiv="content-type" content="text/html; charset=UTF-8">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    
  <link rel="stylesheet" href="../css/index.css">
  <link rel="alternate" href="univercity_content_feed.php" title="Index page Announcements feed" type="application/rss+xml" />
  
</head>

<body> 
<?php
session_start();
include	("../secure_website.php");
?>

<header><!-- Σε αυτό το σημείο το navigation menu έρχεται με jquery --></header>

<section id="home">
<br><br><br></br>
<div id="myCarousel" class="carousel slide" data-ride="carousel">
    <!-- Indicators -->
    <ol class="carousel-indicators">
      <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
      <li data-target="#myCarousel" data-slide-to="1"></li>
    </ol>

    <!-- Wrapper for slides -->
    <div class="carousel-inner" role="listbox">
      
	  <div class="item active">
        <img src="../assets/graduation2.jpg" alt="Image">     
      </div>

      <div class="item">
        <img src="../assets/graduation1.png" alt="Image">
      </div>
	  
    </div>

    <!-- Left and right controls -->
    <a class="left carousel-control" href="#myCarousel" role="button" data-slide="prev">
      <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
      <span class="sr-only">Previous</span>
    </a>
    <a class="right carousel-control" href="#myCarousel" role="button" data-slide="next">
      <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
      <span class="sr-only">Next</span>
    </a>
</div>

<br><br>
<div class="container text-center">
	<h1>Καλώς ορίσατε στην σελίδα των αποφοίτων του Πανεπιστημίου Δυτικής Μακεδονίας</h1>
</div>
</section> 

<section id="about"> 
<br><br>
<hr>
<h3 bgcolor="#E6E6FA">ΣΧΕΤΙΚΑ ΜΕ ΤΟΝ ΙΣΤΟΤΟΠΟ</h3><br>
 
<div class="container text-center">   
  <br>
  
  <div style="text-indent: 40px;"><p>Το Πανεπιστήμιο Δυτικής Μακεδονίας προσπαθεί να βρίσκεται πάντα σε επαφή με τους αποφοίτους του. Ιδιαίτερα το τελευταίο διάστημα, έχει πραγματοποιηθεί μία συστηματική προσπάθεια ανεύρεσης όλων των αποφοίτων, ώστε το Πανεπιστήμιο να συγκεντρώσει και να οργανώσει επικαιροποιημένα στοιχεία, για όσο το δυνατόν περισσότερους από αυτούς. Η προσπάθεια αυτή θα είναι συνεχής.</p></div><br>
  <div style="text-indent: 40px;"><p>Στα πλαίσια αυτής της ενέργειας, δημιουργήσαμε την ιστοσελίδα των αποφοίτων μας, θέλοντας να σας διευκολύνουμε να παραμείνετε σε επαφή μαζί μας και να μας ενημερώνετε για την εξέλιξη της καριέρας σας μετά την απόκτηση του πτυχίου σας. Θα έχετε επίσης τη δυνατότητα, να βρίσκετε εύκολα πληροφορίες για τους παλαιούς συμφοιτητές και φίλους σας.</p></div><br>
  <div style="text-indent: 40px;"><p>Ο ιστότοπος σχεδιάστηκε και υλοποιήθηκε από τον απόφοιτο του τμήματος Μηχανικών Πληροφορικής και Τηλεπικοινωνιών, Βασίλη Τσιλιμπάρη, στα πλαίσια της διπλωματικής εργασίας του, υπό την επίβλεψη του λέκτορα του αντίστοιχου τμήματος Μηνά Δασυγένη. <font size="3"><a href="http://arch.icte.uowm.gr/" target="_blank">(http://arch.icte.uowm.gr)</a></font></p></div> 

</div><br><br>

</section> 

<section id="action">
<br><br> 
<hr>
<h3 bgcolor="#E6E6FA">ΕΚΔΗΛΩΣΕΙΣ – ΔΡΑΣΕΙΣ – ΑΝΑΚΟΙΝΩΣΕΙΣ ΤΟΥ ΠΑΝΕΠΙΣΤΗΜΙΟΥ <a href="univercity_content_feed.php" target="_blank"><img src="../assets/Feed-icon.png" alt="Feed-icon" id="feed" width=15 height=15></a></h3><br>
 
<div class="divtable">    
		
    <table class="table" id="cssTable" width="100%">
    <thead>
      <tr>
        <th class="text-center">Τίτλος</th>
        <th class="text-center">Περιγραφή</th>
		<th class="text-center">Ημερομηνία Δημοσίευσης</th>
		<th class="text-center">Δυνατότητες</th>
      </tr>
    </thead>
    <tbody id ="post_data">
<?php

include ("../connectPDO.php");

$stmt = $conn->prepare("SELECT * FROM contents WHERE status = 1 AND published_index_page = 1 ORDER BY publication_id DESC LIMIT 4");
$stmt->execute();
$result = $stmt->fetchAll();
include ("../links.php");

$row_count = 1;		
if (sizeof($result)> 0) {
	foreach($result as $row) {
		
		$id = $row['id'];
		$timestamp  = strtotime($row['publication_date']); 
		$date = date('d-m-Y',$timestamp);
		$body = makeLinks($row['body']);
		$title = $row['title'];
		$description = $row['description'];
		$publication_id = $row['publication_id'];
													
		$stmt4 = $conn->prepare("SELECT * from images WHERE contentID = ?");   
		$stmt4->execute(array($id));		// αναζητώ αν υπάρχουν αποθηκευμένες εικόνες για την συγκεκριμένη δημοσίευσή													
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
							echo '<img id = "'.$image_id.'" onclick="javascript:openModal(this.id);" onmouseover="" style="cursor: pointer;" src ="../content_images/'.$images_path.'" width=100 height=100>';   
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
		echo '<tr><td colspan = "4"> Δεν υπάρχουν διαθέσιμες Εκδηλώσεις – Δράσεις – Ανακοινώσεις </td></tr>';
}	
							
?>	
    </tbody>
  </table>
</div><br>  
  <div class = "show_more">
		 <a href = "university_content.php">Δείτε όλες τις Εκδηλώσεις – Δράσεις – Ανακοινώσεις</a> 
  </div>
  
<br><br>
</section>

<section id="communicate">
<hr>
<h3 bgcolor="#E6E6FA">ΣΤΟΙΧΕΙΑ ΕΠΙΚΟΙΝΩΝΙΑΣ ΜΕ ΔΙΑΧΕΙΡΙΣΤΕΣ</h3><br>

<div class="container">    
  <p>Οι απόφοιτοί μας που επιθυμούν να ενεργοποιήσουν την καρτέλα με τα στοιχεία τους και να μας δώσουν την απαραίτητη έγκριση για τη δημοσίευσή τους, θα πρέπει να επικοινωνήσουν μαζί μας στέλνοντας email στους διαχειριστές του ιστοχώρου αναφέροντας, το ονοματεπώνυμο τους, το αριθμό μητρώου φοιτητή (ΑΕΜ) που είχαν στα χρόνια φοίτησης και το τμήμα στο οποίο φοίτησαν, ώστε να τους αποσταλεί το username και ο κωδικός επεξεργασίας του προφίλ τους. <br><br> Επίσης μπορούν να εγγραφούν στο τμήμα που τους ενδιαφέρει χρησιμοποιώντας την φόρμα εγγραφής.</p>
  <br>
  <p><span style="color:#0059b3" class="glyphicon">&#x2709;</span> Email επικοινωνίας:   XXXXXXXX@xxxxx.com</p>
  <p><span style="color:#0059b3" class="glyphicon">&#xe182;</span> Τηλέφωνο επικοινωνίας:   ΧΧΧ ΧΧΧ ΧΧΧΧ</p>

</div>
<br><br><br><br><br><br><br><br><br><br><br><br><br>
</section>

<div id="myModal" class="modal fade" role="dialog"> <!-- modal για να εμφανίζονται σε μεγαλύτερο μεγεθος οι εικόνες -->
    <button type="button" class="close" data-dismiss="modal">&times;</button>
	<img id="modalImageId" src="" class="modal-content">
</div>

<footer class="container-fluid"></footer>
<script>
$(document).ready(function() {
	
	// cut τα μεγαλα κειμενα (>50) ωστε να φαινονται πιο όμορφα
	var showChar = 50;
	var ellipsestext = "...";
	var moretext = "more";
	var counter = 0; 	
	
	$('.more').each(function() {  

		var content = $(this).html();
				
		if(content.length > showChar) {

			var c = content.substr(0, showChar);
			c = c.substr(0, Math.min(c.length, c.lastIndexOf(" ")))
			
			var h = content.substr(c.length, content.length);
			var more = '<span id="more2'+counter+'" style="display:none;">' + h + '</span>';
			var content = '<span style="display:inline">' + c + '</span>';
			var elementid = counter;
			
			var html = content + '<span id="moreellipses'+counter+'">' + ellipsestext + '&nbsp;</span>&nbsp;'+ more + '<span id="morelink'+counter+'"><a href="" onclick="javascript:showMore(event, \'' + elementid + '\')">' + moretext + ' </a></span>';
			$(this).html(html);
			counter = counter + 1;
		} 

	});	
	
	$.post("guest_header.php", {	
	}, function(data) {
		$('header').html(data);
	});
	
	$.post("../footer.php", {	
	}, function(data) {
		$('footer').html(data);
	});
				
});
</script>

<script type="text/javascript">
	function openModal(id) {
		var imgsrc = document.getElementById(id).src;  //χρησιμοποιώ το id της εικόνας και βρίσκω το src το οποίο δίνω σαν src στην εικόνα του modal
		$('#modalImageId').attr('src',imgsrc); 
		$('#myModal').modal('show');  //εμφανίζω το modal
	}
	
	
	function showMore(ev, id){  //συναρτηση η οποία εμφανιζει το υπολοιπο κειμενο στην περιπτωση που κοπει
		ev.preventDefault();
		$('span[id^="moreellipses'+id+'"]').remove();
		$('span[id^="morelink'+id+'"]').remove();
		var Elementid = "more2"+id+"";
		document.getElementById(Elementid).style.display = "inline";
	} 
</script>		

</body>
</html>
