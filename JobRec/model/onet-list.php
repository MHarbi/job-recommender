<?php 
include_once 'db-connect.php';

if(!isset($_REQUEST['query']))
{
	$query = "SELECT soc_group FROM onet WHERE soc_code LIKE '" . htmlspecialchars($_GET['code']) . "'";
	
	$db = Db::getInstance();
    $result = $db->query($query);

	$count = $result->rowCount();

	if($count != 0)
	{
		$row = $result->fetch();
		$m = '';
		if($row['soc_group'] == "major") $m = '._'; 
		else if($row['soc_group'] == "minor") $m = '.%.%';

		if($m != '')
		{

			$query = "SELECT soc_code, job_title FROM onet 
			          WHERE path LIKE (SELECT concat(path, '$m') FROM onet WHERE soc_code LIKE '" . htmlspecialchars($_GET['code']) . "')";

			$result = $db->query($query);

			$resultArray = array();
			foreach ($result->fetchAll() as $row) { 
			    extract($row);
			    $resultArray[] = array('value' => $soc_code, 'name' => $job_title);  
			}

			print_r(json_encode($resultArray));
		}
	}
}
else
{
	$query = $_REQUEST['query'];
    $sql = "SELECT soc_code, job_title FROM onet WHERE soc_group LIKE 'detailed' AND job_title LIKE '%{$query}%'";

    $db = Db::getInstance();
    $result = $db->query($sql);

	$array = array();
    foreach ($result->fetchAll() as $row) {
        $array[] = array (
            'label' => $row['job_title'],
            'value' => $row['job_title'],
        );
    }
    //RETURN JSON ARRAY
    echo json_encode ($array);
}
?>