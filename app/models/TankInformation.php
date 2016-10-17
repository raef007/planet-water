<?php

class TankInformation extends Eloquent {
    
    /*-------------------------------------------------------------------------
	|	Class Attributes
	|--------------------------------------------------------------------------
	|	table		--- Database Table
	|	primaryKey	--- Primary key of the Projects Table
	|	timestamps	--- Prevents created_at and deleted_at fields from being required
	|------------------------------------------------------------------------*/
	protected $table        = 'tank_informations';
    protected $primaryKey   = 'tank_info_id';
    public $timestamps      = false;
    
    /*-------------------------------------------------------------------------
	|	Get Tank Information by Tank ID
	|--------------------------------------------------------------------------
	|	@param [in] 	tank_id     --- Tank ID
	|	@param [out] 	NONE
	|	@return 		tank_data   --- Database Record
	|------------------------------------------------------------------------*/
    public function getTankInformationByTankId($tank_id)
    {
        $tank_data  = self::where('tank_id', $tank_id)
            ->where('status', STS_OK)
            ->first();
        
        /*	関数終亁E   */
        return $tank_data;
    }
    
}