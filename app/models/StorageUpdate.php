<?php

class StorageUpdate extends Eloquent {
    
    /*-------------------------------------------------------------------------
	|	Class Attributes
	|--------------------------------------------------------------------------
	|	table		--- Database Table
	|	primaryKey	--- Primary key of the Projects Table
	|	timestamps	--- Prevents created_at and deleted_at fields from being required
	|------------------------------------------------------------------------*/
	protected $table        = 'storage_updates';
    protected $primaryKey   = 'update_id';
    public $timestamps      = false;
    
    /*-------------------------------------------------------------------------
	|	Get Storage Update by ID
	|--------------------------------------------------------------------------
	|	@param [in] 	tank_id     --- Tank ID
	|	@param [out] 	NONE
	|	@return 		udpate_data --- Database Record
	|------------------------------------------------------------------------*/
    public function getStorageUpdateById($update_id)
    {
        $udpate_data    = self::find($update_id);
        
        /*	関数終亁E   */
        return $udpate_data;
    }
    
    /*-------------------------------------------------------------------------
	|	Get alls Storage Updates by Tank
	|--------------------------------------------------------------------------
	|	@param [in] 	tank_id     --- Tank ID
	|	@param [out] 	NONE
	|	@return 		udpate_data --- Database Record
	|------------------------------------------------------------------------*/
    public function getAllStorageUpdateByTank($tank_id)
    {
        $udpate_data    = self::where('tank_id', $tank_id)
            ->where('status', STS_OK)
            ->get();
        
        /*	関数終亁E   */
        return $udpate_data;
    }
    
    /*-------------------------------------------------------------------------
	|	Get latest limited storage updates by Tank
	|--------------------------------------------------------------------------
	|	@param [in] 	tank_id     --- Tank ID
	|	@param [out] 	NONE
	|	@return 		udpate_data --- Database Record
	|------------------------------------------------------------------------*/
    public function getLatestLimitedStorageUpdateByTank($tank_id)
    {
        $udpate_data    = self::where('tank_id', $tank_id)
            ->orderBy('update_id', 'desc')
            ->limit(15)
            ->get();
        
        /*	関数終亁E   */
        return $udpate_data;
    }
    
    /*-------------------------------------------------------------------------
	|	Compute the Average Daily usage
	|--------------------------------------------------------------------------
	|	@param [in] 	tank_id         --- Tank ID
	|	@param [out] 	NONE
	|	@return 		avg_daily_usage --- Average Daily Usage
	|------------------------------------------------------------------------*/
    public function getAverageDailyUsage($tank_id)
    {
        /*--------------------------------------------------------------------
        /*	Variable Declaration
		/*------------------------------------------------------------------*/
        $avg_daily_usage    = 0;
        $update_count       = 0;
        $weekend            = 0;
        
        /*--------------------------------------------------------------------
        /*	Instantiate Database Classes
		/*------------------------------------------------------------------*/
        $tank_db    = new StorageTank;
        
        /*--------------------------------------------------------------------
        /*	Get Tank Information from Database
		/*------------------------------------------------------------------*/
        $tank       = $tank_db->getStorageTankById($tank_id);
        
        /*--------------------------------------------------------------------
        /*	Get Storage Updates Information from Database
		/*------------------------------------------------------------------*/
        /** Get the 2 latest delivery with before fill  */
        $updates    = self::where('tank_id', $tank_id)
            ->where('delivery_made', STS_OK)
            ->where('before_delivery', '!=', STR_EMPTY)
            ->orderBy('update_id', 'desc')
            ->take(2)
            ->get();
        
        $update_count   = count($updates);
        
        /*--------------------------------------------------------------------
        /*	Compute the Average daily balance if there are 2 records
		/*------------------------------------------------------------------*/
        if (2 <= $update_count) {
            /** Get the difference between the 2 reading dates  */
            $start_date = new DateTime($updates[1]->reading_date);
            $end_date   = new DateTime($updates[0]->reading_date);
            $interval   = $start_date->diff($end_date);
            
            /*---------------------------------------------------------------
            /*	Get all the weekends from the span between 2 dates
            /*-------------------------------------------------------------*/
            for ($day = 0; $day <= $interval->days; $day++) {
                $date_check = date('w', strtotime($updates[1]->reading_date.' +'.$day.' Days'));
                
                /** Day is a weekend (Saturday or Sunday)   */
                if ((0 == $date_check)
                || (6 == $date_check)) {
                    $weekend++;
                }
            }
            
            /*---------------------------------------------------------------
            /*	Determine the total days passed
            /*-------------------------------------------------------------*/
            if (1 == $tank->business_days) {
                /** 5 Days businesses, subtract the number of weekends  */
                $days_passed    = $interval->days - $weekend;
            }
            else {
                $days_passed    = $interval->days;
            }
            
            /** Compute the total usage between 2 dates                 */
            $total_usage    = $updates[1]->remaining_litres - $updates[0]->before_delivery;
            
            /** Compute the average daily usage */
            $avg_daily_usage    = $total_usage / $days_passed;
        }
        /*--------------------------------------------------------------------
        /*	Fallback to the static estimated daily usage
		/*------------------------------------------------------------------*/
        else {
            $avg_daily_usage    = $tank->estimated_usage;
        }
        
        /*	関数終亁E   */
        return $avg_daily_usage;
    }
    
