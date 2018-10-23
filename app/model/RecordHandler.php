<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of RecordHandler
 *
 * @author martin
 */
class RecordHandler {
    public $database;
    
    function __construct(Nette\Database\Context $database)
    {
        $this->database = $database;
    }
    
    function deleteRecord($recordId){
        $this->database->query('DELETE from record where id = ?',$recordId); 
    }
    
    function getRecordsByMonthYearUser($month, $year, $userId){
        return $this->database->fetchAll('select r.*, p.name as projectName from record r, project p where p.id = r.project_id and r.user_id = ? and r.year = ? and r.month = ? ORDER by day ASC',$userId, $year, $month);
    }
    
    function getRecordsByMonthYearProjectUser($month, $year, $project, $userId){
        return $this->database->fetchAll('select r.*, p.name as projectName from record r, project p where p.id = r.project_id and r.user_id = ? and r.year = ? and r.month = ? and r.project_id = ? ORDER by day ASC',$userId, $year, $month, $project);
    }
    
    function getRecordDetail($recordId){
        return $this->database->fetch('select * from record where id = ?', $recordId);
    }
    
    function getRecordOwnerId($recordId){
        return $this->database->fetchField('select user_id from record where id = ?', $recordId);
    }
    
    function insertNewRecord($row){
        return $this->database->table('record')->insert($row)->toArray();
    }
    
    function updateRecord($recordId, $projectId, $hours, $hoursOver){
        return $this->database->query("UPDATE record set hours = ?, hours_over = ?, project_id = ? WHERE id = ?", $hours, $hoursOver, $projectId, $recordId);
    }
    
    function getProjectName($recordId){
        return $this->database->fetchField("select name from project p, record r where p.id = r.project_id and r.id = ?",$recordId);
    }
    
    function getMyChargeableProjects($userId){
        return $this->database->fetchAll('select p.* from project p, users_project_rel upr where p.id = upr.project_id and upr.user_id = ? and upr.rel = ?',$userId,'user');
    }
    
    function isMyChargeableProject($projectId, $userId){
        $rowCount = $this->database->query('select * from users_project_rel upr where upr.project_id = ?  and upr.user_id = ?  and upr.rel = ?',$projectId, $userId, 'user')->getRowCount();
        if($rowCount>0)
            {return true;}
            else
            {return false;}
    }
    
    function getMaxChargedYear($userId){
        return $this->database->fetchField("select max(year) from record r where r.user_id = ?",$userId);
    }
    
    function getMaxChargedMonthOfTheYear($userId, $year){
        return $this->database->fetchField("select max(month) from record r where r.user_id = ? and r.year = ? ",$userId, $year);
    }
    
}
