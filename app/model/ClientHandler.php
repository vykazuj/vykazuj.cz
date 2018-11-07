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
    
    function isMyCompany($userId, $companyId){
        $rowNum = $this->database->query('select * from users_company_rel where user_id = ? and company_id = ?',$userId, $companyId)->getRowCount();
        if($rowNum>0)
            {return true;
        }else{
            $rowNum2 = $this->database->query('select * from company where id = ? and owner_id = ?',$companyId, $userId)->getRowCount();
            if($rowNum>0){
                return true;
            }else{
                return false;
            }
        }
    }
    
    function setPrefCompany($userId, $companyId){
        return $this->database->query("update users set pref_company = ? where id = ? ",$companyId, $userId);
    }
    
    function getPrefCompany($userId){
        return $this->database->fetchField("select pref_company from users where id = ? ", $userId);
    }
    
    function getMyCompanies($userId){
        return $this->database->fetchAll('select distinct abc.id, abc.name, u.pref_company from ('
                . 'select c.id, c.name from users_company_rel ucr, company c where c.id = ucr.company_id and ucr.user_id = ?  '
                . ' UNION '
                . 'select c2.id, c2.name from company c2 where c2.owner_id = ? '
                . ') abc left join users u on u.pref_company = abc.id and u.id = ? ',$userId, $userId, $userId);
    }
    
    function getUserIdByIntegrationId($integrationId){
        return $this->database->fetchField("select id  from users where integration_id = ? ",$integrationId);
    }
    
    function addRequest($senderId, $acceptorId, $type){
        $data['sender_id']=$senderId;
        $data['acceptor_id']=$acceptorId;
        $data['type']=$type;
        $data['status']='sent';
        return $this->database->table('request')->insert($data);
    }
    
    function addRequestParam($requestId, $param, $value){
        $data['request_id']=$requestId;
        $data['param']=$param;
        $data['value']=$value;
        return $this->database->table('request_param')->insert($data);
    }
    
    function loadAllRequests($userId){
        return $this->database->fetchAll("select 'incoming' as direction, u.first_name as firstName, u.last_name as lastName, r.status as status, r.type as type, r.id as requestId from users u, request r where r.sender_id = u.id and r.acceptor_id = ? "
                . "UNION ALL "
                . " select 'outgoing' as direction, u.first_name as firstName, u.last_name as lastName, r.status as status, r.type as type, r.id as requestId from users u, request r where r.acceptor_id = u.id and r.sender_id = ? ",$userId,$userId);

    }
    
    function getRequestParamValue($requestId, $param){
        return $this->database->fetchField("select value from request_param where request_id = ? and param = ? ",$requestId, $param);
    }
    function acceptRequest($requestId, $userId){
        return $this->database->query('update request set status = ? where acceptor_id = ? and id = ? and status = ? ',"accepted",$userId, $requestId, "sent")->getRowCount();
    }
    
    function cancelRequest($requestId, $userId){
        return $this->database->query('update request set status = ? where sender_id = ? and id = ? and status = ? ',"cancelled",$userId, $requestId, "sent")->getRowCount();
    }
    
    function denyRequest($requestId, $userId){
        return $this->database->query('update request set status = ? where acceptor_id = ? and id = ? and status = ? ',"denied",$userId, $requestId, "sent")->getRowCount();
    }
    
    function isRequestAlreadySent($senderId, $acceptorId, $type){
        $rowCount = $this->database->query("select * from request where sender_id = ? and acceptor_id = ? and type = ? and status not in ('denied','accepted','cancelled')",$senderId, $acceptorId, $type)->getRowCount();
        if($rowCount==0){return false;}else{return true;}
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
        $client["name"] = 'Zadejte název klienta';
        $client["ico"] = 'Zadejte IČO';
        $client["phone"] = 'Zadejte telefon';
        $client["email"] = 'Zadejte email';
        $client["contact"] = 'Zadejte kontaktní osobu';
        $client["address"] = 'Zadejte adresa';
        return $this->database->table('client')->insert($client);
    }
    
    function createUserCompanyRel($userId, $companyId, $role){
        $rownum = $this->database->query("select * from users_company_rel where user_id = ? and company_id = ? and role = ?",$userId, $companyId, $role)->getRowCount();
        if($rownum>0){return true;}else{
            return $this->database->query("insert into users_company_rel (id, user_id, company_id, role) values (null,?,?,?)",$userId, $companyId, $role);   
        }
    }
    
    function createUserProjectRel($userId, $projectId, $mdRate){
        return $this->database->query("insert into users_company_rel (id, user_id, project_id, rel, md_rate) valuse (null,?,?,?)",$userId, $projectId, 'user' ,$mdRate);
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