    /*-------------------------------------------------------------------------
	|	Compute the Estimated remaining litres today
	|--------------------------------------------------------------------------
	|	@param [in] 	tank_id             --- Tank ID
	|	@param [out] 	NONE
	|	@return 		remaining_litres    --- Estimated Remaining Litres
	|------------------------------------------------------------------------*/
    public function getRemaningLitresToday($tank_id)
    {
        /*--------------------------------------------------------------------
        /*	Variable Declaration
		/*------------------------------------------------------------------*/
        $remaining_litres   = 0;
        
        /*--------------------------------------------------------------------
        /*	Get the latest entry
		/*------------------------------------------------------------------*/
        $latest_entry       = self::where('tank_id', $tank_id)
            ->orderBy('update_id', 'desc')
            ->first();
        
        /*--------------------------------------------------------------------
        /*	Get the average daily usage
		/*------------------------------------------------------------------*/
        $avg_daily_usage    = self::getAverageDailyUsage($tank_id);
        
        /*--------------------------------------------------------------------
        /*	If there is an entry in the database
		/*------------------------------------------------------------------*/
        if ($latest_entry) {
            /*---------------------------------------------------------------
            /*	Determine if today is the latest entry
            /*-------------------------------------------------------------*/
            $date_today = date('Y-m-d');
            $date_entry = date('Y-m-d', strtotime($latest_entry->reading_date));
            
            /** Today is the latest entry   */
            if ($date_today <= $date_entry) {
                $remaining_litres   = $latest_entry->remaining_litres;
            }
            /** Latest entry is days passed */
            else {
                /*----------------------------------------------------------
                /*	Get the days passed between today and the last entry
                /*--------------------------------------------------------*/
                $start_date = new DateTime($date_entry);
                $end_date   = new DateTime($date_today);
                $interval   = $start_date->diff($end_date);
                
                /*----------------------------------------------------------
                /*	Compute the remaining litres using the average daily
                /*  usage and days passed
                /*--------------------------------------------------------*/
                $remaining_litres   = $latest_entry->remaining_litres - ($avg_daily_usage * $interval->days);
            }
        }
        
        /*	関数終亁E   */
        return $remaining_litres;
    }
    
