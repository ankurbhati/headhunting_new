<?php

/**
 * SearchProvider.php
 *
 * This file contatins Model class to provide DataLogic for all Search Operations
 *
 * @category   Models
 * @package    Search
 * @version    SVN: <svn_id>
 * @since      29th May 2016
 */

/**
 * Model class to provide Data Logic
 *
 * @category   Models
 * @package    Search
 *
 */
class SearchProvider {

    /**
     * 
     * Search Model
     * 
     * @param  string $firstName   :   Provider First Name
     * @param  string $LastName    :   Providers Last Name
     * @param  string $speciality  :   Providers Specialty
     * @param  string $region      :   Providers Region
     * @param  string $language    :   Provider Language
     * @param  string $address     :   Providers Address
     * @param  float  $latitude       :   Latitude
     * @param  float  $longitude   :   Longitude
     * @param Integer $providerCode : Provider to get Provider Information
     * 
     */
    public  $firstName, 
            $lastName,
            $userId, 
            $speciality_id, 
            $speciality,
            $region, 
            $language,
            $flagFav,
            $latitude,
            $longitude,
            $street_address,
            $zipcode,
            $username,
            $flagColleague,
            $flagPrev,
            $providerId,
            $appendReview,
            $flagReviewed,
            $providerCode;

    /**
     * 
     * getProvider : is to Return Provider for a Criteria Set by Client End
     * 
     * @return Object Json.
     */
    public function getProvider() {

        // Query Builder for Search
        $docUser = DB::table('users AS u')
                            ->leftJoin('provider_users AS pu', 'u.id', '=', 'pu.user_id')
                            ->leftJoin('user_social_media AS usm', 'u.id', '=', 'usm.user_id')
                            ->leftJoin('specialties AS s', "pu.speciality_id", "=", "s.id")
                            ->leftJoin('myphysician AS mp', "u.id", "=", "mp.userId")
                            ->leftjoin('user_subscription as usub',"u.id", "=","usub.user_id")
                            ->leftjoin('countries as con',"pu.country_id", "=","con.id")
                            ->leftjoin('states as sta',"pu.state_id", "=","sta.id")
                            ->leftJoin('user_speciality_tags AS ust', "u.id", "=", "ust.user_id")
                            ->select('u.id', 'u.email', 'u.phone','pu.latitude', 'pu.longitude',
                                     'u.image', 'u.username', 'u.first_name', 'u.last_name', 'pu.street_address','pu.providerNumber', 'pu.hr_R',
                                     'pu.zipcode', 's.specialty', 'usm.facebook_url', 'usm.twitter_url', 'u.parent_user_id','con.country','sta.state')
                            ->addSelect(DB::raw("(SELECT CAST(AVG((expertise_level + punctuality + customer_satisfaction + professionalism)/4)
                            		 AS DECIMAL(10,1)) FROM provider_reviews where doc_id = u.id) as rating"))
                           	->distinct()
                            ->where("u.type", "=", Config::get('app.user_type.SERVICEPROVIDER'))
                            ->where("u.is_template", "=", Config::get('app.status.PENDING'))
                            ->where("u.status", "=", Config::get('app.user_status.APPROVED'));
        /*if( ( isset($this->latitude) && !empty($this->latitude) && isset($this->longitude) && !empty($this->longitude) )
         *  || (isset($this->street_address) && !empty($this->street_address)) || (isset($this->zipcode) && !empty($this->zipcode)) ) {      */                // for Zipcode, Address and Google API based API

        if(isset($this->userId) && !empty($this->userId) && (isset($this->flagFav) || isset($this->flagColleague)) ) {

        	// checking if flagFav is set for if a provider is a fav one or not.
            if($this->flagFav == 1) {

                $docUser =     $docUser->whereRaw('u.id in (select docId from myphysician where userId ='.$this->userId.')');
                $docUser =     $docUser->addSelect(DB::raw("1 as favFlag"));
            } else if($this->flagFav == 0 && !isset($this->flagColleague)) {

                $docUser =     $docUser->addSelect(DB::raw("(SELECT count(*) from myphysician where docId = u.id AND userID = $this->userId) as favFlag"));
            }

            // checking if flagColleague is set for if a provider is a colleague or not.
            if($this->flagColleague == Config::get('app.status.APPROVED')) {

                $docUser =     $docUser->whereRaw('u.id in (select colleague from mycolleague where docId ='.$this->userId.')');
                $docUser =     $docUser->addSelect(DB::raw("1 as flagColleague"));
            } else if($this->flagColleague == Config::get('app.user_status.PENDING')) {

                $docUser =     $docUser->addSelect(DB::raw("0 as flagColleague"));
                $docUser =     $docUser->where('u.id', '!=', $this->userId);
                $docUser =     $docUser->whereRaw('u.id not in (select colleague from mycolleague where docId ='.$this->userId.')');
            }
        }

        if(isset($this->latitude) && !empty($this->latitude) && isset($this->longitude) && !empty($this->longitude)) {

            $docUser->addSelect(DB::raw("ROUND(GetDistance(pu.latitude, pu.longitude, {$this->latitude}, {$this->longitude}),2) as distance"));
        }

        // condition First_name is Available
        if(isset($this->firstName) && !empty($this->firstName)) {

            $docUser->where('u.first_name', 'like', "{$this->firstName}");        
        }

        // condition Last Name is Available
        if(isset($this->lastName) && !empty($this->lastName)) {

            $docUser->where('u.last_name', 'like', "{$this->lastName}");
        }

        // condition User Name is Available
        if(isset($this->username) && !empty($this->username)) {

            $docUser->where('u.username', 'like', "$this->username%");
        }

        // condition Speciality id is Available
        if(isset($this->speciality_id) && !empty($this->speciality_id)) {

            $docUser->where('pu.speciality_id', '=', $this->speciality_id);
        }

        if(isset($this->speciality) && !empty($this->speciality)) {

            $docUser->where(
                function($query)
            	{
            		// Checking for Appointments which is starting on some date or Ending on some date
            		$query->where('s.specialty', 'like', "{$this->speciality}%")
            			  ->orWhere('ust.tag', 'like', "{$this->speciality}%");
            	}
            );
        }

        if(isset($this->speciality) && !empty($this->speciality)) {

            $docUser->where('s.specialty', 'like', "{$this->speciality}%");
        }

        if(isset($this->providerId) && !empty($this->providerId)) {

            $docUser->where('u.id', '!=', $this->providerId);
        }

        // for getting self info.
        if(isset($this->providerCode) && !empty($this->providerCode)) {

            $docUser->where('u.id', '=', $this->providerCode);
        }

        if(isset($this->userId) && !empty($this->userId) && isset($this->flagPrev)
            &&  $this->flagPrev == Config::get('app.status.APPROVED')) {

            // used when skip To get Providers who are already else only Contacted Providers.
            if(isset($this->flagReviewed)
                && $this->flagReviewed == Config::get('app.status.APPROVED')) {

                $docUser->whereRaw('pu.user_id in (SELECT DISTINCT ca.doc_user_id
                                FROM client_appointments ca
                                WHERE ca.user_id ='.$this->userId.' AND ca.doc_user_id NOT IN (SELECT doc_id
                                    FROM provider_reviews
                                    WHERE user_id ='.$this->userId.'))');
            } else {

                $docUser->whereRaw('pu.user_id in (SELECT DISTINCT doc_user_id
                                FROM client_appointments
                                WHERE user_id ='.$this->userId.')');
            }

            $docUser->addSelect(DB::raw("(select count(*) from 
            		myphysician where docId = u.id 
            		AND userId =".$this->userId.") as favFlag"));
            $docUser->addSelect(DB::raw("1 as flagPrev"));
        }

        // condition for Cordinates are available for nearby Search
        if(isset($this->latitude) && !empty($this->latitude) &&
        		 isset($this->longitude) && !empty($this->longitude)) {

            $docUser->having('distance', '<', 10);
            // condition if Cordinates not Available and address is Available
        } elseif((isset($this->street_address) &&
        		 !empty($this->street_address)) ||
        		 (isset($this->zipcode) && 
        		 		!empty($this->zipcode))) {

            // Condition for If street Address is not Empty
            if(isset($this->street_address) && 
            		!empty($this->street_address)) {
                //orwhere condition is added to suit search condition in client app
                $docUser->where("pu.street_address", 'like', "%{$this->street_address}%")
                        ->orwhere("pu.zipcode", '=', "{$this->street_address}");
            }

            // Condition for Zipcode is not empty
            if(isset($this->zipcode) && 
            		!empty($this->zipcode)) {

                $docUser->where("pu.zipcode", '=', "{$this->zipcode}");
            }
        }

        // code added : To refine response for chat requests
        if(isset($this->forChatRequest)) {
          
            // forChatRequest : 1 for search via filters in client app, 2 for search via provider pin
            if($this->forChatRequest == '1'){

                $docUser->where('pu.accept_system_client_chat', '=', 1);
                $docUser->where('pu.is_online', '=', 1);
            }

            if($this->forChatRequest == '2'){

                $docUser->where('pu.is_online', '=', 1);
                $docUser->where('usub.status', '=', 1);
            }
            
        }

        // code added : Condition for home visit check
        if(isset($this->homeVisit) && $this->homeVisit == '1') {

            $docUser->where('pu.accept_house_calls', '=', 1);
        }

        // code added : Condition for language
        if(isset($this->language_id) && $this->language_id != '') {

            $docUser->where('pu.language_id', '=', $this->language_id);
        }

        // code added : Condition for state
        if(isset($this->state_id) && $this->state_id != '') {
            $docUser->where('pu.state_id', '=', $this->state_id);
        }

        // code added : Condition for country
        if(isset($this->country_id) && $this->country_id != '') {
            $docUser->where('pu.country_id', '=', $this->country_id);
        }

        // code added : To list only provider approved by the admin in the admin panel
        $docUser->where('pu.hr_R', '=', 'A');

        // code added : To sort the response based on username
        $docUser->orderBy('username');

        // getting Response from Query
        $arrResponse = $docUser->get();

        $queries = DB::getQueryLog();
        $last_query = end($queries);
        Log::info("Last Query ".json_encode($last_query));

        // Checking for If Latitude is Set and Longitude is Set Merge that also
        if(isset($this->latitude) && 
        	!empty($this->latitude) &&
            isset($this->longitude) &&
        	!empty($this->longitude)) {

            // using array_sort for Sorting using Distance in multidimential Array of Objecyts.
            $arrResponse = array_values(
                               array_sort($arrResponse, function($value)
                               {
                                   return $value->distance;
                               }
                           )
                        );
        }

        // assigning first_name + last_name as username if username is empty.
        foreach($arrResponse as $key => $response) {

        	// getting via providercode.
            if(isset($this->providerCode) && !empty($this->providerCode) && 
                isset($this->appendReview) && !empty($this->appendReview) &&
                $this->appendReview == Config::get('app.status.APPROVED')) {

                    $providerReview = ProviderReview::where('doc_id', '=', $this->providerCode);

                    if(!empty($providerReview)) {

                    	$providerReview->with(array('userReviewed'));
                        $arrResponse[$key]->review = $providerReview->get()->toArray();
                    }
            }

            // setting username if not available.
            if(empty($response->username)) {

                $arrResponse[$key]->username = $response->first_name . " " . $response->last_name;
            }

            // setting Image for Employee if not available.
            if(!empty($response->parent_user_id) && $response->parent_user_id == 1) {

            	$employee = ProviderEmployee::where('user_id', '=', $response->id)->get();

            	if(!$employee->isEmpty()) {

            		$employee = $employee->first();

            		if(empty($response->image)) {
            		$arrResponse[$key]->image = User::select(array('id', 'image'))
            		                                ->find($employee->parent_user_id)->image;
            		}
            		
            		if($employee->accept_appointments != 1) {

            			unset($arrResponse[$key]);
            		}
            	}
            }
        }

        // code added : To refine response for chat requests
        if(isset($this->scrollIndex) && is_numeric($this->scrollIndex) && $this->scrollIndex >= 0) {

            $length = 10;
            if(isset($this->scrollLength) && is_numeric($this->scrollLength) && $this->scrollLength > 0) {
                $length = $this->scrollLength;
            }
            $arrResponse = array_slice($arrResponse, $this->scrollIndex * $length, $length);
            //$docUser->skip($this->scrollIndex * $length)->take($length);
        }

        return $arrResponse;
    }
}