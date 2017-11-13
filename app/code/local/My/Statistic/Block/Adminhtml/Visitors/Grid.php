<?php

class My_Statistic_Block_Adminhtml_Visitors_Grid extends Mage_Adminhtml_Block_Widget_Grid
{

    public function __construct()
    {
        parent::__construct();
        $this->setId('cmsBlockGrid');
        $this->setDefaultSort('block_identifier');
        $this->setDefaultDir('ASC');
    }

    protected function _prepareCollection()
    {
        $collection = Mage::getModel('statistic/visits')->getCollection();
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    protected function _prepareColumns()
    {
        $this->addColumn('visit_id', array( // Название колонки
            'header'    => Mage::helper('statistic')->__('Id посетителя'),
            'align'     => 'left',
            'index'     => 'visit_id', // Поле в таблице
        ));

        $this->addColumn('userid', array( // Название колонки
            'header'    => Mage::helper('statistic')->__('User Id'),
            'align'     => 'left',
          //  'type'      => 'options',
          //  'options'   => array(0 => $this->__('not register')),
            'index'     => 'userid', // Поле в таблице
        ));

        $this->addColumn('visit_date', array(  // тут title это ID калонки грида
            'header'    => Mage::helper('statistic')->__('Дата посещения'), // загаловок калонки
            'align'     => 'left', // к какму краю прижимать текст
            'index'     => 'visit_date', // какому полю из бд соответствует колонка
            'type'      => 'date',
        ));

        $this->addColumn('	visit_time', array(  // тут title это ID калонки грида
            'header'    => Mage::helper('statistic')->__('Время посещения'), // загаловок калонки
            'align'     => 'left', // к какму краю прижимать текст
            'index'     => 'visit_time', // какому полю из бд соответствует колонка
            'type'      => 'time'
        ));

        $this->addColumn('sys_string', array(  // тут title это ID калонки грида
            'header'    => Mage::helper('statistic')->__('ОС'), // загаловок калонки
            'align'     => 'left', // к какму краю прижимать текст
            'index'     => 'sys_string', // какому полю из бд соответствует колонка
        ));

        $this->addColumn('sys_fullname', array(  // тут title это ID калонки грида
            'header'    => Mage::helper('statistic')->__('Полное название ОС'), // загаловок калонки
            'align'     => 'left', // к какму краю прижимать текст
            'index'     => 'sys_fullname', // какому полю из бд соответствует колонка
        ));

        $this->addColumn('browser_name', array(  // тут title это ID калонки грида
            'header'    => Mage::helper('statistic')->__('Название браузера'), // загаловок калонки
            'align'     => 'left', // к какму краю прижимать текст
            'index'     => 'browser_name', // какому полю из бд соответствует колонка
        ));

        $this->addColumn('browser_version', array(  // тут title это ID калонки грида
            'header'    => Mage::helper('statistic')->__('Версия браузера'), // загаловок калонки
            'align'     => 'left', // к какму краю прижимать текст
            'index'     => 'browser_version', // какому полю из бд соответствует колонка
        ));



        return parent::_prepareColumns();
    }

    //Куда лезем при клике по строчке грида
    public function getRowUrl($row)
    {
        return $this->getUrl('*/*/view', array('visit_id' => $row->getId()));
    }


}