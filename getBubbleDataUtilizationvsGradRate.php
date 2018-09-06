<?php 
include("db.php");

$DataPull = "SELECT * FROM utilization 
WHERE 
	utilization_rate < 4 and
	utilization_rate > .1 and 
	type != '' ";

$order_by = 'order by type';


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
    array('label' => 'Graduation Rate', 'type' => 'number'),
    
    //column 2 Y coordinate
    array('label' => 'Utilization Rate', 'type' => 'number'),
    
    //column 3 Color (String or number)
    array('label' => 'Type', 'type' => 'string'),
    
    //column 4 Size of bubble
    array('label' => 'TTM', 'type' => 'number')
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
    $temp[] = array('v' => (float) $nt['grad_rate'], 'f' =>NULL);
   
    //column 2 Y coordinate
    $temp[] = array('v' => (float) $nt['utilization_rate'], 'f' =>NULL);
   
    //column 3 Color 
    $temp[] = array('v' => (string) $nt['type'], 'f' =>NULL);
   
    //column 4 Size of bubble
    $temp[] = array('v' => (int) $nt['ttm'], 'f' =>NULL);

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