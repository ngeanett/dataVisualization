<?php 
include("db.php");

$DataPull = "SELECT * FROM utilization 
WHERE 
	utilization_rate < 4 and
	utilization_rate > .1 and 
	type != '' ";

$order_by = 'order by type';

//$get_type = $_GET['type'];

$DataPull .= $order_by;

$result = $conn->query($DataPull);

// get all types
$type_result = $conn->query("SELECT distinct type FROM utilization order by type;");
$type_list = array();
while ($nt = $type_result->fetch_assoc())
{
	// Store off type list
	$type_list[]= $nt['type'];
}

// Get distinct (n) count for each type
$type_count_list = array();

foreach ($type_list as $type){	
	$type_count_sql = "SELECT count(type) FROM utilization where type = '" . $type . "'";
	$type_count_rs = $conn->query($type_count_sql);
	$type_count_list[$type] = $type_count_rs->fetch_row();
}

// Create Json table used to store google chart data object
$table = array();

// cols object used for column labels
$table['cols'] = array(
	//column 0 ID (name) of the bubble
    array('label' => 'Member', 'type' => 'string'),
    
    //column 1 X coordinate (google calls it h)s
    array('label' => 'Type', 'type' => 'number'),
    
    //column 2 Y coordinate
    array('label' => 'Utilization', 'type' => 'number'),
    
    //column 3 Color (String or number)
    array('label' => 'TTM', 'type' => 'string'),
    
    //column 4 Size of bubble
    array('label' => 'Enrollment', 'type' => 'number')
);

// Rows array used for results from query
$rows = array();

// Format Array for json object and add values from query
while ($nt = $result->fetch_assoc())
{
    $temp = array();
    
    //column 0 ID (name) of the bubble
    $temp[] = array('v' => (string) $nt['member_name'], 'f' =>NULL);
	
	//column 1 X coordinate (google calls it h)s
	$haxis_type = null;
	
	switch ($nt['type'])
	{
		case 'Private for profit 2 year':
			$haxis_type = mt_rand(1,9)/10;
			//$type_count_list['Private for profit 2 year'][0];
			break;
		case 'Private for profit 4 year or above':
			$haxis_type = mt_rand(11,19)/10;
			break;	
		case 'Private not for profit less than 2 year':
			$haxis_type = mt_rand(21,24)/10;
			break;
		case 'Private not for profit 2 year':
			$haxis_type = mt_rand(25,29)/10;
			break;
		case 'Private not for profit 4 year or above':
			$haxis_type = mt_rand(31,44)/10;
			break;
		case 'Public 2 year':
			$haxis_type = mt_rand(45,59)/10;
			break;
		case 'Public 4 year or above':
			$haxis_type = mt_rand(61,77)/10;
			break;
		case 'Administrative Unit':
			$haxis_type = mt_rand(78,79)/10;
			break;	
	}
    $temp[] = array('v' => $haxis_type, 'f' =>NULL);
   
    //column 2 Y coordinate
    $temp[] = array('v' => (float) $nt['utilization_rate'], 'f' =>NULL);
   
    //column 3 Color 
    $temp[] = array('v' => (string) $nt['type'], 'f' =>NULL);
   
    //column 4 Size of bubble
    $temp[] = array('v' => (float) $nt['enrollment'], 'f' =>NULL);

    // Add row array to array
    $rows[] = array('c' => $temp);
}

// Store rows as part of google chart json array
$table['rows'] = $rows;

// Convert result set to json
$jsonTable = json_encode($table, JSON_NUMERIC_CHECK);
//$jsonTable = json_encode($table, JSON_PRETTY_PRINT); // used for debugging with <pre>

echo $jsonTable;

?>