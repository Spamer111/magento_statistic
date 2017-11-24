<?php
class My_Statistic_Adminhtml_VisitorsController extends Mage_Adminhtml_Controller_Action {

    public function indexAction()
    {
        $this->loadLayout();
        $this->_addContent($this->getLayout()->createBlock('statistic/adminhtml_visitors'));
        $this->renderLayout();
    }

    public function viewAction() 
    {
        $this->loadLayout();
        $this->_addContent($this->getLayout()->createBlock('statistic/adminhtml_visitors_view'));
        $this->renderLayout();
    }
}
