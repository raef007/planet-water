<?php

class Vehicle extends Eloquent {
    
    /*-------------------------------------------------------------------------
	|	Class Attributes
	|--------------------------------------------------------------------------
	|	table		--- Database Table
	|	primaryKey	--- Primary key of the Projects Table
	|	timestamps	--- Prevents created_at and deleted_at fields from being required
	|------------------------------------------------------------------------*/
	protected $table        = 'vehicles';
    protected $primaryKey   = 'vehicle_id';
    public $timestamps      = false;
    
    /*-------------------------------------------------------------------------
	|	Get Vehicle by ID
	|--------------------------------------------------------------------------
	|	@param [in] 	vehicle_id      --- Vehicle ID
	|	@param [out] 	NONE
	|	@return 		vehicle_data    --- Database Record
	|------------------------------------------------------------------------*/
    public function getVehicleById($vehicle_id)
    {
        $vehicle_data  = self::find($vehicle_id);
        
        /*	関数終亁E   */
        return $vehicle_data;
    }
    
}
