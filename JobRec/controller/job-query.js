// NEED TO ADD LINK TO APPLY BUTTONS 
var debug = true;

function htmlEncode(value) 
{
    return $('<div/>').text(value).html();
}

function htmlDecode(value) 
{
    return $('<div/>').html(value).text();
}

function add_user_interest(type, result)
{
    var funct = 'add_user_interest'
    var jobkey = result.DID;
    var job_title = result.JobTitle;
    var onet = result.ONetCode;
    var company = result.Company;
    var job_desc = htmlDecode(htmlDecode(result.JobDescription+' '+result.JobRequirements)).replace(/\s\s+/g, ' ');
    var degree_required = result.DegreeRequired;
    var employment_type = result.EmploymentType;
    var loc_formatted = result.LocationFormatted;
    var loc_latitude = result.LocationLatitude;
    var loc_longitude = result.LocationLongitude;
    
    $.ajax(
        { 
            url: 'model/job-query.php',
            type: 'POST',
            data: {action: funct, type: type, jobkey: jobkey, job_title: job_title, onet: onet, company: company, job_desc: job_desc, degree_required: degree_required, employment_type: employment_type, loc_formatted: loc_formatted, loc_latitude: loc_latitude, loc_longitude: loc_longitude},
            success: function(data) 
            {  
                if(debug)
                {
                    console.log(data);
                }
                if(type === 'bookmark')
                {
                    alert(job_title+' bookmarked!');
                }
            }
        }
    );
}

function update_user_interest(type, result)
{
    var funct = 'update_user_interest'
    var jobkey = result.DID;
    var job_title = result.JobTitle;
    
    $.ajax(
        { 
            url: 'model/job-query.php',
            type: 'POST',
            data: {action: funct, type: type, jobkey: jobkey},
            success: function(data) 
            {  
                if(debug)
                {
                    console.log(data);
                }
                if(data == "-1")
                {
                    alert('We cannot find "'+job_title+'" in your profile! Update the page and try again.');
                }
                else
                {
                    if(type === 'unbookmark')
                    {
                        alert(job_title+' unbookmarked!');
                    }
                    if(type === 'unapply')
                    {
                        alert(job_title+' unapplied!');
                    }
                }
            }
        }
    );
}

function user_interest(type, jobkey)
{
    var funct ='fetch_job_details';
    $.ajax(
        { 
            url: 'model/job-query.php',
            type: 'POST',
            data: {action: funct, jobkey: jobkey},
            success: function(data) 
            {  
                // data = JSON.parse(data);
                if(debug)
                {
                    console.log(data);
                }
                if(data.ResponseJob.Errors != null)
                {
                    console.log(data.ResponseJob.Errors.Error);
                }
                else if(type == 'bookmark' || type == 'apply')
                {
                    add_user_interest(type, data.ResponseJob.Job);
                    if(type == 'bookmark')
                    {
                        $('#btn-bk-'+data.ResponseJob.Job.DID).addClass("w3-hide");
                        $('#btn-unbk-'+data.ResponseJob.Job.DID).removeClass("w3-hide");
                    }
                    if(type == 'apply')
                    {
                        $('#btn-app-'+data.ResponseJob.Job.DID).addClass("w3-hide");
                        $('#btn-unapp-'+data.ResponseJob.Job.DID).removeClass("w3-hide");
                    }
                }
                else if(type == 'unbookmark' || type == 'unapply')
                {
                    update_user_interest(type, data.ResponseJob.Job);
                    if(type == 'unbookmark')
                    {
                        $('#btn-bk-'+data.ResponseJob.Job.DID).removeClass("w3-hide");
                        $('#btn-unbk-'+data.ResponseJob.Job.DID).addClass("w3-hide");
                    }
                    if(type == 'unapply')
                    {
                        $('#btn-app-'+data.ResponseJob.Job.DID).removeClass("w3-hide");
                        $('#btn-unapp-'+data.ResponseJob.Job.DID).addClass("w3-hide");
                    }
                }
            }
        }
    );
}

function display_job_details(result)
{
    $('body').append(result);
    $('#apply-button').append('<button class=\"job-button\" onclick=\"user_interest(\'apply\', \''+result.DID+'\')\">Apply Now</button>');
    $('#bookmark-button').append('<button class=\"job-button\" onclick=\"user_interest(\'bookmark\', \''+result.DID+'\')\">Bookmark</button>');
    $('#job-title').append(result.JobTitle);
    $('#company').append(result.Company+'<br>');
    $('#location').append(result.LocationCity+' - '+result.LocationState+'<br>');
    $('#categories').append(result.Categories+'<br>');
    $('#job-desc').append(htmlDecode(result.JobDescription)+'<br>');
    $('#job-reqs').append(htmlDecode(result.JobRequirements)+'<br>');
    $('#degree').append(result.DegreeRequired+'<br>');
    $('#experience').append(result.ExperienceRequired+'<br>');
    $('#emp-type').append(result.EmploymentType+'<br>');
    $('#pay').append(result.PayHighLowFormatted+'<br>');
    $('#post-date').append(result.BeginDate+'<br>');
}

function display_job_details_error(result)
{
    $('#job-title').append('<span style=\"color: red;\">'+result.Error+'</span>');
}

