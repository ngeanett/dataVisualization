<?php 
include("db.php");

$DataPull = 
"SELECT u.member_name as member_name, u.type as type, u.utilization_rate as utilization_rate, 
aov.documentCount as documentCount, u.enrollment as enrollment
FROM utilization u
join ng_warehouse.alpha_order_volume_june_15 as aov on u.member_name = aov.school_name
where 
	u.service like '%A-Code%' and
	utilization_rate < 4 and
	utilization_rate > .1 and 
	documentCount > 0 and 
	type != ''";

$order_by = 'order by member_name';

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

// Create Json table used to store google chart data object
$table = array();

// cols object used for column labels
$table['cols'] = array(
    array('label' => 'Member', 'type' => 'string'),
    array('label' => 'Type', 'type' => 'string'),
    array('label' => 'Trailing Month', 'type' => 'number'),
    array('label' => 'Utilization', 'type' => 'string'),
);

// Rows array used for results from query
$rows = array();

// Build the first rows of data for the partent ID
$row1 = array();
	$row1[] = array('v' => 'Type', 'f' =>NULL);
	$row1[] = array('v' => NULL, 'f' =>NULL);
	$row1[] = array('v' => NULL, 'f' =>NULL);
	$row1[] = array('v' => NULL, 'f' =>NULL);
	$rows[] = array('c' => $row1);

foreach ($type_list as $type)
{
	$temp = array();
	$temp[] = array('v' => (string) $type, 'f' =>NULL);
	$temp[] = array('v' => 'Type', 'f' =>NULL);
	$temp[] = array('v' => NULL, 'f' =>NULL);
	$temp[] = array('v' => NULL, 'f' =>NULL);
	$rows[] = array('c' => $temp);
}


// Format Array for json object and add values from query
while ($nt = $result->fetch_assoc())
{
    $temp = array();
    
    //Column 0 - [string] An ID for this node. 
    //It can be any valid JavaScript string, including spaces, 
    //and any length that a string can hold. 
    // This value is displayed as the node header.
    $temp[] = array('v' => (string) $nt['member_name'], 'f' =>NULL);
	
	//Column 1 - [string] - The ID of the parent node. 
	// If this is a root node, leave this blank. Only one root is allowed per treemap.
    $temp[] = array('v' => (string) $nt['type'], 'f' =>NULL);
   
    // Column 2 - [number] - The size of the node. Any positive value is allowed. 
    // This value determines the size of the node, computed relative to all 
    // other nodes currently shown. For non-leaf nodes, this value is ignored and 
    // calculated from the size of all its children.
    $temp[] = array('v' => (int) $nt['documentCount'], 'f' =>NULL);
   
    // Column 3 - [optional, number] - An optional value used to calculate a color for 
    // this node. Any value, positive or negative, is allowed. 
    // The color value is first recomputed on a scale from minColorValue to maxColorValue,
    // and then the node is assigned a color from the 
    // gradient between minColor and maxColor.
    
    //$temp[] = array('v' => (float) $nt['utilization_rate'], 'f' =>NULL);
    //echo ($nt['enrollment'] / $nt['documentCount']) . "\n\r";
    $temp[] = array('v' =>  ($nt['enrollment'] / $nt['documentCount']), 'f' =>NULL);

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