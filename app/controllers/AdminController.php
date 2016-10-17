<?php

class AdminController extends BaseController
{
    public function showAdminDashboard()
    {
        $update_db  = new StorageUpdate;
        $update_data    = $update_db->getWaterLevelAlert(20, 15);
        
        foreach($update_data as $company) {
            //echo $company['company_name'].', '.$company['level'].', '.$company['date_reach'].'<br/>';
        }
        
        return View::make('admin.admin-dashboard',
            array(
                'update_data' => $update_data
            )
        );
    }
    
    /*-------------------------------------------------------------------------
	|	Displays the Dashboard of a user
	|--------------------------------------------------------------------------
    |	@param [in] 	NONE
	|	@param [out] 	NONE
	|	@return 		User dashboard HTML
	|------------------------------------------------------------------------*/
	public function showCustomerList()
	{
        $tank_db    = new StorageTank;
        $profile_db = new TankInformation;
        $tanks      = $tank_db->getAllActiveTanks();
        
        foreach ($tanks as $tank) {
            $tank->information = $profile_db->getTankInformationByTankId($tank->tank_id);
        }
        
        return View::make('customer.customer-list',
            array(
                'tanks' => $tanks
            )
        );
    }
    
    public function showCustomerDashboard($tank_id)
	{
        Session::put('admin_view_tank', $tank_id);
        return App::make('HomeController')->showHomeDashboard();
    }
    
    public function getReadingDate($update_id)
    {
        $update_db      = new StorageUpdate;
        $update_data    = $update_db->getStorageUpdateById($update_id);
        
        $update_data['reading_date']   = date('Y-m-d', strtotime($update_data['reading_date']));

        return json_encode($update_data);
    }
    
    public function updateCustomerLitres()
    {
        /*--------------------------------------------------------------------
        /*	Variable Declaration
		/*------------------------------------------------------------------*/
        $delivery_made          = STS_NG;
        
        $messages               = array();
        $rules                  = array();
        $srv_resp               = array();
        
        $srv_resp['sts']        = STS_NG;
        $srv_resp['messages']   = array();
        
        $input['litres']            = Input::get('litres');
        $input['before_delivery']   = Input::get('before_delivery');
        $input['delivery']          = Input::get('delivery_received');
        $input['reading_date']      = Input::get('reading_date');
        $input['tank_id']           = Input::get('tank_id');
        $input['update_id']         = Input::get('update_id');
        
        $messages = array(
		    'litres.required'    => 'The Litres is required.',
		);

		$rules = array(
	        'litres'     => 'required|numeric',
	    );
        
        if ((NULL != $input['delivery']) || (STR_EMPTY != $input['delivery'])) {
            $delivery_made  = STS_OK;
            
            $messages['before_delivery.required']   = 'Before Fill is required';
            $messages['before_delivery.numeric']    = 'Before Fill should be numeric';
            $rules['before_delivery']               = 'required|numeric';
        }
        
        $validator = Validator::make(Input::all(), $rules, $messages);
        
        if ($validator->fails()) {
            $srv_resp['messages']	= $validator->messages()->all();
		}
        else {
            
            $data_update = array(
                'tank_id'           => $input['tank_id'],
                'remaining_litres'  => $input['litres'],
                'delivery_made'     => $delivery_made,
                'before_delivery'   => $input['before_delivery'],
                'status'            => STS_OK,
                'reading_date'      => $input['reading_date'],
                'updated_date'      => date('Y-m-d H:i:s')
            );
            
            if (0 == $input['update_id']) {
                $data_update['created_date'] = date('Y-m-d H:i:s');
                
                $upd_sts    = DB::table('storage_updates')
                    ->insert($data_update);
            }
            else {                
                $upd_sts    = DB::table('storage_updates')
                    ->where('update_id', $input['update_id'])
                    ->update($data_update);
            }
            
            if (STS_OK == $upd_sts) {
                $srv_resp['sts']    = STS_OK;
            }
            else {
                $srv_resp['messages'][0]    = 'An error occured during update. Please try again.';
            }
        }
        
        return json_encode($srv_resp);
    }
    
