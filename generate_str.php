<?php

/*подключение к БД*/
//require_once dirname(__FILE__) . '/bootstrap.php';
require_once dirname(__FILE__) . '/vendor/autoload.php';
$connection=mysql_connect("172.26.200.14","str_natali","str_natali");
mysql_query("set names 'utf8'");
mysql_select_db("str");




/* in data */

$date_start=date('Y-m-d');


$sql = 'SELECT rec.`latitude`, rec.`longitude`, t.`mark`, DATE_FORMAT(NOW(),"%Y-%m-%d") AS dateduty,t.`numbsign`, c.`id_type` FROM car AS c

LEFT JOIN ss.`technics` AS t ON t.`id`=c.`id_teh`
LEFT JOIN ss.`records` AS rec ON rec.`id`=t.`id_record`

WHERE "'.$date_start.'"=c.`dateduty` AND c.`id_teh` NOT IN (
SELECT rc.`id_teh`  FROM reservecar AS rc WHERE ("'.$date_start.'" BETWEEN rc.`date1` AND rc.`date2`) OR ("'.$date_start.'">=rc.`date1` AND rc.`date2` IS NULL)
) AND c.`id_type`=1
UNION
(
SELECT  rec1.`latitude`,rec1.`longitude`, t1.`mark`, DATE_FORMAT(NOW(),"%Y-%m-%d") AS dateduty,t1.`numbsign`, ca.`id_type`  FROM reservecar AS rc
LEFT JOIN ss.`records` AS rec1 ON rec1.`id`=rc.`id_card`
LEFT JOIN ss.`technics` AS t1 ON t1.`id`=rc.`id_teh`

LEFT JOIN car AS ca ON ca.`id_teh`=rc.`id_teh` AND ca.`dateduty`="'.$date_start.'"


WHERE (("'.$date_start.'" BETWEEN rc.`date1` AND rc.`date2`) OR ("'.$date_start.'">=rc.`date1` AND rc.`date2` IS NULL)) AND ca.`id_type`=1
)';



//echo $sql;
$rigs=mysql_query($sql);
$totalitems1 =  mysql_num_rows($rigs);
//echo $totalitems1;

if ($totalitems1 == 0) {
	//echo '1';

	$date_1_day = new DateTime('-1 days');
	$date_start=$date_1_day->format('Y-m-d');

$sql = 'SELECT rec.`latitude`, rec.`longitude`, t.`mark`, DATE_FORMAT(NOW(),"%Y-%m-%d") AS dateduty,t.`numbsign`, c.`id_type` FROM car AS c

LEFT JOIN ss.`technics` AS t ON t.`id`=c.`id_teh`
LEFT JOIN ss.`records` AS rec ON rec.`id`=t.`id_record`

WHERE "'.$date_start.'"=c.`dateduty` AND c.`id_teh` NOT IN (
SELECT rc.`id_teh`  FROM reservecar AS rc WHERE ("'.$date_start.'" BETWEEN rc.`date1` AND rc.`date2`) OR ("'.$date_start.'">=rc.`date1` AND rc.`date2` IS NULL)
) AND c.`id_type`=1
UNION
(
SELECT  rec1.`latitude`,rec1.`longitude`, t1.`mark`, DATE_FORMAT(NOW(),"%Y-%m-%d") AS dateduty,t1.`numbsign`, ca.`id_type`  FROM reservecar AS rc
LEFT JOIN ss.`records` AS rec1 ON rec1.`id`=rc.`id_card`
LEFT JOIN ss.`technics` AS t1 ON t1.`id`=rc.`id_teh`

LEFT JOIN car AS ca ON ca.`id_teh`=rc.`id_teh` AND ca.`dateduty`="'.$date_start.'"


WHERE (("'.$date_start.'" BETWEEN rc.`date1` AND rc.`date2`) OR ("'.$date_start.'">=rc.`date1` AND rc.`date2` IS NULL)) AND ca.`id_type`=1
)';

//echo $sql;
$rigs=mysql_query($sql);
$totalitems1 =  mysql_num_rows($rigs);
}


//print_r($rigs);
//exit();


if ($totalitems1 > 0) {

    /* export to csv */
    $inf = array();
       while ($row=mysql_fetch_array($rigs))
     {
          $mark1=$detail_1 = trim(str_replace(array("\r\n", "\r", "\n"), '', strip_tags($row['mark'])));

           $mark = trim(str_replace(array('"', "'", ";"), ' ', strip_tags($mark1)));

     $inf[] = array('lat' => $row['latitude'], 'lon' => $row['longitude'], 'mark' => $mark, 'dateduty' => $row['dateduty'], 'numbsign' => $row['numbsign']);


    //echo $myrow["id"] . '-----' . $myrow['time_msg'];
    //echo '<br>';
   // echo $row['numbsign'];echo '<br>';
    }

//print_r($inf);
//exit();
    if(isset($inf) && !empty($inf)){
        $csv = new ParseCsv\Csv('data.csv');
       // $csv->encoding( 'UTF-8');
        # When saving,  write the header row:
        $csv->heading = FALSE;
        # Specify which columns to write, and in which order.
        # We won't output the 'Awesome' column this time.
        $csv->titles = ['lat', 'lon', 'mark', 'dateduty', 'numbsign'];
          # Data to write:
//        $csv->data = [
//            0 => ['Name' => 'Anne', 'Age' => 45, 'Awesome' => true],
//            1 => ['Name' => 'John', 'Age' => 44, 'Awesome' => false],
//        ];


        $csv->delimiter = ";";
        $csv->data = $inf;

        //$path = $_SERVER['DOCUMENT_ROOT'] . '/out';
        $path = 'out';

        if ($csv->save($path . '/ex_sz_limit50.csv',true)) {
			//echo '1';
           // $data['is_save'] = array('success','Выезды успешно сохранены в папку 172.26.200.14/www/out/. Имя файла ex_jor.csv. ');
        } else {
		//	echo '2';
           // $data['is_save'] = array('danger','Что-то пошло не так. ');
        }
    }



}




