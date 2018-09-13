<?php

namespace App\Presenters;
use Nette\Application\Responses\JsonResponse;

class ClientsPresenter extends BasePresenter
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
	}
        
        public function actionDefault(){
            //$this->user->login(3);
            /*
            $myRecordHandler = new \RecordHandler($this->database);
            $this->template->myChargeableProjects = $myRecordHandler->getMyChargeableProjects($this->user->getId());
            $this->template->activeMonth = 1;
            $this->template->activeYear = 2018;
            $dateSessions = $this->getSession('Date'); 
            
            if(!isset($dateSessions->year))
                {$dateSessions->year = $myRecordHandler->getMaxChargedYear($this->user->getId());}
                
            if(!isset($dateSessions->month))
                {$dateSessions->month = $myRecordHandler->getMaxChargedMonthOfTheYear($this->user->getId(), $dateSessions->year);}
                
            $this->template->actualMonth = $dateSessions->month;  
            $this->template->actualYear = $dateSessions->year;    
             */
            
        }        
}