    /*-------------------------------------------------------------------------
	|	Computes for the Forecast of water level according to range
	|--------------------------------------------------------------------------
    |	@param [in] 	tank_id --- Tank ID
	|	@param [out] 	NONE
	|	@return 		output  --- Forecast Data
	|------------------------------------------------------------------------*/
    public function getForecastData($tank_id, $range)
    {
        /*--------------------------------------------------------------------
        /*	Variable Declaration
		/*------------------------------------------------------------------*/
        $output     = array();
        $days       = 0;
        $date_sump  = 'N/A';
        $days_sump  = 'N/A';
        
        /** Business Operation  */
        $days_operational       = 0;
        $business_operational   = STS_NG;
        
        /** Forecast Data       */
        $forecast               = array();
        $forecast_dump          = array();
        $forecast_chart_data    = array();
        $forecast_chart_count   = 0;
        
        /*--------------------------------------------------------------------
        /*	Instantiate Database Classes
		/*------------------------------------------------------------------*/
        $tank_db    = new StorageTank;
        
        /*--------------------------------------------------------------------
        /*	Fetch Tank Information
		/*------------------------------------------------------------------*/
        $tank       = $tank_db->getStorageTankById($tank_id);
        
        /*--------------------------------------------------------------------
        /*	Computes for the Average Daily Usage
		/*------------------------------------------------------------------*/
        $average_usage          = self::getAverageDailyUsage($tank_id);
        
        /*--------------------------------------------------------------------
        /*	Computes for the litres remaining before sump
		/*------------------------------------------------------------------*/
        $litres_today           = self::getRemaningLitresToday($tank_id);
        
        /*--------------------------------------------------------------------
        /*	Compute for the Tank's Actual Capacity
		/*------------------------------------------------------------------*/
        $tank_actual_capacity   = $tank->safety_limit - $tank->sump_level;
        
        /*--------------------------------------------------------------------
        /*	Forecast usage according to range of days
		/*------------------------------------------------------------------*/
        for ($days = 0; $range > $days; $days++) {
            /** Iterate the from today  */
            $forecast['date']       = date('d M Y', strtotime('+'.$days.' Days'));
            
            /*--------------------------------------------------------------------
            /*	Checks if the Business is operating on the current day
            /*------------------------------------------------------------------*/
            $business_operational   = STS_OK;
            
            /** Business that operates only 5 days a week   */
            if (1 == $tank->business_days) {
                /** Business is not operational during the weekend  */
                if ((0 == date('w', strtotime($forecast['date'])))
                || (6 == date('w', strtotime($forecast['date'])))) {
                    $business_operational   = STS_NG;
                }
            }
            
            /*--------------------------------------------------------------------
            /*	Business is Operational
            /*------------------------------------------------------------------*/
            if (STS_OK == $business_operational) {
                /** Decrease the estimated remaining litres by the average daily usage  */
                $forecast['litres'] = $litres_today - ($average_usage * $days_operational);
                
                /** Stop Forecasting when Litres reach 0    */
                if (0 >= $forecast['litres']) {
                    break;
                }
                /** Remaining Litres is above 0             */
                else {
                    /** Remaining Litres is above the Sump Level    */
                    if ((int)$tank->sump_level <= (int)$forecast['litres']) {
                        /** Compute the percentage before sump level is reached */
                        $forecast['sump']   = ($forecast['litres'] / $tank_actual_capacity) * 100;
                    }
                    /** Remaining Litres is below the Sump Level */
                    else {
                        $forecast['sump']   = 0;
                    }
                    
                    /** The date the Sump level is reached  */
                    if ((0 >= $forecast['sump']) 
                    && ('N/A' == $date_sump)) {
                        $date_sump      = date('d M', strtotime($forecast['date']));
                        $days_sump      = $days;
                    }
                    
                    $forecast_dump[]    = $forecast;
                    
                    /** The first 15 days are displayed in the Chart    */
                    if ($forecast_chart_count <= 15) {
                        $forecast_chart_data[]  = $forecast;
                    }
                }
                
                $days_operational++;
            }
            
            $forecast_chart_count++;
        }
        
        /*--------------------------------------------------------------------
        /*	Arranges the Output data
		/*------------------------------------------------------------------*/
        $output['forecast_dump']    = $forecast_dump;
        $output['chart_data']       = $forecast_chart_data;
        $output['date_sump']        = $date_sump;
        $output['days_sump']        = $days_sump;
        
        /*	関数終亁E   */
        return $output;
    }
    
