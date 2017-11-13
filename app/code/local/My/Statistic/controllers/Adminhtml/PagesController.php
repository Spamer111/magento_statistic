<?php

class My_Statistic_Adminhtml_PagesController extends Mage_Adminhtml_Controller_Action {

    public function indexAction()
    {

        date_default_timezone_set('Europe/Moscow'); // установим временную зону
        $collection = Mage::getModel('statistic/visits');
        $collection_country = Mage::getModel('statistic/country');
        //
        //
        // Данные для графика % континентов
        //
        //
        $collection_country_continent = $collection_country->getCollection();
        $continent_all = array();
        foreach ($collection_country_continent as $v){
            $continent_all[]=$v['continent']; // Получаем список всех континентов
        }
        $continent=array_unique($continent_all); // уникальные записи континентов
        $count_continent = array(); // количество записи каждого континента
        foreach ($continent as $v){
            $count_continent[] = count($collection_country -> getCollection()-> addFieldToFilter('continent',array('eq'=>$v)));
        }
        $all_zp = count($collection_country->getCollection()); // считаем сколько всего записей -100%
        $percent_con = array(); // % от общего количества каждого континента
        foreach ($count_continent as $v) {
            $percent_con[] = round($v/$all_zp*100,2);
        }
        $continent_data = implode(",",$percent_con); // строка с % для заполнения диаграммы
        $continent_legend = "'".implode("','",$continent)."'"; // строка с легендой
        $continent_rgba = '';
        foreach ($continent as $v) {
            $continent_rgba = "'rgba(".rand(0,255).",".rand(0,255).",".rand(0,255).",0.5)',".$continent_rgba;// строка с цветовой заливкой
        }
        //
        //
        // Данные для графика % стран
        //
        //
        $collection_country_country_name = $collection_country->getCollection();
        $country_name_all = array();
        foreach ($collection_country_country_name as $v){
            $country_name_all[] =  $v['country_name_en'];
        }
        $country=array_unique($country_name_all); // уникальные записи континентов
        $country_key = array();
        foreach ($country as $value){
            $country_key[] = $value;
        }
        $count_country = array();
        foreach ($country as $v){
            $count_country[] = count($collection_country -> getCollection()-> addFieldToFilter('country_name_en',array('eq'=>$v)));
        }
        $country_data_map ='';
        foreach ($country_key as $key => $value) {
            $country_data_map = "['".$value."','".$value.": ".$count_country[$key]."'],".$country_data_map;
        }
        $all_zp = count($collection_country->getCollection()); // считаем сколько всего записей -100%
        $percent_country = array();
        foreach ($count_country as $v) {
            $percent_country[] = round($v/$all_zp*100,2);
        }
        $country_data = implode(",",$percent_country); // строка с % для заполнения диаграммы
        $country_legend = "'".implode("','",$country)."'"; // строка с легендой
        $country_rgba = '';
        foreach ($country as $v) {
            $country_rgba = "'rgba(".rand(0,255).",".rand(0,255).",".rand(0,255).",0.5)',".$country_rgba;// строка с цветовой заливкой
        }
        //
        //
        // Данные для графика % браузеров
        //
        //
        $browser_collection = $collection->getCollection();// массив со всеми элементами таблицы
        $browser_all = array();
        foreach ($browser_collection as $v){
            $browser_all[] = $v['browser_name'];// делаем массив состоящий только из названия браузеров
        }
        $browser=array_unique($browser_all); // массив из представленных браузеров
        $count_browser = array(); // количество вхождений браузеров в бд
        foreach ($browser as $v){
            $count_browser[] = count($collection -> getCollection()-> addFieldToFilter('browser_name',array('eq'=>$v)));
        }
        $all = array_sum($count_browser); // считаем сколько всего записей -100%
        $percent = array(); // процент каждого браузера из общего числа
        foreach ($count_browser as $v) {
            $percent[] = round($v/$all*100,2);// процент каждого браузера из общего числа
        }
        $data = implode(",",$percent); // строка с % для заполнения диаграммы
        $rgba = '';
        $legend_browser = "'".implode("','",$browser)."'"; // строка с легендой браузеров
        foreach ($percent as $v) {
            $rgba = "'rgba(".rand(0,255).",".rand(0,255).",".rand(0,255).",0.5)',".$rgba;// строка с цветовой заливкой
        }
        //
        //
        // Данные для графика % ОС
        //
        //
        $os_collection = $collection->getCollection();// массив со всеми элементами таблицы
        $os_all = array();
        foreach ($os_collection as $v){
            $os_all[] = $v['sys_fullname'];
        }
        $os=array_unique($os_all); // массив из представленных браузеров
        $legend_os = "'".implode("','",$os)."'"; // строка с легендой ос
        $count_os = array(); // количество вхождений браузеров в бд
        foreach ($os as $v){
            $count_os[] = count($collection ->getCollection()-> addFieldToFilter('sys_fullname',array('eq'=>$v)));
        }
        $allOs = array_sum($count_os); // считаем сколько всего записей -100%
        $percent_os = array(); // процент каждого браузера из общего числа
        foreach ($count_os as $v) {
            $percent_os[] = round($v/$allOs*100,2);
        }
        $data_os = implode(",",$percent_os); // строка с % для заполнения диаграммы
        $rgba_os = '';
        foreach ($percent_os as $v) {
            $rgba_os = "'rgba(".rand(0,255).",".rand(0,255).",".rand(0,255).",0.5)',".$rgba_os;// строка с цветовой заливкой
        }

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
        Mage::register('data', $data);
        Mage::register('rgba', $rgba);
        Mage::register('data_os', $data_os);
        Mage::register('rgba_os', $rgba_os);
        Mage::register('legend_browser', $legend_browser);
        Mage::register('legend_os', $legend_os);
        Mage::register('month_legend', $month_legend);
        Mage::register('day_data', $day_data);
        Mage::register('day_legend', $day_legend);
        Mage::register('continent_data', $continent_data);
        Mage::register('continent_legend', $continent_legend);
        Mage::register('continent_rgba', $continent_rgba);
        Mage::register('country_data', $country_data);
        Mage::register('country_legend', $country_legend);
        Mage::register('country_rgba', $country_rgba);
        Mage::register('country_data_map', $country_data_map);

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