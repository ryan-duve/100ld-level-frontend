<?php

//level.php
//returns 100ld liquid level

 $connect = mysql_connect("localhost","uva","uva1");
 mysql_select_db("slowcontrols",$connect);
 
 //make one query for all data in last 2 minute
 //http://stackoverflow.com/questions/4961524/mysql-query-latest-timestamp-unique-value-from-the-last-30-minutes
 $level_query="SELECT device AS d, created_at AS createdtime, measurement_reading AS measurement FROM usb1608g WHERE created_at > (now() - interval 10000 second)";


 //not getting anything back?  try running this
// $test_query="SELECT device AS d, created_at AS createdtime, measurement_reading AS measurement FROM usb1208ls WHERE created_at > (now() - interval 120 second)";
// $test_result=mysql_query($test_query) or die(mysql_error());
// $test_r=mysql_fetch_assoc($test_result);
// echo "console.log('".$test_r['d']."');";


 //run one query
 $level_result=mysql_query($level_query) or die(mysql_error());

 //go through results, stacking four data arrays

 while($r=mysql_fetch_assoc($level_result)){

	//factor of 1K for javascript time, subtract 18K seconds for flot EST->GMT fudge factor
 	$ctime=(strtotime($r["createdtime"])-18000)*1000;

	switch ($r["d"]){
		case "d1": 
			$data0[]="[\"".$ctime."\",\"".$r["measurement"]."\"]";
			break;
	}
 }

 //echo "data0=".implode(',',$data0)."\n";

 //make output like:
 //[	{ label:"Foo", data: [ [10, 1], [17, -14] },
 // 	{ label:"Bar", data: [ [11, 13], [19, 11] }
 //]

 $res="{\"100ld-level\":";
 $res.="{\"label\":\"100LD Level\",\"data\":[";
 $res.=implode(',',$data0);
 $res.="]}}";

 echo $res;

 //echo "console.log('leaving level.php')";

?>