    /*-------------------------------------------------------------------------
	|	Gets the latest 15 days historical data of a Tank
	|--------------------------------------------------------------------------
    |	@param [in] 	tank_id --- Tank ID
	|	@param [out] 	NONE
	|	@return 		output  --- Historical data (Latest 15 days)
	|------------------------------------------------------------------------*/
    public function getHistoricalData($tank_id)
    {
        /*--------------------------------------------------------------------
        /*	Variable Declaration
		/*------------------------------------------------------------------*/
        $output             = array();
        $remaining_litres   = 0;
        $input_today        = STS_NG;
        $delivery_date      = STR_EMPTY;
        $reading_date       = STR_EMPTY;
        
        $tank_db            = new StorageTank;
        $tank               = $tank_db->getStorageTankById($tank_id);
        
        $storage_updates    = self::getLatestLimitedStorageUpdateByTank($tank_id);
        
        /*--------------------------------------------------------------------
        /*	Compute for Sump Capacity and Actual Capacity
		/*------------------------------------------------------------------*/
        $tank_actual_capacity   = $tank->safety_limit - $tank->sump_level;
        
        /*--------------------------------------------------------------------
        /*	Checks if there is an update today
		/*------------------------------------------------------------------*/
        if (0 < count($storage_updates)) {
            if (date('Y-m-d') == date('Y-m-d', strtotime($storage_updates[0]->reading_date))) {
                $input_today    = STS_OK;
            }
        }
        
        /*--------------------------------------------------------------------
        /*	Historical Data of Updates
		/*------------------------------------------------------------------*/
        foreach ($storage_updates as $update) {
            /*---------------------------------------------------------------
            /*	Computes the Percentage until Sump
            /*-------------------------------------------------------------*/
            $update->before_sump = ($update->remaining_litres / $tank_actual_capacity) * 100;
            
            /** Display should not surpass 100  */
            if (100 <= $update->before_sump) {
                $update->before_sump = 100;
            }
            
            /** Display should not go below 0   */
            if (0 >= $update->before_sump) {
                $update->before_sump = 0;
            }
            
            /*---------------------------------------------------------------
            /*	Get the latest delivery date
            /*-------------------------------------------------------------*/
            if ((STR_EMPTY == $delivery_date)
            && (STS_OK == $update->delivery_made)) {
                $delivery_date  = $update->reading_date;
            }
            
            /*---------------------------------------------------------------
            /*	Get the latest reading date and remaining litres
            /*-------------------------------------------------------------*/
            if (STR_EMPTY == $reading_date) {
                $reading_date       = $update->reading_date;
            }
        }
        
        /*--------------------------------------------------------------------
        /*	Arranges the Output data
		/*------------------------------------------------------------------*/
        $output['storage_updates']  = $storage_updates;
        $output['delivery_date']    = $delivery_date;
        $output['reading_date']     = $reading_date;
        $output['input_today']      = $input_today;
        
        /*	関数終亁E   */
        return $output;
    }
    
    public function getWaterLevelAlert($level, $range)
    {
        $tank_db    = new StorageTank;
        $tanks      = $tank_db->getAllActiveTanks();
        
        $start_date     = date('Y-m-d');
        $end_date       = date('Y-m-d', strtotime('+'.$range.' Days'));
        
        $litres_today   = 0;
        $litres_now     = 0;
        $avg_usage      = 0;
        
        $percent_lvl            = 0;
        $business_operational   = 0;
        $days_operational       = 0;
        
        foreach ($tanks as $tank) {
            $percent_lvl            = 0;
            $business_operational   = STS_OK;
            $days_operational       = 0;
            
            $litres_today   = self::getRemaningLitresToday($tank->tank_id);
            $avg_usage      = self::getAverageDailyUsage($tank->tank_id);
            
            
            for($day = 0; $day < $range; $day++) {
                $date_check = date('w', strtotime('+'.$day.' Days'));
                
                if (1 == $tank->business_days) {
                    /** Day is a weekend (Saturday or Sunday)   */
                    if ((0 == $date_check)
                    || (6 == $date_check)) {
                        $business_operational = STS_NG;
                    }
                }
                
                if (STS_OK == $business_operational) {
                    $litres_now     = $litres_today - ($avg_usage * $days_operational);
                    
                    $percent_lvl    = 100 * ($litres_now / ($tank->safety_limit - $tank->sump_level));
        
                    if ($level >= $percent_lvl) {
                        $tank_info_db   = new TankInformation;
                        $tank_info      = $tank_info_db->getTankInformationByTankId($tank->tank_id);
                        
                        $company['level']           = $litres_now;
                        $company['company_name']    = $tank_info->company_name;
                        $company['sump_level']      = $tank->sump_level;
                        $company['date_reach']      = date('Y-m-d', strtotime('+'.$day.' Days'));
                        
                        $companies[]                = $company;
                        
                        break;
                    }
        
                    $days_operational++;
                }
            }
        }
        
        return $companies;
    }
    
