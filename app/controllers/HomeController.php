<?php

class HomeController extends BaseController
{
    /*-------------------------------------------------------------------------
	|	Displays the Dashboard of a user
	|--------------------------------------------------------------------------
    |	@param [in] 	NONE
	|	@param [out] 	NONE
	|	@return 		User dashboard HTML
	|------------------------------------------------------------------------*/
	public function showHomeDashboard()
	{
        /*--------------------------------------------------------------------
        /*	User is not Logged in
		/*------------------------------------------------------------------*/
        if (!Auth::check()) {
            return Redirect::to('/login');
        }
        
        /*--------------------------------------------------------------------
        /*	Variable Declaration
		/*------------------------------------------------------------------*/
        $tank_sump_capacity     = 0;
        $tank_actual_capacity   = 0;        
        
        /** Summary Forecast data Column    */
        $litres_before_sump = 0;
        
        /** Summary Current Level Column    */
        $average_usage          = 0;
        
        /*--------------------------------------------------------------------
        /*	Instantiate Database Classes
		/*------------------------------------------------------------------*/
        $tank_db        = new StorageTank;
        $tank_info_db   = new TankInformation;
        $update_db      = new StorageUpdate;
        
        
        /*--------------------------------------------------------------------
        /*	Fetch Tank Information
		/*------------------------------------------------------------------*/
        /** Admin User viewing a customer       */
        if (2 == Auth::user()->user_type) {
            $tank   = $tank_db->getStorageTankById(Session::get('admin_view_tank'));
        }
        /** Customer viewing their Dashboard    */
        else {
            $tank   = $tank_db->getStorageTankByUser(Auth::user()->user_id);
        }
        
        $tank_info          = $tank_info_db->getTankInformationByTankId($tank->tank_id);
        
        /*--------------------------------------------------------------------
        /*	Get Historical Data of Tank
		/*------------------------------------------------------------------*/
        $update_data        = $update_db->getHistoricalData($tank->tank_id);
        
        /*--------------------------------------------------------------------
        /*	Compute for Sump Capacity and Actual Capacity
		/*------------------------------------------------------------------*/
        $tank_sump_capacity     = $tank->maximum_capacity - $tank->sump_level;
        $tank_actual_capacity   = $tank->safety_limit - $tank->sump_level;
        
        /*--------------------------------------------------------------------
        /*	Computes for the Average Daily Usage
		/*------------------------------------------------------------------*/
        $average_usage      = $update_db->getAverageDailyUsage($tank->tank_id);
        
        /*--------------------------------------------------------------------
        /*	Computes for the estimated litres today
		/*------------------------------------------------------------------*/
        $litres_today       = $update_db->getRemaningLitresToday($tank->tank_id);
        
        /*--------------------------------------------------------------------
        /*	Computes for the litres remaining before sump
		/*------------------------------------------------------------------*/
        $litres_before_sump = $litres_today - $tank->sump_level;
        
        /** Remaining litres before sump should not go below 0  */
        if (0 >= $litres_before_sump) {
            $litres_before_sump = 0;
        }
        
        /*--------------------------------------------------------------------
        /*	Safety Fill used in Bar Graph
		/*------------------------------------------------------------------*/
        $bar_safety_fill    = $tank->safety_limit - $litres_today;
        
        /*--------------------------------------------------------------------
        /*	Forecast usage for 30 days
		/*------------------------------------------------------------------*/
        $forecast_data      = $update_db->getForecastData($tank->tank_id, 30);
        
        /*	関数終亁E   */
		return View::make('home-dashboard',
            array(
                'storage_updates'       => $update_data['storage_updates'],
                'delivery_date'         => $update_data['delivery_date'],
                'reading_date'          => $update_data['reading_date'],
                'average_usage'         => $average_usage,
                
                'forecast_dump'         => $forecast_data['forecast_dump'],
                'forecast_chart_data'   => $forecast_data['chart_data'],
                'date_sump'             => $forecast_data['date_sump'],
                'days_sump'             => $forecast_data['days_sump'],
                'litres_before_sump'    => $litres_before_sump,
                
                'input_today'           => $update_data['input_today'],
                'litres_today'          => $litres_today,
                'bar_safety_fill'       => $bar_safety_fill,
                'tank_actual_capacity'  => $tank_actual_capacity,
                'tank'                  => $tank,
                'tank_info'             => $tank_info
            )
        );
	}
    
    /*-------------------------------------------------------------------------
	|	Submit the Litres update
	|--------------------------------------------------------------------------
    |	@param [in] 	NONE
	|	@param [out] 	NONE
	|	@return 		NONE
	|------------------------------------------------------------------------*/
    public function submitLitresUpdate()
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
        
        $input['litres']    = Input::get('litres');
        $input['initials']  = Input::get('initials');
        $input['delivery']  = Input::get('delivery_received');
        
        $messages = array(
		    'litres.required'    => 'The Litres is required.',
		);

		$rules = array(
	        'litres'     => 'required|numeric',
	    );
        
        if (NULL != $input['delivery']) {
            $delivery_made  = STS_OK;
        }
        
        $validator = Validator::make(Input::all(), $rules, $messages);
        
        if ($validator->fails()) {
            $srv_resp['messages']	= $validator->messages()->all();
		}
        else {
            
            $tank_data  = DB::table('storage_tanks')
                ->where('user_id', Auth::user()->user_id)
                ->first();
                
            $data_update = array(
                'tank_id'           => $tank_data->tank_id,
                'remaining_litres'  => $input['litres'],
                'delivery_made'     => $delivery_made,
                'initials'          => $input['initials'],
                'status'            => STS_OK,
                'reading_date'      => date('Y-m-d H:i:s'),
                'created_date'      => date('Y-m-d H:i:s'),
                'updated_date'      => date('Y-m-d H:i:s')
            );
            
            $add_sts    = DB::table('storage_updates')
                ->insert($data_update);
            
            if (STS_OK == $add_sts) {
                $srv_resp['sts']    = STS_OK;
            }
            else {
                $srv_resp['messages'][0]    = 'An error occured during update. Please try again.';
            }
        }
        
        return json_encode($srv_resp);
    }
    
    public function deleteLitresUpdate($update_id)
    {
        $sts    = STS_NG;
        
        $delete = DB::table('storage_updates')
            ->where('update_id', $update_id)
            ->delete();
        
        if ($delete) {
            $sts    = STS_OK;
        }
        
        return $sts;
    }
    
    public function featureTester()
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
}
