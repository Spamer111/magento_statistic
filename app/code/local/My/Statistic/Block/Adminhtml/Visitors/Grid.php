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
        $this->addColumn('visit_id', array(
            'header'    => Mage::helper('statistic')->__('Id посетителя'),
            'align'     => 'left',
            'index'     => 'visit_id',
        ));

        $this->addColumn('userid', array(
            'header'    => Mage::helper('statistic')->__('User Id'),
            'align'     => 'left',
            'index'     => 'userid',
        ));

        $this->addColumn('visit_date', array(
            'header'    => Mage::helper('statistic')->__('Дата посещения'),
            'align'     => 'left',
            'index'     => 'visit_date',
            'type'      => 'date',
        ));

        $this->addColumn('	visit_time', array(
            'header'    => Mage::helper('statistic')->__('Время посещения'),
            'align'     => 'left',
            'index'     => 'visit_time',
            'type'      => 'time'
        ));

        $this->addColumn('sys_string', array(
            'header'    => Mage::helper('statistic')->__('ОС'),
            'align'     => 'left',
            'index'     => 'sys_string',
        ));

        $this->addColumn('sys_fullname', array(
            'header'    => Mage::helper('statistic')->__('Полное название ОС'),
            'align'     => 'left',
            'index'     => 'sys_fullname',
        ));

        $this->addColumn('browser_name', array(
            'header'    => Mage::helper('statistic')->__('Название браузера'),
            'align'     => 'left',
            'index'     => 'browser_name',
        ));

        $this->addColumn('browser_version', array(
            'header'    => Mage::helper('statistic')->__('Версия браузера'),
            'align'     => 'left',
            'index'     => 'browser_version',
        ));
        return parent::_prepareColumns();
    }
    
    public function getRowUrl($row)
    {
        return $this->getUrl('*/*/view', array('visit_id' => $row->getId()));
    }


}