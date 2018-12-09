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
                $this->template->jobTitle = $this->user->getIdentity()->job_title; 
                $this->template->activePage = 'clients'; 
	}
        
        public function actionDefault(){
            if(!$this->user->isLoggedIn() ){
                $this->redirect('Homepage:default');
            }
            
            //$this->user->login(3);
            $myRecordHandler = new \RecordHandler($this->database);
            
            $myClientHandler = new \ClientHandler($this->database);
            $companySessions = $this->getSession('Company');
            $companyId = $myClientHandler->getPrefCompany($this->user->getId());
            $companySessions->id = $companyId;
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

            $myRole = $myClientHandler->getUserCompanyRel($this->user->getId(),$companyId);
            if($myRole == 'accountant' || $myRole == 'owner'){
                $this->template->actualMonth = $dateSessions->month;  
                $this->template->actualYear = $dateSessions->year; 
                $this->template->myRole = $myRole;
                $this->template->displaySection = true;
            }
            
        }       
        
        public function actionGetMyClients(){
            
                        
            if(!$this->user->isLoggedIn() ){
                $myObj['result'] = 'NOT OK';
                $myObj['code'] = '100';
                $myObj['data'] = 'Nejste přihlášen.'; 
            }else{
            
            $myClientHandler = new \ClientHandler($this->database);
            $companySessions = $this->getSession('Company');
            $companyId = $myClientHandler->getPrefCompany($this->user->getId());
            $companySessions->id = $companyId;
            $myRole = $myClientHandler->getUserCompanyRel($this->user->getId(),$companyId);
            if($myRole == 'accountant' || $myRole == 'owner'){

                    $myObj = null;
                    $myObj['result'] = 'OK';
                    $myObj['code'] = '0';
                    //$myObj['data'] = $myClientHandler->getMyClients($this->user->getId());
                    $myObj['data'] = $myClientHandler->getMyClients($this->user->getId(), $companyId);

                }else{
                $myObj['result'] = 'NOT OK';
                $myObj['code'] = '101';
                $myObj['data'] = 'Nemáte právo na požadovaný zdroj';
                }
            }
            
            $myJSON = json_encode($myObj);
            $this->sendResponse(new JsonResponse($myJSON)); 
        }

        
        public function actionGetMyClient($clientId){
            $myClientHandler = new \ClientHandler($this->database);
            
            $myObj = null;
            $myObj['result'] = 'OK';
            $myObj['code'] = '0';
            
            if($this->user->isLoggedIn() ){
                $myObj['data'] = $myClientHandler->getMyClient($this->user->getId(), $clientId);   
            }else{
                $myObj['result'] = 'NOT OK';
                $myObj['code'] = '102';
                $myObj['data'] = 'Nejste přihlášen.'; 
                
            }
            
            $myJSON = json_encode($myObj);
            $this->sendResponse(new JsonResponse($myJSON)); 
        }
        
        public function actionGetMyClientProjects($clientId){
            $myClientHandler = new \ClientHandler($this->database);
            
            $myObj = null;
            if(!$this->user->isLoggedIn()){
                $myObj['result'] = 'NOT OK';
                $myObj['code'] = '103';
                $myObj['data'] = 'Nejste přihlášen.'; 
            }elseif(!$myClientHandler->isUserAllowedToManageClient($this->user->getId(), $clientId)){
                $myObj['result'] = 'NOT OK';
                $myObj['code'] = '104';
                $myObj['data'] = 'Nemáte právo na tohoto klienta'; 
                
            }else{
                $myObj['result'] = 'OK';
                $myObj['code'] = '0';
                $myObj['data'] = $myClientHandler->getMyClientProjectsWithParameters($this->user->getId(), $clientId); 
            }
                
            $myJSON = json_encode($myObj);
            $this->sendResponse(new JsonResponse($myJSON)); 
        }
        
        public function actionGetMyClientOrders($clientId){
            $myClientHandler = new \ClientHandler($this->database);
            $myObj = null;
            $myObj['result'] = 'OK';
            $myObj['code'] = '0';
            
            if(!$this->user->isLoggedIn()){
                $myObj['result'] = 'NOT OK';
                $myObj['code'] = '105';
                $myObj['data'] = 'Nejste přihlášen.'; 
            }elseif(!$myClientHandler->isUserAllowedToManageClient($this->user->getId(), $clientId)){
                $myObj['result'] = 'NOT OK';
                $myObj['code'] = '106';
                $myObj['data'] = 'Nemáte právo na tohoto klienta'; 
            }else{
                $myObj['data'] = $myClientHandler->getMyClientOrdersWithParameters($clientId);
            }
            $myJSON = json_encode($myObj);
            $this->sendResponse(new JsonResponse($myJSON)); 
        }
        
        public function actionGetUsersNotLinkedToClientOrders($clientId){
            $myClientHandler = new \ClientHandler($this->database);
            $myObj = null;
            $myObj['result'] = 'OK';
            $myObj['code'] = '0';
            if(!$this->user->isLoggedIn()){
                $myObj['result'] = 'NOT OK';
                $myObj['code'] = '107';
                $myObj['data'] = 'Nejste přihlášen.'; 
            }elseif(!$myClientHandler->isUserAllowedToManageClient($this->user->getId(), $clientId)){
                $myObj['result'] = 'NOT OK';
                $myObj['code'] = '108';
                $myObj['data'] = 'Nemáte právo na tohoto klienta'; 
            }else{
                $myObj['data'] = $myClientHandler->getUsersNotLinkedToClientOrders($clientId);
            }
            
            $myJSON = json_encode($myObj);
            $this->sendResponse(new JsonResponse($myJSON)); 
        }
        
        public function actionCreateNewClient(){
            $myClientHandler = new \ClientHandler($this->database);    
            $companySessions = $this->getSession('Company');
            $companyId = $myClientHandler->getPrefCompany($this->user->getId());
            $companySessions->id = $companyId;
            
            $myRole = $myClientHandler->getUserCompanyRel($this->user->getId(), $companyId);
            
            if($this->user->isLoggedIn() && ( $myRole == 'owner' || $myRole == 'accountant') ){
                $myClientHandler->createNewClient($this->user->getId());   
            }
            
            $this->redirect("Clients:default");
        }
        
        public function actionCreateNewWorkOrder($clientId){
            $myClientHandler = new \ClientHandler($this->database);            
            $myObj = null;
            $myObj['result'] = 'OK';
            $myObj['code'] = '0';
            
            if(!$this->user->isLoggedIn()){
                $myObj['result'] = 'NOT OK';
                $myObj['code'] = '109';
                $myObj['data'] = 'Nejste přihlášen.'; 
            }elseif(!$myClientHandler->isUserAllowedToManageClient($this->user->getId(), $clientId)){
                $myObj['result'] = 'NOT OK';
                $myObj['code'] = '110';
                $myObj['data'] = 'Nemáte právo na tohoto klienta'; 
            }else{
                $myClientHandler->createNewWorkOrder($clientId);
            }
            
            $myJSON = json_encode($myObj);
            $this->sendResponse(new JsonResponse($myJSON)); 
        }
        
        public function actionCreateNewProject($clientId){
            $myClientHandler = new \ClientHandler($this->database);            
            $myObj = null;
            $myObj['result'] = 'OK';
            $myObj['code'] = '0';
            
            if(!$this->user->isLoggedIn()){
                $myObj['result'] = 'NOT OK';
                $myObj['code'] = '111';
                $myObj['data'] = 'Nejste přihlášen.'; 
            }elseif(!$myClientHandler->isUserAllowedToManageClient($this->user->getId(), $clientId)){
                $myObj['result'] = 'NOT OK';
                $myObj['code'] = '112';
                $myObj['data'] = 'Nemáte právo na tohoto klienta'; 
            }else{
                $project = $myClientHandler->createNewProject($this->user->getId(), $clientId);
                $client = $myClientHandler->getClient($clientId);
                $myClientHandler->addParamToProject($project["id"], 'status','Status','active');
                $myClientHandler->addParamToProject($project["id"], 'contact','Fakturační kontakt',$client[0]["contact"]);
                $myClientHandler->addParamToProject($project["id"], 'contactRole','Role fakturačního kontaktu','stavby vedoucí');
                $myClientHandler->addParamToProject($project["id"], 'email','Fakturační email',$client[0]["email"]);
                $myObj['data'] = $myClientHandler->getProjectWithParameters($project["id"]);
            }
            
            $myJSON = json_encode($myObj);
            $this->sendResponse(new JsonResponse($myJSON)); 
        }
        
        public function actionUpdateClientDetails($clientId, $param, $value){
            $myClientHandler = new \ClientHandler($this->database);
            
            $myObj = null;
            $myObj['result'] = 'OK';
            $myObj['code'] = '0';
            
            if(!$this->user->isLoggedIn()){
                $myObj['result'] = 'NOT OK';
                $myObj['code'] = '113';
                $myObj['data'] = 'Nejste přihlášen.'; 
            }elseif(!$myClientHandler->isUserAllowedToManageClient($this->user->getId(), $clientId)){
                $myObj['result'] = 'NOT OK';
                $myObj['code'] = '114';
                $myObj['data'] = 'Nemáte právo na tohoto klienta'; 
            }else{
                $myClientHandler->updateClient($clientId, $param, $value);
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
            
             if(!$this->user->isLoggedIn()){
                $myObj['result'] = 'NOT OK';
                $myObj['code'] = '115';
                $myObj['data'] = 'Nejste přihlášen.'; 
            }elseif(!$myClientHandler->isUserAllowedToManageClient($this->user->getId(), $clientId)){
                $myObj['result'] = 'NOT OK';
                $myObj['code'] = '116';
                $myObj['data'] = 'Nemáte právo na tohoto klienta'; 
            }else{
                $myClientHandler->updateWorkOrder($workOrderId, $finder, $value);
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
            
            if(!$this->user->isLoggedIn()){
                $myObj['result'] = 'NOT OK';
                $myObj['code'] = '117';
                $myObj['data'] = 'Nejste přihlášen.'; 
            }elseif(!$myClientHandler->isUserAllowedToManageClient($this->user->getId(), $clientId)){
                $myObj['result'] = 'NOT OK';
                $myObj['code'] = '118';
                $myObj['data'] = 'Nemáte právo na tohoto klienta'; 
            }else{
                if($uworId==-1){
                    $myClientHandler->createUwor($userId, $workOrderId);
                }else{
                    $myClientHandler->updateUwor($uworId, $finder, $value);
                }
            }
            
            $myJSON = json_encode($myObj);
            $this->sendResponse(new JsonResponse($myJSON)); 
            
        } 
        
        public function actionUpdateProjectDetails($projectId, $finder, $value){
            $myClientHandler = new \ClientHandler($this->database);
            
            $myObj = null;
            $myObj['result'] = 'OK';
            $myObj['code'] = '0';
            $project = $myClientHandler->getProject($projectId);
            $clientId = $project[0]["client_id"];
            
            if(!$this->user->isLoggedIn()){
                $myObj['result'] = 'NOT OK';
                $myObj['code'] = '119';
                $myObj['data'] = 'Nejste přihlášen.'; 
            }elseif(!$myClientHandler->isUserAllowedToManageClient($this->user->getId(), $clientId)){
                $myObj['result'] = 'NOT OK';
                $myObj['code'] = '120';
                $myObj['data'] = 'Nemáte právo na tohoto klienta'; 
            }else{
                $myClientHandler->updateProject($projectId, $finder, $value);
            }
            
            $myJSON = json_encode($myObj);
            $this->sendResponse(new JsonResponse($myJSON)); 
        }
        
        public function actionUpdateProjectParam($projectId, $projectParamId, $value){
            $myClientHandler = new \ClientHandler($this->database);
            
            $myObj = null;
            $myObj['result'] = 'OK';
            $myObj['code'] = '0';
            $project = $myClientHandler->getProject($projectId);
            $clientId = $project[0]["client_id"];
            
            if(!$this->user->isLoggedIn()){
                $myObj['result'] = 'NOT OK';
                $myObj['code'] = '121';
                $myObj['data'] = 'Nejste přihlášen.'; 
            }elseif(!$myClientHandler->isUserAllowedToManageClient($this->user->getId(), $clientId)){
                $myObj['result'] = 'NOT OK';
                $myObj['code'] = '122';
                $myObj['data'] = 'Nemáte právo na tohoto klienta'; 
            }else{
                $myClientHandler->updateProjectParam($projectParamId, $value);
            }
            
            $myJSON = json_encode($myObj);
            $this->sendResponse(new JsonResponse($myJSON)); 
        }
        
        public function actionDeleteProject($projectId){
            $myClientHandler = new \ClientHandler($this->database);
            
            $myObj = null;
            $myObj['result'] = 'OK';
            $myObj['code'] = '0';
            $project = $myClientHandler->getProject($projectId);
            $clientId = $project[0]["client_id"];
            
            if(!$this->user->isLoggedIn()){
                $myObj['result'] = 'NOT OK';
                $myObj['code'] = '121';
                $myObj['data'] = 'Nejste přihlášen.'; 
            }elseif(!$myClientHandler->isUserAllowedToManageClient($this->user->getId(), $clientId)){
                $myObj['result'] = 'NOT OK';
                $myObj['code'] = '122';
                $myObj['data'] = 'Nemáte právo na tohoto klienta'; 
            }else{
                $myClientHandler->deleteProject($projectId);
            }
            
            $myJSON = json_encode($myObj);
            $this->sendResponse(new JsonResponse($myJSON)); 
        }
        
}
