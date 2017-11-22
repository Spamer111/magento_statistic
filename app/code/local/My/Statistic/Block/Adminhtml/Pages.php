<?php
class My_Statistic_Block_Adminhtml_Pages extends Mage_Adminhtml_Block_Template{

    protected $month=array(1 => 'Janu', 2 => 'Feb', 3 => 'Mar',
                           4=> 'Apr', 5 => 'May', 6 => 'Jun',
                           7 => 'Jul', 8 => 'Aug', 9 => 'Sep',
                           10 => 'Oct', 11 => 'Nov', 12 => 'Dec');
    
    protected function pieChart($collection,$chart){
        $arrayOfParameters = array();
        $data = '';
        $legend = '';
        $rgba = '';
        $countryDataMap = '';
        $collectionChart = $collection->getCollection();// Получаем колекцию
        $allRecord = array();
        foreach ($collectionChart as $v){
            $allRecord[]=$v[$chart]; // Получаем список всех записей
        }
        $uniqueRecord=array_unique($allRecord); // уникальные записи
        $quantityUniqueRecord = array(); // количество каждой уникальной записи в бд
        foreach ($uniqueRecord as $v){
            $quantityUniqueRecord[] = count($collection->getCollection()-> addFieldToFilter($chart,array('eq'=>$v)));
        }
        $allQuantity = count($collectionChart); // считаем сколько всего записей -100%
        $percentUniqueRecord = array(); // % уникальных записей от общего числа
        foreach ($quantityUniqueRecord as $v) {
            $percentUniqueRecord[] = round($v/$allQuantity*100,2);
        }
        $data = implode(",",$percentUniqueRecord); // строка с % для заполнения диаграммы
        $arrayOfParameters['data'] = $data;
        $legend = "'".implode("','",$uniqueRecord)."'"; // строка с легендой
        $arrayOfParameters['legend'] = $legend;
        foreach ($uniqueRecord as $v) {
            $rgba = "'rgba(".rand(0,255).",".rand(0,255).",".rand(0,255).",0.5)',".$rgba;// строка с цветовой заливкой
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
        $arrayOfParameters['countryDataMap'] = $countryDataMap;
        return $arrayOfParameters;
    }

    protected function histogramByMonth($collection){
        $arrayOfParameters =array();
        $dataYearGist = array();
        $monthLegend = '';
        $monthLegend = "'".implode("','",$this->month)."'"; // получаем строку из массива
        $arrayOfParameters['monthLegend'] = $monthLegend;
        $myYear = array();// массив с годами
        if(array_key_exists('myYear',$this->getRequest()->getPost())){ // если существуе в $_POST значение my_year
            foreach ($_POST['myYear'] as $value) {
                $myYear[] =$value; // присваиваем массиву значения из $_POST
            }
        }else{
            $myYear[] =date('Y'); // если нет. то текущая дата.
        }
        $countMonth = array(); // количество посещений сайта по месяцам
        foreach ($myYear as $k=>$value){
            foreach ($this->month as $key=>$val) {
                $countMonth[$k][$key] = count($collection ->getCollection()-> addFieldToFilter('month',array('eq'=>$key))-> addFieldToFilter('year',array('eq'=>$value)));
            }
        }
        $rgbaYearBackgroundColor = '';
        $rgbaYearBorderColor = '';
        foreach ($this->month as $v) {
            $rgbaYearBackgroundColor = "'rgba(".rand(0,255).",".rand(0,255).",".rand(0,255).",0.5)',".$rgbaYearBackgroundColor;// строка с цветовой заливкой
            $rgbaYearBorderColor = "'rgba(".rand(0,255).",".rand(0,255).",".rand(0,255).",0.5)',".$rgbaYearBorderColor;// строка с цветовой заливкой
        }
        foreach ($myYear as $key => $value){
            $dataYearGist[] = "{
            label: 'Посещений в месяц',        
            data: [".implode(",",$countMonth[$key])."],
            backgroundColor: [".$rgbaYearBackgroundColor."],
            borderColor: [".$rgbaYearBorderColor."],
            borderWidth: 1},";
        }
        $arrayOfParameters['dataYearGist'] = $dataYearGist;

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
        $number = 31; // количество дней в месяце.
        $day = array();
        for ($i = 1; $i <= $number; $i++) {
            $day[] = $i;
        }
        $dayLegend = implode(",",$day); //-------
        $arrayOfParameters['dayLegend'] = $dayLegend;
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
                label: '".$this->month[(int)$month_num[$key]]." - ".$yearNum."',
                data:[".implode(",",$value)."],
                borderWidth: 1,
                borderColor: 'rgba(".rand(0,255).",".rand(0,255).",".rand(0,255).",1)',
                backgroundColor: 'transparent',
                pointBorderWidth: 2,
                pointRadius: 3},";
        }
        $arrayOfParameters['dayData'] = $dayData;
        
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
        $arrayOfParameters['allVisits'] = $allVisits;

        return $arrayOfParameters;
    }

    public function __construct()
    {
        parent::__construct();
        $collection = Mage::getModel('statistic/visits');
        $collectionCountry = Mage::getModel('statistic/country');
        $this->continentChart = $this->pieChart($collectionCountry,'continent');
        $this->browserChart = $this->pieChart($collection,'browser_name');
        $this->osChart = $this->pieChart($collection,'browser_name');
        $this->countryChart = $this->pieChart($collectionCountry,'country_name_en');
        $this->histogramChart = $this->histogramByMonth($collection);
        $this->visitsByDayChart = $this->chartVisitsByDay($collection);
        $this->period = $this->visitsForPeriod($collection);
        $this->generalStatistics = $this->statistics($collection);
        
        $this->setTemplate('pages/pages.phtml'); //грузим темплейт (контент который выводим) из design/adminhtml/default/default/template/pages/test.phtml
    }

}