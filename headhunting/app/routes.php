<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

/**
 * Routes For REST API for New Payment Form.
 */
Route::match(array('GET'), '/', array(
		'as'    =>    'login-view',
		'uses'    =>    'UserController@loginView'
));

/**
 * Routes For REST API forsendMailFromCron
 */
Route::match(array('GET'), '/send-mail-from-cron', array(
		'as'    =>    'send-mail-from-cron',
		'uses'    =>    'UserController@sendMailFromCron'
));

/**
 * Routes For REST API for New Payment Form.
 */
Route::match(array('GET'), 'login', array(
	'as'    =>    'login-views',
	'uses'    =>    'UserController@loginView'
));

/**
 * Routes For REST API for New Payment Form.
 */
Route::match(array('POST'), 'login', array(
		'as'    =>    'login-member',
		'uses'    =>    'UserController@login'
), array('before' => 'csrf', function(){}));

Route::group(array('before' => 'auth'), function() {


    /**
	 * Routes for post-requirement
	 */
	Route::match(array('POST'), '/validate-candidate', array(
			'as'    =>    'validateCandidate',
			'uses'    =>    'CandidateController@validateCandidate'
	));
	/**
	 * Routes for post-requirement
	 */
	Route::match(array('POST'), '/get-assignee', array(
			'as'    =>    'get-assignee',
			'uses'    =>    'UserController@postRequirement'
	));

	/**
	 * Routes For REST API for new Employee
	 */
	Route::match(array('GET', 'POS'), 'job-submittel/{jobId}/{userId}', array(
			'as'    =>    'job-submittel',
			'uses'    =>    'CandidateController@jobSubmittel'
	));

	/**
	 * Routes for post-requirement
	 */
	Route::match(array('POST'), '/get-mail-groups', array(
			'as'    =>    'get-mail-groups',
			'uses'    =>    'HomeController@getMailGroups'
	));

	/**
	 * Routes For REST API for New Payment Form.
	 */
	Route::match(array('GET'), 'logout', array(
			'as'    =>    'logout-member',
			'uses'    =>    'UserController@logout'
	));
	/**
	 * Routes For REST API for New Payment Form.
	 */
	Route::match(array('GET'), 'dashboard', array(
			'as'    =>    'dashboard-view',
			'uses'    =>    'UserController@home'
	));

	/**
	 * Routes For REST API for new Employee
	 */
	Route::match(array('GET'), '/add-employee', array(
			'as'    =>    'add-employee',
			'uses'    =>    'UserController@addEmployee'
	));

	/**
	 * Routes For REST API for new Employee
	 */
	Route::match(array('POST'), '/add-employee', array(
			'as'    =>    'add-member',
			'uses'    =>    'UserController@addEmp'
	));

	/**
	 * Routes For REST API for new Employee
	 */
	Route::match(array('GET'), '/delete-employee/{id}', array(
			'as'    =>    'delete-member',
			'uses'    =>    'UserController@deleteEmp'
	));

	/**
	 * Routes For REST API for new Employee
	 */
	Route::match(array('GET'), '/edit-employee/{id}', array(
			'as'    =>    'edit-member',
			'uses'    =>    'UserController@editEmp'
	));

	/**
	 * Routes For REST API for new Employee
	 */
	Route::match(array('POST'), '/edit-employee/{id}', array(
			'as'    =>    'update-member',
			'uses'    =>    'UserController@updateEmp'
	));

	/**
	 * Routes For REST API for states
	 */
	Route::match(array('GET'), '/states/{id}', array(
			'as'    =>    'get-states',
			'uses'    =>    'UserController@getStates'
	));

	/**
	 * Routes For REST API for states
	 */
	Route::match(array('GET'), '/peers/{id}', array(
			'as'    =>    'get-peers',
			'uses'    =>    'UserController@getPeers'
	));

	/**
	 * Routes For REST API for states
	 */
	Route::match(array('GET'), '/team/{id?}', array(
			'as'    =>    'peers',
			'uses'    =>    'UserController@getTeam'
	));

	/**
	 * Routes For REST API for states
	 */
	Route::match(array('GET'), '/cities/{id}', array(
			'as'    =>    'get-cities',
			'uses'    =>    'UserController@getCities'
	));


	/**
	 * Routes For REST API for Change Password
	 */
	Route::match(array('GET'), '/change-password/{id}', array(
			'as'    =>    'change-password',
			'uses'    =>    'UserController@updatePassView'
	));

	/**
	 * Routes For REST API for Change Password
	 */
	Route::match(array('POST'), '/change-password/{id}', array(
			'as'    =>    'update-password',
			'uses'    =>    'UserController@updatePass'
	));

	/**
	 * Routes For REST API for new Employee
	 */
	Route::match(array('POST'), '/edit-employee/{id}', array(
			'as'    =>    'update-member',
			'uses'    =>    'UserController@updateEmp'
	));

	/**
	 * Routes For REST API for new Employee
	 */
	Route::match(array('GET'), '/view-employee/{id}', array(
		'as'    =>    'view-member',
		'uses'    =>    'UserController@viewEmp'
	));

	/**
	 * Routes For REST API for new Employee
	 */
	Route::match(array('GET', 'POST'), '/employees', array(
			'as'    =>    'employee-list',
			'uses'    =>    'UserController@employeeList'
	));


	/**
	 * Routes for post-requirement
	 */
	Route::match(array('GET'), '/post-requirement', array(
			'as'    =>    'post-requirement',
			'uses'    =>    'SaleController@postRequirementView'
	));

	/**
	 * Routes for post-requirement
	 */
	Route::match(array('GET'), '/edit-requirement/{id}', array(
			'as'    =>    'edit-requirement',
			'uses'    =>    'SaleController@editRequirementView'
	));

	/**
	 * Routes for list-requirement
	 */
	Route::match(array('GET', 'POST'), '/list-requirement', array(
			'as'    =>    'list-requirement',
			'uses'    =>    'SaleController@listRequirement'
	));

	/**
	 * Routes for list-submittel
	 */
	Route::match(array('GET'), '/list-submittel/{id?}', array(
			'as'    =>    'list-submittel',
			'uses'    =>    'SaleController@listSubmittel'
	));

	/**
	 * Routes for list-requirement
	 */
	Route::match(array('GET'), '/add-comment-job-post/{jobId}', array(
			'as'    =>    'add-comment-job-post-view',
			'uses'    =>    'SaleController@addCommentView'
	));

	/**
	 * Routes for list-requirement
	 */
	Route::match(array('POST'), '/add-comment-job-post/{jobId}', array(
			'as'    =>    'add-comment-job-post',
			'uses'    =>    'SaleController@addComment'
	));

	/**
	 * Routes for list-requirement
	 */
	Route::match(array('GET'), '/view-requirement/{id}', array(
			'as'    =>    'view-requirement',
			'uses'    =>    'SaleController@viewRequirement'
	));


	/**
	 * Routes for list-requirement
	 */
	Route::match(array('GET', 'POST'), '/assigned-requirement/{id?}', array(
			'as'    =>    'assigned-requirement',
			'uses'    =>    'SaleController@listRequirement'
	));

	/**
	 * Routes for list-requirement
	 */
	Route::match(array('GET'), '/delete-requirement/{id}', array(
			'as'    =>    'delete-requirement',
			'uses'    =>    'SaleController@deleteRequirement'
	));

	/**
	 * Routes for list-requirement
	 */
	Route::match(array('GET'), '/close-requirement/{id}', array(
			'as'    =>    'close-requirement',
			'uses'    =>    'SaleController@closeRequirement'
	));

	/**
	 * Routes for list-requirement
	 */
	Route::match(array('POST'), '/close-requirement/{id}', array(
			'as'    =>    'close-requirement',
			'uses'    =>    'SaleController@closeRequirement'
	));

	/**
	 * Routes for list-requirement
	 */
	Route::match(array('GET'), '/reopen-requirement/{id}', array(
			'as'    =>    'reopen-requirement',
			'uses'    =>    'SaleController@reopenRequirement'
	));

	/**
	 * Routes for list-requirement
	 */
	Route::match(array('GET'), '/assign-requirement/{id}/{assignedTo?}', array(
			'as'    =>    'assign-requirement',
			'uses'    =>    'SaleController@assignRequirement'
	));

	/**
	 * Routes for post-requirement
	 */
	Route::match(array('POST'), '/post-requirement', array(
			'as'    =>    'post-requirement-action',
			'uses'    =>    'SaleController@postRequirement'
	));

	/**
	 * Routes for post-requirement
	 */
	Route::match(array('POST'), '/update-requirement/{id}', array(
			'as'    =>    'update-requirement-action',
			'uses'    =>    'SaleController@updateRequirement'
	));

	/**
	 * Routes For REST API for new Employee
	 */
	Route::match(array('GET'), '/advance-search/{jobId?}', array(
			'as'    =>    'advance-search',
			'uses'    =>    'SearchController@advanceSearch'
	));

	/** ANKUR BHATI **/


	/**
	 * Routes For REST API for new Client
	 */
	Route::match(array('GET'), '/add-client', array(
			'as'    =>    'add-client',
			'uses'    =>    'ClientController@create'
	));

	/**
	 * Routes For REST API for new Client
	 */
	Route::match(array('POST'), '/add-client', array(
			'as'    =>    'add-client',
			'uses'    =>    'ClientController@createClient'
	));

	/**
	 * Routes For REST API for new Client
	 */
	Route::match(array('GET', 'POST'), '/clients', array(
			'as'    =>    'client-list',
			'uses'    =>    'ClientController@clientList'
	));

	/**
	 * Routes For REST API for new Client
	 */
	Route::match(array('GET'), '/view-client/{id}', array(
		'as'    =>    'view-client',
		'uses'    =>    'ClientController@viewClient'
	));

	/**
	 * Routes For REST API for edit Client
	 */
	Route::match(array('GET'), '/edit-client/{id}', array(
			'as'    =>    'edit-client',
			'uses'    =>    'ClientController@editClient'
	));

	/**
	 * Routes For REST API for edit Client
	 */
	Route::match(array('POST'), '/edit-client/{id}', array(
			'as'    =>    'update-client',
			'uses'    =>    'ClientController@updateClient'
	));

	/**
	 * Routes For REST API for new Employee
	 */
	Route::match(array('GET'), '/delete-client/{id}', array(
			'as'    =>    'delete-client',
			'uses'    =>    'ClientController@deleteClient'
	));

	/**
	 * Routes For REST API for new Client
	 */
	Route::match(array('GET'), '/client-upload', array(
			'as'    =>    'client-upload',
			'uses'    =>    'ClientController@clientUpload'
	));

	/**
	 * Routes For REST API for new Client
	 */
	Route::match(array('POST'), '/upload-client-csv', array(
			'as'    =>    'upload-client-csv',
			'uses'    =>    'ClientController@uploadClientCsv'
	));

	/**
	 * Routes For REST API for new Candidate
	 */
	Route::match(array('GET'), '/add-candidate', array(
			'as'    =>    'add-candidate',
			'uses'    =>    'CandidateController@create'
	));

	/**
	 * Routes For REST API for new Candidate
	 */
	Route::match(array('POST'), '/add-candidate', array(
			'as'    =>    'add-candidate',
			'uses'    =>    'CandidateController@createCandidate'
	));

	/**
	 * Routes For REST API for new Candidate
	 */
	Route::match(array('GET', 'POST'), '/candidates', array(
			'as'    =>    'candidate-list',
			'uses'    =>    'CandidateController@candidateList'
	));

	/**
	 * Routes For REST API for new Candidate
	 */
	Route::match(array('GET'), '/view-candidate/{id}/{jobId?}/{searchingText?}', array(
		'as'    =>    'view-candidate',
		'uses'    =>    'CandidateController@viewCandidate'
	));

	/**
	 * Routes For REST API for edit Candidate
	 */
	Route::match(array('GET'), '/edit-candidate/{id}', array(
			'as'    =>    'edit-candidate',
			'uses'    =>    'CandidateController@editCandidate'
	));

	/**
	 * Routes For REST API for edit Candidate
	 */
	Route::match(array('POST'), '/edit-candidate/{id}', array(
			'as'    =>    'update-candidate',
			'uses'    =>    'CandidateController@updateCandidate'
	));

	/**
	 * Routes For REST API for new Candidate
	 */
	Route::match(array('GET'), '/delete-candidate/{id}', array(
			'as'    =>    'delete-candidate',
			'uses'    =>    'CandidateController@deleteCandidate'
	));

	/**
	 * Routes For REST API for new Employee
	 */
	Route::match(array('POST'), '/advance-search/{jobId?}', array(
			'as'    =>    'search-result',
			'uses'    =>    'SearchController@searchResult'
	));

	/**
	 * Routes For GET API for mass mail
	 */
	Route::match(array('GET'), '/mass-mail/', array(
			'as'    =>    'mass-mail',
			'uses'    =>    'UserController@massMail'
	));

	/**
	 * Routes For GET API for mass mail
	 */
	Route::match(array('POST'), '/mass-mail/', array(
			'as'    =>    'mass-mail',
			'uses'    =>    'UserController@massMail'
	));

	/**
	 * Routes For GET API for mass mail
	 */
	Route::match(array('GET', 'POST'), '/mass-mail-list/', array(
			'as'    =>    'mass-mail-list',
			'uses'    =>    'UserController@massMailList'
	));

	/**
	 * Routes For REST API for new Employee
	 */
	Route::match(array('GET'), '/view-mass-mail/{id}', array(
		'as'    =>    'view-mass-mail',
		'uses'    =>    'UserController@viewMassMail'
	));

	/**
	 * Routes For POST API for mass mail
	 */
	Route::match(array('POST'), '/mass-mail/', array(
			'as'    =>    'mass-mail',
			'uses'    =>    'UserController@massMail'
	));


	/**
	 * Routes For REST API for new Vendor
	 */
	Route::match(array('GET'), '/add-third-party', array(
			'as'    =>    'add-third-party',
			'uses'    =>    'ThirdpartyController@create'
	));

	/**
	 * Routes For REST API for new Vendor
	 */
	Route::match(array('POST'), '/add-third-party', array(
			'as'    =>    'add-third-party',
			'uses'    =>    'ThirdpartyController@createThirdparty'
	));

	/**
	 * Routes For REST API for new Vendor
	 */
	Route::match(array('GET', 'POST'), '/third-parties', array(
			'as'    =>    'third-party-list',
			'uses'    =>    'ThirdpartyController@thirdpartyList'
	));

	/**
	 * Routes For REST API for new Vendor
	 */
	Route::match(array('GET'), '/view-third-party/{id}', array(
		'as'    =>    'view-third-party',
		'uses'    =>    'ThirdpartyController@viewThirdparty'
	));

	/**
	 * Routes For REST API for edit Vendor
	 */
	Route::match(array('GET'), '/edit-third-party/{id}', array(
			'as'    =>    'edit-third-party',
			'uses'    =>    'ThirdpartyController@editThirdparty'
	));

	/**
	 * Routes For REST API for edit Vendor
	 */
	Route::match(array('POST'), '/edit-third-party/{id}', array(
			'as'    =>    'update-third-party',
			'uses'    =>    'ThirdpartyController@updateThirdparty'
	));

	/**
	 * Routes For REST API for new Vendor
	 */
	Route::match(array('GET'), '/delete-third-party/{id}', array(
			'as'    =>    'delete-third-party',
			'uses'    =>    'ThirdpartyController@deleteThirdparty'
	));

	/**
	 * Routes For REST API for states
	 */
	Route::match(array('GET'), '/third_party/', array(
			'as'    =>    'get-third-party',
			'uses'    =>    'CandidateController@getThirdParty'
	));

	/**
	 * Routes For REST API for new Client
	 */
	Route::match(array('GET'), '/third-party-upload', array(
			'as'    =>    'vendor-third-party',
			'uses'    =>    'ThirdpartyController@thirdpartyUpload'
	));

	/**
	 * Routes For REST API for new Client
	 */
	Route::match(array('POST'), '/upload-third-party-csv', array(
			'as'    =>    'upload-third-party-csv',
			'uses'    =>    'ThirdpartyController@uploadThirdpartyCsv'
	));

	/**
	 * Routes For REST API for new Client
	 */
	Route::match(array('GET'), '/settings', array(
			'as'    =>    'settings',
			'uses'    =>    'UserController@settings'
	));

	/**
	 * Routes For REST API for new Client
	 */
	Route::match(array('POST'), '/settings', array(
			'as'    =>    'settings',
			'uses'    =>    'UserController@saveSettings'
	));
	/** ANKUR BHATI **/
});
