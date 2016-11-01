<?php

class VehicleController extends BaseController
{

    public function showVehicleForm($vehicle_id)
    {
        $vehicle_db     = new Vehicle;
        $vcat_db        = new VehicleCategory;
        $ccat_db        = new CompanyCategory;
        $info_db        = new TankInformation;
        
        $vehicle_info   = $vehicle_db->getVehicleById($vehicle_id);
        $vcat_data      = $vcat_db->getVehicleCategoryByVehicle($vehicle_id);
        $uncat_data     = $ccat_db->getUnCategorizedByVehicle($vehicle_id);
        
        foreach ($uncat_data as $uncat) {
            $uncat->tank_info   = $info_db->getTankInformationByTankId($uncat->tank_id);
        }
        
        $ccat_data      = $ccat_db->getCompanyCategoryByVehicle($vehicle_id);
        
        return View::make('vehicle.vehicle-update-form',
            array(
                'vehicle_info'  => $vehicle_info,
                'vcat_data'     => $vcat_data,
                'ccat_data'     => $ccat_data,
                'uncat_data'    => $uncat_data
            )
        );
    }
    
    public function submitVehicleForm()
    {
        /*--------------------------------------------------------------------
        /*	Variable Declaration
		/*------------------------------------------------------------------*/
        $messages               = array();
        $rules                  = array();
        $srv_resp               = array();
        
        $srv_resp['sts']        = STS_NG;
        $srv_resp['messages']   = array();
        
        $input['vehicle_id']            = Input::get('vehicle_id');
        $input['vehicle_label']         = Input::get('vehicle_label');
        $input['tank_capacity']         = Input::get('tank_capacity');
        $input['plate_number']          = Input::get('plate_number');
        $input['vehicle_description']   = Input::get('vehicle_description');
        
        $input['address']               = Input::get('address');
        $input['city']                  = Input::get('city');
        $input['state']                 = Input::get('state');
        $input['latitude']              = Input::get('latitude');
        $input['longtitude']            = Input::get('longtitude');
        
        $messages = array(
		    'vehicle_label.required'    => 'The Vehicle Label is required.',
		    'tank_capacity.required'    => 'The Vehicle Capacity is required.',
		    'tank_capacity.numeric'     => 'The Vehicle Capacity should be numeric.',
            
		    'address.required'          => 'The Address is required.',
		    'city.required'             => 'The City is required.',
		    'state.required'            => 'The State is required.',
		    'latitude.numeric'          => 'The Latitude should be numeric.',
		    'longtitude.numeric'        => 'The Longtitude should be numeric.'
		);

		$rules = array(
	        'vehicle_label'     => 'required',
	        'tank_capacity'     => 'required|numeric',
            
	        'address'           => 'required',
	        'city'              => 'required',
	        'state'             => 'required',
	        'latitude'          => 'numeric',
	        'longtitude'        => 'numeric'
	    );
        
        $validator = Validator::make(Input::all(), $rules, $messages);
        
        if ($validator->fails()) {
            $srv_resp['messages']	= $validator->messages()->all();
		}
        else {
            
            $data_vehicle   = array(
                'vehicle_label'         => $input['vehicle_label'],
                'tank_capacity'         => $input['tank_capacity'],
                'plate_number'          => $input['plate_number'],
                'vehicle_description'   => $input['vehicle_description'],
                
                'address'               => $input['address'],
                'city'                  => $input['city'],
                'state'                 => $input['state'],
                'latitude'              => $input['latitude'],
                'longtitude'            => $input['longtitude'],
                
                'status'                => STS_OK,
                'created_date'          => date('Y-m-d H:i:s'),
                'updated_date'          => date('Y-m-d H:i:s')
            );
            
            $upd_sts    = DB::table('vehicles')
                ->where('vehicle_id', $input['vehicle_id'])
                ->update($data_vehicle);
                
            $srv_resp['sts']            = STS_OK;
            $srv_resp['messages'][0]    = 'Requested Operation Successfull';
        }
        
        return json_encode($srv_resp);
    }
    
    public function submitCcatForm()
    {
        $messages               = array();
        $rules                  = array();
        $srv_resp               = array();
        $upd_sts                = STS_NG;
        
        $srv_resp['sts']        = STS_NG;
        $srv_resp['messages']   = array();
        
        $company_name           = Input::get("company_name");
        $category_name          = Input::get("category_name");
        
        $messages = array(
		    'company_name.required'     => 'Please choose a Company.',
		    'category_name.required'    => 'Please choose a Category.',
		);

		$rules = array(
	        'company_name'  => 'required',
	        'category_name' => 'required',
	    );
        
        $validator = Validator::make(Input::all(), $rules, $messages);
        
        if ($validator->fails()) {
            $srv_resp['messages']	= $validator->messages()->all();
		}
        else {
            $data_add   = array(
                'tank_id'       => $company_name,
                'category_id'   => $category_name,
                'status'        => STS_OK,
                'created_date'  => date('Y-m-d H:i:s'),
                'updated_date'  => date('Y-m-d H:i:s')
            );
            
            $upd_sts    = DB::table('company_categories')->insert($data_add);
            
            if (STS_OK == $upd_sts) {
                $srv_resp['sts']    = STS_OK;
            }
            else {
                $srv_resp['messages'][0]    = 'An error occured during update. Please try again.';
            }
        }
        
        return json_encode($srv_resp);
    }
    
    public function deleteCcat($ccat_id)
    {
        DB::table('company_categories')->where('company_category_id', $ccat_id)->delete();
    }
    
    public function addVcat($vehicle_id)
    {
        $messages               = array();
        $rules                  = array();
        $srv_resp               = array();
        $upd_sts                = STS_NG;
        
        $srv_resp['sts']        = STS_NG;
        $srv_resp['messages']   = array();
        
        $vehicle_id             = $vehicle_id;
        $category_label         = Input::get("category_label");
        
        $messages = array(
		    'category_label.required'   => 'Label is required.',
		);

		$rules = array(
	        'category_label'            => 'required',
	    );
        
        $validator = Validator::make(Input::all(), $rules, $messages);
        
        if ($validator->fails()) {
            $srv_resp['messages']	= $validator->messages()->all();
		}
        else {
            $data_add   = array(
                'vehicle_id'       => $vehicle_id,
                'category_label'   => $category_label,
                'status'        => STS_OK,
                'created_date'  => date('Y-m-d H:i:s'),
                'updated_date'  => date('Y-m-d H:i:s')
            );
            
            $upd_sts    = DB::table('vehicle_categories')->insert($data_add);
            
            if (STS_OK == $upd_sts) {
                $srv_resp['sts']    = STS_OK;
            }
            else {
                $srv_resp['messages'][0]    = 'An error occured during update. Please try again.';
            }
        }
        
        return json_encode($srv_resp);
    }
    
    public function deleteVcat($vcat_id)
    {
        DB::table('vehicle_categories')->where('category_id', $vcat_id)->delete();
        DB::table('company_categories')->where('category_id', $vcat_id)->delete();
    }
}