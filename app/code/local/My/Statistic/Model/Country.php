<?php
class My_Statistic_Model_Country extends Mage_Core_Model_Abstract
{

    public function _construct()
    {
        parent::_construct();
        $this->_init('statistic/country'); 
    }

}