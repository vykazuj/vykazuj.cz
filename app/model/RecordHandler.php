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
    public $rolesToChargeOnProject = array('user','pmo','owner');
    public $rolesPrimaryWorkOrderForProject = array('primary');
    
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
    
    function getProjectChargesOverview($month, $year, $companyId){
        return $this->database->fetchAll(' select '
                . ' cl.name as clientName, pr.name as projectName, u.first_name as firstName, '
                . ' u.last_name as lastName, sum(rec.hours)+sum(rec.hours_over) as hours'
                . ' from '
                . 'company c '
                . 'join client cl on cl.company_id = c.id '
                . 'join project pr on pr.client_id = cl.id '
                . 'join record rec on rec.project_id = pr.id '
                . 'join users u on rec.user_id = u.id '
                . '     and rec.year = ? '
                . '     and rec.month = ? '
                . ' where '
                . ' c.id = ? '
                . 'group by cl.name, pr.name, u.first_name, u.last_name '
                . 'order by cl.name, pr.name, u.first_name, u.last_name '
                , $year, $month, $companyId);
    }    
    
    
    function getWorkOrderChargesOverview($month, $year, $companyId){
        return $this->database->fetchAll(' select '
                . ' wo.id as workorderid, wo.name as name, sum(upr.md_rate*(r.hours+r.hours_over)/8) as charged '
                . ' from '
                . ' company co'
                . ' join client cl '
                . '   on cl.company_id = co.id'
                . ' join work_order wo '
                . '   on wo.client_id = cl.id '
                . ' join record r '
                . '   on r.work_order_id = wo.id '
                . '   and r.year = ? '
                . '   and r.month = ? '
                . ' join project pr '
                . '   on pr.client_id = cl.id '
                . ' join users_project_rel upr '
                . '   on r.user_id = upr.user_id '
                . '   and pr.id = upr.project_id'
                . ' where '
                . '   co.id = ? '
                . ' group by '
                . '   wo.id, wo.name '
                , $year, $month, $companyId);
    }
    
    function getWorkOrdersOverview($companyId){
        return $this->database->fetchAll(' select '
                . ' wo.id as workorderid, wo.name as name, wo.amount as amount, sum(upr.md_rate*(r.hours+r.hours_over)/8) as charged '
                . ' from '
                . ' company co'
                . ' join client cl '
                . '   on cl.company_id = co.id'
                . ' join work_order wo '
                . '   on wo.client_id = cl.id '
                . ' join record r '
                . '   on r.work_order_id = wo.id '
                . ' join project pr '
                . '   on pr.client_id = cl.id '
                . ' join users_project_rel upr '
                . '   on r.user_id = upr.user_id '
                . '   and pr.id = upr.project_id'
                . ' where '
                . '   co.id = ? '
                . ' group by '
                . '   wo.id, wo.name, wo.amount '
                , $companyId);
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
    
    function getPrimaryWorkOrder($projectId){
        $workOrderId =  $this->database->fetchField("select work_order_id from project_work_order_rel where role in (?) and project_id = ? ",$this->rolesPrimaryWorkOrderForProject, $projectId);
        if($workOrderId == NULL){ return -1;}else{ return $workOrderId;}
        
    }
    
    function insertNewRecord($row){
        $row["work_order_id"] = $this->getPrimaryWorkOrder($row["project_id"]);
        return $this->database->table('record')->insert($row)->toArray();
    }
    
    function updateRecord($recordId, $projectId, $hours, $hoursOver){
        $workOrderIdNew = $this->getPrimaryWorkOrder($projectId);
        return $this->database->query("UPDATE record set work_order_id = ?, hours = ?, hours_over = ?, project_id = ? WHERE id = ?", $workOrderIdNew, $hours, $hoursOver, $projectId, $recordId);
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
        return $this->database->fetchAll('select p.* from project p, users_project_rel upr, project_param pp, client cl, company co where co.id = cl.company_id and cl.id = p.client_id and p.id = upr.project_id and p.id = pp.project_id and pp.param_id = ? and pp.value = ? and upr.user_id = ? and upr.rel in (?) and co.id= ? ','status','active',$userId,$this->rolesToChargeOnProject,$companyId);
    }
    
    function isMyChargeableProject($projectId, $userId){
        $rowCount = $this->database->query('select * from users_project_rel upr, project_param pp where upr.project_id = ? and upr.project_id = pp.project_id and pp.param_id = ? and pp.value = ?  and upr.user_id = ?  and upr.rel in (?) ',$projectId,'status','active', $userId, $this->rolesToChargeOnProject)->getRowCount();
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
