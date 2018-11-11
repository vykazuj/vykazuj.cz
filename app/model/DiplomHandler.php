<?php

require_once 'TCPDF/tcpdf.php';
require_once 'TCPDF/fonts/arialce.php';
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of DiplomHandler
 *
 * @author martin
 */
class DiplomHandler {
    //put your code here
    public $database;
    private $userId;
    private $projectId;
    private $month;
    private $year;
    private $user;

    function __construct(Nette\Database\Context $database)
    {
        $this->database = $database;
    }
    
    function setMonth($month){
        $this->month = $month;
    }
    
    function setYear($year){
        $this->year = $year;
    }
    
    function setProjectId($projectId){
        $this->projectId = $projectId;
    }
    
    function setUserId($userId){
        $this->userId = $userId;
    }
    
    function setUser($user){
        $this->user = $user;
    }
    
    function createDiplom($emailAttachment)
    {
         //require_once(dirname(__FILE__).'\TCPDF\examples\lang\ces.php');
         //require_once(dirname(__FILE__).'\TCPDF\examples\lang\eng.php');
        // set document information
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor($this->userId);
        $pdf->SetTitle('Výkaz práce za období XYZ');
        $pdf->SetSubject('Výkaz práce za období XYZ');
        $pdf->SetKeywords('');

        $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));

        // set default monospaced font
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
        $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        $pdf->SetHeaderMargin(0);
        $pdf->SetFooterMargin(0);
        $pdf->setPrintFooter(false);
        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
        // set font
        //$pdf->SetFont('arialce', '', 48, '', 'false');
        $pdf->setPrintHeader(false);
        $pdf->AddPage('P', 'A4');
        $bMargin = $pdf->getBreakMargin();
        $auto_page_break = $pdf->getAutoPageBreak();
        $pdf->SetAutoPageBreak(false, 0);
        /*
        $img_file = 'http://www.vsfg.cz/www/images/certifikat_template.png';
        $pdf->Image($img_file, 0, 0, 297, 210, '', '', '', false, 300, '', false, false, 0);
        */
        
        // restore auto-page-break status
        $pdf->SetAutoPageBreak($auto_page_break, $bMargin);
        $pdf->setPageMark();

        // nemaz, pouziju to na vykazy pro HOnzu / jednatele
        $myRecordHandler = new RecordHandler($this->database);
        $myClientHandler = new ClientHandler($this->database);
        $myUserHandler = new MyRegistrator($this->database);
        $records = $myRecordHandler->getRecordsByMonthYearProjectUser($this->month, $this->year, $this->projectId, $this->userId);
        $company = $myClientHandler->getMyCompany($this->userId);
        $project = $myRecordHandler->getProjectNameByProjectId($this->projectId);
        //$clientId = $myRecordHandler->getProjectDetail($this->projectId)->client_id;
        //$client =  $myClientHandler->getMyClient($this->userId, $clientId);
        $myDetails = $myUserHandler->getAttributes($this->userId);
        /*
        $rankings = $myGame->getRankings();
        $totalRanking = $myGame->getTotalRanking();
        $rankingGroupsPositive = $myGame->getRankingGroupsPositive();
        $rankingGroupsNegative = $myGame->getRankingGroupsNegative();
        */
        
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
        
        
        $pdf->SetFont('dejavuserifcondensed', 'b', 16);
        $pdf->SetFillColor(255, 255, 255);
        
        $pdf->SetLineWidth(0.2);
        $pdf->SetDrawColor(0,0,0);
        $pdf->Line(15, 10, 200, 10);

        $pdf->SetXY(15, 15);
        $txt = 'Submitted by';
        $pdf->MultiCell(155, 0, $txt, 0, 'L', 1, 0, '', '', true);
        
        $pdf->SetXY(100, 15);
        $txt = 'Authorised by';
        $pdf->MultiCell(155, 0, $txt, 0, 'L', 1, 0, '', '', true);

        $pdf->SetFont('dejavuserifcondensed', '', 10);
        $pdf->SetXY(15, 30);
        $txt = 'Name: '.$myDetails->first_name.' '.$myDetails->last_name;
        $pdf->MultiCell(155, 0, $txt, 0, 'L', 1, 0, '', '', true);
        
        $pdf->SetXY(15, 35);
        $txt = 'Role:';
        $pdf->MultiCell(155, 0, $txt, 0, 'L', 1, 0, '', '', true);
        
        $pdf->SetXY(100, 30);
        $txt = 'Name:';
        $pdf->MultiCell(155, 0, $txt, 0, 'L', 1, 0, '', '', true);
        
        $pdf->SetXY(100, 35);
        $txt = 'Role:';
        $pdf->MultiCell(155, 0, $txt, 0, 'L', 1, 0, '', '', true);
        
        $pdf->SetLineWidth(2.5);
        $pdf->SetDrawColor(210,210,210);
        $pdf->Line(15, 45, 200, 45);         
        
        $todayDate = date("j F Y"); 
        $pdf->SetXY(15, 50);
        $txt = 'Date: '.$todayDate;
        $pdf->MultiCell(155, 0, $txt, 0, 'L', 1, 0, '', '', true);

        $pdf->SetXY(100, 50);
        $txt = 'Date: '.$todayDate;
        $pdf->MultiCell(155, 0, $txt, 0, 'L', 1, 0, '', '', true);

        $pdf->SetXY(15, 60);
        $txt = 'Signed:';
        $pdf->MultiCell(155, 0, $txt, 0, 'L', 1, 0, '', '', true);

        $pdf->SetXY(100, 60);
        $txt = 'Signed:';
        $pdf->MultiCell(155, 0, $txt, 0, 'L', 1, 0, '', '', true);

        
        $pdf->SetLineWidth(2.5);
        $pdf->SetDrawColor(210,210,210);
        $pdf->Line(15, 75, 200, 75);  

          
        $pdf->SetLineWidth(0.2);
        $pdf->SetDrawColor(0,0,0);
        
        $pdf->SetFont('dejavuserifcondensed', '', 10);
        

        
        if($records!=NULL){
            $tbl = '<table cellspacing="0" cellpadding="1" border="1">
                    <tr>
                        <td width="160" align="center"><b>Date</b></td>
                        <td width="335" align="center"> <b>Project</b></td>
                        <td width="75" align="center"><b>Hours</b></td>
                        <td width="85" align="center"><b>Hours over</b></td>
                    </tr>
            </table>';  
      

        $pdf->SetXY(15, 80);
        $pdf->writeHTML($tbl, true, false, false, false, '');

        $x=15;
        $y=85;
        $counter=0;
        $sumHours=0;
        $sumOver=0;

        foreach($records as $record){
            
            if($counter>30){break;};
        
            $pdf->SetXY($x, $y);
            $tbl = '<table cellspacing="0" cellpadding="1" border="1">
                        <tr>
                            <td width="160" align="center">'.$record->day.'.'.$record->month.'.'.$record->year.'</td>
                            <td width="335" align="center">'.$project.'</td>
                            <td width="75" align="center">'.$record->hours.'</td>
                            <td width="85" align="center">'.$record->hours_over.'</td>
                        </tr>
                    </table>'; 
           
            $pdf->writeHTML($tbl, true, false, false, false, '');
        // $txt = $record->day."/".$record->month."/".$record->year." - ".$record->hours."+".$record->hours_over."h";
 
        //$pdf->MultiCell(115, 0, $txt, 0, 'L', 1, 0, '', '', true);
            $y+=5;
            $counter++;
            $sumHours+=$record->hours;
            $sumOver+=$record->hours_over;
            }
        }else{
                $pdf->SetXY($x, $y);
                $txt = "(nic k vykázání)";
                $pdf->MultiCell(115, 0, $txt, 0, 'L', 1, 0, '', '', true);
                $y+=5;
                $counter++;
        }
          
        $pdf->SetXY($x, $y);
        $tbl = '<table cellspacing="0" cellpadding="1" border="1">
                        <tr>
                            <td width="160" align="center"><b>Total</b></td>
                            <td width="335" align="center"></td>
                            <td width="75" align="center"><b>'.$sumHours.'</b></td>
                            <td width="85" align="center"><b>'.$sumOver.'</b></td>
                        </tr>
                    </table>'; 
           
        $pdf->writeHTML($tbl, true, false, false, false, '');

        $pdf->lastPage();
        if($emailAttachment){
            return $pdf->Output('diplom.pdf', 'S');
        }
        $pdf->Output('diplom.pdf', 'I');
     }   
}
        