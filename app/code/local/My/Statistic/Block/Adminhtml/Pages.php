<?php
class My_Statistic_Block_Adminhtml_Pages extends Mage_Adminhtml_Block_Template{

    protected $_month=array(1 => 'Janu', 2 => 'Feb', 3 => 'Mar',
                           4=> 'Apr', 5 => 'May', 6 => 'Jun',
                           7 => 'Jul', 8 => 'Aug', 9 => 'Sep',
                           10 => 'Oct', 11 => 'Nov', 12 => 'Dec');
    protected $_continentChart;
    protected $_browserChart;
    protected $_osChart;
    protected $_countryChart;
    protected $_histogramChart;
    protected $_visitsByDayChart;
    protected $_period;
    protected $_generalStatistics;


    public function getContinentChartObject() {
        return new Varien_Object($this->_continentChart);
    }

    public function getBrowserChartObject() {
        return new Varien_Object($this->_browserChart);
    }

    public function getOsChartObject() {
        return new Varien_Object($this->_osChart);
    }

    public function getCountryChartObject() {
        return new Varien_Object($this->_countryChart);
    }

    public function getHistogramChartObject() {
        return new Varien_Object($this->_histogramChart);
    }

    public function getVisitsByDayChartObject() {
        return new Varien_Object($this->_visitsByDayChart);
    }

    public function getPeriodObject() {
        return new Varien_Object($this->_period);
    }

    public function getGeneralStatisticsObject() {
        return new Varien_Object($this->_generalStatistics);
    }
    
    public function setContinentChart($value){
        $this->_continentChart = $value;
    }

    public function setBrowserChart($value) {
        $this->_browserChart = $value;
    }

    public function setOsChart($value) {
        $this->_osChart = $value;
    }

    public function setCountryChart($value) {
        $this->_countryChart = $value;
    }

    public function setHistogramChart($value) {
        $this->_histogramChart = $value;
    }

    public function setVisitsByDayChart($value) {
        $this->_visitsByDayChart = $value;
    }

    public function setPeriod($value) {
        $this->_period = $value;
    }

    public function setGeneralStatistics($value) {
        $this->_generalStatistics = $value;
    }
    
    protected function pieChart($collection,$chart){
        $arrayOfParameters = array();
        $data = '';
        $legend = '';
        $rgba = '';
        $countryDataMap = '';
        $collectionChart = $collection->getCollection();
        $allRecord = array();
        foreach ($collectionChart as $v){
            $allRecord[]=$v[$chart];
        }
        $uniqueRecord=array_unique($allRecord);
        $quantityUniqueRecord = array();
        foreach ($uniqueRecord as $v){
            $quantityUniqueRecord[] = count($collection->getCollection()-> addFieldToFilter($chart,array('eq'=>$v)));
        }
        $allQuantity = count($collectionChart);
        $percentUniqueRecord = array();
        foreach ($quantityUniqueRecord as $v) {
            $percentUniqueRecord[] = round($v/$allQuantity*100,2);
        }
        $data = implode(",",$percentUniqueRecord);
        $arrayOfParameters['data'] = $data;
        $legend = "'".implode("','",$uniqueRecord)."'";
        $arrayOfParameters['legend'] = $legend;
        foreach ($uniqueRecord as $v) {
            $rgba = "'rgba(".rand(0,255).",".rand(0,255).",".rand(0,255).",0.5)',".$rgba;
        }
        $arrayOfParameters['rgba'] = $rgba;
        if($chart == 'country_name_en'){
            $countryKey = array();
            foreach ($uniqueRecord as $value){
                $countryKey[] = $value;
            }
            foreach ($countryKey as $key => $value) {
                $countryDataMap = "['".$value."','".$value.": ".$quantityUniqueRecord[$key]."'],".$countryDataMap;
            }
        }
        $arrayOfParameters['countrydatamap'] = $countryDataMap;
        return $arrayOfParameters;
    }

    protected function histogramByMonth($collection){
        $arrayOfParameters =array();
        $dataYearGist = array();
        $monthLegend = '';
        $monthLegend = "'".implode("','",$this->_month)."'";
        $arrayOfParameters['monthlegend'] = $monthLegend;
        $myYear = array();
        if(array_key_exists('myYear',$this->getRequest()->getPost())){
            foreach ($this->getRequest()->getPost('myYear') as $value) {
                $myYear[] =$value;
            }
        }else{
            $myYear[] =date('Y');
        }
        $countMonth = array();
        foreach ($myYear as $k=>$value){
            foreach ($this->_month as $key=>$val) {
                $countMonth[$k][$key] = count($collection ->getCollection()-> addFieldToFilter('month',array('eq'=>$key))-> addFieldToFilter('year',array('eq'=>$value)));
            }
        }
        $rgbaYearBackgroundColor = '';
        $rgbaYearBorderColor = '';
        foreach ($this->_month as $v) {
            $rgbaYearBackgroundColor = "'rgba(".rand(0,255).",".rand(0,255).",".rand(0,255).",0.5)',".$rgbaYearBackgroundColor;
            $rgbaYearBorderColor = "'rgba(".rand(0,255).",".rand(0,255).",".rand(0,255).",0.5)',".$rgbaYearBorderColor;
        }
        foreach ($myYear as $key => $value){
            $dataYearGist[] = "{
            label: 'Посещений в месяц',        
            data: [".implode(",",$countMonth[$key])."],
            backgroundColor: [".$rgbaYearBackgroundColor."],
            borderColor: [".$rgbaYearBorderColor."],
            borderWidth: 1},";
        }
        $arrayOfParameters['datayeargist'] = $dataYearGist;

