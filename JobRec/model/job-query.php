<?php

include('session.php');
require_once('Vectorization.php');

$devkey = 'YOUR CAREERBUILDER DEVELOPER KEY';
$show_app_reqs = 'false';
$retrieve_onet_code = 'true';
$output_json = 'true'; 
// NEED TO WRITE FUNCTION THAT CONVERTS $SESSION['location'], e.g. "US-PA-Pittsburgh" into the form below
if(isset($_SESSION['location']))
{
    $location = $_SESSION['location'];

    $location = str_replace('US-', '', $location);
    $city = substr($location, 0, 2);
    $state = substr($location, 3);
    $location = $city.','.$state;
}
else
    $location = '';

$connection = mysqli_connect("localhost", "root", "");
if (!$connection)
{
    die("Database connection failed: " . mysqli_error());
}
$db = mysqli_select_db($connection, "job_recommendation");

if(isset($_POST['action']) && !empty($_POST['action'])) 
{
    $action = $_POST['action'];
    
    
    
    switch($action) 
    {
        case 'fetch_job_details' : 
            
            if(isset($_POST['jobkey']) && !empty($_POST['jobkey']))
            {
                $jobkey = $_POST['jobkey'];
            }
            else
            {
                return "ERROR: parameter jobkey not passed into fetch_job_details";
            }
            $data = curl_fetch_job_details($jobkey);
            header('Content-Type: application/json');
            echo $data; 
            break;
            
        case 'fetch_job_search_results' :
            
            if(isset($_POST['key']) && !empty($_POST['key']))
            {
                $key = $_POST['key'];
            }
            else
            {
                return "ERROR: parameter 'key' not passed into function";
            }
            $data = curl_fetch_job_search_results($key, 100, 100);
            header('Content-Type: application/json');
            echo $data;
            break;

        case 'add_user_interest' : 
            add_user_interest($_POST['type'], $_POST['jobkey'], $_POST['job_title'], $_POST['onet'], $_POST['company'], $_POST['job_desc'], $_POST['degree_required'], $_POST['employment_type'], $_POST['loc_formatted'], $_POST['loc_latitude'], $_POST['loc_longitude']);
            break;

        case 'update_user_interest' : 
            update_user_interest($_POST['type'], $_POST['jobkey']);
            break;

        case 'display_bookmarks' : 
            display_bookmarks();
            break;
    }
}

function curl_fetch_job_details($jobkey)
{
    $url = "https://api.careerbuilder.com/v3/job?DID=$jobkey&ShowApplyRequirements={$GLOBALS['show_app_reqs']}&RetrieveONetCode={$GLOBALS['retrieve_onet_code']}&outputjson={$GLOBALS['output_json']}&DeveloperKey={$GLOBALS['devkey']}";
    
    if (!function_exists('curl_init'))
    {
        die('Sorry cURL is not installed!');
    }
 
    $curl = curl_init();
    
    /* This is insecure and should be replaced with the commands below it */
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
 
    $data = curl_exec($curl);
    if(curl_errno($curl) or !$data)
    {
        $err = curl_error($curl);
        curl_close($curl);
        return $err;
    }
    curl_close($curl);
 
    return $data;
}

function curl_fetch_job_search_results($key, $radius, $perPage, $loc)
{
    $key = preg_replace('#\s+#',',',trim($key));
    $url = "http://api.careerbuilder.com/v1/jobsearch?keywords=$key&FacetCity=$loc&Radius=$radius&UseFacets=true&PerPage=$perPage&outputjson={$GLOBALS['output_json']}&DeveloperKey={$GLOBALS['devkey']}";
    
    if (!function_exists('curl_init'))
    {
        die('Sorry cURL is not installed!');
    }
 
    $curl = curl_init();
    
    /* This is insecure and should be replaced with the commands below it */
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    #curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true);
    #curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);
    #curl_setopt($curl, CURLOPT_CAINFO, '/CAcerts/GoDaddyRootCertificateAuthority-G2.crt');
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
 
    $data = curl_exec($curl);
    
    if(curl_errno($curl) or !$data)
    {
        $err = curl_error($curl);
        curl_close($curl);
        return $err;
    }
    
    curl_close($curl);
    return $data;
}