    public function showAddCustomerForm()
    {
        $tank_info  = new stdClass;
        $tank       = new stdClass;
        
        $tank_info->company_name    = STR_EMPTY;
        $tank_info->address         = STR_EMPTY;
        $tank_info->city            = STR_EMPTY;
        $tank_info->state           = STR_EMPTY;
        
        $tank_info->latitude        = STR_EMPTY;
        $tank_info->longtitude      = STR_EMPTY;
        
        $tank_info->contact_person  = STR_EMPTY;
        $tank_info->contact_number  = STR_EMPTY;
        $tank_info->contact_email   = STR_EMPTY;
        
        $tank->business_days        = STR_EMPTY;
        $tank->maximum_capacity     = STR_EMPTY;
        $tank->safety_limit         = STR_EMPTY;
        $tank->sump_level           = STR_EMPTY;
        $tank->estimated_usage      = STR_EMPTY;
        
        return View::make('customer.add-form',
            array(
                'tank_info' => $tank_info,
                'tank'      => $tank,
            )
        );
    }
    
    public function showEditCustomerForm($tank_id)
    {
        $tank_db    = new StorageTank;
        $info_db    = new TankInformation;
        
        $tank       = $tank_db->getStorageTankById($tank_id);
        $tank_info  = $info_db->getTankInformationByTankId($tank_id);
        
        return View::make('customer.add-form',
            array(
                'tank_id'       => $tank_id,
                'tank'          => $tank,
                'tank_info'     => $tank_info
            )
        );
    }
    
