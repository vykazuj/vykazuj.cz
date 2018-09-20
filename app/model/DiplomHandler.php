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
    private $game;
    private $gameDetails;

    function __construct(Nette\Database\Context $database, \Game $game)
    {
        $this->database = $database;
        $this->game = $game;
        $this->gameDetails = $this->game->getGameDetails();
    }
    
    function createDiplom()
    {
        $myGame = $this->game;
         //require_once(dirname(__FILE__).'\TCPDF\examples\lang\ces.php');
         //require_once(dirname(__FILE__).'\TCPDF\examples\lang\eng.php');
        // set document information
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('vsfg.cz');
        $pdf->SetTitle('Diplom za dokončení hry');
        $pdf->SetSubject('Diplom za dokončení hry');
        $pdf->SetKeywords('Život podle Vás, VSFG, Diplom');

        $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));

        // set default monospaced font
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

        // set margins
        $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        $pdf->SetHeaderMargin(0);
        $pdf->SetFooterMargin(0);

        // remove default footer
        $pdf->setPrintFooter(false);

        // set auto page breaks
        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

        // set image scale factor
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

        // ---------------------------------------------------------

        // set font
        //$pdf->SetFont('arialce', '', 48, '', 'false');
        
        // --- example with background set on page ---

        // remove default header
        $pdf->setPrintHeader(false);

        // add a page
        $pdf->AddPage('L', 'A4');


        // -- set new background ---

        // get the current page break margin
        $bMargin = $pdf->getBreakMargin();
        // get current auto-page-break mode
        $auto_page_break = $pdf->getAutoPageBreak();
        // disable auto-page-break
        $pdf->SetAutoPageBreak(false, 0);
        // set bacground image
        $img_file = 'http://www.vsfg.cz/www/images/certifikat_template.png';
        $pdf->Image($img_file, 0, 0, 297, 210, '', '', '', false, 300, '', false, false, 0);
        // restore auto-page-break status
        $pdf->SetAutoPageBreak($auto_page_break, $bMargin);
        // set the starting point for the page content
        $pdf->setPageMark();


        /*
        $html = '<span style="color:white;text-align:center;font-weight:bold;font-size:80pt;">PAGE 3</span>';
        $pdf->writeHTML($html, true, false, true, false, '');
        */

        $rankings = $myGame->getRankings();
        $totalRanking = $myGame->getTotalRanking();
        $rankingGroupsPositive = $myGame->getRankingGroupsPositive();
        $rankingGroupsNegative = $myGame->getRankingGroupsNegative();
        
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
        $pdf->SetFont('dejavuserifcondensed', 'i', 72);
        $pdf->SetFillColor(255, 255, 255);
        
        $pdf->SetXY(100, 15);
        $txt = 'Certifikát';
        $pdf->MultiCell(155, 0, $txt, 0, 'L', 1, 0, '', '', true);
        
        
        $pdf->SetFont('dejavuserifcondensed', 'i', 24);
        $pdf->SetXY(90, 45);
        $txt = 'Za dokončení hry Život Podle Vás';
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
        
        
        
        
        $pdf->SetFont('dejavuserifcondensed', 'b', 24);
        $pdf->SetXY(100, 65);
        $txt = $myGame->getPlayerName();
        $pdf->MultiCell(75, 0, $txt, 0, 'L', 1, 0, '', '', true);
        
        $pdf->SetXY(100, 87);
        $txt = $totalRanking." bodů";
        $pdf->MultiCell(75, 0, $txt, 0, 'L', 1, 0, '', '', true);
        
        $pdf->SetXY(100, 109);
        if($totalRanking >= 300){$txt = "A - výborně";}
        if($totalRanking <= 299){$txt = "B - velmi dobře";}
        if($totalRanking <= 199){$txt = "C - dobře";}
        if($totalRanking <= 149){$txt = "D - nedostatečně";}
        $pdf->MultiCell(95, 0, $txt, 0, 'L', 1, 0, '', '', true);
        
        $pdf->SetFont('dejavuserifcondensed', 'i', 12);
        $pdf->SetXY(100, 119);
        $txt = "(A - 300+ bodů, B - 200-299 bodů, C - 150-199 bodů, D 149 a méně)";
        $pdf->MultiCell(135, 0, $txt, 0, 'L', 1, 0, '', '', true);
        
        
        
        $pdf->SetFont('dejavuserifcondensed', 'b', 12);
        $pdf->SetXY(13, 133);
        $txt = 'Povedlo se Vám: ';
        $pdf->MultiCell(75, 0, $txt, 0, 'L', 1, 0, '', '', true);
        
        $pdf->SetFont('dejavuserifcondensed', '', 12);
        $x=13;
        $y=138;
        $counter=0;
        if($rankingGroupsPositive!=NULL){
        foreach($rankingGroupsPositive as $rankingGroup){
            if($counter>9){break;};
            $pdf->SetXY($x, $y);
            $txt = $rankingGroup->source." (+".$rankingGroup->points." bodů)";
            $pdf->MultiCell(115, 0, $txt, 0, 'L', 1, 0, '', '', true);
            $y+=5;
            $counter++;
            }
        }else{
                $pdf->SetXY($x, $y);
                $txt = "(nemáte žádné pozitivní události)";
                $pdf->MultiCell(115, 0, $txt, 0, 'L', 1, 0, '', '', true);
                $y+=5;
                $counter++;
        }
        
        
        $pdf->SetFont('dejavuserifcondensed', 'b', 12);
        $pdf->SetXY(148, 133);
        $txt = 'Nepovedlo se Vám: ';
        $pdf->MultiCell(75, 0, $txt, 0, 'L', 1, 0, '', '', true);
        
        $pdf->SetFont('dejavuserifcondensed', '', 12);
        $x=148;
        $y=138;
        $counter=0;
        if($rankingGroupsNegative!=NULL){
            foreach($rankingGroupsNegative as $rankingGroup){
                if($counter>9){break;};
                $pdf->SetXY($x, $y);
                $txt = $rankingGroup->source." (".$rankingGroup->points." bodů)";
                $pdf->MultiCell(115, 0, $txt, 0, 'L', 1, 0, '', '', true);
                $y+=5;
                $counter++;
            }
        }else{
                $pdf->SetXY($x, $y);
                $txt = "(nemáte žádné negativní události)";
                $pdf->MultiCell(115, 0, $txt, 0, 'L', 1, 0, '', '', true);
                $y+=5;
                $counter++;
        }


        $pdf->lastPage();
        
        $pdf->Output('diplom.pdf', 'I');
     }   
}
