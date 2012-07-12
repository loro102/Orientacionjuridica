<?php

	include_once('db_connect.php');
	$year = date('Y');
	$month = date('m');


    $title=$_GET['t'];
	$start=$_GET['s'];
	$end=$_GET['e'];

	
	$query = mysql_query("INSERT INTO calendar_list (title,start,end) VALUES('$title','$start','$end')");

	
	/*
	json_encode(
	   array(
	         'id' => 111,
			'title' => "title",
			'start' => "$year-$month-10",
			'url' => "http://yahoo.com/"
		));
	// qua provo a inserire nel db
	
//------------------- START TEST -----------------------------------------	                     
     // continuiamo la sessione
     session_start();
     // eseguiamo il filtraggio dell'input per ovviare ad eventuali XSS
     $x = htmlspecialchars( $_GET['x'] );
     $y = htmlspecialchars( $_GET['y'] );
     // setto le variabili di sessione che contengono la risoluzione dello schermo dell'utente
     $_SESSION['x'] = $x;
     $_SESSION['y'] = $y;
//-------------- END TEST  --------------------------------
	while($fetch = mysql_fetch_assoc($query)) {
	$content[]=$fetch;
	}

	$how_many = count($content);
	$i = 1;
	
	echo '['; 
	foreach ($content as $item) {
	
	echo '{ "id": "'.$item['id'].'", "title": "'.$item['title'].'", "start": "'.$item['start'].'", "end": "'.$item['end'].'" }';
	if($how_many==$i) {echo '';} else {echo ',';}
	$i++;
	}
	
	echo ']';
    /* [{"id":1 , "start" : "Tue May 12 2009 05:45:00 GMT+0300" , "end" : "Tue May 12 2009 07:45:00 GMT+0300" , "title" : "test"},{"id":1 , "start" : "Thu May 14 2009 05:15:00 GMT+0300" , "end" : "Thu May 14 2009 06:45:00 GMT+0300" , "title" : "test"}] */
	
	mysql_close($connect);
	

?>
