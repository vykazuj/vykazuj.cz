<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ClientHandler
 *
 * @author martin
 */
class ClientHandler {
    public $database;
    
    function __construct(Nette\Database\Context $database)
    {
        $this->database = $database;
    }
    
    function getMyClients($userId){
        return $this->database->fetchAll('select cl.* from client cl, company co where cl.company_id = co.id and co.owner_id = ?',$userId);
    }
    
    function getMyClient($userId, $clientId){
        return $this->database->fetchAll('select cl.* from client cl, company co where cl.company_id = co.id and co.owner_id = ? and cl.id = ?',$userId, $clientId);
    }
    
    function getMyClientProjects($userId, $clientId){
        return $this->database->fetchAll('select pr.* from client cl, company co, project pr where pr.client_id = cl.id and cl.company_id = co.id and co.owner_id = ? and cl.id = ?',$userId, $clientId);
    }
    
    function isMyClient($userId, $clientId){
        $rowNum = $this->database->query('select cl.* from client cl, company co where cl.company_id = co.id and co.owner_id = ? and cl.id = ?',$userId, $clientId)->getRowCount();
        if($rowNum>0)
            {return true;}
        else
            {return false;}
    }
    
    function isMyProject($userId, $projectId){
        $rowNum = $this->database->query('select * from users_project_rel where user_id = ? and project_id = ?',$userId, $projectId)->getRowCount();
        if($rowNum>0)
            {return true;}
        else
            {return false;}
    }
    
    function updateClient($clientId, $param, $value){
        $os = array("company_id", "name", "ico","contact", "phone", "email","address");
        if (in_array($param, $os)) {
            return $this->database->query("update client set ".$param." = ? where id = ?", $value, $clientId);
        }else{
            return false;
        }
    }
    
    function updateProject($projectId, $param, $value){
        $os = array("name");
        if (in_array($param, $os)) {
            return $this->database->query("update project set ".$param." = ? where id = ?", $value, $projectId);
        }else{
            return false;
        }
    }
    
    function deleteProject($projectId){

        return $this->database->query("delete from project where id = ?", $projectId);

    }
    
    function getMyCompany($userId){
        return $this->database->fetch("select * from company where owner_id = ?",$userId);
    }
    
    function createNewClient($userId){
        $company = $this->getMyCompany($userId);
        $companyId = $company["id"];
        $client["id"] = null;
        $client["company_id"] = $companyId;
        $client["name"] = 'Nový klient';
        $client["ico"] = 'ičo';
        $client["phone"] = 'telefon';
        $client["email"] = 'email';
        $client["contact"] = 'kontaktní osoba';
        $client["address"] = 'adresa';
        return $this->database->table('client')->insert($client);
    }
    
    function createNewProject($userId, $clientId){
        $project["id"] = null;
        $project["client_id"] = $clientId;
        $project["name"] = 'Nový projekt';
        $project["note"] = '';
        $row = $this->database->table('project')->insert($project);
        
        $userProjectRel["id"] = null;
        $userProjectRel["user_id"] = $userId;
        $userProjectRel["project_id"] = $row["id"];
        $userProjectRel["rel"] = 'user';
        $row2 = $this->database->table('users_project_rel')->insert($userProjectRel);
        
        return $row->toArray();
    }
    
}
