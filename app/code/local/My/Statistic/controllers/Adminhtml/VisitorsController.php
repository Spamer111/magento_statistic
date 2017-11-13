<?php
class My_Statistic_Adminhtml_VisitorsController extends Mage_Adminhtml_Controller_Action {

    public function indexAction()
    {
        $this->loadLayout();
        $this->_addContent($this->getLayout()->createBlock('statistic/adminhtml_visitors'));
        $this->renderLayout();
    }

    public function viewAction() // Контроллер просмотра данных о посетители
    {
        $id = $this->getRequest()->getParam('visit_id'); // получаем id записи из Get массива
        //
        //
        //Делаем ссылки для кнопок предыдущая-следующая
        //
        //
        $count = count(Mage::getModel('statistic/visits')->getCollection());
        $next_id = $id+1;
        $prev_id = $id-1;
        if($next_id>$count){
            $next_id = 1;
        }
        if($prev_id<1){
            $prev_id = $count;
        }
        Mage::register('next_id', $next_id);
        Mage::register('prev_id', $prev_id);
        //
        //
        // данные о посетители
        //
        //
        $statistic_visits_page = Mage::getModel('statistic/pagevisit')->getCollection()-> addFieldToFilter('visit_id',array('eq'=>$id))->getData();
        Mage::register('statistic_visits_page',$statistic_visits_page);
        //
        //
        // Примерное время проведенное на сайте
        //
        //
        $t ='';
        foreach($statistic_visits_page as $key=>$value){
            $time1 = date_create($value['visit_time']);
            $time2 = date_create($statistic_visits_page[$key+1]['visit_time']);
            if(!$statistic_visits_page[$key+1]==null){
                $interval = date_diff($time2, $time1);
                $t =$t +strtotime($interval->format('%H:%I:%S'));
            }else{
                break;
            }
        }
        $time_spent_on_site = date('H:i:s',$t);
        Mage::register('time_spent_on_site', $time_spent_on_site);
        //
        //
        //Время посещения сайта
        //
        //
        $statistic_visits_page_time = array();
        foreach (Mage::getModel('statistic/pagevisit')->getCollection()-> addFieldToFilter('visit_id',array('eq'=>$id)) as $v){
            $statistic_visits_page_time[] = $v['visit_time'];
        }
        Mage::register('statistic_visits_page_time', $statistic_visits_page_time);
        //
        //
        //Данные о посетителе
        //
        //
        Mage::register('statistic_visits', Mage::getModel('statistic/visits')->load($id)); //получаем по ID запись из БД и записываем ее в глобальный регистр в переменную statistic_visits
        // она будет видна в любой части кода через Mage::registry('statistic_visits')
        Mage::register('statistic_country', Mage::getModel('statistic/country')->load($id));
        Mage::register('statistic_ipaddresses', Mage::getModel('statistic/ipaddresses')->load($id));
        $blockObject = (array)Mage::getSingleton('adminhtml/session')->getBlockObject(true);
        if(count($blockObject)){
            Mage::register('statistic_visits')->setData($blockObject);
            Mage::register('statistic_country')->setData($blockObject);
            Mage::register('statistic_ipaddresses')->setData($blockObject);
            Mage::register('statistic_visits_page')->setData($blockObject);
        }
        $this->loadLayout();
        $this->_addContent($this->getLayout()->createBlock('statistic/adminhtml_visitors_view'));
        $this->renderLayout();
    }
}
