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
        return $this->database->fetchAll('select r.*, p.name as project_name from record r, project p where p.id = r.project_id and r.user_id = ? and r.year = ? and r.month = ? ORDER by day ASC',$userId, $year, $month);
    }
    
    function getRecordDetail($recordId){
        return $this->database->fetch('select * from record where id = ?', $recordId);
    }
    
    function getRecordOwnerId($recordId){
        return $this->database->fetchField('select user_id from record where id = ?', $recordId);
    }
    
    
    function insertNewRecord($row){
        return $this->database->table('record')->insert($row);
    }
}
