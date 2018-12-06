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
    
    function getRecordsByMonthYearUser($month, $year, $userId, $companyId){
        return $this->database->fetchAll('select r.*, p.name as projectName from record r, project p, client cl, company co where co.id = cl.company_id and cl.id = p.client_id and p.id = r.project_id and r.user_id = ? and r.year = ? and r.month = ? and co.id = ? ORDER by day ASC',$userId, $year, $month, $companyId);
    }
    
    function getEmployeeChargesOverview($month, $year, $companyId){
        return $this->database->fetchAll(' select '
                . ' u.id, u.first_name as firstName, '
                . ' u.last_name as lastName, '
                . ' sum(case when rec1.special_flag = \'\' then rec1.hours else 0 end ) as hours, '
                . ' sum(case when rec1.special_flag = \'\' then rec1.hoursOver else 0 end ) as hoursOver, '
                . ' sum(case when rec1.special_flag = \'vacation\' then rec1.hours else 0 end ) as vacation, '
                . ' sum(case when rec1.special_flag = \'sick\' then rec1.hours else 0 end ) as sick '
                . ' from '
                . 'company c '
                . 'join client cl on cl.company_id = c.id '
                . 'left join (select '
                . '             r.user_id as user_id, '
                . '             p.client_id as client_id, '
                . '             p.special_flag as special_flag, '
                . '             sum(r.hours) as hours, '
                . '             sum(r.hours_over) as hoursOver '
                . '           from '
                . '             project p, '
                . '             record r '
                . '           where '
                . '             p.id = r.project_id '
                . '             and r.year = ? '
                . '             and r.month = ? '
                . '           group by r.user_id, p.client_id, p.special_flag '
                . '         ) rec1 '
                . '             on rec1.client_id = cl.id '
                
                . ' join users u on rec1.user_id = u.id '
                . ' where '
                . ' cl.company_id = c.id '
                . 'and c.id = ? '
                . 'group by u.id, u.first_name, u.last_name '
                , $year, $month, $companyId);
    }
    
    /*
    function getEmployeeChargesOverview($month, $year, $companyId){
        return $this->database->fetchAll(' select u.id, u.first_name as firstName, u.last_name as lastName, sum(hours) as hours, sum(hours_over) as hoursOver'
                . ' from '
                . 'users u, '
                . 'record r, '
                . 'project p, '
                . 'client cl, '
                . 'company c '
                . ' where '
                . 'u.id = r.user_id '
                . 'and p.id = r.project_id '
                . 'and p.special_flag = ? '
                . 'and p.client_id = cl.id '
                . 'and cl.company_id = c.id '
                . 'and c.id = ? '
                . 'and r.year = ? '
                . 'and r.month = ? '
                . ' GROUP BY u.id, u.first_name, u.last_name ','',$companyId, $year, $month);
    }
    */
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
    
    function getProjectNameByProjectId($projectId){
        return $this->database->fetchField("select name from project where id = ?",$projectId);
    }
    
    function getProjectDetails($projectId){
        return $this->database->fetch("select * from project where id = ?",$projectId);
    }
    
    
    function getProjectParam($projectId, $param){
        return $this->database->fetchField("select value from project_param where project_id = ? and param_id = ? ",$projectId,$param);
    }
    
    function getMyChargeableProjects($userId, $companyId){
        return $this->database->fetchAll('select p.* from project p, users_project_rel upr, project_param pp, client cl, company co where co.id = cl.company_id and cl.id = p.client_id and p.id = upr.project_id and p.id = pp.project_id and pp.param_id = ? and pp.value = ? and upr.user_id = ? and upr.rel = ? and co.id= ? ','status','active',$userId,'user',$companyId);
    }
    
    function isMyChargeableProject($projectId, $userId){
        $rowCount = $this->database->query('select * from users_project_rel upr, project_param pp where upr.project_id = ? and upr.project_id = pp.project_id and pp.param_id = ? and pp.value = ?  and upr.user_id = ?  and upr.rel = ?',$projectId,'status','active', $userId, 'user')->getRowCount();
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