function add_user_interest($type, $jobkey, $job_title, $onet, $company, $job_desc, $degree_required, $employment_type, $loc_formatted, $loc_latitude, $loc_longitude)
{
    $case = mysqli_query($GLOBALS['connection'], "SELECT job_id FROM job_case WHERE job_key LIKE '$jobkey'");
    if(mysqli_num_rows($case) === 0)
    {
        $type            = mysqli_real_escape_string($GLOBALS['connection'], $type);
        $job_title       = mysqli_real_escape_string($GLOBALS['connection'], $job_title);
        $onet            = mysqli_real_escape_string($GLOBALS['connection'], $onet);
        $onet            = substr($onet, 0, strpos($onet, '.'));
        $company         = mysqli_real_escape_string($GLOBALS['connection'], $company);
        $job_desc        = mysqli_real_escape_string($GLOBALS['connection'], $job_desc);
        $degree_required = mysqli_real_escape_string($GLOBALS['connection'], $degree_required);
        $employment_type = mysqli_real_escape_string($GLOBALS['connection'], $employment_type);
        $loc_formatted   = mysqli_real_escape_string($GLOBALS['connection'], $loc_formatted);
        $loc_latitude    = mysqli_real_escape_string($GLOBALS['connection'], $loc_latitude);
        $loc_longitude   = mysqli_real_escape_string($GLOBALS['connection'], $loc_longitude);
        $v = new Vectorization();
        $vectors         = $v->vectorizer($job_desc);
        $term_vec        = mysqli_real_escape_string($GLOBALS['connection'], $vectors['word_vector']);
        $freq_vec        = mysqli_real_escape_string($GLOBALS['connection'], $vectors['freq_vector']);

        mysqli_query($GLOBALS['connection'], "INSERT INTO job_case(job_key, case_type, job_title, soc_code, company, job_desc,  degree_required, employment_type, loc_formatted, loc_latitude, loc_longitude) VALUES('$jobkey', 'result', '$job_title', '$onet', '$company', '$job_desc', '$degree_required', '$employment_type', '$loc_formatted', '$loc_latitude', '$loc_longitude')");
        mysqli_query($GLOBALS['connection'], "UPDATE job_case SET job_desc_terms='$term_vec' WHERE job_key LIKE '$jobkey'");
        mysqli_query($GLOBALS['connection'], "UPDATE job_case SET job_desc_freqs='$freq_vec' WHERE job_key LIKE '$jobkey'");
        $case = mysqli_query($GLOBALS['connection'], "SELECT job_id FROM job_case WHERE job_key LIKE '$jobkey'");
    }
    $case = mysqli_fetch_assoc($case);
    
    $interest = mysqli_query($GLOBALS['connection'], "SELECT job_id, email FROM user_interest WHERE job_id = {$case['job_id']} AND email LIKE '{$_SESSION['user']}'");
    
    if(mysqli_num_rows($interest) === 0)
    {
        mysqli_query($GLOBALS['connection'], "INSERT INTO user_interest(job_id, email) VALUES({$case['job_id']}, '{$_SESSION['user']}')");
    }
    
    if (strpos($type, 'bookmark') !== false)
    {
        mysqli_query($GLOBALS['connection'], "UPDATE user_interest SET bookmarked = 1 WHERE job_id = {$case['job_id']} AND email LIKE '{$_SESSION['user']}'");
    }
    else if (strpos($type, 'apply') !== false)
    {
        mysqli_query($GLOBALS['connection'], "UPDATE user_interest SET applied = 1 WHERE job_id = {$case['job_id']} AND email LIKE '{$_SESSION['user']}'");
    }
}

