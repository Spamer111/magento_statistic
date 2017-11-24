<?php

class My_Statistic_Block_Adminhtml_Visitors extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    public function __construct()
    {
        $this->_controller = 'adminhtml_visitors';
        $this->_blockGroup = 'statistic';
        $this->_headerText = Mage::helper('statistic')->__('Посетители сайта');
        parent::__construct();
        $this->_removeButton('add');
    }

}