<?php/////////////////////////////////////////////////////////////////////////////////// Licensed Source Code - Property of f-project.net//// © Copyright f-project.net 2014. All Rights Reserved./////////////////////////////////////////////////////////////////////////////////Yii::import('application.services.amf.*');Yii::import('application.services.vo.*');/** * This service is exposed to AMF clients to handle AppContext information * @author Bui Sy Nguyen <nguyenbs@gmail.com> */class AppContextService{    /**     * Load application context data for an user.     * @param int $loginUserId the id of user     * @return FAppContextData     */    public function loadAppContextData($loginUserId)	{        /** @var FAppContextData $contextData */        $contextData = FAppContextData::staticInstance()->with("loginUser")->findByAttributes(array('loginUserId'=>$loginUserId));        if(is_null($contextData))        {            $contextData = new FAppContextData;            /** @var FUser $loginUser */            $loginUser = FUser::staticInstance()->findByPk($loginUserId);            if(is_null($loginUser))                return null;            $contextData->loginUser = $loginUser;        }        else        {            $loginUser = $contextData->loginUser;        }        $this->updateAppContext($contextData);        $criteria=new CDbCriteria;        $criteria->addColumnCondition(array('userId'=>$loginUserId));        $criteria->addColumnCondition(array('userId'=>null), null, 'OR');        //Set user personal calendars        $loginUser->userPersonalCalendars = FPersonalCalendar::staticInstance()->findAll($criteria);        return $contextData;	}    /**     * @param FAppContextData $contextData     */    private function updateAppContext($contextData)    {        if(is_null($contextData->xmlData))        {            $contextData->xmlData = new SimpleXMLElement('<AppContextData/>');        }        $assignments = ProjectResourceAssignment::model()->findAllByUserId($contextData->loginUser->id);        $projectsXml = "<Projects>";        foreach($assignments as $assignment)        {            $node = "<Project>";            $node = $node."<Id>".$assignment->projectId."</Id>";            $node = $node."<Name>".$assignment->project->name."</Name>";            $node = $node."<Type>".$assignment->project->type."</Type>";            $node = $node."<Status>".$assignment->project->status."</Status>";            $node = $node."<AssignmentRole>".$assignment->role."</AssignmentRole>";            $node = $node."<AssignmentStart>".$assignment->startTime."</AssignmentStart>";            $node = $node."<AssignmentEnd>".$assignment->endTime."</AssignmentEnd>";            $node = $node."</Project>";            $projectsXml = $projectsXml.$node;        }        $projectsXml = $projectsXml."</Projects>";        unset($contextData->xmlData->Projects);        XMLHelper::simpleXmlInsert($contextData->xmlData, $projectsXml);        /** @var UserManager $userManager */        $userManager = Yii::app()->userManager;        unset($contextData->xmlData->ServiceConfig);        XMLHelper::simpleXmlInsert($contextData->xmlData, $userManager->getUserServiceConfig($contextData->loginUser->id));        $contextData->save();    }    /**     * Save application context data     * @param FAppContextData $data     * @return bool     */    public function saveAppContextData(FAppContextData $data)    {        return $data->save();    }}