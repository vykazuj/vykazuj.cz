<?php

namespace App\Presenters;
use Nette\Application\Responses\JsonResponse;
use Nette\Mail\Message;
use Nette\Mail\SmtpMailer;

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
                $this->template->activePage = 'charge'; 
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
            if($dateSessions->year<2000 || $dateSessions->year>3000 || $dateSessions->year==""){ $dateSessions->year = date('o');}
            
                
            if(!isset($dateSessions->month))
                {$dateSessions->month = $myRecordHandler->getMaxChargedMonthOfTheYear($this->user->getId(), $dateSessions->year);}
            if($dateSessions->month<1 || $dateSessions->month>12 || $dateSessions->month==""){ $dateSessions->month = date('n');}
            
            $myClientHandler = new \ClientHandler($this->database);
            $companySessions = $this->getSession('Company');
            $companyId = $myClientHandler->getPrefCompany($this->user->getId());
            $companySessions->id = $companyId;
        
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
        
        public function actionBulkRecords($projectId, $hours, $hours_over, $month, $year){
            $myRecordHandler = new \RecordHandler($this->database);
            if(!$myRecordHandler->isMyChargeableProject($projectId, $this->user->getId())){
                $myObj2['result'] = 'NOK';
                $myObj2['code'] = 'Nemáte právo na tento projekt.';
                $myJSON = json_encode($myObj2);
                $this->sendResponse(new JsonResponse($myJSON));
            }
            
            $myObj = null;
            $myObj['project_id'] = $projectId;
            $myObj['hours'] = $hours;
            $myObj['hours_over'] = 0;
            $myObj['month'] = $month;
            $myObj['year'] = $year;
            $myObj['user_id'] = $this->user->getId();
            $myObj['status'] = 'created';
            $myObj['note'] = 'Vytvořeno jen tak';
            $myObj2['result'] = 'OK';
            $myObj2['code'] = '0';
            $days = cal_days_in_month(CAL_GREGORIAN, $month, $year);
            for($i=1; $i<=$days; $i++){
                try
                        { 
                            $dayOfWeek = date("l", mktime(0,0,0,$month,$i,$year));
                            if($dayOfWeek != "Sunday" && $dayOfWeek != "Saturday"){
                                $myObj['day'] = $i;
                                $myRecordHandler->insertNewRecord($myObj); 
                            }
                        }
                    catch (\Nette\Neon\Exception $e) {
                        $myObj2['result'] = 'NOK';
                        $myObj2['code'] = $e->getMessage();
                        $myObj2['data'] = $e->getMessage();
                    }  
            }
                            
            $myJSON = json_encode($myObj2);
            $this->sendResponse(new JsonResponse($myJSON));
            
        }
        
        public function actionCreateRecordByDate($projectId, $hours, $hours_over, $day, $month, $year){
            $myRecordHandler = new \RecordHandler($this->database);
            
            if(!$myRecordHandler->isMyChargeableProject($projectId, $this->user->getId())){
                $myObj2['result'] = 'NOK';
                $myObj2['code'] = 'Nemáte právo na tento projekt.';
                $myJSON = json_encode($myObj2);
                $this->sendResponse(new JsonResponse($myJSON));
            }
            
            $myObj = null;
            $myObj['project_id'] = $projectId;
            $myObj['hours'] = 0;
            $myObj['hours_over'] = 0;
            $myObj['day'] = $day;
            $myObj['month'] = $month;
            $myObj['year'] = $year;
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
        

	public function actionCreateTimesheet($year, $month, $projectId, $withPrices){            

            $myTimeSheet = new \DiplomHandler($this->database);
            $myTimeSheet->setMonth($month);
            $myTimeSheet->setYear($year);
            $myTimeSheet->setUserId($this->user->getId());
            $myTimeSheet->setUser($this->user);
            $myTimeSheet->setProjectId($projectId);
            
            $myTimeSheet->createDiplom(false);
            $this->redirect('Charge:default');
            
            /*
            $myGame = $this->myGame;
            $myDiplom = new \DiplomHandler($this->database, $myGame);
            return $myDiplom->createDiplom();
            */
	}  
        

	public function actionSendTimesheet($year, $month, $projectId, $withPrices){            

            $myClientHandler = new \ClientHandler($this->database);
            
            if($myClientHandler->isMyProject($this->user->getId(), $projectId))
            {
                $myTimeSheet = new \DiplomHandler($this->database);
                $myTimeSheet->setMonth($month);
                $myTimeSheet->setYear($year);
                $myTimeSheet->setUserId($this->user->getId());
                $myTimeSheet->setUser($this->user);
                $myTimeSheet->setProjectId($projectId);
                $timesheet = $myTimeSheet->createDiplom(true);            

                 //odeslání emailu

                $to = $myClientHandler->getProjectParam($projectId, 'email');
                $subject = "Faktura za aktuální období";

                $message = "
                <html>
                <head>
                <title>Faktura za aktuální období</title>
                </head>
                <body>
                <p>Dobrý den,</p>
                <p>v příloze Vám zasílám fakturu za aktuální období</p>
                <br>
                <p>Díky, Martin</p>
                </body>
                </html>
                ";

                // Always set content-type when sending HTML email
                $headers = "MIME-Version: 1.0" . "\r\n";
                $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

                // More headers
                $headers .= 'From: <info@vykazuj.cz>' . "\r\n";
                $mail = new Message;
                $mail->setFrom('usata.veverka@vykazuj.cz')
                    ->addTo('martin.sivok@centrum.cz')
                    ->setSubject('Potvrzení objednávky')
                    ->setBody("Dobrý den,\nvaše objednávka byla přijata.")
                    ->addAttachment($timesheet, null, '');

                //mail($to,$subject,$message,$headers);
                $mailer = new SmtpMailer([
                    'host' => 'smtp-200863.m63.wedos.net'
                ]);

                $mailer->send($mail);

            }
            $this->redirect('Charge:default');
	}  
}
