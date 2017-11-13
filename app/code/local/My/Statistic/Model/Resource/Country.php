<?php

class My_Statistic_Model_Resource_Country extends Mage_Core_Model_Resource_Db_Abstract {

    public function _construct()
    {
        $this->_init('statistic/country','id'); // PRIMARY KEY в таблице,
    }

}