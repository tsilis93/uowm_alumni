<?php

include ("../connectPDO.php");
$link = "https://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

header("Content-Type: application/rss+xml; charset=UTF-8");

$rssfeed = '<?xml version="1.0" encoding="UTF-8"?>';
$rssfeed .= "\n" . '<rss version="2.0">'  . "\n\t";

$rssfeed .= '<channel>'  . "\n\n\t";
$rssfeed .= '<title>Index page Announcements feed</title>'  . "\n\t";
$rssfeed .= '<link>'.$link.'</link>'  . "\n\t";
$rssfeed .= '<description>This is a RSS feed for Index page Announcements</description>'  . "\n\t";
$rssfeed .= '<language>el</language>'  . "\n\t";
$rssfeed .= '<copyright>Copyright (C) 2018 Alumni_UOWM</copyright>' . "\n\t";

$stmt = $conn->prepare("SELECT * FROM contents WHERE status = 1 AND published_index_page = 1 ORDER BY publication_id DESC LIMIT 4");
$stmt->execute();
$result = $stmt->fetchAll();

if (sizeof($result)> 0) {
	foreach($result as $row) {
		
		$id = $row['id'];
		$timestamp  = strtotime($row['publication_date']); 
		$date = date("D, d M Y H:i:s O",$timestamp);
		$body = $row['body'];
		$title = $row['title'];
		$description = $row['description'];
		
		$rssfeed .= "\n" . '<item>'  . "\n\t";
		$rssfeed .= '<title>' . $title . '</title>'  . "\n\t";
		$rssfeed .= '<description>' . $description . '</description>'  . "\n\t";
		$rssfeed .= '<pubDate>' . $date . '</pubDate>'  . "\n\t";
		
		$stmt2 = $conn->prepare("SELECT * from images WHERE contentID = ?");
		$stmt2->execute(array($id));		// αναζητώ αν υπάρχουν αποθηκευμένες εικόνες για την συγκεκριμένη δημοσίευσή													
		$result2 = $stmt2->fetchAll();
		
		if (sizeof($result2)> 0) {  //αν υπάρχουν εικόνες 
			$body = $body . "<p>";
			foreach($result2 as $row2) {
				$images_path = $row2['images_path'];
				$image =  '<img src ="../content_images/'.$images_path.'" width=100 height=100>&nbsp;&nbsp;'; 
				$body = $body . $image;
			}
			$body = $body . "</p>";
		}
		
		$rssfeed .= '<body><![CDATA[' . $body . ']]></body>' . "\n\t";
		$rssfeed .= "\n" . '</item>' . "\n\t";
		
	}
}


$rssfeed .= "\n\t" . '</channel>' . "\n";
$rssfeed .= '</rss>';

echo $rssfeed;

?>