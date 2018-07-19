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
 
</head>

<body> 
<header>
</header>

<?php 
session_start();
include ("../connectPDO.php");

if(isset($_GET['id'])) {
	$id = $_GET['id']; // id του τμήματος
}

// φέρνω τα images path από την βάση		
$stmt4 = $conn->prepare("SELECT * from images WHERE departmentID = ?");
$stmt4->execute(array($id));
$result4 = $stmt4->fetchAll();
$images_path;  // πινακας που αποθηκεύονται τα paths
$counter2 = 0; //μετρητής για τις φώτο

if (sizeof($result4)> 0) {
	foreach($result4 as $row4) {
		$images_path[$counter2] = $row4['images_path'];	
		$counter2 = $counter2 + 1;
	} 
}
		
// κανω fetch τα δεδομένα από την βάση
$stmt2 = $conn->prepare("SELECT * from departments WHERE id = ?");
$stmt2->execute(array($id));
$result2 = $stmt2->fetch(PDO::FETCH_ASSOC);

$nav_color;
$about_text;
$promp_text;

if (sizeof($result2)> 0) {
	$nav_color = $result2['nav_color'];	
	$about_text = $result2['about_text'];
	$promp_text = $result2['promp_text'];
}
	 
?>

<section id="home">
<br><br><br><br>
<div id="myCarousel" class="carousel slide" data-ride="carousel">
    <!-- Indicators -->
    <ol class="carousel-indicators">
      <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
      <li data-target="#myCarousel" data-slide-to="1"></li>
    </ol>

    <!-- Wrapper for slides -->
    <div class="carousel-inner" role="listbox">
	
		 <div class="item active">
			<img src="" alt="Image" class = "picture1">     
		</div>

      <div class="item">
        <img src="" alt="Image" class = "picture2">
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

<br>
<div class="container text-center">
	<h1></h1>
</div>
</section> 

<section id="about"> 
<br><br><br>
<hr>
<h3 bgcolor="#E6E6FA">ΣΧΕΤΙΚΑ ΜΕ ΤΟΝ ΙΣΤΟΤΟΠΟ</h3><br>
 
<div class="container text-center">   
  <br>
  <p class = "about"></p>
  
</div>

</section> 


<section id="action">
<br><br><br>
<hr>
<h3 bgcolor="#E6E6FA">ΑΝΑΚΟΙΝΩΣΕΙΣ ΤΟΥ ΤΜΗΜΑΤΟΣ <a href="department_content_feed.php?id=<?php echo $id; ?>" target="_blank"><img src="../assets/Feed-icon.png" alt="Feed-icon" id="feed"></a></h3><br>

<div class = "divtable">
	<table class = "table" id="cssTable" width="100%">
	<thead>
		<tr>
			<th class="text-center">Τίτλος</th>
			<th class="text-center">Περιγραφή</th>
			<th class="text-center">Ημερομηνία Δημοσίευσης</th>
			<th class="text-center">Δυνατότητες</th>
		</tr>
	</thead>
	<tbody id = "post_data">
<?php 
$department = "published_department" . $id;  //ελεγχω αν η σημαια δημοσίευσης του τμήματος με id = $id ειναι 1

$stmt = $conn->prepare("SELECT * FROM contents WHERE status = 1 AND ".$department." = 1 ORDER BY publication_id DESC LIMIT 4");
$stmt->execute();
$result = $stmt->fetchAll();
include ("../links.php");

$row_count = 1;		
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
	</tbody>
	</table>
</div>	
	<div class = "show_more">
		<a href="departments_content.php?id=<?php echo $id; ?>" class="btn btn-link">Δείτε όλες τις Ανακοινώσεις</a>
	</div>

<br><br><br><br>	
</section>


<section id="stories">
<hr>
<h3 bgcolor="#E6E6FA">ΙΣΤΟΡΙΕΣ ΤΟΥ ΤΜΗΜΑΤΟΣ</h3><br><br>

<div class = "divtable">
	<table class = "table" id="cssTable" width="100%">
	<thead>
		<tr>
			<th class="text-center">Τίτλος</th>
			<th class="text-center">Περιγραφή</th>
			<th class="text-center">Ημερομηνία Δημοσίευσης</th>
			<th class="text-center">Δυνατότητες</th>
		</tr>
	</thead>
	<tbody id = "story_data"> 
<?php

if(isset($_SESSION['student']) || isset($_SESSION['name'])) 
{
	$stmt = $conn->prepare("SELECT * FROM stories WHERE status = 1 AND ".$department." = 1 ORDER BY publication_id DESC LIMIT 4");
}
else
{																							//definition = 1 = δημόσια ιστορία
	$stmt = $conn->prepare("SELECT * FROM stories WHERE status = 1 AND ".$department." = 1 AND definition = 1 ORDER BY publication_id DESC LIMIT 4");
}
$stmt->execute();
$result = $stmt->fetchAll();

