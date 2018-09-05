<?php

namespace App\Presenters;
use Nette\Application\Responses\JsonResponse;

class ChargePresenter extends BasePresenter
{  
        private $database;

        private $projectName = [
                    0=>'T-Mobile, migrace',
                    1=>'Amazon Logistics Prague, IT support',
                    2=>'ČSAD - noční linky',
                    3=>'Vykazuj, PHP a CSS skripty',
                    4=>'Rady ve snázi i nesnázi'
                ];
        
        function __construct(\Nette\Database\Context $database)
        {
            $this->database = $database;
        }
        
	public function renderDefault()
	{
		$this->template->anyVariable = 'any value';
                $this->template->actualMonth = 8;
                $this->template->actualYear = 2018;
	}
        
        public function actionDefault(){
            //$this->user->login(3);
        }
        
        public function actionGetChargeRecord($month, $year){
            $myRecordHandler = new \RecordHandler($this->database);
            
            $myObj = null;
            $myObj['result'] = 'OK';
            $myObj['code'] = '0';
            $month = 8;
            $year = 2018;
            $myObj['data'] = $myRecordHandler->getRecordsByMonthYearUser($month, $year, $this->user->getId());
            
            /*
            for($i=0;$i<31;$i++){
                $myObj['data'][$i]['id'] = round(rand(0, 5555 ));
                $myObj['data'][$i]['day'] = $i+1;
                $myObj['data'][$i]['project_id'] = round(rand(0, 4 ));
                $myObj['data'][$i]['hours'] = round(rand(4, 8));
                $myObj['data'][$i]['hours_over'] = round(rand(0, 2));
                $myObj['data'][$i]['month'] = $month;
                $myObj['data'][$i]['year'] = $year;
                $myObj['data'][$i]['user_id'] = 3;
                $myObj['data'][$i]['status'] = 'created';
                $myObj['data'][$i]['note'] = 'Vytvořeno skriptem';
                //$this->database->table('record')->insert($myObj['data'][$i]);
                $myObj['data'][$i]['project_name'] = $this->projectName[$myObj['data'][$i]['project_id']];
            }
            */

            $myJSON = json_encode($myObj);
            $this->sendResponse(new JsonResponse($myJSON));
             
        }
        
        //public function actionCreateRecord($project_id, $hours, $hours_over, $day, $month, $year){
        public function actionCreateRecord($id, $projectId){
            //TODO $project ID by mělo jít z nějakýho defaultu na screeně
            
            $projectId = 1;
            $myRecordHandler = new \RecordHandler($this->database);
            $row = $myRecordHandler->getRecordDetail($id);
            
            //TODO jestli má uživatel právo přidání daného projektu
            
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
            
            $myObj = null;
                try
                    { 
                    $row = $this->database->query('SELECT * from project');
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
}
