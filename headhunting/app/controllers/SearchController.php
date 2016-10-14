<?php
/**
 * SearchController.php
 *
 * This file contatins controller class to provide APIs for Users
 *
 * @category   Controller
 * @package    sale Management
 * @version    SVN: <svn_id>
 * @since      29th May 2014
 *
 */

/**
 * Contrller class will be responsible for All User management Related Actions
 *
 * @category   Controller
 * @package    User Management
 *
 */
class SearchController extends HelperController {

	/**
	 *
	 * advanceSearch() : advanceSearch View
	 *
	 * @return Object : View
	 *
	 */
	public function advanceSearch($jobId = 0) {
		return View::make('search.searchForm')->with(array('title' => 'Search - Headhunting', 'jobId' => $jobId));
	}


	/**
	 *
	 * searchResult() : searchResult View
	 *
	 * @return Object : View
	 *
	 */
	public function searchResult($jobId = 0) {

		$query = Input::get('query', '');
		$key_skills = Input::get('key_skills', '');
    	$designation = Input::get('designation', '');
    	$visa = Input::get('visa', '');
    	$region = Input::get('region', '');
    	$searching_text = '';
		if($query || $key_skills || $designation || $visa || $region) {
		    // Use the Elasticquent search method to search ElasticSearch
		    try {
		    	$searching_text = $key_skills." ".$designation." ".$visa." ".$region." ".$query;
		    	$query = $query?str_ireplace(" and ", " ", $query): "";
		    	$key_skills = $key_skills?str_ireplace(" and ", " ", $key_skills):"";
		    	$designation = $designation?str_ireplace(" and ", " ", $designation):"";
		    	$visa = $visa?str_ireplace(" and ", " ", $visa):"";
		    	$region = $region?str_ireplace(" and ", " ", $region):"";
		    	//$candidate_resumes = CandidateResume::searchByQuery(['match' => ['resume' => $query, 'key_skills' => $key_skills, 'designation' => $designation, 'visa' => $visa, 'region' => $region]]);
		    	//$candidate_resumes = CandidateResume::searchByQuery(['bool' => ['should' => [['match' => ['resume' => $query]], ['match' => ['key_skills' => $key_skills]], ['match' => ['designation' => $designation]], ['match' => ['visa' => $visa]], ['match' => ['region' => $region]]]]]);
		    	$candidate_resumes = CandidateResume::searchByQuery(['bool' => ['should' => [['match' => ['resume' => $query]], ['match' => ['key_skills' => $key_skills]], ['match' => ['designation' => $designation]], ['match' => ['visa' => $visa]], ['match' => ['region' => $region]]]]]);
		    }catch(Exception $e){
		    	print $e->getMessage();
		    }
	  	} else {
	    	// Show all posts if no query is set
	    	$candidate_resumes = CandidateResume::all();
	    }
		 // echo "<pre>". count($candidate_resumes);
		 // print_r($candidate_resumes);
		 // die;

	  	#return 'Done';
		return View::make('search.searchResult')->with(array('title' => 'Search - Headhunting', 'searching_text' => $searching_text, 'candidate_resumes' => $candidate_resumes, 'jobId' => $jobId, 'query' => $query, 'key_skills' => $key_skills, 'designation' => $designation, 'visa' => $visa, 'region'=>$region));
	}
}
