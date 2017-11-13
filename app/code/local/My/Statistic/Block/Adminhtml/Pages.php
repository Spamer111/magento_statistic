<?php
class My_Statistic_Block_Adminhtml_Pages extends Mage_Adminhtml_Block_Template{

    public function __construct()
    {
        parent::__construct();
        $this->setTemplate('pages/pages.phtml'); //грузим темплейт (контент который выводим) из design/adminhtml/default/default/template/pages/test.phtml
    }

}