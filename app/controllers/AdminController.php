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
        $archives   = $tank_db->getAllArchiveTanks();
        
        foreach ($tanks as $tank) {
            $tank->information = $profile_db->getTankInformationByTankId($tank->tank_id);
        }
        
        foreach ($archives as $tank) {
            $tank->information = $profile_db->getTankInformationByTankId($tank->tank_id);
        }
        
        return View::make('customer.customer-list',
            array(
                'tanks'     => $tanks,
                'archives'  => $archives
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
        $tank_info->delivery_time   = STR_EMPTY;
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
        $tank->monthly_usage        = STR_EMPTY;
        $tank->annual_usage         = STR_EMPTY;
        
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
    
    public function archiveCustomer($tank_id)
    {
        $sts        = 2;
        
        $tank_db    = new StorageTank;
        $tank       = $tank_db->getStorageTankById($tank_id);
        
        if (2 == $tank->status) {
            $sts    = 1;
        }
        else {
            $sts    = 2;
        }
        
        $tank_db->setCustomerStatus($tank_id, $sts);
        
        return Redirect::To('admin/customer/list');
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
        $input['delivery_time']     = Input::get('delivery_time');
        
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
        $input['monthly_usage']     = Input::get('monthly_usage');
        $input['annual_usage']      = Input::get('annual_usage');
        
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
		    'safety_limit.required'     => 'The Safety Fill is required.',
		    'sump_level.required'       => 'The Sump Level is required.',
		    'estimated_usage.required'  => 'The Estimated usage is required.',
		);

		$rules = array(
	        'company_name'      => 'required',
            
	        'address'           => 'required',
	        'city'              => 'required',
	        'state'             => 'required',
	        'latitude'          => 'numeric',
	        'longtitude'        => 'numeric',
            
	        'contact_email'     => 'required',
            
	        'business_days'     => 'required',
            
	        'maximum_capacity'  => 'required',
	        'safety_limit'      => 'required',
	        'sump_level'        => 'required',
	        'estimated_usage'   => 'required',
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
                'maximum_capacity'  => str_replace(',', '', $input['maximum_capacity']),
                'safety_limit'      => str_replace(',', '', $input['safety_limit']),
                'sump_level'        => str_replace(',', '', $input['sump_level']),
                'estimated_usage'   => str_replace(',', '', $input['estimated_usage']),
                'monthly_usage'     => str_replace(',', '', $input['monthly_usage']),
                'annual_usage'      => str_replace(',', '', $input['annual_usage']),
                
                'business_days'     => $input['business_days'],
                
                'status'            => STS_OK,
                'updated_date'      => date('Y-m-d H:i:s')
            );
            
            $data_customer = array(
                'company_name'      => $input['company_name'],
                'delivery_time'     => $input['delivery_time'],
                
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
        $delivery_db    = new VehicleDelivery;
        
        $update_data    = $update_db->getWaterLevelAlert(20, 15);
        $categories     = $category_db->getVehicleCategoryByVehicle($vehicle_id);
        $companies      = $co_cat_db->getCompanyCategoryByVehicle($vehicle_id);
        $deliv_data     = $delivery_db->getScheduledDelivery($vehicle_id);
        $refills        = $delivery_db->getRefillDays($vehicle_id);
        $deliveries     = $deliv_data[0];
        $totals         = $deliv_data[1];
        
        return View::make('admin.delivery-planner',
            array(
                'update_data'   => $update_data,
                'categories'    => $categories,
                'companies'     => $companies,
                'deliveries'    => $deliveries,
                'totals'        => $totals,
                'refills'       => $refills,
                'vehicle_id'    => $vehicle_id
            )
        );
    }
    
    public function showDeliveryForm()
    {
        return View::make('modal.modal-create-delivery');
    }
    
    public function getScheduledDelivery()
    {
        $tank_id        = Input::get('tank_id');
        $litres         = Input::get('litres');
        $delivery_date  = Input::get('delivery_date');
        $vehicle_id     = Input::get('vehicle_id');
        
        $tank_db        = new StorageTank;
        $delivery_db    = new VehicleDelivery;
        
        $tank       = $tank_db->getStorageTankById($tank_id);
        $delivery   = $delivery_db->getScheduledDeliveryByTank($vehicle_id, $tank_id, $delivery_date);
        $free_vol   = $delivery_db->getVehicleRemainingLitres($vehicle_id, $delivery_date);
        
        $srv_resp['round_down']             = ((int)(($tank->safety_limit - $litres) / 100)) * 100;
        $srv_resp['round_down_txt']         = number_format($srv_resp['round_down'], 2);
        
        $srv_resp['remaining_litres']       = $litres;
        $srv_resp['litres_txt']             = number_format($srv_resp['remaining_litres'], 2);
        
        $srv_resp['delivery_date']          = $delivery_date;
        $srv_resp['date_txt']               = date('M d', strtotime($delivery_date));
        
        $srv_resp['tank_id']                = $tank_id;
        $srv_resp['delivery_id']            = 0;
        $srv_resp['vehicle_delivery_id']    = 0;
        
        $srv_resp['purchase_number']        = '';
        $srv_resp['volume_manual']          = '';
        $srv_resp['safety_fill']            = number_format($tank->safety_limit, 2);
        $srv_resp['free_volume']            = number_format($free_vol, 2);
        
        if ($delivery) {
            $srv_resp['purchase_number']        = $delivery->purchase_number;
            $srv_resp['volume_manual']          = $delivery->volume_manual;
            $srv_resp['vehicle_delivery_id']    = $delivery->vehicle_delivery_id;
        }
        
        return json_encode($srv_resp);
    }
    
    public function createScheduledDelivery()
    {
        /*--------------------------------------------------------------------
        /*	Variable Declaration
		/*------------------------------------------------------------------*/
        $messages               = array();
        $rules                  = array();
        $srv_resp               = array();
        $total_added            = 0;
        
        $srv_resp['sts']        = STS_NG;
        $srv_resp['messages']   = array();
        
        $purchase_number        = Input::get('purchase_number');
        $volume_manual          = Input::get('volume_manual');
        $remaining_litres       = Input::get('remaining_litres');
        $delivery_date          = Input::get('delivery_date');
        $tank_id                = Input::get('tank_id');
        $vehicle_id             = Input::get('vehicle_id');
        
        $vehicle_delivery_id    = Input::get('vehicle_delivery_id');
        
        $delivery_db    = new VehicleDelivery;
        $storage_db     = new StorageTank;
        
        $tank           = $storage_db->getStorageTankById($tank_id);
        $free_volume    = $delivery_db->getVehicleRemainingLitres($vehicle_id, $delivery_date);
        $total_added    = $remaining_litres + $volume_manual;
        
        $messages = array(
		    'volume_manual.numeric'    => 'The Volume must be a number - please do not use any signs ($, %, #, etc)',
		);

		$rules = array(
	        'volume_manual'     => 'numeric',
	    );
        
        $validator = Validator::make(Input::all(), $rules, $messages);
        
        if ($validator->fails()) {
            $srv_resp['messages']	= $validator->messages()->all();
		}
        else if ($free_volume < $volume_manual) {
            $srv_resp['messages'][0]    = 'The vehicle cannot carry more than '.$free_volume.' at this time. Please schedule a refill at a date before this delivery.';
        }
        else if ($tank->safety_limit < $total_added) {
            $srv_resp['messages'][0]    = 'The Safety Fill of the tank will be surpassed with the volume inputted';
        }
        else {
            if (0 == $vehicle_delivery_id) {
                $delivery   = new VehicleDelivery;
                
                $delivery->vehicle_id       = $vehicle_id;
                $delivery->tank_id          = $tank_id;
                $delivery->remaining_litres = $remaining_litres;
                $delivery->status           = STS_OK;
                $delivery->delivery_date    = $delivery_date;
                $delivery->created_date     = date('Y-m-d');
            }
            else {
                $delivery   = VehicleDelivery::find($vehicle_delivery_id);
            }
            
            $delivery->purchase_number  = $purchase_number;
            $delivery->volume_manual    = $volume_manual;
            $delivery->updated_date     = date('Y-m-d');
            
            $delivery->save();
            
            $srv_resp['sts']        = STS_OK;
        }
        
        return json_encode($srv_resp);
    }
    
    public function createRefillDay($vehicle_id)
    {
        $srv_resp['sts']        = STS_NG;
        $srv_resp['messages']   = array();
        
        $refill_date    = Input::get('refill_date');
        $refill_num     = Input::get('batch_number');
        
        $refill_exist   = VehicleDelivery::where('delivery_date', $refill_date)
            ->where('status', 3)
            ->first();
        
        if ($refill_exist) {
            VehicleDelivery::where('vehicle_delivery_id', $refill_exist->vehicle_delivery_id)
                ->delete();
        }
        else {
            VehicleDelivery::insert(
                array(
                    'vehicle_id'        => $vehicle_id,
                    'purchase_number'   => $refill_num,
                    'tank_id'           => 0,
                    'volume_manual'     => 20000,
                    'status'            => 3,
                    'delivery_date'     => $refill_date,
                    'created_date'      => date('Y-m-d'),
                    'updated_date'      => date('Y-m-d')
                )
            );
            
            $srv_resp['sts']    = STS_OK;
        }
        
        return $srv_resp;
    }
    
    public function cancelDelivery($vehicle_delivery_id)
    {
        VehicleDelivery::where('vehicle_delivery_id', $vehicle_delivery_id)
                ->delete();
    }
    
    public function showDeliverySummary($vehicle_id)
    {
        $delivery_db    = new VehicleDelivery;
        $deliveries     = $delivery_db->getVehicleDeliverySummary($vehicle_id);
        
        return View::make('admin.delivery-summary',
            array(
                'deliveries'    => $deliveries,
                'vehicle_id'    => $vehicle_id,
            )
        );
    }
    
    public function sendSummaryEmail()
    {
        $email_rcp      = Input::get('email_rcp');
        $delivery_ids   = Input::get('delivery_ids');
        $vehicle_id     = Input::get('vehicle_id');
        
        $send_list      = array();
        $delivery_db    = new VehicleDelivery;
        $deliveries     = $delivery_db->getVehicleDeliverySummary($vehicle_id);
        
        if (NULL != $delivery_ids) {
            foreach ($deliveries as $delivery) {
                if (in_array($delivery->vehicle_delivery_id, $delivery_ids)) {
                    $send_list[]    = $delivery;
                    $comments[]     = Input::get('delivery_remarks_'.$delivery->vehicle_delivery_id);
                }
            }
        }
        
        /*
        return View::make('emails.summary-email',
            array(
                'deliveries'    => $send_list,
                'comments'      => $comments
            )
        );
        */
        
        Mail::send('emails.summary-email',
            array(
                'deliveries'    => $send_list,
                'comments'      => $comments
            ),
            function($message) use ($email_rcp) {
                $message->to($email_rcp, $email_rcp)
                    ->subject('Delivery List');
            }
        );
    }
    
    public function showTransactionLogs()
    {
        return View::make('admin.transaction-log');
    }
    
    public function showLogsTable()
    {
        $from_date  = Input::get('from_date');
        $to_date    = Input::get('to_date');
        
        $deliveries = DB::table('transaction_logs')
            ->where('delivery_date', '>=', $from_date)
            ->where('delivery_date', '<=', $to_date)
            ->where('status', 1)
            ->get();
            
        return View::make('admin.log-table',
            array(
                'deliveries'    => $deliveries
            )
        );
    }
    
    public function saveTransactionLogs()
    {
        $idx            = 0;
        $messages       = array();
        $rules          = array();
        
        /** Initialize Server Response  */
        $srv_resp['sts']        = STS_NG;
        $srv_resp['messages']   = STR_EMPTY;
        $srv_resp['validator']  = array();
        
        $transaction_ids    = Input::get('transaction_id');
        $tank_ids           = Input::get('tank_id');
        $delivery_dates     = Input::get('delivery_date');
        $products           = Input::get('product');
        $batches            = Input::get('batch');
        $quantities         = Input::get('quantity');
        $delivery_dockets   = Input::get('delivery_docket');
        $invoice_numbers    = Input::get('invoice_number');
        $actual_volumes     = Input::get('actual_volume');
        $remarks            = Input::get('remarks');
        $order_dates        = Input::get('order_date');
        $remaining_litres   = Input::get('remaining_litres');
        $before_fills       = Input::get('before_fill');
        $after_fills        = Input::get('after_fill');
        
        /*--------------------------------------------------------------------
		/*	Sets rules and messages
		/*------------------------------------------------------------------*/
		foreach($transaction_ids as $key => $value) {
            $idx++;
            
            $rules['transaction_id.'.$key]                  = 'required';
            $messages['transaction_id.'.$key.'.required']   = 'Transaction ID for field '.$idx.' is required';
		}
        
        $validator = Validator::make(Input::all(), $rules, $messages);
        
        if ($validator->fails()) {
            $srv_resp['messages']	= $validator->messages()->all();
            $srv_resp['rules']      = $validator->failed();
        }
        else {
            foreach ($transaction_ids as $key => $val) {
                $data_update    = array(
                    'product'           => $products[$key],
                    'batch'             => $batches[$key],
                    'quantity'          => str_replace(',', '', $quantities[$key]),
                    'delivery_docket'   => $delivery_dockets[$key],
                    'invoice_number'    => $invoice_numbers[$key],
                    'actual_volume'     => str_replace(',', '', $actual_volumes[$key]),
                    'remarks'           => $remarks[$key],
                    'order_date'        => $order_dates[$key],
                    'remarks'           => $remarks[$key],
                    'remaining_litres'  => str_replace(',', '', $remaining_litres[$key]),
                    'before_fill'       => str_replace(',', '', $before_fills[$key]),
                    'after_fill'        => str_replace(',', '', $after_fills[$key])
                );
                
                $update = DB::table('transaction_logs')
                    ->where('transaction_id', $val)
                    ->update($data_update);
                    
                if ((0 != $tank_ids[$key])
                && (STR_EMPTY != $before_fills[$key])
                && (STR_EMPTY != $after_fills[$key])) {
                    
                    $storage = StorageUpdate::where('tank_id', $tank_ids[$key])
                        ->where('reading_date', $delivery_dates[$key])
                        ->first();
                        
                    if ($storage) {
                        $storage->before_delivery = $before_fills[$key];
                        $storage->save();
                    }
                    else {
                        $storage = new StorageUpdate;
                        
                        $storage->tank_id           = $tank_ids[$key];
                        $storage->remaining_litres  = str_replace(',', '', $after_fills[$key]);
                        $storage->delivery_made     = 1;
                        $storage->initials          = 'SYS';
                        $storage->before_delivery   = str_replace(',', '', $before_fills[$key]);
                        $storage->status            = STS_OK;
                        $storage->reading_date      = $delivery_dates[$key];
                        $storage->created_date      = date('Y-m-d H:i:s');
                        $storage->updated_date      = date('Y-m-d H:i:s');
                        $storage->save();
                    }
                }
            }
            
            $srv_resp['sts']        = STS_OK;
        }
        
        return json_encode($srv_resp);
    }
    
    public function deleteTransactionLog($transaction_id)
    {
        $transaction_db = DB::table('transaction_logs')->where('transaction_id', $transaction_id)->delete();
        
        return STS_OK;
    }
    
    public function saveDeliveryRemarks($delivery_id)
    {
        DB::table('vehicle_deliveries')
            ->where('vehicle_delivery_id', $delivery_id)
            ->update(
                array(
                    'remarks'   => Input::get('remarks')
                )
            );
    }
}