    public function submitCustomerForm()
    {
        /*--------------------------------------------------------------------
        /*	Variable Declaration
		/*------------------------------------------------------------------*/
        $password               = STR_EMPTY;
        $characters             = array_merge(range('A','Z'), range('a','z'));
        $max                    = count($characters) - 1;
        
        $delivery_made          = STS_NG;
        
        $messages               = array();
        $rules                  = array();
        $srv_resp               = array();
        
        $srv_resp['sts']        = STS_NG;
        $srv_resp['messages']   = array();
        
        $input['tank_id']           = Input::get('tank_id');
        $input['company_name']      = Input::get('company_name');
        
        $input['address']           = Input::get('address');
        $input['city']              = Input::get('city');
        $input['state']             = Input::get('state');
        $input['latitude']          = Input::get('latitude');
        $input['longtitude']        = Input::get('longtitude');
        
        $input['contact_person']    = Input::get('contact_person');
        $input['contact_number']    = Input::get('contact_number');
        $input['contact_email']     = Input::get('contact_email');
        
        $input['business_days']     = Input::get('business_days');
        
        $input['maximum_capacity']  = Input::get('maximum_capacity');
        $input['safety_limit']      = Input::get('safety_limit');
        $input['sump_level']        = Input::get('sump_level');
        $input['estimated_usage']   = Input::get('estimated_usage');
        
        $messages = array(
		    'company_name.required' => 'The Customer Name is required.',
            
		    'address.required'          => 'The Address is required.',
		    'city.required'             => 'The City is required.',
		    'state.required'            => 'The State is required.',
		    'latitude.numeric'          => 'The Latitude should be numeric.',
		    'longtitude.numeric'        => 'The Longtitude should be numeric.',
            
		    'contact_email.required'    => 'The Email Address is required.',
		    'contact_email.email'       => 'The Email Address should be a valid email.',
            
		    'business_days.required'    => 'The Business Days is required.',
            
		    'maximum_capacity.required' => 'The Tank Size is required.',
		    'maximum_capacity.numeric'  => 'The Tank Size should be numeric.',
		    'safety_limit.required'     => 'The Safety Fill is required.',
            'safety_limit.numeric'      => 'The Safety Fill should be numeric.',
		    'sump_level.required'       => 'The Sump Level is required.',
            'sump_level.numeric'        => 'The Sump Level should be numeric.',
		    'estimated_usage.required'  => 'The Estimated usage is required.',
            'estimated_usage.numeric'   => 'The Estimated usage should be numeric.',
		);

		$rules = array(
	        'company_name'      => 'required',
            
	        'address'           => 'required',
	        'city'              => 'required',
	        'state'             => 'required',
	        'latitude'          => 'numeric',
	        'longtitude'        => 'numeric',
            
	        'contact_email'     => 'required',
            
	        'business_days'     => 'required|numeric',
            
	        'maximum_capacity'  => 'required|numeric',
	        'safety_limit'      => 'required|numeric',
	        'sump_level'        => 'required|numeric',
	        'estimated_usage'   => 'required|numeric',
	    );
        
        $validator = Validator::make(Input::all(), $rules, $messages);
        
        if ($validator->fails()) {
            $srv_resp['messages']	= $validator->messages()->all();
		}
        else {
            
            for ($ctr = 0; 8 > $ctr; $ctr++) {
                $rand       = mt_rand(0, $max);
                $password   .= $characters[$rand];
            }
            
            $data_user = array(
                'email'         => $input['contact_email'],
                'password'      => Hash::make($password),
                
                'user_type'     => 1,
                'status'        => STS_OK,
                'updated_date'  => date('Y-m-d H:i:s')
            );
            
            $data_tank = array(
                'maximum_capacity'  => $input['maximum_capacity'],
                'safety_limit'      => $input['safety_limit'],
                'sump_level'        => $input['sump_level'],
                'estimated_usage'   => $input['estimated_usage'],
                
                'business_days'     => $input['business_days'],
                
                'status'            => STS_OK,
                'updated_date'      => date('Y-m-d H:i:s')
            );
            
            $data_customer = array(
                'company_name'      => $input['company_name'],
                
                'address'           => $input['address'],
                'city'              => $input['city'],
                'state'             => $input['state'],
                'latitude'          => $input['latitude'],
                'longtitude'        => $input['longtitude'],
                
                'contact_person'    => $input['contact_person'],
                'contact_number'    => $input['contact_number'],
                'contact_email'     => $input['contact_email'],
                
                'status'            => STS_OK,
                'updated_date'      => date('Y-m-d H:i:s')
            );
            
            if (0 == $input['tank_id']) {
                $data_user['created_date']      = date('Y-m-d H:i:s');
                
                $user_id    = DB::table('user_accounts')
                    ->insertGetId($data_user);
                
                $data_tank['user_id']           = $user_id;
                $data_tank['created_date']      = date('Y-m-d H:i:s');
                
                $tank_id    = DB::table('storage_tanks')
                    ->insertGetId($data_tank);
                    
                $data_customer['tank_id']       = $tank_id;
                $data_customer['created_date']  = date('Y-m-d H:i:s');
                
                $upd_sts    = DB::table('tank_informations')
                    ->insert($data_customer);
                
                $srv_resp['tank_id']    = $tank_id;
                
                if (0) {
                Mail::send('emails.user-credentials',
                    array(
                        'data_customer' => $data_customer,
                        'data_user'     => $data_user,
                        'password'      => $password
                    ),
                    function($message) use ($data_user) {
                        $message->to($data_user['email'], $data_user['email'])
                            ->subject('Tank Level Tracker - Welcome, You account credentials');
                    }
                );
                }
            }
            else {                
                $upd_sts    = DB::table('storage_tanks')
                    ->where('tank_id', $input['tank_id'])
                    ->update($data_tank);
                
                $upd_sts    = DB::table('tank_informations')
                    ->where('tank_id', $input['tank_id'])
                    ->update($data_customer);
                
                $srv_resp['tank_id']    = $input['tank_id'];
            }
            
            if (STS_OK == $upd_sts) {
                $srv_resp['sts']            = STS_OK;
                $srv_resp['messages'][0]    = 'Requested Operation Successfull';
            }
            else {
                $srv_resp['messages'][0]    = 'An error occured during update. Please try again.';
            }
        }
        
        return json_encode($srv_resp);
    }
    
    public function showDeliveryPlanner($vehicle_id)
    {
        $update_db      = new StorageUpdate;
        $co_cat_db      = new CompanyCategory;
        $category_db    = new VehicleCategory;
        
        $update_data    = $update_db->getWaterLevelAlert(20, 15);
        $categories     = $category_db->getVehicleCategoryByVehicle($vehicle_id);
        $companies      = $co_cat_db->getCompanyCategoryByVehicle($vehicle_id);
        
        return View::make('admin.delivery-planner',
            array(
                'update_data'   => $update_data,
                'categories'    => $categories,
                'companies'     => $companies
            )
        );
    }
}