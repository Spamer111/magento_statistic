<?php
include(Mage::getBaseDir('lib') . '/my_statistic/SxGeo.php');
require_once(Mage::getBaseDir('lib') . '/my_statistic/BrowserDetection.php');

class My_Statistic_Model_Visits extends Mage_Core_Model_Abstract
{

    public function _construct()
    {
        parent::_construct();
        $this->_init('statistic/visits');
    }

    public function controllerActionPredispatch($observer)
    {
        $value = Mage::getModel('core/date')->date('H:i:s');
        $period = Mage::getModel('core/cookie')->getLifetime()*12;
        Mage::getModel('core/cookie')->set("lastVisit", $value, $period);
        if(!array_key_exists('lastVisit',Mage::getModel('core/cookie')->get())){
            $SxGeo = new SxGeo(Mage::getBaseDir('lib') . '/my_statistic/SxGeoCity.dat');
            $browser = new BrowserDetection();
            $ip = $_SERVER['REMOTE_ADDR'];
            $CityFull = $SxGeo->getCityFull($ip);
            $CityShort = $SxGeo->get($ip);

            $visitor = Mage::getModel('statistic/visits');
            $visitor
                ->setVisitDate(date('Y-m-d'))
                ->setYear(date('Y'))
                ->setMonth(date('m'))
                ->setDay(date('d'))
                ->setVisitTime(date('H:i:s'))
                ->setHour(date('H'))
                ->setMinute(date('i'))
                ->setSysString($browser->getPlatform())
                ->setSysFullname($browser->getPlatformVersion())
                ->setBrowserName($browser->getName())
                ->setBrowserVersion($browser->getVersion())
                ->save();
            if (Mage::getSingleton('customer/session')->isLoggedIn()) {
                $customer = Mage::getModel('customer/session');
                $visitor->setUserid($customer->getCustomer()->getID())->save();
            }

            Mage::getModel('core/cookie')->set("visitId", $visitor->getID(), $period);
            Mage::register('visitorId', $visitor->getID());

            $ipaddress = Mage::getModel('statistic/ipaddresses');
            $ipaddress->setIp($_SERVER['REMOTE_ADDR'])
                ->setVisitId($visitor->getID())
                ->setIpServer($_SERVER['SERVER_ADDR'])
                ->setTld($CityFull['country']['iso'])
                ->setUseragent($_SERVER['HTTP_USER_AGENT'])
                ->setSystem($browser->getPlatform())
                ->setBrowser($browser->getName())
                ->save();

            $country = Mage::getModel('statistic/country');
            $country->setContinent($CityFull['country']['continent'])
                ->setVisitId($visitor->getID())
                ->setTld($CityFull['country']['iso'])
                ->setTimezone($CityFull['country']['timezone'])
                ->setLon($CityShort['city']['lon'])
                ->setCountryNameRu($CityFull['country']['name_ru'])
                ->setCountryNameEn($CityFull['country']['name_en'])
                ->setCityNameRu($CityShort['city']['name_ru'])
                ->setCityNameEn($CityShort['city']['name_en'])
                ->setLat($CityShort['city']['lat'])
                ->setLon($CityShort['city']['lon'])
                ->save();
        }
    }
}
