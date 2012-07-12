<?php

	include_once('db_connect.php');
	$query = mysql_query('SELECT * from '.$database_table.'');
    
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
	/*$year = date('Y');
	$month = date('m');

	echo json_encode(array(
	
		array(
			'id' => 111,
			'title' => "Event1",
			'start' => "$year-$month-10",
			'url' => "http://yahoo.com/"
		),
		
		array(
			'id' => 222,
			'title' => "Event2",
			'start' => "$year-$month-20",
			'end' => "$year-$month-22",
			'url' => "http://yahoo.com/"
		)
	
	));*/

?>
