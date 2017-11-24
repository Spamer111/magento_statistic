<?php
class My_Statistic_Block_Adminhtml_Visitors_View extends Mage_Adminhtml_Block_Template{

    protected function navigation($id,$collection){
        $arrayOfParameters = array();
        $count = count($collection->getCollection());
        $nextId = $id+1;
        $prevId = $id-1;
        if($nextId>$count){
            $nextId = 1;
        }
        if($prevId<1){
            $prevId = $count;
        }
        $arrayOfParameters['nextId'] = $nextId;
        $arrayOfParameters['prevId'] = $prevId;
        return $arrayOfParameters;
    }
    
    protected function dataVisitors($id,$collection){
        $statisticVisitsPage = $collection->getCollection()-> addFieldToFilter('visit_id',array('eq'=>$id))->getData();
        return $statisticVisitsPage;
    }

    protected function timeOnSite($dVis){
        $t ='';
        foreach($dVis as $key=>$value){
            $time1 = date_create($value['visit_time']);
            $time2 = date_create($dVis[$key+1]['visit_time']);
            if(!$dVis[$key+1]==null){
                $interval = date_diff($time2, $time1);
                $t =$t +strtotime($interval->format('%H:%I:%S'));
            }else{
                break;
            }
        }
        $timeSpentOnSite = date('H:i:s',$t);
        return $timeSpentOnSite;
    }

    protected  function timeOfVisitingSite($id,$collection){
        $statisticVisitsPageTime = array();
        foreach ($collection->getCollection()-> addFieldToFilter('visit_id',array('eq'=>$id)) as $v){
            $statisticVisitsPageTime[] = $v['visit_time'];
        }
        return $statisticVisitsPageTime;
    }

    protected  function visitorInformation($id){
        $arrayOfParameters = array();
        $arrayOfParameters['statisticVisits'] = Mage::getModel('statistic/visits')->load($id);
        $arrayOfParameters['statisticCountry'] = Mage::getModel('statistic/country')->load($id);
        $arrayOfParameters['statisticIpAddresses'] = Mage::getModel('statistic/ipaddresses')->load($id);
        return $arrayOfParameters;
    }

    public function __construct()
    {
        $id = $this->getRequest()->getParam('visit_id'); // получаем id записи из Get массива
        $collection = Mage::getModel('statistic/visits');
        $collectionPageVisits = Mage::getModel('statistic/pagevisit');
        parent::__construct();
        $this->navigation = $this->navigation($id,$collection);
        $this->dataVisitors = $this->dataVisitors($id,$collectionPageVisits);
        $this->timeOnSite = $this->timeOnSite($this->dataVisitors);
        $this->timeOfVisitingSite = $this->timeOfVisitingSite($id,$collectionPageVisits);
        $this->visitorInformation = $this->visitorInformation($id);
        $this->setTemplate('visitors/view.phtml'); //грузим темплейт (контент который выводим) из design/adminhtml/default/default/template/pages/test.phtml
    }

}