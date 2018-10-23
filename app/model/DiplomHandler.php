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
    
    function createDiplom()
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

        $myRecordHandler = new RecordHandler($this->database);
        $records = $myRecordHandler->getRecordsByMonthYearProjectUser($this->month, $this->year, $this->projectId, $this->userId);

        /*
        $rankings = $myGame->getRankings();
        $totalRanking = $myGame->getTotalRanking();
        $rankingGroupsPositive = $myGame->getRankingGroupsPositive();
        $rankingGroupsNegative = $myGame->getRankingGroupsNegative();
        */
        
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
        
        
        $pdf->SetFont('dejavuserifcondensed', 'i', 72);
        $pdf->SetFillColor(255, 255, 255);
        
        $pdf->SetXY(100, 15);
        $txt = 'Výkaz práce';
        $pdf->MultiCell(155, 0, $txt, 0, 'L', 1, 0, '', '', true);
        
        
        $pdf->SetFont('dejavuserifcondensed', 'i', 24);
        $pdf->SetXY(90, 45);
        $txt = 'Něco něco';
        $pdf->MultiCell(155, 0, $txt, 0, 'L', 1, 0, '', '', true);
        
        
        
        
        $pdf->SetFont('dejavuserifcondensed', '', 24);
        $pdf->SetXY(13, 65);
        $txt = 'Jméno: ';
        $pdf->MultiCell(75, 0, $txt, 0, 'L', 1, 0, '', '', true);
        
        $pdf->SetXY(13, 87);
        $txt = 'Výsledné skóre: ';
        $pdf->MultiCell(75, 0, $txt, 0, 'L', 1, 0, '', '', true);
        
        $pdf->SetXY(13, 109);
        $txt = 'Výsledná známka: ';
        $pdf->MultiCell(75, 0, $txt, 0, 'L', 1, 0, '', '', true);
                
        $pdf->SetFont('dejavuserifcondensed', 'b', 12);
        $pdf->SetXY(13, 133);
        $txt = 'Povedlo se Vám: ';
        $pdf->MultiCell(75, 0, $txt, 0, 'L', 1, 0, '', '', true);
        
        $pdf->SetFont('dejavuserifcondensed', '', 12);
        $x=13;
        $y=138;
        $counter=0;
        if($records!=NULL){
        foreach($records as $record){
            if($counter>9){break;};
            $pdf->SetXY($x, $y);
            $txt = $record->day."/".$record->month."/".$record->year." - ".$record->hours."+".$record->hours_over."h";
            $pdf->MultiCell(115, 0, $txt, 0, 'L', 1, 0, '', '', true);
            $y+=5;
            $counter++;
            }
        }else{
                $pdf->SetXY($x, $y);
                $txt = "(nic k vykázání)";
                $pdf->MultiCell(115, 0, $txt, 0, 'L', 1, 0, '', '', true);
                $y+=5;
                $counter++;
        }
        
        $pdf->lastPage();
        $pdf->Output('diplom.pdf', 'I');
     }   
}
