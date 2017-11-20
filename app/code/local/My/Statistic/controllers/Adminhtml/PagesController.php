<?php

class My_Statistic_Adminhtml_PagesController extends Mage_Adminhtml_Controller_Action {
   // public $a;

    public function indexAction()
    {
        date_default_timezone_set('Europe/Moscow'); // установим временную зону
        $collection = Mage::getModel('statistic/visits');
        $collection_country = Mage::getModel('statistic/country');

        //
        //
        // Посещения за периуд
        //
        //
        if(array_key_exists('from_data',$_POST) && array_key_exists('to_data',$_POST)){
            $from_data = $_POST['from_data'];
            $to_data = $_POST['to_data'];
        }

        if(!empty($from_data)&& !empty($to_data)) {
            $period = count($collection->getCollection()
                ->addFieldToFilter('visit_date', array('from' => $from_data, 'to' => $to_data)));
        }else{
            $period = 'Нет данных';
        }

        $year = count($collection -> getCollection()
                                  -> addFieldToFilter('year',array('eq'=>date('Y'))));

        $month = count($collection -> getCollection()
                                   -> addFieldToFilter('month',array('eq'=>date('m')))
                                   -> addFieldToFilter('year',array('eq'=>date('Y'))));

        $day = count($collection -> getCollection()
                                 -> addFieldToFilter('day',array('eq'=>date('d')))
                                 -> addFieldToFilter('month',array('eq'=>date('m')))
                                 -> addFieldToFilter('year',array('eq'=>date('Y'))));

        $hour = count($collection -> getCollection()
                                  -> addFieldToFilter('hour',array('eq'=>date('H')))
                                  -> addFieldToFilter('day',array('eq'=>date('d')))
                                  -> addFieldToFilter('month',array('eq'=>date('m')))
                                  -> addFieldToFilter('year',array('eq'=>date('Y'))));

        $minute = count($collection -> getCollection()
                                    -> addFieldToFilter('minute',array('eq'=>date('i')))
                                    -> addFieldToFilter('hour',array('eq'=>date('H')))
                                    -> addFieldToFilter('day',array('eq'=>date('d')))
                                    -> addFieldToFilter('month',array('eq'=>date('m')))
                                    -> addFieldToFilter('year',array('eq'=>date('Y'))));

        $allvisits = count($collection->getCollection());
        Mage::register('allvisits', $allvisits);
        Mage::register('year', $year);
        Mage::register('month', $month);
        Mage::register('day', $day);
        Mage::register('hour', $hour);
        Mage::register('minute', $minute);
        Mage::register('period', $period);

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