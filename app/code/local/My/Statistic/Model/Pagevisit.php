<?php
class My_Statistic_Model_Pagevisit extends Mage_Core_Model_Abstract
{

    public function _construct()
    {
        parent::_construct();
        $this->_init('statistic/pagevisit'); //Все в соотвествии с указанными в config.xml параметрами
    }

}