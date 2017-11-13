<?php

class My_Statistic_Model_Resource_Pages extends Mage_Core_Model_Resource_Db_Abstract {

    public function _construct()
    {
        $this->_init('statistic/pages','page_id'); // PRIMARY KEY в таблице,
    }

}