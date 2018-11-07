<?php

namespace App\Presenters;
use Nette\Application\Responses\JsonResponse;

class SettingsPresenter extends BasePresenter
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
                $this->template->activePage = 'settings'; 
	}
        
        public function actionDefault(){
            
        }       
        
        
        public function actionAddEmployeeToCompany($userIntegrationId){
            $myClientHandler = new \ClientHandler($this->database);
            $userId = $myClientHandler->getUserIdByIntegrationId($userIntegrationId);
            if($userId == null){
                $myObj = null;
                $myObj['result'] = 'NOT OK';
                $myObj['code'] = '404';
            }else{
                /* Existuje už request? */
                if($myClientHandler->isRequestAlreadySent($this->user->getId(), $userId, 'addEmployeeToCompany')){
                    $myObj = null;
                    $myObj['result'] = 'NOT OK';
                    $myObj['code'] = '405';
                }else{
                    $newRequest = $myClientHandler->addRequest($this->user->getId(), $userId, 'addEmployeeToCompany');
                    $newRequestParametr = $myClientHandler->addRequestParam($newRequest["id"], "parametr", "jeho hodnota");
                    $myObj = null;
                    $myObj['result'] = 'OK';
                    $myObj['code'] = '0';   
                }
            }
            
            $myJSON = json_encode($myObj);
            $this->sendResponse(new JsonResponse($myJSON));
             
        }  
        
        
        public function actionLoadAllRequests(){
            $myClientHandler = new \ClientHandler($this->database);
            $myObj = null;
            $myObj['result'] = 'OK';
            $myObj['code'] = '0';
            $myObj['data'] = $myClientHandler->loadAllRequests($this->user->getId());
            
            $myJSON = json_encode($myObj);
            $this->sendResponse(new JsonResponse($myJSON));        
        }
        
        public function actionAcceptRequest($requestId){
            $myClientHandler = new \ClientHandler($this->database);
            $myObj = null;
            $myObj['result'] = 'OK';
            $myObj['code'] = '0';
            $myObj['data'] = null; //$myClientHandler->loadAllRequests($this->user->getId());
            
            $myJSON = json_encode($myObj);
            $this->sendResponse(new JsonResponse($myJSON));        
        }
}