    /*-------------------------------------------------------------------------
	|	Computes for the Forecast of water level according to range
	|--------------------------------------------------------------------------
    |	@param [in] 	tank_id --- Tank ID
	|	@param [out] 	NONE
	|	@return 		output  --- Forecast Data
	|------------------------------------------------------------------------*/
    public function getPlannerForecastData($tank_id, $range)
    {
        /*--------------------------------------------------------------------
        /*	Variable Declaration
		/*------------------------------------------------------------------*/
        $output     = array();
        $days       = 0;
        $date_sump  = 'N/A';
        $days_sump  = 'N/A';
        
        /** Business Operation  */
        $days_operational       = 0;
        $business_operational   = STS_NG;
        
        /** Forecast Data       */
        $forecast               = array();
        $forecast_dump          = array();
        
        /*--------------------------------------------------------------------
        /*	Instantiate Database Classes
		/*------------------------------------------------------------------*/
        $tank_db    = new StorageTank;
        
        /*--------------------------------------------------------------------
        /*	Fetch Tank Information
		/*------------------------------------------------------------------*/
        $tank       = $tank_db->getStorageTankById($tank_id);
        
        /*--------------------------------------------------------------------
        /*	Computes for the Average Daily Usage
		/*------------------------------------------------------------------*/
        $average_usage          = self::getAverageDailyUsage($tank_id);
        
        /*--------------------------------------------------------------------
        /*	Computes for the litres remaining before sump
		/*------------------------------------------------------------------*/
        $litres_today           = self::getRemaningLitresToday($tank_id);
        
        /*--------------------------------------------------------------------
        /*	Compute for the Tank's Actual Capacity
		/*------------------------------------------------------------------*/
        $tank_actual_capacity   = $tank['safety_limit'] - $tank['sump_level'];
        
        /*--------------------------------------------------------------------
        /*	Forecast usage according to range of days
		/*------------------------------------------------------------------*/
        for ($days = 0; $range > $days; $days++) {
            /** Iterate the from today  */
            $forecast['date']       = date('d M Y', strtotime('+'.$days.' Days'));
            
            /*--------------------------------------------------------------------
            /*	Checks if the Business is operating on the current day
            /*------------------------------------------------------------------*/
            $business_operational   = STS_OK;
            
            /** Business that operates only 5 days a week   */
            if (1 == $tank['business_days']) {
                /** Business is not operational during the weekend  */
                if ((0 == date('w', strtotime($forecast['date'])))
                || (6 == date('w', strtotime($forecast['date'])))) {
                    $business_operational   = STS_NG;
                }
            }
            
            /*--------------------------------------------------------------------
            /*	Business is Operational
            /*------------------------------------------------------------------*/
            if (STS_OK == $business_operational) {
                /** Decrease the estimated remaining litres by the average daily usage  */
                $forecast['litres'] = $litres_today - ($average_usage * $days_operational);
                
                /** Stop Forecasting when Litres reach 0    */
                if (0 >= $forecast['litres']) {
                    $forecast['litres'] = 0;
                    $forecast['sump']   = 0;
                }
                /** Remaining Litres is above 0             */
                else {
                    /** Remaining Litres is above the Sump Level    */
                    if ((int)$tank->sump_level <= (int)$forecast['litres']) {
                        /** Compute the percentage before sump level is reached */
                        $forecast['sump']   = ($forecast['litres'] / $tank_actual_capacity) * 100;
                    }
                    /** Remaining Litres is below the Sump Level */
                    else {
                        $forecast['sump']   = 0;
                    }
                    
                    /** The date the Sump level is reached  */
                    if ((0 >= $forecast['sump']) 
                    && ('N/A' == $date_sump)) {
                        $date_sump      = date('M d', strtotime($forecast['date']));
                        $days_sump      = $days;
                    }
                }
                
                $days_operational++;
            }
            
            $forecast_dump[]    = $forecast;
        }
        
        /*--------------------------------------------------------------------
        /*	Arranges the Output data
		/*------------------------------------------------------------------*/
        $output['forecast_dump']    = $forecast_dump;
        $output['date_sump']        = $date_sump;
        $output['days_sump']        = $days_sump;
        
        /*	関数終亁E   */
        return $output;
    }
}
