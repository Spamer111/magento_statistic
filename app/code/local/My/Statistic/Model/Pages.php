<?php

class My_Statistic_Model_Pages extends Mage_Core_Model_Abstract
{

    public function _construct()
    {
        parent::_construct();
        $this->_init('statistic/pages');
    }

    public function controllerFrontSendResponseAfter ($observer)
    {
        if(array_key_exists('visitId',Mage::getModel('core/cookie')->get())){
            $visitor = Mage::getModel('core/cookie')->get('visitId');
        } else{
            $visitor = Mage::registry('visitorId');
        }
        $pages = Mage::getModel('statistic/pages');
        $page = $pages ->getCollection()->addFieldToFilter('page',array('eq'=>Mage::helper('core/url')->getCurrentUrl()))->setPageSize(1)->getFirstItem()->getId(); 
        $pageVisit = Mage::getModel('statistic/pagevisit');
        if($page==NULL) {
            $pageVisit  -> setPageId($pages->getId())->save();
        } else {
            $pageVisit  -> setPageId($page)->save();
        }
        $pageVisit  -> setPage(Mage::helper('core/url')->getCurrentUrl())
            -> setVisitId($visitor)
            -> setReferrer($_SERVER[HTTP_REFERER])
            -> setVisitTime(date('H:i:s'))
            -> setVisitDate(date('Y-m-d'))
            -> save();
    }
}