function update_user_interest($type, $jobkey)
{
    $res = 0;
    $case = mysqli_query($GLOBALS['connection'], "SELECT j.job_id, bookmarked, applied FROM job_case AS j, user_interest AS u 
                                                  WHERE j.job_id = u.job_id
                                                  AND j.job_key LIKE '$jobkey' AND email LIKE '{$_SESSION['user']}'");
    if(mysqli_num_rows($case) === 0)
    {
        echo -1;
        return;
    }
    $type = mysqli_real_escape_string($GLOBALS['connection'], $type);
    $case = mysqli_fetch_assoc($case);

    if($case['bookmarked'] === '1' && strpos($type, 'unbookmark') !== false)
    {
        mysqli_query($GLOBALS['connection'], "UPDATE user_interest SET bookmarked = 0 WHERE job_id = {$case['job_id']} AND email LIKE '{$_SESSION['user']}'");
    }
    
    if($case['applied'] === '1' && strpos($type, 'unapply') !== false)
    {
        mysqli_query($GLOBALS['connection'], "UPDATE user_interest SET applied = 0 WHERE job_id = {$case['job_id']} AND email LIKE '{$_SESSION['user']}'");
    }
    
    $acase = mysqli_query($GLOBALS['connection'], "SELECT j.job_id, bookmarked, applied FROM job_case AS j, user_interest AS u 
                                                  WHERE j.job_id = u.job_id
                                                  AND j.job_key LIKE '$jobkey' AND email LIKE '{$_SESSION['user']}'");
    if(mysqli_num_rows($acase) !== 0)
    {
        $acase = mysqli_fetch_assoc($acase);
        if(($acase['applied'] === '0' || is_null($acase['applied'])) && ($acase['bookmarked'] === '0' || is_null($acase['bookmarked'])))
        {
            mysqli_query($GLOBALS['connection'], "DELETE FROM job_case WHERE job_id = {$acase['job_id']}");
        }
    }
    echo $res;
    return;
}

function display_bookmarks()
{
    $interest = mysqli_query($GLOBALS['connection'], "SELECT c.job_key, c.job_title, c.company, c.loc_formatted, c.employment_type, i.bookmarked, i.applied, job_desc  FROM user_interest AS i, job_case AS c WHERE i.email LIKE '{$_SESSION['user']}' AND i.bookmarked = 1 AND i.job_id = c.job_id AND c.case_type LIKE 'result'");
    
    if(mysqli_num_rows($interest) === 0)
    {
        // echo "<th>You have not bookmarked any jobs yet. Search for jobs or browse our recommendations.</th>";
        return 0;
    }
    $data = array();
    while($case = mysqli_fetch_assoc($interest))
    {
        $data[] = $case;
        /*echo "<tr class=\"job-item-end\">";
        echo "<td><a target=\"_blank\" href=\"job-details.php?jobkey={$case['job_key']}\">{$case['job_title']}</a></td>";
        echo "<td>{$case['loc_formatted']}</td>";
        echo "<td>{$case['company']}</td>";
        echo "<td>{$case['employment_type']}</td>";
        echo "</tr>";*/
    }
    return $data;
}

function search($key, $radius, $perPage, $weights, $loc, $logged)
{
    if($logged)
    {
        require_once("sim.php");
        require_once("Vectorization.php");
        $v = new Vectorization();

        $query = "SELECT j.job_id, bookmarked, applied, job_key, case_type, j.job_title AS job_case_job_title, j.soc_code, 
                         company, degree_required, employment_type, loc_formatted, loc_latitude, 
                         loc_longitude, j.pay_avg_monthly, soc_group, path, o.job_title AS onet_job_title, job_terms, 
                         job_frequencies, degree, o.pay_avg_monthly 
                                     FROM user_interest AS u, job_case AS j, onet AS o 
                                     WHERE u.job_id = j.job_id AND
                                           j.soc_code = o.soc_code AND
                                           u.email = '{$_SESSION['user']}' AND 
                                           j.case_type IN ('onet', 'resume', 'result')";

        $interest = mysqli_query($GLOBALS['connection'], $query);
    }

    $data = curl_fetch_job_search_results($key, $radius, $perPage, $loc);

    $data = json_decode($data, true);
    // $data = $data['ResponseJobSearch']['Results']['JobSearchResult'];
    // var_dump($data);
    // if($data == null)
    //     return null;
    if($logged)
        {
        $interestTotal = mysqli_num_rows($interest);
        if($interestTotal !== 0)
        {
            $arr_interest = array();
            while($row = mysqli_fetch_assoc($interest))
            {
                $arr_interest[] = $row;
            }

            $interestResultTotal = 0;

            foreach($arr_interest as &$irow)
            {
                if($irow['case_type'] == 'result')
                {
                    $interestResultTotal += 1;
                }
            }

            $preferedLocCoord = getCityCoordinates($_SESSION['location']);


            foreach($data['ResponseJobSearch']['Results']['JobSearchResult'] as &$drow)
            {
                $drow['ONet17Code'] = substr($drow['ONet17Code'], 0, strpos($drow['ONet17Code'], '.'));
                $sql = "SELECT path 
                        FROM onet 
                        WHERE soc_group like 'detailed' AND (soc_code like '{$drow['ONet17Code']}')";
                $onetPath = mysqli_query($GLOBALS['connection'], $sql);
                $onetPath = mysqli_fetch_array($onetPath);

                if(!isset($drow['jobSocSim']))   $drow['jobSocSim']     = 0;
                if(!isset($drow['empSim']))      $drow['empSim']        = 0;
                if(!isset($drow['eduSim']))      $drow['eduSim']        = 0;
                if(!isset($drow['jobTitleSim'])) $drow['jobTitleSim']   = 0;
                if(!isset($drow['descSim']))     $drow['descSim']       = 0;
                if(!isset($drow['bookmarked']))  $drow['bookmarked']    = 0;
                if(!isset($drow['applied']))     $drow['applied']       = 0;

                /*$job_data = curl_fetch_job_details($drow['DID']);
                $job_data = json_decode($job_data, true);
                $job_data = strip_tags_content($job_data['ResponseJob']['Job']['JobDescription']);
                $job_data = $v->vectorizer($job_data);
                $job_data = $job_data['freq_vector'];*/

                foreach($arr_interest as &$irow)
                {
                    // Onet Similarity
                    $jobSocSim = jobSocSim($onetPath['path'], $irow['path']);
                    $drow['jobSocSim'] += $jobSocSim/$interestTotal;

                    
                    if($irow['case_type'] == 'result')
                    {
                        if($irow['bookmarked'] == 1 && $irow['job_key'] == $drow['DID'])
                            $drow['bookmarked'] = 1;
                        if($irow['applied'] == 1 && $irow['job_key'] == $drow['DID'])
                            $drow['applied'] = 1;

                        // Employment Type Similarity
                        $drow['empSim'] += empSim($irow['employment_type'], $drow['EmploymentType'])/$interestResultTotal;
                    
                        // Education/Degree Similarity
                        $drow['eduSim'] += eduSim($irow['degree_required'], $drow['EducationRequired'])/$interestResultTotal;

                        // job Title Similarity
                        $drow['jobTitleSim'] += jobTitleSim($drow['JobTitle'], $irow['job_case_job_title'])/$interestResultTotal;
                    }
                    // $drow['descSim'] += $v->cosineSim($irow['job_frequencies'], $job_data)/$perPage;
                }

                // Location Similarity
                $jobLocCoord;
                if(isset($drow['LocationLatitude']) && !empty($drow['LocationLatitude']) && !is_null($drow['LocationLatitude']) &&
                    isset($drow['LocationLongitude']) && !empty($drow['LocationLongitude']) && !is_null($drow['LocationLongitude']))
                {
                    $jobLocCoord = array('lat' => $drow['LocationLatitude'], 'long' => $drow['LocationLongitude']);
                }
                else
                    $jobLocCoord = getCityCoordinates('US-'.$drow['State'].'-'.$drow['City']);
                $drow['locSim'] = locSim($preferedLocCoord, $jobLocCoord);

                $drow['rankScore'] = similarity($drow['jobSocSim'], $drow['locSim'], $drow['eduSim'], $drow['jobTitleSim'], $drow['empSim'], $weights);
            }
        }

        function cmp($a, $b)
        {
            $p1 = $a['rankScore'];
            $p2 = $b['rankScore'];
            return (float)$p1 < (float)$p2;
        }
        @uasort($data['ResponseJobSearch']['Results']['JobSearchResult'], "cmp");
    }

    return $data;
}

?>



