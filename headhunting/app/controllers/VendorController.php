<?php

class VendorController extends \BaseController {


	/**
	 * Show the form for creating a new Vendor.
	 *
	 * @return Response
	 */
	public function create()
	{

		return View::make('Vendor.newVendor')->with(array('title' => 'Add Vendor'));
	}


	/**
	 * Show the form for creating a new Vendor.
	 *
	 * @return Response
	 */
	public function createVendor()
	{

		if(Auth::user()->getRole() <= 3) {
			Validator::extend('has', function($attr, $value, $params) {

				if(!count($params)) {

					throw new \InvalidArgumentException('The has validation rule expects at least one parameter, 0 given.');
				}

				foreach ($params as $param) {
					switch ($param) {
						case 'num':
							$regex = '/\pN/';
							break;
						case 'letter':
							$regex = '/\pL/';
							break;
						case 'lower':
							$regex = '/\p{Ll}/';
							break;
						case 'upper':
							$regex = '/\p{Lu}/';
							break;
						case 'special':
							$regex = '/[\pP\pS]/';
							break;
						default:
							$regex = $param;
					}

					if(! preg_match($regex, $value)) {
						return false;
					}
				}

				return true;
			});

			// Server Side Validation.
			$validate=Validator::make (
					Input::all(), array(
							'email' =>  'required|max:50|email|unique:vendors,email',
							'vendor_domain' => 'required|max:50',
							'phone' => 'max:14',
							'partner' => 'max:1'
					)
			);

			if($validate->fails()) {

				return Redirect::to('add-vendor')
							   ->withErrors($validate)
							   ->withInput();
			} else {

				$vendor = new Vendor();
				$vendor->vendor_domain = Input::get('vendor_domain');
				$vendor->email = Input::get('email');
				$vendor->phone = Input::get('phone');
				$vendor->partner = !empty(Input::get('partner')) ? Input::get('partner') : 0;
				$vendor->created_by = Auth::user()->id;

				// Checking Authorised or not
				if($vendor->save()) {
					$mail_group = new MailGroupMember();
					$mail_group->group_id = 2;
					$mail_group->user_id = $vendor->id;
					$mail_group->save();
					return Redirect::to('vendors');
				} else {
					return Redirect::to('add-vendor')->withInput();
				}
			}
		}
	}


	/**
	 *
	 * vendorList() : Vendor List
	 *
	 * @return Object : View
	 *
	 */
	public function vendorList() {
		$vendors = Vendor::paginate(100);
		return View::make('Vendor.vendorList')->with(array('title' => 'Vendors List', 'vendors' => $vendors));
	}


	/**
	 *
	 * thirdpartyListHasDocument() : View Vendor
	 *
	 * @return Object : View
	 *
	 */
	public function viewVendor($id) {

		if(Auth::user()->getRole() <= 3) {

			$vendor = Vendor::with(array('createdby'))->where('id', '=', $id)->get();

			if(!$vendor->isEmpty()) {
				$vendor = $vendor->first();
				return View::make('Vendor.viewVendor')
						   ->with(array('title' => 'View Vendor', 'vendor' => $vendor));
			} else {

				return Redirect::route('dashboard-view');
			}

		} else {
			return Redirect::route('dashboard-view');
		}
	}


	/**
	 *
	 * editVendor() : Edit Vendor
	 *
	 * @return Object : View
	 *
	 */
	public function editVendor($id) {

		if(Auth::user()->getRole() <= 3) {

			$vendor = Vendor::with(array('createdby'))->where('id', '=', $id)->get();

			if(!$vendor->isEmpty()) {
				$vendor = $vendor->first();
				return View::make('Vendor.editVendor')
						   ->with(array('title' => 'Edit Vendor', 'vendor' => $vendor));
			} else {

				return Redirect::route('dashboard');
			}

		} else {

			return Redirect::route('dashboard');
		}
	}


	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function updateVendor($id)
	{
		if(Auth::user()->getRole() <= 3) {
			Validator::extend('has', function($attr, $value, $params) {

				if(!count($params)) {

					throw new \InvalidArgumentException('The has validation rule expects at least one parameter, 0 given.');
				}

				foreach ($params as $param) {
					switch ($param) {
						case 'num':
							$regex = '/\pN/';
							break;
						case 'letter':
							$regex = '/\pL/';
							break;
						case 'lower':
							$regex = '/\p{Ll}/';
							break;
						case 'upper':
							$regex = '/\p{Lu}/';
							break;
						case 'special':
							$regex = '/[\pP\pS]/';
							break;
						default:
							$regex = $param;
					}

					if(! preg_match($regex, $value)) {
						return false;
					}
				}

				return true;
			});

			// Server Side Validation.
			$validate=Validator::make (
				Input::all(), array(
						'email' =>  'required|max:50|email',
						'vendor_domain' => 'required|max:50',
						'phone' => 'max:14',
						'partner' => 'max:1'
				)
			);
			if($validate->fails()) {

				return Redirect::route('edit-vendor', array('id' => $id))
								->withErrors($validate)
								->withInput();
			} else {

				$vendor = Vendor::find($id);

				$email = Input::get('email');
				if($email && $email != $vendor->email){
					if(!Vendor::where('email', '=', $email)->get()->isEmpty()) {
						return Redirect::route('edit-vendor', array('id' => $id))
						               ->withInput()
									   ->withErrors(array('email' => "Email is already in use"));
					}
					$vendor->email = $email;
				}
				
				$vendor->vendor_domain = Input::get('vendor_domain');
				$vendor->phone = Input::get('phone');
				$vendor->partner = !empty(Input::get('partner')) ? Input::get('partner') : 0;
				$vendor->created_by = Auth::user()->id;
				// Checking Authorised or not
				if($vendor->save()) {

					return Redirect::route('vendor-list');
				} else {

					return Redirect::route('edit-client')->withInput();
				}
			}
		}
	}


	/**
	 *
	 * deleteVendor() : Delete Vendor
	 *
	 * @return Object : View
	 *
	 */
	public function deleteVendor($id) {
		if(Auth::user()->getRole() <= 3) {
			$vendor = Vendor::find($id);
			if(MailGroupMember::where('user_id', '=', $vendor->id)->where('group_id', '=', 2)->delete() && $vendor->delete()) {
				return Redirect::route('vendor-list');
			}
		}
	}

}
