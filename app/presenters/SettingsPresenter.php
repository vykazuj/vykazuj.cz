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
            $myClientHandler = new \ClientHandler($this->database);
                
            if(!$this->user->isLoggedIn() ){
                $this->redirect('Homepage:default');
            }else{
                $companySessions = $this->getSession('Company');
                $company = $myClientHandler->getMyCompany($this->user->getId());
                $companyId = $company["id"];
                if(isset($companySessions->id))
                    { $companyId = $companySessions->id; }
                $companySessions->id = $companyId;
            }
                        
            $this->template->jobTitle =  $myClientHandler->getUserCompanyRelTranslated($this->user->getId(), $companyId);
            
        }       
        
        
        public function actionAddEmployeeToCompany($userIntegrationId, $companyId){
            if(!$this->user->isLoggedIn() ){
                $myObj = null;
                $myObj['result'] = 'NOT OK';
                $myObj['code'] = '400';
            }else{
                
                $companySessions = $this->getSession('Company');
                $companyId = $companySessions["id"];

                $myClientHandler = new \ClientHandler($this->database);
                $userId = $myClientHandler->getUserIdByIntegrationId($userIntegrationId);
                if($userId == null){
                    $myObj = null;
                    $myObj['result'] = 'NOT OK';
                    $myObj['code'] = '404';
                }else{
                    /* Existuje už request? */
                    if($myClientHandler->isAlreadyEmployee($userId, $companyId)){
                        $myObj = null;
                        $myObj['result'] = 'NOT OK';
                        $myObj['code'] = '403';
                    }elseif($myClientHandler->isRequestAlreadySent($this->user->getId(), $userId, 'addEmployeeToCompany')){
                        $myObj = null;
                        $myObj['result'] = 'NOT OK';
                        $myObj['code'] = '415';
                    }else{
                        $newRequest = $myClientHandler->addRequest($this->user->getId(), $userId, 'addEmployeeToCompany');
                        $myClientHandler->addRequestParam($newRequest["id"], "companyId", $companyId);
                        $myObj = null;
                        $myObj['result'] = 'OK';
                        $myObj['code'] = '0';   
                    }
                }

                $myJSON = json_encode($myObj);
                $this->sendResponse(new JsonResponse($myJSON));
             
            }
        }  
        
        public function actionGetMyChargableCompanies(){            
            
            if(!$this->user->isLoggedIn() ){
                $myObj = null;
                $myObj['result'] = 'NOT OK';
                $myObj['code'] = '406';
            }else{
                $myClientHandler = new \ClientHandler($this->database);
                $myObj['data']  = $myClientHandler->getMyCompanies($this->user->getId());
                $myObj['result'] = 'OK';
                $myObj['code'] = '0';   
            }
            
            $myJSON = json_encode($myObj);
            $this->sendResponse(new JsonResponse($myJSON));        
        }
        
        public function actionChangeActiveCompany($companyId){   
            $myObj = null;
            if(!$this->user->isLoggedIn() ){
                $myObj['result'] = 'NOT OK';
                $myObj['code'] = '407';
            }else{
                
                $myClientHandler = new \ClientHandler($this->database);
                $companySessions = $this->getSession('Company');
                if($myClientHandler->isMyCompany($this->user->getId(),$companyId)){
                    $myClientHandler->setPrefCompany($this->user->getId(), $companyId);
                    $companySessions->id = $companyId;
                    $myObj['result'] = 'OK';
                    $myObj['code'] = '0';

                }else{
                    $myObj = null;
                    $myObj['result'] = 'NOT OK';
                    $myObj['code'] = '416';
                }
            }
            
            
            $myJSON = json_encode($myObj);
            $this->sendResponse(new JsonResponse($myJSON));        
        }
        
        public function actionLoadAllRequests(){   
            
            $myObj = null;
            if(!$this->user->isLoggedIn() ){
                $myObj['result'] = 'NOT OK';
                $myObj['code'] = '408';
            }else{
                $myClientHandler = new \ClientHandler($this->database);
                $myObj['result'] = 'OK';
                $myObj['code'] = '0';
                $myObj['data'] = $myClientHandler->loadAllRequests($this->user->getId());
            }
            $myJSON = json_encode($myObj);
            $this->sendResponse(new JsonResponse($myJSON));        
        }
        
        public function actionAcceptRequest($requestId){
            
            $myObj = null;
            if(!$this->user->isLoggedIn() ){
                $myObj['result'] = 'NOT OK';
                $myObj['code'] = '409';
            }else{
                
                $myClientHandler = new \ClientHandler($this->database);
                $myObj['result'] = 'OK';
                $myObj['code'] = '0';
                $myObj['data'] = null;
                $numRow = $myClientHandler->acceptRequest($requestId, $this->user->getId());
                if($numRow < 1){
                    $myObj['result'] = 'NOT OK';
                    $myObj['code'] = '417';
                }else{
                    $companyId = $myClientHandler->getRequestParamValue($requestId, 'companyId');

                    $myClientHandler->createUserCompanyRel($this->user->getId(), $companyId, 'user');

                    $vacationProjectId = $myClientHandler->getCompanySpecialProjectId($companyId, 'vacation');
                    $myClientHandler->upsertUserProjectRel($this->user->getId(), $vacationProjectId, 0, 'user');

                    $sickProjectId = $myClientHandler->getCompanySpecialProjectId($companyId, 'sick');
                    $myClientHandler->upsertUserProjectRel($this->user->getId(), $sickProjectId, 0, 'user');
                }
            }
            $myJSON = json_encode($myObj);
            $this->sendResponse(new JsonResponse($myJSON));        
        }
        
        public function actionCancelRequest($requestId){
            
            $myObj = null;
            if(!$this->user->isLoggedIn() ){
                $myObj['result'] = 'NOT OK';
                $myObj['code'] = '410';
            }else{
                $myClientHandler = new \ClientHandler($this->database);
                $myObj['result'] = 'OK';
                $myObj['code'] = '0';
                $myObj['data'] = null;
                $numRow = $myClientHandler->cancelRequest($requestId, $this->user->getId());
                    if($numRow < 1){
                        $myObj['result'] = 'NOT OK';
                        $myObj['code'] = '418';
                    }
            }
            $myJSON = json_encode($myObj);
            $this->sendResponse(new JsonResponse($myJSON));        
        }
        
        public function actionDenyRequest($requestId){
            
            $myObj = null;
            if(!$this->user->isLoggedIn() ){
                $myObj['result'] = 'NOT OK';
                $myObj['code'] = '411';
            }else{
                
                $myClientHandler = new \ClientHandler($this->database);
                $myObj = null;
                $myObj['result'] = 'OK';
                $myObj['code'] = '0';
                $myObj['data'] = null;
                $numRow = $myClientHandler->denyRequest($requestId, $this->user->getId());
                if($numRow < 1){
                    $myObj['result'] = 'NOT OK';
                    $myObj['code'] = '419';
                }
            }
            $myJSON = json_encode($myObj);
            $this->sendResponse(new JsonResponse($myJSON));        
        }

        public function actionGetMySettings(){
            
            $myObj = null;
            if(!$this->user->isLoggedIn() ){
                $myObj['result'] = 'NOT OK';
                $myObj['code'] = '412';
            }else{
                
            
            $mySettingsHandler = new \SettingsHandler($this->database);
                try
                    { 
                    $row = $mySettingsHandler->getMe($this->user->getIdentity()->id);
                    $myObj['result'] = 'OK';
                    $myObj['code'] = '0';
                    $myObj['data'] = $row;
                    }
                catch (\Nette\Neon\Exception $e) {
                    $myObj['result'] = 'NOK';
                    $myObj['code'] = $e->getMessage();
                }  

            }
           
            $myJSON = json_encode($myObj);
            $this->sendResponse(new JsonResponse($myJSON));
             
            }

            public function actionUpdateMyDetails($name, $surname, $phone, $email, $job_title){
            
            $myObj = null;
            if(!$this->user->isLoggedIn() ){
                $myObj['result'] = 'NOT OK';
                $myObj['code'] = '413';
            }else{
                
                $mySettingsHandler = new \SettingsHandler($this->database);
                try
                    { 
                    $row = $mySettingsHandler->updateMyDetails($this->user->getIdentity()->id, $name, $surname, $phone, $email, $job_title);
                    $myObj['result'] = 'OK';
                    $myObj['code'] = '0';
                    $myObj['data'] = $row;
                    }
                catch (\Nette\Neon\Exception $e) {
                    $myObj['result'] = 'NOK';
                    $myObj['code'] = $e->getMessage();
                } 
            }
                
                $myJSON = json_encode($myObj);
                $this->sendResponse(new JsonResponse($myJSON)); 
            }
                        
            public function actionGetMyEmployees($company){
            
            $mySettingsHandler = new \SettingsHandler($this->database);
            $myObj = null;
                try
                    { 
                    $row = $mySettingsHandler->getMyEmployees($this->user->getIdentity()->id, $company);
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
            
            public function actionCreateUserCompanyRel($userId,$companyId,$role){
            
            $myClientHandler = new \ClientHandler($this->database);

                try
                    { 
                    $row = $myClientHandler->createUserCompanyRel($userId, $companyId, $role);
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
            public function actionUpdateMyPassword($password){
            
            $myObj = null;
            if(!$this->user->isLoggedIn() ){
                $myObj['result'] = 'NOT OK';
                $myObj['code'] = '414';
            }else{
                
                    $mySettingsHandler = new \SettingsHandler($this->database);

                    $myObj = null;
                    try
                        { 
                        $row = $mySettingsHandler->updateMyPassword($this->user->getIdentity()->id, $password);
                        $myObj['result'] = 'OK';
                        $myObj['code'] = '0';
                        $myObj['data'] = $row;
                        }
                    catch (\Nette\Neon\Exception $e) {
                        $myObj['result'] = 'NOK';
                        $myObj['code'] = $e->getMessage();
                    } 
                
            }
                $myJSON = json_encode($myObj);
                $this->sendResponse(new JsonResponse($myJSON)); 
            }
            
            public function actionUpdateUsersCosts($userId, $companyId, $internalCost, $defaultMDRate){
            
            $myObj = null;
            if(!$this->user->isLoggedIn() ){
                $myObj['result'] = 'NOT OK';
                $myObj['code'] = '414';
            }else{
                
                    $myClientHandler = new \ClientHandler($this->database);

                    $myObj = null;
                    try
                        { 
                        $row = $myClientHandler->updateUsersCosts($userId, $companyId, $internalCost, $defaultMDRate);
                        $myObj['result'] = 'OK';
                        $myObj['code'] = '0';
                        $myObj['data'] = $row;
                        }
                    catch (\Nette\Neon\Exception $e) {
                        $myObj['result'] = 'NOK';
                        $myObj['code'] = $e->getMessage();
                    } 
                
            }
                $myJSON = json_encode($myObj);
                $this->sendResponse(new JsonResponse($myJSON)); 
            }
            
        }
