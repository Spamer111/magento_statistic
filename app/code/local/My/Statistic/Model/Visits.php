<?php
include(Mage::getBaseDir('media').'/my_statistic/SxGeo.php');
require_once(Mage::getBaseDir('media').'/my_statistic/BrowserDetection.php');
class My_Statistic_Model_Visits extends Mage_Core_Model_Abstract {

public function _construct(){
    parent::_construct();
    $this->_init('statistic/visits'); //Все в соотвествии с указанными в config.xml параметрами
}
    public function controller_action_predispatch ($observer)
    {
        date_default_timezone_set('Europe/Moscow'); // установим временную зону
        $curdate = mktime (0,0,0,date("m"), date("d"), date("Y"));
        $nextday = mktime (0,0,0,date("m"), date("d")+1, date("Y"));
        setcookie ("lastvisit", $curdate, $nextday);
        if(!isset($_COOKIE[lastvisit])) {
            $SxGeo = new SxGeo(Mage::getBaseDir('media').'/my_statistic/SxGeoCity.dat'); // поправить на правильный путь
            $browser = new BrowserDetection();
            $ip = $_SERVER['REMOTE_ADDR'];
            $CityFull = $SxGeo->getCityFull($ip);
            $CityShort = $SxGeo->get($ip);
            
            $visitor = Mage::getModel('statistic/visits');
            $visitor
                    -> setVisitDate(date('Y-m-d'))
                    -> setYear(date('Y'))
                    -> setMonth(date('m'))
                    -> setDay(date('d'))
                    -> setVisitTime(date('H:i:s'))
                    -> setHour(date('H'))
                    -> setMinute(date('i'))
                    -> setSysString($browser->getPlatform())
                    -> setSysFullname($browser->getPlatformVersion())
                    -> setBrowserName($browser->getName())
                    -> setBrowserVersion($browser->getVersion())
                    -> save();
            if (Mage::getSingleton('customer/session')->isLoggedIn()){
                $customer = Mage::getModel('customer/session');
                $visitor->setUserid($customer->getCustomer()->getID())->save();
            }
            setcookie ("visitor_id", $visitor->getID()); // записываем в куку ID посетителя

            
            $ipaddress = Mage::getModel('statistic/ipaddresses');
            $ipaddress->setIp($_SERVER[REMOTE_ADDR])
                    ->setVisitId($visitor->getID())
                    ->setIpServer($_SERVER[SERVER_ADDR])
                    ->setTld($CityFull[country][iso]) /*брать из $SxGeo*/
                    ->setUseragent($_SERVER[HTTP_USER_AGENT])
                    ->setSystem($browser->getPlatform()) /*парсить HTTP_USER_AGENT*/
                    ->setBrowser($browser->getName()) /*парсить HTTP_USER_AGENT*/
                    ->save();

            
            $country = Mage::getModel('statistic/country');
            $country -> setContinent($CityFull[country][continent])
                    -> setVisitId($visitor->getID())
                    -> setTld($CityFull[country][iso])
                    -> setTimezone($CityFull[country][timezone])
                    -> setLon($CityShort[city][lon])
                    -> setCountryNameRu($CityFull[country][name_ru])
                    -> setCountryNameEn($CityFull[country][name_en])
                    -> setCityNameRu($CityShort[city][name_ru])
                    -> setCityNameEn($CityShort[city][name_en])
                    -> setLat($CityShort[city][lat])
                    -> setLon($CityShort[city][lon])
                    -> save();
        }
    }
}
