<?php

require_once('Vectorization.php');

	$servername = "localhost";
	$username   = "root";
	$password   = "";
	$dbname     = "tmp";
	$filename = "http://localhost:8888/INFSCI-2480/Project/soc_structure_2010_o.csv";

	function titleSim($title1, $title2)
	{
		$conn = new mysqli($GLOBALS['servername'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['dbname']);

		// Check connection
		if ($conn->connect_error) {
			die("<p>Connection failed: " . $conn->connect_error . "</p>");
		}

		$sql = "SELECT * 
				FROM onet 
				WHERE soc_group like 'detailed' AND (job_title like '$title1' OR job_title like '$title2')";

		$result = $conn->query($sql);

		$sim = 0;

		if ($result->num_rows > 0)
		{
			$paths = array();
			while ($row = $result->fetch_assoc()) {
				$paths[] = explode('.', $row['path']);
			}

			$sim = jobSocSim($paths[0], $paths[1]); 
		}

		$conn->close();
		return $sim;
	}

	function jobSocSim($soc_path1, $soc_path2)
	{
		$sim = 0;

		if ($soc_path1 && $soc_path2)
		{
			$soc_path1 = explode('.', $soc_path1);
			$soc_path2 = explode('.', $soc_path2);

			$sim = 1;

			
				if($soc_path1[0] == $soc_path2[0]) {
					$sim++;
					if($soc_path1[1] == $soc_path2[1]) {
						$sim++;
						if($soc_path1[2] == $soc_path2[2]) {
							$sim++;
							if(isset($soc_path1[3]) && isset($soc_path2[3]) && $soc_path1[3] == $soc_path2[3])
								$sim++;
						}
					}
				}
			

			$sim /= 5;
		}

		return $sim;
	}

	function eduSim($interest, $incoming)
	{
		$interest = strtolower($interest);
		$incoming = strtolower($incoming);

		if($interest == "not specified" || $incoming == "not specified")
		{
			return 0.5;
		}
		// interest/incoming
		$matrix = [[1.0, 0.7, 0.4, 0.2, 0.1, 0.01],
				   [0.8, 1.0, 0.7, 0.4, 0.2, 0.1],
				   [0.5, 0.8, 1.0, 0.7, 0.4, 0.2],
				   [0.3, 0.5, 0.8, 1.0, 0.7, 0.3],
				   [0.2, 0.3, 0.5, 0.8, 1.0, 0.4],
				   [0.1, 0.2, 0.3, 0.4, 0.5, 1.0]];

		$educLevels = array("none","high school", "2 year degree", "4 year degree", "graduate", "doctorate");

		$ikey = array_search($interest, $educLevels);
		$ckey = array_search($incoming, $educLevels);
		
		if(false !== $ikey && false !== $ckey)
			return $matrix[$ikey][$ckey];

		return 0;
	}

	function empSim($interest, $incoming)
	{
		$interest = strtolower($interest);
		$incoming = strtolower($incoming);

		if($interest == "not specified" || $incoming == "not specified")
		{
			return 0.5;
		}
		// interest/incoming
		$matrix = [[1.0, 0.8, 0.6, 0.3, 0.3, 0.3, 0.8],
		           [0.8, 1.0, 0.8, 0.3, 0.3, 0.5, 0.7],
		           [0.6, 0.8, 1.0, 0.3, 0.5, 0.5, 0.5],
		           [0.3, 0.3, 0.3, 1.0, 0.1, 0.1, 0.1],
		           [0.3, 0.3, 0.5, 0.1, 1.0, 0.5, 0.2],
		           [0.3, 0.5, 0.5, 0.1, 0.5, 1.0, 0.2],
		           [0.8, 0.7, 0.5, 0.1, 0.2, 0.2, 1.0]];

		$empTypes = array("full-time","full-time/part-time", "part-time", "contractor", "intern", "seasonal/temp", "contract-to-hire");

		$ikey = array_search($interest, $empTypes);
		$ckey = array_search($incoming, $empTypes);
		
		if(false !== $ikey && false !== $ckey)
			return $matrix[$ikey][$ckey];

		return 0;
	}

function jobTitleSim($t1, $t2) 
{
    $v = new Vectorization();
    $title1 = $v->vectorizer($t1);
    $title2 = $v->vectorizer($t2);
    
    $title1 = unserialize($title1['word_vector']);
    $title2 = unserialize($title2['word_vector']);
    $intersect = 0;
    
    foreach($title1 as $x => $x_val)
    {
        foreach($title2 as $y => $y_val)
        {
            if($x_val === $y_val)
            {
                $intersect++;
            }
        }
    }
    
    if($intersect == 0) return 0.0;
    else if($intersect == 1) return 0.5;
    else if($intersect >= 2) return 1.0;
}

function getDistanceBetweenPoints($lat1, $lon1, $lat2, $lon2) 
{
    $theta = $lon1 - $lon2;
    $miles = (sin(deg2rad($lat1)) * sin(deg2rad($lat2))) + (cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta)));
    $miles = acos($miles);
    $miles = rad2deg($miles);
    $miles = $miles * 60 * 1.1515;
    return $miles;
}
//echo getDistanceBetweenPoints(40.4406248,-79.9958864,37.7749295, -122.4194155);


function getCityCoordinates($cityInfo)//$cityInfo need to be formated like this US-PA-Pittsburgh
{
    list($country,$state,$city) = explode("-",$cityInfo);
    $address = "$city,+$state,+$country";
    $key = "AIzaSyBfg2wR7Zf_sOWSS5Hsqmi77MgrhRHnQTc";
    $url = "https://maps.googleapis.com/maps/api/geocode/json?address=$address&key=$key";
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_PROXYPORT, 3128);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    $response = curl_exec($ch);
    curl_close($ch);
    $response_a = json_decode($response);
    $location = $response_a->results[0]->geometry->location;
    return $return = array('lat' => $location->lat, 'long' => $location->lng);
}

function locSim($cityCoord1, $cityCoord2) 
{
	$distance = getDistanceBetweenPoints($cityCoord1['lat'], $cityCoord1['long'], $cityCoord2['lat'], $cityCoord2['long']);

	if($distance<=10){
        return 1.0;
    }elseif($distance<=20){
        return 0.8;
    }elseif($distance<=30){
        return 0.6;
    }elseif ($distance<=50){
        return 0.4;
    }elseif ($distance<=70){
        return 0.2;
    }else{
        return 0.1;
    }
}

function getWeights()
{
	// default importance weights
    $_onet      = 5/15;
    $_loc       = 4/15;
    $_edu       = 2/15;
    $_job_title = 3/15;
    $_emp_type  = 1/15;

    $w = array('onet'       => $_onet,
                'loc'       => $_loc,
                'edu'       => $_edu,
                'job_title' => $_job_title,
                'emp_type'  => $_emp_type);
    return $w;
}

function similarity($onet, $loc, $edu, $job_title, $emp_type, $w)
{
	if($w == null)
    {
        $w = getWeights();
    }
	
	return ((($w['onet'] * $onet) + ($w['loc'] * $loc) + ($w['edu'] * $edu) + ($w['job_title'] * $job_title) + ($w['emp_type'] * $emp_type)));
}

function strip_tags_content($text) 
{
	return preg_replace('/(?:<|&lt;)\/?([a-zA-Z]+) *[^<\/]*?(?:>|&gt;)/', ' ', $text);
 }
?>