$row_count2 = 1;		
if (sizeof($result)> 0) {
	foreach($result as $row) {

		$storyid  = $row['id'];
		$timestamp  = strtotime($row['publication_date']); 
		$date = date('d-m-Y',$timestamp);
		$body = makeLinks($row['body']);
		$title = $row['title'];
		$description = $row['description'];
		$publication_id = $row['publication_id'];
		
		$stmt5 = $conn->prepare("SELECT * from images WHERE storyID = ?");   
		$stmt5->execute(array($storyid));		// αναζητώ αν υπάρχουν αποθηκευμένες εικόνες για την συγκεκριμένη ιστορία													
		$result5 = $stmt5->fetchAll();
		
		echo '<tr class="post-id" id="'.$publication_id.'">';
		echo '<td class="mores" width="25%">'.$title.'</td>';
		echo '<td class="mores" width="25%">'.$description.'</td>';
		echo '<td width="25%">'.$date.'</td>';
		echo '<td width="25%"><a href="#s'.$row_count2.'" class="btn btn-info" data-toggle="collapse">Λεπτομέρειες</a></td>'; 
		echo "<tr>";
			echo '<td style ="text-align:left" colspan = "4">';
			echo '<div id="s'.$row_count2.'" class="collapse">';
			echo $body;
			if (sizeof($result5)> 0) {  //αν υπάρχουν εικόνες τότε τις εμφανίζω την μια διπλα στην αλλη
				echo "<br><br>";
				foreach($result5 as $row5) {
					$images_path3 = $row5['images_path'];
					$image_id2 = $row5['id']; // αποθηκεύω το id της εικόνας από την βάση στο id του tag <img>
					echo '<img id = "'.$image_id2.'" onclick="javascript:openModal(this.id);" onmouseover="" style="cursor: pointer;" src ="../content_images/'.$images_path3.'" width=100 height=100>';  //allakse to assets se images 
					echo "&nbsp;&nbsp;";			// περνάω στην συνάρτηση το <tag> id και παράλληλα το id της εικόνας
				}
			}			
			echo "</div>";
			echo '</td>';
		echo "</tr>";
		
		
		echo "</tr>";
		$row_count2 = $row_count2 + 1;
	}
}
else
{
	echo '<tr><td colspan = "4"> Δεν υπάρχουν διαθέσιμες Ιστορίες </td></tr>';
}

?>
	</tbody>
	</table>
</div>	
	<div class = "show_more">
		<a href="departments_stories.php?id=<?php echo $id; ?>" class="btn btn-link">Δείτε όλες τις Ιστορίες</a>
	</div>

<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
</section>

<div id="myModal" class="modal fade" role="dialog"> <!-- modal για να εμφανίζονται σε μεγαλύτερο μεγεθος οι εικόνες -->
    <button type="button" class="close" data-dismiss="modal">&times;</button>
	<img id="modalImageId" src="" class="modal-content">
</div>


<footer class="container-fluid"></footer>

<script>
$(document).ready(function(){

	// cut τα μεγαλα κειμενα (>50) ωστε να φαινονται πιο όμορφα
	var showChar = 50;
	var ellipsestext = "...";
	var moretext = "more";
	var counter = 0;
	var counter2 = 0;	
	
	//συνάρτηση που κάνει δυναμικά το setup των αρχικών σελίδων των σχολών
	
	$.post("../footer.php", {	
	}, function(data) {
		$('footer').html(data);
	});	
	
	 var id = <?php echo(json_encode($id)); ?>;  // παίρνω το id από την dropdown λίστα
	 var i; //μετρητής για φώτο
	 var picture_array = new Array();
	 
	 picture_array = <?php if(!empty($images_path)) { echo(json_encode($images_path)); } else { echo(json_encode("")); }?>;
	 var nav_color = <?php if(!empty($nav_color)) { echo(json_encode($nav_color)); } else { echo(json_encode("")); }?>;
	 var about_text = <?php if(!empty($about_text)) { echo(json_encode($about_text)); } else { echo(json_encode("")); }?>;
	 var promp_text = <?php if(!empty($promp_text)) { echo(json_encode($promp_text)); } else { echo(json_encode("")); }?>;
	 
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
			$(".about").text(about_text);
			$("h1").text(promp_text);

			$(".picture1").attr("src",'../assets/'+ picture_array[0]);
			$(".picture2").attr("src",'../assets/'+ picture_array[1]);	
		}
	 });

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


	$('.mores').each(function() {  

		var content2 = $(this).html();
				
		if(content2.length > showChar) {

			var c = content2.substr(0, showChar);
			c = c.substr(0, Math.min(c.length, c.lastIndexOf(" ")))
			
			var h = content2.substr(c.length, content2.length);
			var more = '<span id="more4'+counter2+'" style="display:none;">' + h + '</span>';
			var content2 = '<span style="display:inline">' + c + '</span>';
			var elementid2 = counter2;
			
			var html = content2 + '<span id="moreellipses2'+counter2+'">' + ellipsestext + '&nbsp;</span>&nbsp;'+ more + '<span id="morelink2'+counter2+'"><a href="" onclick="javascript:showMore2(event, \'' + elementid2 + '\')">' + moretext + ' </a></span>';
			$(this).html(html);
			counter2 = counter2 + 1;
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

	function showMore2(ev, id){  //συναρτηση η οποία εμφανιζει το υπολοιπο κειμενο στην περιπτωση που κοπει
		ev.preventDefault();
		$('span[id^="moreellipses2'+id+'"]').remove();
		$('span[id^="morelink2'+id+'"]').remove();
		var Elementid = "more4"+id+"";
		document.getElementById(Elementid).style.display = "inline";
	}  	
</script>


</body>
</html>
