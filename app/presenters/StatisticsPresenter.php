<?php

namespace App\Presenters;
use Nette\Application\Responses\JsonResponse;

class StatisticsPresenter extends BasePresenter
{  
        private $database;
        
        function __construct(\Nette\Database\Context $database)
        {
            $this->database = $database;
        }
        
	public function renderDefault()
	{
		$this->template->anyVariable = 'any value';
                $this->template->firstName = $this->user->getIdentity()->first_name;
                $this->template->lastName = $this->user->getIdentity()->last_name; 
                $this->template->userImage = $this->user->getIdentity()->image; 
                $this->template->activePage = 'statistics'; 
	}
        
        public function actionDefault(){
            //$this->user->login(3);
            $myRecordHandler = new \RecordHandler($this->database);
            $myClientHandler = new \ClientHandler($this->database);
                       
            /*
            $this->template->activeMonth = 1;
            $this->template->activeYear = 2018;
             */
            $dateSessions = $this->getSession('Date'); 
            $companySessions = $this->getSession('Company');
            $company = $myClientHandler->getMyCompany($this->user->getId());
            $companyId = $company["id"];
            if(isset($companySessions->id))
                { $companyId = $companySessions->id; }
            $companySessions->id = $companyId;
            
            $this->template->myChargeableProjects = $myRecordHandler->getMyChargeableProjects($this->user->getId(), $companyId);
            
            if(!isset($dateSessions->year))
                {$dateSessions->year = $myRecordHandler->getMaxChargedYear($this->user->getId());}
                
            if(!isset($dateSessions->month))
                {$dateSessions->month = $myRecordHandler->getMaxChargedMonthOfTheYear($this->user->getId(), $dateSessions->year);}
                
            $this->template->actualMonth = $dateSessions->month;  
            $this->template->actualYear = $dateSessions->year;   
            $this->template->jobTitle =  $myClientHandler->getUserCompanyRelTranslated($this->user->getId(), $companyId); 
            
        }
                
        public function actionGetEmployeeChargesOverview($month, $year){
            $myRecordHandler = new \RecordHandler($this->database);
            $companySessions = $this->getSession('Company');
            
            $myObj = null;
            $myObj['result'] = 'OK';
            $myObj['code'] = '0';
            $myObj['data'] = $myRecordHandler->getEmployeeChargesOverview($month, $year, $companySessions->id);
            
            $myJSON = json_encode($myObj);
            $this->sendResponse(new JsonResponse($myJSON));
             
        }
        
        
}
