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

		$query = "";
		if($query = Input::get('query', false)) {
		    // Use the Elasticquent search method to search ElasticSearch
		    try{
		    	$query = str_ireplace(" and ", " ", $query);
		    	$candidate_resumes = CandidateResume::searchByQuery(['match' => ['resume' => $query]]);
		    }catch(Exception $e){
		    	print $e->getMessage();
		    }
	  	} else {
	    	// Show all posts if no query is set
	    	$candidate_resumes = CandidateResume::all();
	  	}

	  	#return 'Done';
		return View::make('search.searchResult')->with(array('title' => 'Search - Headhunting', 'candidate_resumes' => $candidate_resumes, 'jobId' => $jobId, 'query' => $query));
	}
}