function fetch_job_details(jobkey) 
{
    var funct ='fetch_job_details';
    $.ajax(
        { 
            url: 'model/job-query.php',
            type: 'POST',
            data: {action: funct, jobkey: jobkey},
            success: function(data) 
            {  
                // data = JSON.parse(data);
                if(debug)
                {
                    console.log(data);
                }
                if(data.ResponseJob.Errors != null)
                {
                    display_job_details_error(data.ResponseJob.Errors);
                }
                else 
                {
                    display_job_details(data.ResponseJob.Job);
                }
            }
        }
    );

}

function no_job_search_results()
{
    $('#search-results tbody').empty();
    $('#search-results tbody').append('<tr><th style=\"text-align: center;\">No jobs meet your search criteria. Please refine your query and re-submit.</th></tr>');
}

function display_hit(hit, result_table) 
{
    if(debug) 
    {
        console.log('RESULT CASE: ');
        console.log('   '+hit.DID); 
        console.log('   '+hit.JobTitle);
        console.log('   '+hit.Company);
        console.log('   '+hit.DescriptionTeaser);
        console.log('   '+hit.EmploymentType);
        console.log('   '+hit.Pay);
        console.log('   '+hit.Location);
        console.log('   '+hit.PostedDate);
    }
    
    var r0 = $('<tr>');
    var r1 = $('<tr>');
    var r2 = $('<tr>');
    var r3 = $('<tr>', {class : 'job-item-end'});
    
    r0.append('<td></td>');
    r0.append('<td></td>');
    r0.append('<td></td>');
    r0.append('<td><button class=\"job-button\" onclick=\"user_interest(\'apply\', \''+hit.DID+'\')\">Apply Now</button><div class="divider">&nbsp;</div><button class=\"job-button\" onclick=\"user_interest(\'bookmark\', \''+hit.DID+'\')\">Bookmark</button></td>');
            
    r1.append('<th class=\"short-header\">Job Title: </th>');
    r1.append('<td><a target=\"_blank\" href=\"job-details.php?jobkey='+hit.DID+'\">'+hit.JobTitle+'</a></td>');
    r1.append('<th class=\"right-align"\>Posted: </th>');
    r1.append('<td>'+hit.PostedDate+'</td>');
            
    r2.append('<th class=\"short-header\">Company: </th>');
    r2.append('<td>'+hit.Company+' ('+hit.Location+')</td>');
    r2.append('<td class=\"right-align\">'+hit.EmploymentType+'</td>');
    r2.append('<td>'+hit.Pay+'</td>');
            
    r3.append('<th class=\"short-header\">Description: </th>');
    r3.append('<td colspan=\"3\">'+hit.DescriptionTeaser+'</td>');
    
    result_table.append(r0);
    result_table.append(r1);
    result_table.append(r2);
    result_table.append(r3);
}

function display_job_search_results(result, table)
{
    var result_table = $(table);
    result_table.empty();
    
    if(!$.isArray(result)) 
    {
        display_hit(result, result_table);
        return;
    }
    
    $.each
    (
        result,
        function(key, val)
        {
            display_hit(this, result_table);
        }
    );
}

function fetch_job_search_results()
{
    event.preventDefault();
    var funct ='fetch_job_search_results';
    var key = $.trim($('#search-input').val());
    if(key === '') return;
    $.ajax(
        { 
            url: 'model/job-query.php',
            type: 'POST',
            data: {action: funct, key: key},
            success: function(data) 
            {  
                // data = JSON.parse(data);
                if(debug)
                {
                    console.log(data);
                }
                if(data.ResponseJobSearch.Results == null) 
                {
                    no_job_search_results();    
                }
                else 
                {
                    display_job_search_results(data.ResponseJobSearch.Results.JobSearchResult, '#search-results tbody');
                }
            }
        }
    );
    return false;
}

function display_bookmarks()
{
    var funct ='display_bookmarks';
    var bookmark_table = $('#bookmark-results tbody');
    bookmark_table.empty();
    
    $.ajax(
        { 
            url: 'model/job-query.php',
            type: 'POST',
            data: {action: funct},
            success: function(data) 
            {
                if(debug)
                {
                    console.log('Bookmarks: ');
                    console.log(data);
                }
                bookmark_table.append(data);
                bookmark_table.show();
            }
        }
    );
}

function display_recommendations()
{
    var funct ='display_recommendations';
    var recommendation_table = $('#recommendation-results tbody');
    recommendation_table.empty();
    
    $.ajax(
        { 
            url: 'model/job-query.php',
            type: 'POST',
            data: {action: funct},
            success: function(data) 
            {
                // data = JSON.parse(data);
                if(debug)
                {
                    console.log('Recommendations: ');
                    console.log(data);
                }
                if(data.ResponseJobSearch.Results == null) 
                {
                    recommendation_table.append("<tr><th style=\"text-align: center;\">Somthing went wrong. Please try again later.</th></tr>");  
                }
                else 
                {
                    display_job_search_results(data.ResponseJobSearch.Results.JobSearchResult, "#recommendation-results tbody");
                }
                recommendation_table.append(data);
                recommendation_table.show();
            }
        }
    );
}