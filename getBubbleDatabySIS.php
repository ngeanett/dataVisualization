<?php 
include("db.php");

$sqlQuery = "SELECT * FROM utilization 
WHERE 
	utilization_rate < 4 and
	utilization_rate !=0
order by 
	sis
";

$result = $conn->query($sqlQuery);

// table used to store google chart data object
$table = array();

// cols object used for column labels
$table['cols'] = array(
	//column 0 ID (name) of the bubble
    array('label' => 'member_name', 'type' => 'string'),
    array('label' => 'ttm', 'type' => 'number'),
    array('label' => 'utilization_rate', 'type' => 'number'),
    array('label' => 'sis', 'type' => 'string'),
    array('label' => 'enrollment', 'type' => 'number')
);

// Rows array used for results from query
$rows = array();

// Format Array for json object and add values from query
while ($nt = $result->fetch_assoc())
{
    $temp = array();
    $temp[] = array('v' => (string) $nt['member_name'], 'f' =>NULL);
    $temp[] = array('v' => (float) $nt['ttm'], 'f' =>NULL);
    $temp[] = array('v' => (float) $nt['utilization_rate'], 'f' =>NULL);
    $temp[] = array('v' => (string) $nt['sis'], 'f' =>NULL);
    $temp[] = array('v' => (float) $nt['enrollment'], 'f' =>NULL);
    $rows[] = array('c' => $temp);
}

// Store rows as part of google chart json array
$table['rows'] = $rows;

// Convert result set to json
$jsonTable = json_encode($table, JSON_NUMERIC_CHECK);
//$jsonTable = json_encode($table, JSON_PRETTY_PRINT); // used for debugging with <pre>
echo $jsonTable;

?>