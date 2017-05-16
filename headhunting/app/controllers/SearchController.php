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
    	$visa = Input::get('visa', []);
    	$region = Input::get('region', []);

    	$searching_text = '';
		if($query || $designation || count($visa) > 0 || count($region) > 0 || (!empty(Input::get('from_date')) && !empty(Input::get('to_date'))) ) {
		    // Use the Elasticquent search method to search ElasticSearch
		    try {
		    	$searching_text_to_send = $designation."---".join("---",$visa)."---".join("---",$region)."---".$query;
		    	$searching_text = $designation." ".join(" ",$visa)." ".join(" ",$region)." ".$query;

		    	$q = CandidateResume::query();

		    	if( isset($visa) && count($visa) > 0 ) {
		    		 //print "IN visa".$visa;exit;
		    		 //$q->where('visa','like', "'%".$visa."%'");
		    		 $q->whereIn('visa', $visa);
		    	}

		    	if( isset($region) && count($region) > 0 ) {

		    		 $q->whereIn('region', $region);
		    	}


		    	if( isset($designation) && !empty($designation) ) {
					$q->where('designation','like', $designation."%");
		    	}

		    	if(!empty(Input::get('from_date')) && !empty(Input::get('to_date'))) {
					$fromDateTime = datetime::createfromformat('m/d/Y',Input::get('from_date'))->format('Y-m-d 00:00:00');
					$toDateTime = datetime::createfromformat('m/d/Y', Input::get('to_date'))->format('Y-m-d 23:59:59');
					$q->whereBetween('created_at', [$fromDateTime, $toDateTime]);
					$searching_text_to_send .= $fromDateTime."---".$toDateTime;
		    		$searching_text .= $fromDateTime." ".$toDateTime;
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

			 	if(!empty(Input::get('csv_download_input'))) {
					$arrSelectFields = array('designation', 'visa', 'region', 'resume');

			        $q->select($arrSelectFields);
			        $data = $q->get();

			        // passing the columns which I want from the result set. Useful when we have not selected required fields
			        $arrColumns = array('designation', 'visa', 'region', 'resume');
			         
			        // define the first row which will come as the first row in the csv
			        $arrFirstRow = array('Designation', 'Visa', 'Region', 'Resume');
			         
			        // building the options array
			        $options = array(
			          'columns' => $arrColumns,
			          'firstRow' => $arrFirstRow,
			        );

			        return $this->convertToCSV($data, $options);
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
	    	if(!empty(Input::get('csv_download_input'))) {
				$arrSelectFields = array('designation', 'visa', 'region', 'resume');
				$q = CandidateResume::query();
		        $q->select($arrSelectFields);
		        $data = $q->get();

		        // passing the columns which I want from the result set. Useful when we have not selected required fields
		        $arrColumns = array('designation', 'visa', 'region', 'resume');
		         
		        // define the first row which will come as the first row in the csv
		        $arrFirstRow = array('Designation', 'Visa', 'Region', 'Resume');
		         
		        // building the options array
		        $options = array(
		          'columns' => $arrColumns,
		          'firstRow' => $arrFirstRow,
		        );

		        return $this->convertToCSV($data, $options);
			}
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
				'searching_text_to_send' => isset($searching_text_to_send)?urlencode($searching_text_to_send): "",
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
