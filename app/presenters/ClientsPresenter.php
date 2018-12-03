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
                $this->template->activePage = 'clients'; 
	}
        
        public function actionDefault(){
            //$this->user->login(3);
            $myRecordHandler = new \RecordHandler($this->database);
            /*
            $this->template->myChargeableProjects = $myRecordHandler->getMyChargeableProjects($this->user->getId());
            $this->template->activeMonth = 1;
            $this->template->activeYear = 2018;
            */
            $dateSessions = $this->getSession('Date'); 
            
            if(!isset($dateSessions->year))
                {$dateSessions->year = $myRecordHandler->getMaxChargedYear($this->user->getId());}
            if($dateSessions->year<2000 || $dateSessions->year>3000 || $dateSessions->year==""){ $dateSessions->year = date('o');}
            
                
            if(!isset($dateSessions->month))
                {$dateSessions->month = $myRecordHandler->getMaxChargedMonthOfTheYear($this->user->getId(), $dateSessions->year);}
            if($dateSessions->month<1 || $dateSessions->month>12 || $dateSessions->month==""){ $dateSessions->month = date('n');}
            
            $this->template->actualMonth = $dateSessions->month;  
            $this->template->actualYear = $dateSessions->year; 
            
        }       
        
        public function actionGetMyClients(){
            $myClientHandler = new \ClientHandler($this->database);
            
            $myObj = null;
            $myObj['result'] = 'OK';
            $myObj['code'] = '0';
            $myObj['data'] = $myClientHandler->getMyClients($this->user->getId());
            
            $myJSON = json_encode($myObj);
            $this->sendResponse(new JsonResponse($myJSON)); 
        }

        
        public function actionGetMyClient($clientId){
            $myClientHandler = new \ClientHandler($this->database);
            
            $myObj = null;
            $myObj['result'] = 'OK';
            $myObj['code'] = '0';
            $myObj['data'] = $myClientHandler->getMyClient($this->user->getId(), $clientId);
            
            $myJSON = json_encode($myObj);
            $this->sendResponse(new JsonResponse($myJSON)); 
        }
        
        public function actionGetMyClientProjects($clientId){
            $myClientHandler = new \ClientHandler($this->database);
            
            $myObj = null;
            $myObj['result'] = 'OK';
            $myObj['code'] = '0';
            //$myObj['data'] = $myClientHandler->getMyClientProjects($this->user->getId(), $clientId);
            $myObj['data'] = $myClientHandler->getMyClientProjectsWithParameters($this->user->getId(), $clientId);
            $myJSON = json_encode($myObj);
            $this->sendResponse(new JsonResponse($myJSON)); 
        }
        
        public function actionGetMyClientOrders($clientId){
            $myClientHandler = new \ClientHandler($this->database);
            $myObj = null;
            $myObj['result'] = 'OK';
            $myObj['code'] = '0';
            
            if($myClientHandler->isMyClient($this->user->getId(), $clientId)){
                $myObj['data'] = $myClientHandler->getMyClientOrdersWithParameters($clientId);
            }else{
                $myObj['result'] = 'NOT OK';
                $myObj['code'] = '404';
            }
            $myJSON = json_encode($myObj);
            $this->sendResponse(new JsonResponse($myJSON)); 
        }
        
        public function actionGetUsersNotLinkedToClientOrders($clientId){
            $myClientHandler = new \ClientHandler($this->database);
            $myObj = null;
            $myObj['result'] = 'OK';
            $myObj['code'] = '0';
            
            if($myClientHandler->isMyClient($this->user->getId(), $clientId)){
                $myObj['data'] = $myClientHandler->getUsersNotLinkedToClientOrders($clientId);
            }else{
                $myObj['result'] = 'NOT OK';
                $myObj['code'] = '404';
            }
            $myJSON = json_encode($myObj);
            $this->sendResponse(new JsonResponse($myJSON)); 
        }
        
        public function actionCreateNewClient(){
            $myClientHandler = new \ClientHandler($this->database);
            $myClientHandler->createNewClient($this->user->getId());
            
            $this->redirect("Clients:default");
        }
        
        public function actionCreateNewWorkOrder($clientId){
            $myClientHandler = new \ClientHandler($this->database);            
            $myObj = null;
            $myObj['result'] = 'OK';
            $myObj['code'] = '0';
            
            if($myClientHandler->isMyClient($this->user->getId(), $clientId)){
                $myClientHandler->createNewWorkOrder($clientId);
            }else{
                $myObj['result'] = 'NOT OK';
                $myObj['code'] = 'Nemáte právo na přidání';
            }
            $myJSON = json_encode($myObj);
            $this->sendResponse(new JsonResponse($myJSON)); 
        }
        
        public function actionCreateNewProject($clientId){
            $myClientHandler = new \ClientHandler($this->database);            
            $myObj = null;
            $myObj['result'] = 'OK';
            $myObj['code'] = '0';
            
            if($myClientHandler->isMyClient($this->user->getId(), $clientId)){
                $project = $myClientHandler->createNewProject($this->user->getId(), $clientId);
                $client = $myClientHandler->getClient($clientId);
                $myClientHandler->addParamToProject($project["id"], 'status','Status','active');
                $myClientHandler->addParamToProject($project["id"], 'contact','Fakturační kontakt',$client[0]["contact"]);
                $myClientHandler->addParamToProject($project["id"], 'contactRole','Role fakturačního kontaktu','stavby vedoucí');
                $myClientHandler->addParamToProject($project["id"], 'email','Fakturační email',$client[0]["email"]);
                $myObj['data'] = $myClientHandler->getProjectWithParameters($project["id"]);
            }else{
                $myObj['result'] = 'NOT OK';
                $myObj['code'] = 'Nemáte právo na přidání';
            }
            $myJSON = json_encode($myObj);
            $this->sendResponse(new JsonResponse($myJSON)); 
        }
        
        public function actionUpdateClientDetails($clientId, $param, $value){
            $myClientHandler = new \ClientHandler($this->database);
            
            $myObj = null;
            $myObj['result'] = 'OK';
            $myObj['code'] = '0';
            
            if($myClientHandler->isMyClient($this->user->getId(), $clientId)){
                $myClientHandler->updateClient($clientId, $param, $value);
            }else{
                $myObj['result'] = 'NOT OK';
                $myObj['code'] = 'Nemáte právo na úpravu';
            }
            
            $myJSON = json_encode($myObj);
            $this->sendResponse(new JsonResponse($myJSON)); 
        }
        
        public function actionUpdateWorkOrderDetails($workOrderId, $finder, $value){
            $myClientHandler = new \ClientHandler($this->database);
            
            $myObj = null;
            $myObj['result'] = 'OK';
            $myObj['code'] = '0';
            $client = $myClientHandler->getClientOfWorkOrder($workOrderId);
            $clientId = $client["client_id"];
                    
            if($myClientHandler->isMyClient($this->user->getId(), $clientId)){
                $myClientHandler->updateWorkOrder($workOrderId, $finder, $value);
            }else{
                $myObj['result'] = 'NOT OK';
                $myObj['code'] = 'Nemáte právo na úpravu';
            }
            
            $myJSON = json_encode($myObj);
            $this->sendResponse(new JsonResponse($myJSON)); 
            
        } 
        
        public function actionUpdateUworDetails($uworId, $finder, $value, $userId, $workOrderId){
            $myClientHandler = new \ClientHandler($this->database);
            
            $myObj = null;
            $myObj['result'] = 'OK';
            $myObj['code'] = '0';
            
            /*
            $wordOrder = $myClientHandler->getWorkOrderOfUwor($uworId);
            $workOrderId = $wordOrder["work_order_id"];
            */
            
            $client = $myClientHandler->getClientOfWorkOrder($workOrderId);
            $clientId = $client["client_id"];
            
                if($myClientHandler->isMyClient($this->user->getId(), $clientId)){
                    if($uworId==-1){
                        $myClientHandler->createUwor($userId, $workOrderId);
                    }else{
                        $myClientHandler->updateUwor($uworId, $finder, $value);
                    }
                }else{
                    $myObj['result'] = 'NOT OK';
                    $myObj['code'] = 'Nemáte právo na úpravu tohoto záznamu (error 9857)';
                }
            
            $myJSON = json_encode($myObj);
            $this->sendResponse(new JsonResponse($myJSON)); 
            
        } 
        
        public function actionUpdateProjectDetails($projectId, $finder, $value){
            $myClientHandler = new \ClientHandler($this->database);
            
            $myObj = null;
            $myObj['result'] = 'OK';
            $myObj['code'] = '0';
            
            if($myClientHandler->isMyProject($this->user->getId(), $projectId)){
                $myClientHandler->updateProject($projectId, $finder, $value);
            }else{
                $myObj['result'] = 'NOT OK';
                $myObj['code'] = 'Nemáte právo na úpravu';
            }
            
            $myJSON = json_encode($myObj);
            $this->sendResponse(new JsonResponse($myJSON)); 
        }
        
        public function actionUpdateProjectParam($projectId, $projectParamId, $value){
            $myClientHandler = new \ClientHandler($this->database);
            
            $myObj = null;
            $myObj['result'] = 'OK';
            $myObj['code'] = '0';
            
            if($myClientHandler->isMyProject($this->user->getId(), $projectId)){
                $myClientHandler->updateProjectParam($projectParamId, $value);
            }else{
                $myObj['result'] = 'NOT OK';
                $myObj['code'] = 'Nemáte právo na úpravu';
            }
            
            $myJSON = json_encode($myObj);
            $this->sendResponse(new JsonResponse($myJSON)); 
        }
        
        public function actionDeleteProject($projectId){
            $myClientHandler = new \ClientHandler($this->database);
            
            $myObj = null;
            $myObj['result'] = 'OK';
            $myObj['code'] = '0';
            
            if($myClientHandler->isMyProject($this->user->getId(), $projectId)){
                $myClientHandler->deleteProject($projectId);
            }else{
                $myObj['result'] = 'NOT OK';
                $myObj['code'] = 'Nemáte právo na úpravu';
            }
            
            $myJSON = json_encode($myObj);
            $this->sendResponse(new JsonResponse($myJSON)); 
        }
        
}
