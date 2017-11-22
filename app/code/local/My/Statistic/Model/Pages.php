<?php

class My_Statistic_Model_Pages extends Mage_Core_Model_Abstract
{

    public function _construct()
    {
        parent::_construct();
        $this->_init('statistic/pages'); //Все в соотвествии с указанными в config.xml параметрами
    }

    public function controller_front_send_response_after ($observer)
    {
        if(array_key_exists('visitor_id',Mage::getModel('core/cookie')->get())){
            $visitor = Mage::getModel('core/cookie')->get('visitor_id');
        } else{
            $visitor = Mage::getModel('statistic/visits')->getCollection()->setOrder('visit_id','DESC')->getFirstItem()->getId();
        }

        var_dump($visitor);

        $pages = Mage::getModel('statistic/pages');
        $page = $pages ->getCollection()->addFieldToFilter('page',array('eq'=>Mage::helper('core/url')->getCurrentUrl()))->setPageSize(1)->getFirstItem()->getId(); //ищем в бд id
        // которое соответствует текущей странице

        if($page==NULL and strpos(Mage::helper('core/url')->getCurrentUrl(), 'admin')===false) {
            $pages
                ->setPage(Mage::helper('core/url')->getCurrentUrl())
                ->save();
        }

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
