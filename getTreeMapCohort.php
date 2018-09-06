<?php 
include("db.php");

$DataPull = "SELECT 
	distinct member_name, cohort, utilization_rate, enrollment, ttm 
FROM 
	utilization 
WHERE 
	utilization_rate < 4 and
	utilization_rate > .1";

$order_by = 'order by cohort';

$DataPull .= $order_by;

$result = $conn->query($DataPull);

// Create Json table used to store google chart data object
$table = array();

// cols object used for column labels
$table['cols'] = array(
    array('label' => 'Member', 'type' => 'string'),
    array('label' => 'Cohort', 'type' => 'string'),
    array('label' => 'TTM', 'type' => 'number'),
    array('label' => 'Utilization', 'type' => 'string'),
);

// Rows array used for results from query
$rows = array();

// Build the first rows of data for the partent ID
$row1 = array();
	$row1[] = array('v' => 'Cohort', 'f' =>NULL);
	$row1[] = array('v' => NULL, 'f' =>NULL);
	$row1[] = array('v' => NULL, 'f' =>NULL);
	$row1[] = array('v' => NULL, 'f' =>NULL);
	$rows[] = array('c' => $row1);

$row2 = array();
	$row2[] = array('v' => '<2011', 'f' =>NULL);
	$row2[] = array('v' => 'Cohort', 'f' =>NULL);
	$row2[] = array('v' => NULL, 'f' =>NULL);
	$row2[] = array('v' => NULL, 'f' =>NULL);
	$rows[] = array('c' => $row2);

$row3 = array();
	$row3[] = array('v' => '2011', 'f' =>NULL);
	$row3[] = array('v' => 'Cohort', 'f' =>NULL);
	$row3[] = array('v' => NULL, 'f' =>NULL);
	$row3[] = array('v' => NULL, 'f' =>NULL);
	$rows[] = array('c' => $row3);


$row4 = array();
	$row4[] = array('v' => '2012', 'f' =>NULL);
	$row4[] = array('v' => 'Cohort', 'f' =>NULL);
	$row4[] = array('v' => NULL, 'f' =>NULL);
	$row4[] = array('v' => NULL, 'f' =>NULL);
	$rows[] = array('c' => $row4);

$row5 = array();
	$row5[] = array('v' => '2013', 'f' =>NULL);
	$row5[] = array('v' => 'Cohort', 'f' =>NULL);
	$row5[] = array('v' => NULL, 'f' =>NULL);
	$row5[] = array('v' => NULL, 'f' =>NULL);
	$rows[] = array('c' => $row5);

$row6 = array();
	$row6[] = array('v' => '2014', 'f' =>NULL);
	$row6[] = array('v' => 'Cohort', 'f' =>NULL);
	$row6[] = array('v' => NULL, 'f' =>NULL);
	$row6[] = array('v' => NULL, 'f' =>NULL);
	$rows[] = array('c' => $row6);

$row7 = array();
	$row7[] = array('v' => '2015', 'f' =>NULL);
	$row7[] = array('v' => 'Cohort', 'f' =>NULL);
	$row7[] = array('v' => NULL, 'f' =>NULL);
	$row7[] = array('v' => NULL, 'f' =>NULL);
	$rows[] = array('c' => $row7);

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
    $temp[] = array('v' => (string) $nt['cohort'], 'f' =>NULL);
   
    // Column 2 - [number] - The size of the node. Any positive value is allowed. 
    // This value determines the size of the node, computed relative to all 
    // other nodes currently shown. For non-leaf nodes, this value is ignored and 
    // calculated from the size of all its children.
    $temp[] = array('v' => (int) $nt['ttm'], 'f' =>NULL);
   
    // Column 3 - [optional, number] - An optional value used to calculate a color for 
    // this node. Any value, positive or negative, is allowed. 
    // The color value is first recomputed on a scale from minColorValue to maxColorValue,
    // and then the node is assigned a color from the 
    // gradient between minColor and maxColor.
    $temp[] = array('v' => (float) $nt['utilization_rate'], 'f' =>NULL);

    // Add row array to array
    $rows[] = array('c' => $temp);
}

// Store rows as part of google chart json array
$table['rows'] = $rows;

// Convert result set to json
//$jsonTable = json_encode($table, JSON_NUMERIC_CHECK);
$jsonTable = json_encode($table, JSON_PRETTY_PRINT); // used for debugging with <pre>
//echo "<pre>";
echo $jsonTable;
//echo "</pre>";
?>