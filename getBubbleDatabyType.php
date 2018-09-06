<?php 
include("db.php");
 
// Simple query to pull a few rows from my table
$sqlQuery = 
"SELECT 
* 
FROM 
utilization 
WHERE 
utilization_rate < 4 and
utilization_rate !=0
";

//Execute query    
$result = $conn->query($sqlQuery);

// table used for google chart data object
$table = array();

//cols object used for column labels
$table['cols'] = array(
    array('label' => 'member_name', 'type' => 'string'),
    array('label' => 'ttm', 'type' => 'number'),
    array('label' => 'utilization_rate', 'type' => 'number'),
    array('label' => 'type', 'type' => 'string'),
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
    $temp[] = array('v' => (string) $nt['type'], 'f' =>NULL);
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