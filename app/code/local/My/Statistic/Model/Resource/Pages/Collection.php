<?php
class My_Statistic_Model_Resource_Pages_Collection extends Mage_Core_Model_Resource_Db_Collection_Abstract {

    public function _construct()
    {
        parent::_construct();
        $this->_init('statistic/pages');
    }
}