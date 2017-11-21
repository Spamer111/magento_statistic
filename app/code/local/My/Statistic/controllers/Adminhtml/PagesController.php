<?php

class My_Statistic_Adminhtml_PagesController extends Mage_Adminhtml_Controller_Action {

    public function indexAction()
    {
        // создаем свой блок для вывода
        $this->loadLayout();
        $this->_addContent($this->getLayout()->createBlock('statistic/adminhtml_pages'));// Statistic/Block(пропускаеться)/Adminhtml/Pages
        $this->renderLayout();
    }

    public function postAction()
    {
        if (!$this->_validateFormKey()) {
            $this->_redirectReferer();
            return;
        }
    }

}