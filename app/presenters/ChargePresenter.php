<?php

namespace App\Presenters;
use Nette\Application\Responses\JsonResponse;
use Nette\Mail\Message;
use Nette\Mail\SendmailMailer;
use Nette\Utils\Validators;
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
            
            $myClientHandler = new \ClientHandler($this->database);
            $companySessions = $this->getSession('Company');
            $companyId = $myClientHandler->getPrefCompany($this->user->getId());
            $companySessions->id = $companyId;
            
            $myRecordHandler = new \RecordHandler($this->database);
            $this->template->myChargeableProjects = $myRecordHandler->getMyChargeableProjects($this->user->getId(), $companyId);
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
            
            $this->template->actualMonth = $dateSessions->month;  
            $this->template->actualYear = $dateSessions->year;    
            
            $this->template->jobTitle =  $myClientHandler->getUserCompanyRelTranslated($this->user->getId(), $companyId);
            
        }
        
        public function actionGetChargeRecord($month, $year){
            $myRecordHandler = new \RecordHandler($this->database);
            
            $myClientHandler = new \ClientHandler($this->database);
            $companySessions = $this->getSession('Company');
            $companyId = $myClientHandler->getPrefCompany($this->user->getId());
            $companySessions->id = $companyId;
            
            $myObj = null;
            $myObj['result'] = 'OK';
            $myObj['code'] = '0';
            $myObj['data'] = $myRecordHandler->getRecordsByMonthYearUser($month, $year, $this->user->getId(), $companyId);
            
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
                            
            $reg_holidays = array('1.1','1.5','8.5','5.7','6.7','28.9','28.10','17.11','24.12','25.12','26.12');
            $easter_holidays =  array('19.4.2019','22.4.2019',
                                      '10.4.2020','13.4.2020',
                                      '2.4.2021','5.4.2021',
                                      '15.4.2022','18.4.2022',
                                      '7.4.2023','10.4.2023',
                                      '29.3.2024','1.4.2024',
                                      '18.4.2025','21.4.2025',
                                      '3.4.2026','6.4.2026',
                                      '26.4.2027','29.4.2027',
                                      '14.4.2028','17.4.2028',
                                      '30.3.2029','2.4.2029',
                                      '11.4.2030','14.4.2030',
                                      '26.4.2031','29.3.2031',
                                      '19.4.2032','19.4.2032',
                                      '15.4.2033','18.4.2033',
                                      '7.4.2034','10.4.2034',
                                      '23.3.2035','26.3.2035',
                                      '11.4.2036','14.4.2036',
                                      '3.4.2037','6.4.2037',
                                      '23.4.2038','26.4.2038');            
        
        
            for($i=1; $i<=$days; $i++){
                try
                        { 
                            $dayOfWeek = date("l", mktime(0,0,0,$month,$i,$year));
                            $reg_holiday = $i.".".$month;
                            $easter_holiday = $i.".".$month.".".$year;
                            if($dayOfWeek != "Sunday" && $dayOfWeek != "Saturday" && !in_array($reg_holiday, $reg_holidays) && !in_array($easter_holiday, $easter_holidays)){
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
            
            
            $myClientHandler = new \ClientHandler($this->database);
            $companySessions = $this->getSession('Company');
            $companyId = $myClientHandler->getPrefCompany($this->user->getId());
            $companySessions->id = $companyId;
            
            $myRecordHandler = new \RecordHandler($this->database);
            $myObj = null;
                try
                    { 
                    $row = $myRecordHandler->getMyChargeableProjects($this->user->getId(), $companyId);
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
            
            if($myClientHandler->isUserAllowedToChargeOnProject($this->user->getId(), $projectId))
            {
                $myTimeSheet = new \DiplomHandler($this->database);
                $myTimeSheet->setMonth($month);
                $myTimeSheet->setYear($year);
                $myTimeSheet->setUserId($this->user->getId());
                $myTimeSheet->setUser($this->user);
                $myTimeSheet->setProjectId($projectId);
                $timesheet = $myTimeSheet->createDiplom(true);            

                 //odeslání emailu

                $to = strtolower($myClientHandler->getProjectParam($projectId, 'email'));
                $userDetails = $myClientHandler->getUserDetails($this->user->getId());
                $from = $userDetails["first_name"]." ".$userDetails["last_name"]." <".strtolower($userDetails['email']).">";
                $fromRaw = strtolower($userDetails['email']);
                $subject = "Faktura za aktuální období";

                $message = "
                <html>
                <head>
                <title>Faktura za ".$month."/".$year."</title>
                </head>
                <body>
                <p>Dobrý den,</p>
                <p>v příloze Vám zasílám fakturu za aktuální období.</p>
                <br>
                <p>Díky, ".$userDetails["first_name"]."</p>
                </body>
                </html>
                ";

                // Always set content-type when sending HTML email
                $headers = "MIME-Version: 1.0" . "\r\n";
                $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

                if( Validators::isEmail($to) && Validators::isEmail($fromRaw))
                {
                // More headers
                $headers .= 'From: <info@vykazuj.cz>' . "\r\n";
                $mail = new Message;
                $mail->setFrom($from)
                    ->addTo($to)
                    ->addBcc($from)
                    ->setSubject($subject)
                    ->setHtmlBody($message)
                    ->addAttachment('Timesheet_'.$year.'_'.$month.'_'.$userDetails["last_name"].'.pdf',$timesheet, 'application/pdf');
                //mail($to,$subject,$message,$headers);
                
                $mailer = new SendmailMailer();
                try{
                    $mailer->send($mail);
                    $this->flashMessage('Email byl odeslán.','success');
                } catch (\Nette\Mail\SendException $e) {
                    $this->flashMessage($e->getMessage(),'danger');
                 }
                    
                }else{
                    if(!Validators::isEmail($to)){  
                        $this->flashMessage('Odeslání selhalo - Zadaný příjemce je neplatný: \''.$to.'\'','danger');
                    }
                    if(!Validators::isEmail($fromRaw)){  
                        $this->flashMessage('Odeslání selhalo - Zadaný odesílatel je neplatný: \''.$fromRaw.'\'','danger');
                    }
                }
            }
            $this->redirect('Charge:default');
	}  
}
