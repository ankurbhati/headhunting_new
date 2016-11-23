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
		$searchQuery = Input::get('searchQuery');
		$searchQuery = explode(',', $searchQuery);
		$searcType = Input::get('searchType');
    	$designation = Input::get('designation', '');
    	$visa = Input::get('visa', '');
    	$region = Input::get('region', '');
    	$searching_text = '';
		if($query || $designation || $visa || $region) {
		    // Use the Elasticquent search method to search ElasticSearch
		    try {
		    	$searching_text_to_send = $designation."---".$visa."---".$region."---".$query;
		    	$searching_text = $designation." ".$visa." ".$region." ".$query;


		    	$q = CandidateResume::query();

		    	if( isset($visa) && !empty($visa) ) {
		    		 //print "IN visa".$visa;exit;
		    		 //$q->where('visa','like', "'%".$visa."%'");
		    		 $q->where('visa','like', $visa);
		    	}

		    	if( isset($region) && !empty($region) ) {
		    		 $q->where('region','like', $region);
		    	}

		    	if( isset($designation) && !empty($designation) ) {
					$q->where('designation','like', $designation."%");
		    	}

		   //  	if( isset($query) && !empty($query) ) {
					// $ands = preg_split("/ AND /i", $query);
					// foreach($ands as $item) {
					// 	$ors = preg_split("/ OR /i", $item);
					// 	$count = 0;
					// 	foreach($ors as $term) {
					// 		if($count == 0){
					// 			$q->where('resume','like', "%".$term."%");
					// 		} else {
					// 			$q->orWhere('resume','like', "%".$term."%");
					// 			//$q->Where('resume','like', "%".$term."%");
					// 		}
					// 		$count++;
					// 	}
					// }
		   //  	}

		    	if(isset($searchQuery) && !empty($searchQuery) && isset($searcType) && !empty($searcType)) {
		    		Log::info(json_encode($searchQuery));
		    		if($searcType == 3 && count($searchQuery) > 0) {
						foreach($searchQuery as $item) {
							$q->where('resume','like', "%".$item."%");
						}
					} elseif($searcType == 2 && count($searchQuery) > 0) {
						foreach($searchQuery as $index => $item) {
							// $ands = preg_split("/ AND /i", $query);
							// foreach($ands as $item) {

							// }
							if($index == 0) {

								$q->where(function ($query) use ($item) {
									$ands = preg_split("/ and /i", $item);
									foreach($ands as $childItem) {
										$query->where('resume','like', "%".$childItem."%");
									}
								}); 
							} else {
								$q->orWhere(function ($query) use ($item) {
								    $ands = preg_split("/ and /i", $item);
									foreach($ands as $childItem) {
										$query->where('resume','like', "%".$childItem."%");
									}
								});
							}
							//$q->where('resume','like', "%".$item."%");
						}
					} else {
						$q->where('resume','like', "%".$searchQuery."%");
					}
		    	}



		    	$candidate_resumes = $q->paginate(100);	

		    	/*
		    	$designation = $designation?str_ireplace(" and ", " ", $designation):"";
		    	//$candidate_resumes = CandidateResume::searchByQuery(['bool' => ['should' => [['match' => ['resume' => $query]], ['match' => ['designation' => $designation]], ['match' => ['visa' => $visa]], ['match' => ['region' => $region]]]]]);
		    	if(preg_match('/"/', $query)){
		    		$candidate_resumes = CandidateResume::searchByQuery([
		    			'bool' => [
		    				'should' => [
		    					[
		    						'match_phrase_prefix' => [
		    							'resume' => $query
		    						]
		    					], 
		    					[
		    						'match_phrase_prefix' => [
		    							'designation' => $designation
		    						]
		    					],
		    					[
			    					'match' => [
			    						'visa' => $visa
			    					]
		    					],
		    					[
		    						'match' => [
		    							'region' => $region
		    						]
		    					]
		    				]
		    			]
		    		]);
		    	} else {
		    		$query = $query?str_ireplace(" and ", " ", $query): "";
		    		$candidate_resumes = CandidateResume::searchByQuery([
		    			'bool' => [
			    			'should' => [
			    				[
			    					'match' => [
			    						'resume' => $query
			    					]
			    				],
			    				[
			    					'match_phrase_prefix' => [
			    						'designation' => $designation
			    					]
			    				], 
			    				[
			    					'match' => [
			    						'visa' => $visa
			    					]
			    				],
			    				[
			    					'match' => [
			    						'region' => $region
			    					]
			    				]
			    			]
			    		]
		    		]);	
		    	}*/

		    } catch(Exception $e){
		    	print $e->getMessage();
		    }
	  	} else {
	    	// Show all posts if no query is set
	    	$candidate_resumes = CandidateResume::paginate(100);
	    }
	    $queries = DB::getQueryLog();
		$last_query = end($queries);
		Log::info(json_encode($last_query));
	    //echo $query;exit;
		/*echo "<pre>". count($candidate_resumes);
		print_r($candidate_resumes[0]);
		die;*/

	  	#return 'Done';
		return View::make('search.searchResult')->with(
			array(
				'title' => 'Search - Headhunting',
				'searching_text_to_send' => urlencode($searching_text_to_send),
				'searching_text' => $searching_text,
				'candidate_resumes' => $candidate_resumes,
				'jobId' => $jobId,
				'query' => $query,
				'designation' => $designation, 
				'visa' => $visa,
				'region'=>$region
			)
		);
	}
}
