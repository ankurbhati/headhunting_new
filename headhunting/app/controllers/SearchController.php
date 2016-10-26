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

		$visa = Visa::all()->lists('title', 'title');
		$visa[""] = "Select visa";
		return View::make('search.searchForm')->with(array('title' => 'Search - Headhunting', 'jobId' => $jobId, 'visa' => $visa));
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
    	$designation = Input::get('designation', '');
    	$visa = Input::get('visa', '');
    	$region = Input::get('region', '');
    	$searching_text = '';
		if($query || $designation || $visa || $region) {
		    // Use the Elasticquent search method to search ElasticSearch
		    try {
		    	$searching_text = $designation." ".$visa." ".$region." ".$query;
		    	$designation = $designation?str_ireplace(" and ", " ", $designation):"";
		    	$visa = $visa?str_ireplace(" and ", " ", $visa):"";
		    	$region = $region?str_ireplace(" and ", " ", $region):"";
		    	//$candidate_resumes = CandidateResume::searchByQuery(['bool' => ['should' => [['match' => ['resume' => $query]], ['match' => ['key_skills' => $key_skills]], ['match' => ['designation' => $designation]], ['match' => ['visa' => $visa]], ['match' => ['region' => $region]]]]]);
		    	//$candidate_resumes = CandidateResume::searchByQuery(['bool' => ['should' => [['match' => ['resume' => $query]], ['match' => ['designation' => $designation]], ['match' => ['visa' => $visa]], ['match' => ['region' => $region]]]]]);
		    	if(preg_match('/"/', $query)){
		    		$candidate_resumes = CandidateResume::searchByQuery(['bool' => ['should' => [['match_phrase_prefix' => ['resume' => $query]], ['match_phrase_prefix' => ['designation' => $designation]], ['match' => ['visa' => $visa]], ['match' => ['region' => $region]]]]]);
		    	} else {
		    		$query = $query?str_ireplace(" and ", " ", $query): "";
		    		$candidate_resumes = CandidateResume::searchByQuery(['bool' => ['should' => [['match' => ['resume' => $query]], ['match_phrase_prefix' => ['designation' => $designation]], ['match' => ['visa' => $visa]], ['match' => ['region' => $region]]]]]);	
		    	}
		    }catch(Exception $e){
		    	print $e->getMessage();
		    }
	  	} else {
	    	// Show all posts if no query is set
	    	$candidate_resumes = CandidateResume::all();
	    }
	    //echo $query;exit;
		/*echo "<pre>". count($candidate_resumes);
		print_r($candidate_resumes[0]);
		die;*/

	  	#return 'Done';
		return View::make('search.searchResult')->with(array('title' => 'Search - Headhunting', 'searching_text' => $searching_text, 'candidate_resumes' => $candidate_resumes, 'jobId' => $jobId, 'query' => $query, 'designation' => $designation, 'visa' => $visa, 'region'=>$region));
	}
}
