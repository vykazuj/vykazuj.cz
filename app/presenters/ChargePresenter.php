<?php

namespace App\Presenters;
use Nette\Application\Responses\JsonResponse;

class ChargePresenter extends BasePresenter
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
            $myRecordHandler = new \RecordHandler($this->database);
            $this->template->myChargeableProjects = $myRecordHandler->getMyChargeableProjects($this->user->getId());
            /*
            $this->template->activeMonth = 1;
            $this->template->activeYear = 2018;
             */
            $dateSessions = $this->getSession('Date'); 
            
            if(!isset($dateSessions->year))
                {$dateSessions->year = $myRecordHandler->getMaxChargedYear($this->user->getId());}
                
            if(!isset($dateSessions->month))
                {$dateSessions->month = $myRecordHandler->getMaxChargedMonthOfTheYear($this->user->getId(), $dateSessions->year);}
                
            $this->template->actualMonth = $dateSessions->month;  
            $this->template->actualYear = $dateSessions->year;    
            
        }
        
        public function actionGetChargeRecord($month, $year){
            $myRecordHandler = new \RecordHandler($this->database);
            
            $myObj = null;
            $myObj['result'] = 'OK';
            $myObj['code'] = '0';
            $myObj['data'] = $myRecordHandler->getRecordsByMonthYearUser($month, $year, $this->user->getId());
            
            $myJSON = json_encode($myObj);
            $this->sendResponse(new JsonResponse($myJSON));
             
        }
        
        public function actionChangeRecord($recordId, $projectId, $hours, $hoursOver){
            $myRecordHandler = new \RecordHandler($this->database);
            $row = $myRecordHandler->getRecordDetail($recordId);
            
            if($row["user_id"] != $this->user->getId()){
                $myObj2['result'] = 'NOK';
                $myObj2['code'] = 'Nemáte právo na tento záznam.';
                $myJSON = json_encode($myObj2);
                $this->sendResponse(new JsonResponse($myJSON));
            }
            
            try
                { 
                $myRecordHandler->updateRecord($recordId, $projectId, $hours, $hoursOver);
                $myObj2['result'] = 'OK';
                $myObj2['code'] = '0';
                $myObj2['data'] = 'OK';
                }
                catch (\Nette\Neon\Exception $e) 
                {
                    $myObj2['result'] = 'NOK';
                    $myObj2['code'] = $e->getMessage();
                    $myObj2['data'] = $e->getMessage();
                }  
                            
            $myJSON = json_encode($myObj2);
            $this->sendResponse(new JsonResponse($myJSON));

        }
        
        //public function actionCreateRecord($project_id, $hours, $hours_over, $day, $month, $year){
        public function actionCreateRecord($id, $projectId){
            $myRecordHandler = new \RecordHandler($this->database);
            $row = $myRecordHandler->getRecordDetail($id);
            
            if(!$myRecordHandler->isMyChargeableProject($projectId, $this->user->getId())){
                $myObj2['result'] = 'NOK';
                $myObj2['code'] = 'Nemáte právo na tento projekt.';
                $myJSON = json_encode($myObj2);
                $this->sendResponse(new JsonResponse($myJSON));
            }
            
            if($row["user_id"] != $this->user->getId()){
                $myObj2['result'] = 'NOK';
                $myObj2['code'] = 'Nemáte právo na vytvoření záznamu.';
                $myJSON = json_encode($myObj2);
                $this->sendResponse(new JsonResponse($myJSON));
            }
            
            $myObj = null;
            $myObj['project_id'] = $projectId;
            $myObj['hours'] = 0;
            $myObj['hours_over'] = 0;
            $myObj['day'] = $row["day"];
            $myObj['month'] = $row["month"];
            $myObj['year'] = $row["year"];
            $myObj['user_id'] = $this->user->getId();
            $myObj['status'] = 'created';
            $myObj['note'] = 'Vytvořeno jen tak';
            
            try
                    { 
                    $rowNum = $myRecordHandler->insertNewRecord($myObj);
                    $rowNum["projectName"] = $myRecordHandler->getProjectName($rowNum["id"]);
                    $myObj2['result'] = 'OK';
                    $myObj2['code'] = '0';
                    $myObj2['data'] = $rowNum;
                    }
                catch (\Nette\Neon\Exception $e) {
                    $myObj2['result'] = 'NOK';
                    $myObj2['code'] = $e->getMessage();
                    $myObj2['data'] = $e->getMessage();
                }  
                            
            $myJSON = json_encode($myObj2);
            $this->sendResponse(new JsonResponse($myJSON));
        }
        
        public function actionDeleteRecord($id){
            $myRecordHandler = new \RecordHandler($this->database);
            $recordDetails = $myRecordHandler->getRecordDetail($id);
            //Je requestor vlastníkem mazaného záznamu?
             if($recordDetails["user_id"] != $this->user->getId()){
                $myObj2['result'] = 'NOK';
                $myObj2['code'] = 'Nemáte právo smazán tohoto záznamu.';
                $myJSON = json_encode($myObj2);
                $this->sendResponse(new JsonResponse($myJSON));
                return false;
            }
            
            $myObj = null;
                try
                    { 
                    $myRecordHandler->deleteRecord($id);
                    $myObj['result'] = 'OK';
                    $myObj['code'] = '0';
                    }
                catch (\Nette\Neon\Exception $e) {
                    $myObj['result'] = 'NOK';
                    $myObj['code'] = $e->getMessage();
                }  

           
            $myJSON = json_encode($myObj);
            $this->sendResponse(new JsonResponse($myJSON));
             
        }
        
        public function actionGetMyChargeableProjects(){
            
            $myRecordHandler = new \RecordHandler($this->database);
            $myObj = null;
                try
                    { 
                    $row = $myRecordHandler->getMyChargeableProjects($this->user->getId());
                    $myObj['result'] = 'OK';
                    $myObj['code'] = '0';
                    $myObj['data'] = $row;
                    }
                catch (\Nette\Neon\Exception $e) {
                    $myObj['result'] = 'NOK';
                    $myObj['code'] = $e->getMessage();
                }  

           
            $myJSON = json_encode($myObj);
            $this->sendResponse(new JsonResponse($myJSON));
             
        }
        

	public function actionCreateTimesheet(){
            /*
            $myGame = $this->myGame;
            $myDiplom = new \DiplomHandler($this->database, $myGame);
            return $myDiplom->createDiplom();
            $this->redirect('Game:default');
            */
	}  
}
