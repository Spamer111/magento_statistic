<?php

class My_Statistic_Model_Resource_Visits extends Mage_Core_Model_Resource_Db_Abstract {

    public function _construct()
    {
        $this->_init('statistic/visits','visit_id'); //block_id это наш PRIMARY KEY в таблице, по умолчанию entity_id
    }

}