        return $arrayOfParameters;
    }

    protected function chartVisitsByDay($collection){
        $arrayOfParameters =array();
        $month_num = array();
        $dayLegend = '';
        $dayData = array();
        if(array_key_exists('my_month',$this->getRequest()->getPost())){
            foreach ($this->getRequest()->getPost('my_month') as $value){
                $monthNum[] = $value;
            }
        } else {
            $monthNum[] = date('m');
        }

        $yearNum = date('Y');
        $number = 31;
        $day = array();
        for ($i = 1; $i <= $number; $i++) {
            $day[] = $i;
        }
        $dayLegend = implode(",",$day);
        $arrayOfParameters['daylegend'] = $dayLegend;
        $dayRazb = array();
        foreach ($monthNum as $key => $value) {
            foreach ($day as $k => $v) {
                $dayRazb[$key][$k] = count($collection->getCollection()
                    ->addFieldToFilter('day', array('eq' => $v))
                    ->addFieldToFilter('year', array('eq' => $yearNum))
                    ->addFieldToFilter('month', array('eq' => $value)));
            }
        }
        foreach ($dayRazb as $key => $value){
            $dayData[] = "{
                label: '".$this->_month[(int)$month_num[$key]]." - ".$yearNum."',
                data:[".implode(",",$value)."],
                borderWidth: 1,
                borderColor: 'rgba(".rand(0,255).",".rand(0,255).",".rand(0,255).",1)',
                backgroundColor: 'transparent',
                pointBorderWidth: 2,
                pointRadius: 3},";
        }
        $arrayOfParameters['daydata'] = $dayData;
        
        return $arrayOfParameters;
    }

    protected function visitsForPeriod($collection){
        if(array_key_exists('from_data',$this->getRequest()->getPost()) && array_key_exists('to_data',$this->getRequest()->getPost())){
            $fromData = $this->getRequest()->getPost('from_data');
            $toData = $this->getRequest()->getPost('to_data');
        }

        if(!empty($fromData)&& !empty($toData)) {
            $period = count($collection->getCollection()
                ->addFieldToFilter('visit_date', array('from' => $fromData, 'to' => $toData)));
        }else{
            $period = 'Нет данных';
        }
        return $period;
    }

    protected function statistics($collection){
        $arrayOfParameters =array();

        $year = count($collection -> getCollection()
            -> addFieldToFilter('year',array('eq'=>date('Y'))));
        $arrayOfParameters['year'] = $year;

        $month = count($collection -> getCollection()
            -> addFieldToFilter('month',array('eq'=>date('m')))
            -> addFieldToFilter('year',array('eq'=>date('Y'))));
        $arrayOfParameters['month'] = $month;

        $day = count($collection -> getCollection()
            -> addFieldToFilter('day',array('eq'=>date('d')))
            -> addFieldToFilter('month',array('eq'=>date('m')))
            -> addFieldToFilter('year',array('eq'=>date('Y'))));
        $arrayOfParameters['day'] = $day;

        $hour = count($collection -> getCollection()
            -> addFieldToFilter('hour',array('eq'=>date('H')))
            -> addFieldToFilter('day',array('eq'=>date('d')))
            -> addFieldToFilter('month',array('eq'=>date('m')))
            -> addFieldToFilter('year',array('eq'=>date('Y'))));
        $arrayOfParameters['hour'] = $hour;

        $minute = count($collection -> getCollection()
            -> addFieldToFilter('minute',array('eq'=>date('i')))
            -> addFieldToFilter('hour',array('eq'=>date('H')))
            -> addFieldToFilter('day',array('eq'=>date('d')))
            -> addFieldToFilter('month',array('eq'=>date('m')))
            -> addFieldToFilter('year',array('eq'=>date('Y'))));
        $arrayOfParameters['minute'] = $minute;

        $allVisits = count($collection->getCollection());
        $arrayOfParameters['all'] = $allVisits;

        return $arrayOfParameters;
    }

    public function __construct()
    {
        parent::__construct();
        $collection = Mage::getModel('statistic/visits');
        $collectionCountry = Mage::getModel('statistic/country');
        $this->setContinentChart($this->pieChart($collectionCountry,'continent'));
        $this->setBrowserChart($this->pieChart($collection,'browser_name'));
        $this->setOsChart($this->pieChart($collection,'browser_name'));
        $this->setCountryChart($this->pieChart($collectionCountry,'country_name_en'));
        $this->setHistogramChart($this->histogramByMonth($collection));
        $this->setVisitsByDayChart($this->chartVisitsByDay($collection));
        $this->setPeriod($this->visitsForPeriod($collection));
        $this->setGeneralStatistics($this->statistics($collection));
        $this->setTemplate('pages/pages.phtml');
    }

}