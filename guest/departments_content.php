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

  <link rel="stylesheet" href="../css/guest.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
 
</head>

<body> 

<header></header>

<?php 

session_start();
$id = $_GET['id'];
include ("../connectPDO.php");
	
	// κανω fetch τα δεδομένα από την βάση
$stmt2 = $conn->prepare("SELECT * from departments WHERE id = ?");
$stmt2->execute(array($id));
$result2 = $stmt2->fetch(PDO::FETCH_ASSOC);
	
if (sizeof($result2)> 0) {
	$nav_color = $result2['nav_color'];	
}

?>

<br><br><br><br><br>

<h3 bgcolor="#E6E6FA"></h3><br>

<div class = "divtable">
	<table class = "table" id="cssTable" width="100%">
	<thead>
		<tr>
			<th class="text-center">Τίτλος</th>
			<th class="text-center">Περιγραφή</th>
			<th class="text-center">Ημερομηνία Δημοσίευσης</th>
		</tr>
	</thead>

	<tbody id = "post_data">
<?php
$department = "published_department" . $id;  //ελεγχω αν η σημαια δημοσίευσης του τμήματος με id = $id ειναι 1

$stmt = $conn->prepare("SELECT * FROM contents WHERE status = 1 AND ".$department." = 1 ORDER BY publication_id DESC LIMIT 4");
$stmt->execute();
$result = $stmt->fetchAll();
include ("../links.php");
		
$row_count = 1;		//αρχικοποιώ τον μετρήτη για τις ανακοινώσεις
if (sizeof($result)> 0) {
	foreach($result as $row) {
		
		$contentid = $row['id'];
		$timestamp  = strtotime($row['publication_date']); 
		$temp = date('d-m-Y',$timestamp);
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
		echo '<td width="25%">'.$temp.'</td>';
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
	</tbody>
	</table>
</div>
	
	<div class="ajax-load text-center" style="display:none">
		<i class="fa fa-spinner fa-spin" style="font-size:30px"></i>
		<p style = "text-align:center">Loading More post</p>
	</div>
	
	<div class = "show_more">
		 <input class="btn btn-link" type="button" id = "btnMore" value = "Δείτε και άλλες Ανακοινώσεις" /> 
	</div>

<div id="myModal" class="modal fade" role="dialog"> <!-- modal για να εμφανίζονται σε μεγαλύτερο μεγεθος οι εικόνες -->
    <button type="button" class="close" data-dismiss="modal">&times;</button>
	<img id="modalImageId" src="" class="modal-content">
</div>	


<br><br><br><br>
<footer class="container-fluid text-center">
  Footer Text
</footer>

<script>
$(document).ready(function(){

	// cut τα μεγαλα κειμενα (>50) ωστε να φαινονται πιο όμορφα
	var showChar = 50;
	var ellipsestext = "...";
	var moretext = "more";
	var counter = 0; 
	
	$.post("../footer.php", {	
	}, function(data) {
		$('footer').html(data);
	});	
	
	var id = <?php echo(json_encode($_GET['id'])); ?>;
	
	var nav_color = <?php if(!empty($nav_color)) { echo(json_encode($nav_color)); } else { echo(json_encode("")); }?>;
	
	$.post("guest_department_header.php", {
		id: id
	}, function(data) {
		if (!$.trim(data)){ 
			location.replace("../notFound.php");
		}
		else
		{	
			$('header').html(data);
			$(".navbar").css("background", nav_color);
			$('h3').html("ΑΝΑΚΟΙΝΩΣΕΙΣ ΤΟΥ ΤΜΗΜΑΤΟΣ");
		}
	});
	
	
	$('#btnMore').click(function() {

		var last_id = $(".post-id:last").attr("id");
		var row_count = $('#cssTable tr').length; //μετράω τον αριθμό των ανακοινώσεων στον πινακα,
		row_count = ((row_count-1)/2)+1;  //αφαιρώ την 1η γραμμή που ειναι η επικεφαλίδα, διαιρώ με το 2 για να βγάλω τις 
										// γραμμές των περιεχομένων και προσθέτο 1 για να παώ στην επόμενη ανακοίνωση

		$.get("more_departments_content.php", {last_id: last_id, id: id, row_count: row_count}, function(data, status) {
			
			$('.ajax-load').show();
			
			setTimeout(function(){ 
				$('.ajax-load').hide();
				$('#post_data').append(data);
				
				$('.more').each(function() {  // σε όλα τα νέα και προηγούμενα δεδομενα πρόσθεσε το read_more link
											  // οπου χρειάζεται
					var content = $(this).html();
					
					if ($(this).hasClass("done")) {
						// αν το εχεις προσθέσει ηδη προχώρα παρακάτω
					}
					else if(content.length > showChar) {

						var c = content.substr(0, showChar);
						c = c.substr(0, Math.min(c.length, c.lastIndexOf(" ")))
						
						var h = content.substr(c.length, content.length);
						var more = '<span id="more2'+counter+'" style="display:none;">' + h + '</span>';
						var content = '<span style="display:inline">' + c + '</span>';
						var elementid = counter;
						$(this).addClass("done");
						
						var html = content + '<span id="moreellipses'+counter+'">' + ellipsestext + '&nbsp;</span>&nbsp;'+ more + '<span id="morelink'+counter+'"><a href="" onclick="javascript:showMore(event, \'' + elementid + '\')">' + moretext + ' </a></span>';
						$(this).html(html);
						counter = counter + 1;
					} 

				});
				
			}, 2000);
		
		})

	});

	$('.more').each(function() {  //προσθεσε read more οταν ειναι απαραίτητο

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
			$(this).addClass("done"); // προσθεσε την κλάση "done" για να παραλήψεις το συγκεκριμένο read_more
			counter = counter + 1;
		} 
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
