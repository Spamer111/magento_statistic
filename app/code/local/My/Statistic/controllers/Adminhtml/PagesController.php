<?php

class My_Statistic_Adminhtml_PagesController extends Mage_Adminhtml_Controller_Action {
   // public $a;

    public function indexAction()
    {
        date_default_timezone_set('Europe/Moscow'); // установим временную зону
        $collection = Mage::getModel('statistic/visits');
        $collection_country = Mage::getModel('statistic/country');
        //
        // Данные для графика %
        //
        $data = '';
        $legend = '';
        $rgba = '';
        $country_data_map = '';

        $pie_chart = function ($collection,$chart) use (&$data, &$legend, &$rgba, &$country_data_map){
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
            $data = implode(",",$percent_unique_record); // строка с % для заполнения диаграммы
            $legend = "'".implode("','",$unique_record)."'"; // строка с легендой
            $rgba = '';
            foreach ($unique_record as $v) {
                $rgba = "'rgba(".rand(0,255).",".rand(0,255).",".rand(0,255).",0.5)',".$rgba;// строка с цветовой заливкой
            }
            if($chart == 'country_name_en'){
                $country_key = array();
                foreach ($unique_record as $value){
                    $country_key[] = $value;
                }
                $country_data_map ='';
                foreach ($country_key as $key => $value) {
                    $country_data_map = "['".$value."','".$value.": ".$quantity_unique_record[$key]."'],".$country_data_map;
                }
            }
        };

        $pie_chart($collection_country,'continent');
        Mage::register('continent_data', $data);
        Mage::register('continent_legend', $legend);
        Mage::register('continent_rgba', $rgba);

        $pie_chart($collection,'browser_name');
        Mage::register('data', $data);
        Mage::register('legend_browser', $legend);
        Mage::register('rgba', $rgba);

        $pie_chart($collection,'sys_fullname');
        Mage::register('data_os', $data);
        Mage::register('legend_os', $legend);
        Mage::register('rgba_os', $rgba);

        $pie_chart($collection_country,'country_name_en');
        Mage::register('country_data', $data);
        Mage::register('country_legend', $legend);
        Mage::register('country_rgba', $rgba);
        Mage::register('country_data_map', $country_data_map);

        //
        //
        //Гистаграмма разбивка по месяцам
        //
        //

       /* $month_str=array(1 =>'январь',2 =>'февраль',3 =>'март',
                         4 =>'апрель',5 =>'май',6 =>'июнь',
                         7 =>'июль', 8 =>'август',9 =>'сентыбрь',
                        10 =>'октябрь',11 =>'ноябрь',12 =>'декабрь');*/
        $month_str=array(1 => 'Janu', 2 => 'Feb', 3 => 'Mar',
                         4=> 'Apr', 5 => 'May', 6 => 'Jun',
                         7 => 'Jul', 8 => 'Aug', 9 => 'Sep',
                        10 => 'Oct', 11 => 'Nov', 12 => 'Dec');
        $month_legend = "'".implode("','",$month_str)."'"; // получаем строку из массива
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
            foreach ($month_str as $key=>$val) {
                $count_month[$k][$key] = count($collection ->getCollection()-> addFieldToFilter('month',array('eq'=>$key))-> addFieldToFilter('year',array('eq'=>$value)));
            }
        }
        $rgba_year_background_color = '';
        $rgba_year_border_color = '';
        foreach ($month_str as $v) {
            $rgba_year_background_color = "'rgba(".rand(0,255).",".rand(0,255).",".rand(0,255).",0.5)',".$rgba_year_background_color;// строка с цветовой заливкой
            $rgba_year_border_color = "'rgba(".rand(0,255).",".rand(0,255).",".rand(0,255).",0.5)',".$rgba_year_border_color;// строка с цветовой заливкой
        }
        $data_year_gist = array();
        foreach ($my_year as $key => $value){
            $data_year_gist[] = "{
            label: 'Посещений в месяц',        
            data: [".implode(",",$count_month[$key])."],
            backgroundColor: [".$rgba_year_background_color."],
            borderColor: [".$rgba_year_border_color."],
            borderWidth: 1},";
        }
        Mage::register('my_year', $my_year);
        Mage::register('data_year_gist', $data_year_gist);
        //
        //
        //График посещений по дням (фильтр)
        //
        //
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
        $day_legend = implode(",",$day);
        $day_razb = array();

        foreach ($month_num as $key => $value) {
            foreach ($day as $k => $v) {
                $day_razb[$key][$k] = count($collection->getCollection()
                    ->addFieldToFilter('day', array('eq' => $v))
                    ->addFieldToFilter('year', array('eq' => $year_num))
                    ->addFieldToFilter('month', array('eq' => $value)));
                }
        }

        $month_label=array(1 => 'January', 2 => 'February', 3 => 'March',
                4=> 'April', 5 => 'May', 6 => 'June',
                7 => 'July', 8 => 'August', 9 => 'September',
                10 => 'October', 11 => 'November', 12 => 'December');
        $day_data = array();
        foreach ($day_razb as $key => $value){
            $day_data[] = "{
                label: '".$month_label[(int)$month_num[$key]]." - ".$year_num."',
                data:[".implode(",",$value)."],
                borderWidth: 1,
                borderColor: 'rgba(".rand(0,255).",".rand(0,255).",".rand(0,255).",1)',
                backgroundColor: 'transparent',
                pointBorderWidth: 2,
                pointRadius: 3},";
        }
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
        Mage::register('month_str', $month_str);
        Mage::register('month_label', $month_label);
        Mage::register('month_num', $month_num);
        Mage::register('month_legend', $month_legend);
        Mage::register('day_data', $day_data);
        Mage::register('day_legend', $day_legend);

        // создаем свой блок для вывода
        $this->loadLayout();
        $this->_addContent($this->getLayout()->createBlock('statistic/adminhtml_pages'));// Statistic/Block(пропускаеться)/Adminhtml/Pages
        $this->renderLayout();
    }
// Хз надо или нет.
    public function postAction()
    {
        if (!$this->_validateFormKey()) {
        // returns to the product item page
            $this->_redirectReferer();
            return;
        }
    }

}