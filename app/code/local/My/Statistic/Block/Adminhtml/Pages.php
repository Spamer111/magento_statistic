<?php
class My_Statistic_Block_Adminhtml_Pages extends Mage_Adminhtml_Block_Template{

    protected $data = '';
    protected $legend = '';
    protected $rgba = '';
    protected $countryDataMap = '';
    protected $dataYearGist = array();
    //protected $myYear = array(); // ?
    protected $monthLegend= array();
    protected $month=array(1 => 'Janu', 2 => 'Feb', 3 => 'Mar',
                           4=> 'Apr', 5 => 'May', 6 => 'Jun',
                           7 => 'Jul', 8 => 'Aug', 9 => 'Sep',
                           10 => 'Oct', 11 => 'Nov', 12 => 'Dec');
    protected $dayLegend = '';
    protected $dayData = array();

    
    protected function PieChart($collection,$chart){
        $collection_chart = $collection->getCollection();// Получаем колекцию
        $all_record = array();
        foreach ($collection_chart as $v){
            $all_record[]=$v[$chart]; // Получаем список всех записей
        }
        $unique_record=array_unique($all_record); // уникальные записи
        $quantity_unique_record = array(); // количество каждой уникальной записи в бд
        foreach ($unique_record as $v){
            $quantity_unique_record[] = count($collection->getCollection()-> addFieldToFilter($chart,array('eq'=>$v)));
        }
        $all_quantity = count($collection_chart); // считаем сколько всего записей -100%
        $percent_unique_record = array(); // % уникальных записей от общего числа
        foreach ($quantity_unique_record as $v) {
            $percent_unique_record[] = round($v/$all_quantity*100,2);
        }
        $this->data = implode(",",$percent_unique_record); // строка с % для заполнения диаграммы
        $this->legend = "'".implode("','",$unique_record)."'"; // строка с легендой
        foreach ($unique_record as $v) {
            $this->rgba = "'rgba(".rand(0,255).",".rand(0,255).",".rand(0,255).",0.5)',".$this->rgba;// строка с цветовой заливкой
        }
        if($chart == 'country_name_en'){
            $country_key = array();
            foreach ($unique_record as $value){
                $country_key[] = $value;
            }
            foreach ($country_key as $key => $value) {
                $this->countryDataMap = "['".$value."','".$value.": ".$quantity_unique_record[$key]."'],".$this->countryDataMap;
            }
        }
    }

    protected function HistogramByMonth($collection){
        $this->monthLegend = "'".implode("','",$this->month)."'"; // получаем строку из массива
        $my_year = array();// массив с годами
        if(array_key_exists('my_year',$_POST)){ // если существуе в $_POST значение my_year
            foreach ($_POST['my_year'] as $value) {
                $my_year[] =$value; // присваиваем массиву значения из $_POST
            }
        }else{
            $my_year[] =date('Y'); // если нет. то текущая дата.
        }
        $count_month = array(); // количество посещений сайта по месяцам

        foreach ($my_year as $k=>$value){
            foreach ($this->month as $key=>$val) {
                $count_month[$k][$key] = count($collection ->getCollection()-> addFieldToFilter('month',array('eq'=>$key))-> addFieldToFilter('year',array('eq'=>$value)));
            }
        }
        $rgba_year_background_color = '';
        $rgba_year_border_color = '';
        foreach ($this->month as $v) {
            $rgba_year_background_color = "'rgba(".rand(0,255).",".rand(0,255).",".rand(0,255).",0.5)',".$rgba_year_background_color;// строка с цветовой заливкой
            $rgba_year_border_color = "'rgba(".rand(0,255).",".rand(0,255).",".rand(0,255).",0.5)',".$rgba_year_border_color;// строка с цветовой заливкой
        }
        foreach ($my_year as $key => $value){
            $this->dataYearGist[] = "{
            label: 'Посещений в месяц',        
            data: [".implode(",",$count_month[$key])."],
            backgroundColor: [".$rgba_year_background_color."],
            borderColor: [".$rgba_year_border_color."],
            borderWidth: 1},";
        }
    }

    protected function ChartVisitsByDay($collection){
        $month_num = array();
        if(array_key_exists('my_month',$_POST)){
            foreach ($_POST['my_month'] as $value){
                $month_num[] = $value;
            }
        } else {
            $month_num[] = date('m');
        }

        $year_num = date('Y');
        $number = 31; // количество дней в месяце.
        $day = array();
        for ($i = 1; $i <= $number; $i++) {
            $day[] = $i;
        }
        $this->dayLegend = implode(",",$day); //-------
        $day_razb = array();
        foreach ($month_num as $key => $value) {
            foreach ($day as $k => $v) {
                $day_razb[$key][$k] = count($collection->getCollection()
                    ->addFieldToFilter('day', array('eq' => $v))
                    ->addFieldToFilter('year', array('eq' => $year_num))
                    ->addFieldToFilter('month', array('eq' => $value)));
            }
        }
        foreach ($day_razb as $key => $value){
            $this->dayData[] = "{
                label: '".$this->month[(int)$month_num[$key]]." - ".$year_num."',
                data:[".implode(",",$value)."],
                borderWidth: 1,
                borderColor: 'rgba(".rand(0,255).",".rand(0,255).",".rand(0,255).",1)',
                backgroundColor: 'transparent',
                pointBorderWidth: 2,
                pointRadius: 3},";
        }
    }

    public function __construct()
    {
        parent::__construct();
        $collection = Mage::getModel('statistic/visits');
        $collection_country = Mage::getModel('statistic/country');

        $this->PieChart($collection_country,'continent');
        $this->continentData = $this->data;
        $this->continentLegend = $this->legend;
        $this->continentRgba = $this->rgba;

        $this->PieChart($collection,'browser_name');
        $this->browserData = $this->data;
        $this->browserLegend = $this->legend;
        $this->browserRgba = $this->rgba;

        $this->PieChart($collection,'sys_fullname');
        $this->osData = $this->data;
        $this->osLegend = $this->legend;
        $this->osRgba = $this->rgba;

        $this->PieChart($collection_country,'country_name_en');
        $this->countryData = $this->data;
        $this->countryLegend = $this->legend;
        $this->countryRgba = $this->rgba;
        $this->countryDataMaps = $this->countryDataMap;

        $this->HistogramByMonth($collection);
        $this->ChartVisitsByDay($collection);
        
        $this->setTemplate('pages/pages.phtml'); //грузим темплейт (контент который выводим) из design/adminhtml/default/default/template/pages/test.phtml
    }

}