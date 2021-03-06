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
    public $rolesToManageProject = array("owner", "pmo");
    public $rolesActiveForCompany = array('owner','accountant','user');
    public $rolesInactiveForCompany = array('alumni');
    public $rolesActiveForProject = array('user','pmo','owner');
    public $rolesInactiveForProject = array('alumni');
    //public $rolesPrimaryForWorkOrder = array('active');
    public $rolesAbleToSeeClients = array('owner','accountant');
    public $rolesActiveForWorkOrder = array('active','primary');
    public $rolesInactiveForWorkOrder = array('inactive');
    public $clientsSpecialFlagNotToBeDisplayed = array('vacation');
    public $mapCompanyRoles = array('owner' => 'Jednatel','user' => 'Zaměstnanec','accountant' => 'Účetní');
    
    function __construct(Nette\Database\Context $database)
    {
        $this->database = $database;
    }
    
    function getMyClients($userId, $companyId){
        return $this->database->fetchAll('select cl.* from client cl, users_company_rel ucr where cl.company_id = ucr.company_id and ucr.user_id = ? and ucr.company_id = ? and ucr.role in (?) and special_flag not in (?)',$userId, $companyId, $this->rolesAbleToSeeClients,$this->clientsSpecialFlagNotToBeDisplayed);
    }
    /*
    function getMyClientOrders($userId){
        return $this->database->fetchAll('select cl.* from client cl, company co where cl.company_id = co.id and co.owner_id = ?',$userId);
    }
    */
    function getMyClient($userId, $clientId){
        return $this->database->fetchAll('select cl.* from client cl, users_company_rel ucr where cl.company_id = ucr.company_id and ucr.user_id = ? and cl.id = ? and ucr.role in (?,?) ',$userId, $clientId, 'owner','accountant');
    
        //return $this->database->fetchAll('select cl.* from client cl, company co where cl.company_id = co.id and co.owner_id = ? and cl.id = ?',$userId, $clientId);
    }
    
    function getClient($clientId){
        return $this->database->fetchAll('select cl.* from client cl where cl.id = ?', $clientId);
    }
    
    function getMyClientProjects($userId, $clientId){
        return $this->database->fetchAll('select pr.* from client cl, company co, project pr where pr.client_id = cl.id and cl.company_id = co.id and co.owner_id = ? and cl.id = ?',$userId, $clientId);
    }
    
    function getMyClientProjectsWithParameters($userId, $clientId){
        $projects =  $this->database->fetchAll('select pr.* from project pr, users_project_rel upr where upr.user_id = ? and upr.project_id = pr.id and pr.client_id = ? and upr.rel in (?,?) ',$userId, $clientId, 'owner','pmo');
        $client = $this->getClient($clientId);
        $companyId = $client[0]["company_id"];
        $i = 0;
        $return = null;
        foreach($projects as $project){
            $return[$i]["project"] = $project;
            $return[$i]["activeWorkOrders"]= $this->database->fetchAll('select wo.name, wo.id as work_order_id, pwor.id as pwor_id, pwor.role as role from work_order wo, project_work_order_rel pwor where pwor.work_order_id = wo.id and pwor.project_id = ? and pwor.role in (?) order by pwor.work_order_id DESC',$project->id, $this->rolesActiveForWorkOrder);
            $return[$i]["projectParams"]= $this->database->fetchAll('select pp.project_id as project_id, pp.param as param, pp.value as value, pp.id as param_id, pp.param_id as param_lic  from project_param pp where pp.project_id = ? ',$project->id);
            $return[$i]["activeUsers"]= $this->database->fetchAll('select upr.id as uprId, u.*, upr.rel as rel from users u, users_project_rel upr where upr.user_id = u.id and upr.project_id = ? and upr.rel in (?)',$project->id, $this->rolesActiveForProject);
            $return[$i]["inactiveUsers"]= $this->database->fetchAll('select -1 as uprId, u.*, upr.rel as rel from users_company_rel ucr, users u  join users_project_rel upr on upr.user_id = u.id and upr.project_id = ? where upr.rel in (?) and u.id = ucr.user_id and ucr.company_id = ? and ucr.role in (?) ',$project->id,$this->rolesInactiveForProject, $companyId,$this->rolesActiveForCompany);
            $return[$i]["inactiveUsers"] += $this->database->fetchAll(''
                    . ' select -1 as uprId, '
                    . ' u.*, '
                    . ' \'inactive\' as rel '
                    . ' from company co '
                    . ' join client cl '
                    . '     on cl.company_id = co.id '
                    . ' join project pr '
                    . '     on pr.client_id = cl.id '
                    . ' join users_company_rel ucr '
                    . '     on ucr.company_id = co.id '
                    . ' join users u '
                    . '     on ucr.user_id = u.id '
                    . ' left join users_project_rel upr '
                    . '     on upr.user_id = u.id '
                    . '     and upr.project_id = pr.id '
                    . ' where '
                    . '     upr.id is null '
                    . '     and cl.id = ? '
                    . '     and co.id = ? '
                    . '     and pr.id = ? ' ,$clientId, $companyId, $project->id);
            $i++;
        }
        return $return;
    }    
    function getMyClientProjectWithParameters($userId, $clientId, $projectId){
        $projects =  $this->database->fetchAll('select pr.* from project pr, users_project_rel upr where upr.user_id = ? and upr.project_id = pr.id and pr.client_id = ? and upr.rel in (?,?) and pr.id = ? ',$userId, $clientId, 'owner','pmo',$projectId);
        $client = $this->getClient($clientId);
        $companyId = $client[0]["company_id"];
        $i = 0;
        $return = null;
        foreach($projects as $project){
            $return[$i]["project"] = $project;
            $return[$i]["projectParams"]= $this->database->fetchAll('select pp.project_id as project_id, pp.param as param, pp.value as value, pp.id as param_id, pp.param_id as param_lic  from project_param pp where pp.project_id = ? ',$project->id);
            $return[$i]["activeUsers"]= $this->database->fetchAll('select upr.id as uprId, u.*, upr.rel as rel  from users u, users_project_rel upr where upr.user_id = u.id and upr.project_id = ? and upr.rel in (?)',$project->id, $this->rolesActiveForProject);
            $return[$i]["inactiveUsers"]= $this->database->fetchAll('select -1 as uprId, u.*, upr.rel as rel  from users_company_rel ucr, users u  join users_project_rel upr on upr.user_id = u.id and upr.project_id = ? where upr.rel in (?) and u.id = ucr.user_id and ucr.company_id = ? and ucr.role in (?) ',$project->id,$this->rolesInactiveForProject, $companyId,$this->rolesActiveForCompany);
            $return[$i]["inactiveUsers"] += $this->database->fetchAll(''
                    . ' select -1 as uprId, '
                    . ' u.*, '
                    . ' \'inactive\' as rel '
                    . ' from company co '
                    . ' join client cl '
                    . '     on cl.company_id = co.id '
                    . ' join project pr '
                    . '     on pr.client_id = cl.id '
                    . ' join users_company_rel ucr '
                    . '     on ucr.company_id = co.id '
                    . ' join users u '
                    . '     on ucr.user_id = u.id '
                    . ' left join users_project_rel upr '
                    . '     on upr.user_id = u.id '
                    . '     and upr.project_id = pr.id '
                    . ' where '
                    . '     upr.id is null '
                    . '     and cl.id = ? '
                    . '     and co.id = ? '
                    . '     and pr.id = ? ' ,$clientId, $companyId, $project->id);
            $i++;
        }
        return $return;
    }
    
    function getProjectWithParameters($projectId){
        return $this->database->fetchAll('select pr.*, pp.param as param, pp.value as value, pp.id as param_id, pp.param_id as param_lic  from project pr, project_param pp where pp.project_id = pr.id and pr.id = ?',$projectId);
    }
    
    function getMyClientOrdersWithParameters($clientId){
        $workOrders =  $this->database->fetchAll('select wo.* from work_order wo where client_id = ?', $clientId);
        $client = $this->getClient($clientId);
        $companyId = $client[0]["company_id"];
        $i = 0;
        $return = null;
        foreach($workOrders as $workOrder){
            $return[$i]["workOrder"] = $workOrder;
            $return[$i]["activeProjects"]= $this->database->fetchAll('select pr.name, pr.id as project_id, pwor.id as pwor_id, pwor.role as role from project pr, project_work_order_rel pwor where pwor.project_id = pr.id and pwor.work_order_id = ? and pwor.role in (?) order by role DESC',$workOrder->id, $this->rolesActiveForWorkOrder);
            $return[$i]["inactiveProjects"]= $this->database->fetchAll('select pr.name, pr.id as project_id, pwor.id as pwor_id, pwor.role as role from project pr, project_work_order_rel pwor where pwor.project_id = pr.id and pwor.work_order_id = ? and pwor.role in (?) order by role DESC',$workOrder->id, $this->rolesInactiveForWorkOrder);
            $return[$i]["inactiveProjects"] += $this->database->fetchAll(''
                    . ' select pr.name, pr.id as project_id, -1 as pwor_id, pwor.role as role '
                    . ' from company co '
                    . ' join client cl '
                    . '     on cl.company_id = co.id '
                    . ' join project pr '
                    . '     on pr.client_id = cl.id '
                    . ' left join project_work_order_rel pwor '
                    . '     on pwor.project_id = pr.id '
                    . '     and pwor.work_order_id = ? '
                    . ' where '
                    . '     pwor.id is null '
                    . '     and cl.id = ? '
                    . '     and co.id = ? ', $workOrder->id ,$clientId, $companyId);
            $i++;
        }
        return $return;
    }
    
    function getMyClientOrderWithParameters($clientId, $workOrderId){
        $workOrder =  $this->database->fetchAll('select wo.* from work_order wo where id = ?', $workOrderId);
        $client = $this->getClient($clientId);
        $companyId = $client[0]["company_id"];
        $i = 0;
        $return = null;

            $return[$i]["workOrder"] = $workOrder;
            $return[$i]["activeProjects"]= $this->database->fetchAll('select pr.name, pr.id as project_id, pwor.id as pwor_id, pwor.role as role from project pr, project_work_order_rel pwor where pwor.project_id = pr.id and pwor.work_order_id = ? and pwor.role in (?) order by role DESC',$workOrderId, $this->rolesActiveForWorkOrder);
            $return[$i]["inactiveProjects"]= $this->database->fetchAll('select pr.name, pr.id as project_id, pwor.id as pwor_id, pwor.role as role from project pr, project_work_order_rel pwor where pwor.project_id = pr.id and pwor.work_order_id = ? and pwor.role in (?) order by role DESC',$workOrderId, $this->rolesInactiveForWorkOrder);
            $return[$i]["inactiveProjects"] += $this->database->fetchAll(''
                    . ' select pr.name, pr.id as project_id, -1 as pwor_id, pwor.role as role '
                    . ' from company co '
                    . ' join client cl '
                    . '     on cl.company_id = co.id '
                    . ' join project pr '
                    . '     on pr.client_id = cl.id '
                    . ' left join project_work_order_rel pwor '
                    . '     on pwor.project_id = pr.id '
                    . '     and pwor.work_order_id = ? '
                    . ' where '
                    . '     pwor.id is null '
                    . '     and cl.id = ? '
                    . '     and co.id = ? ', $workOrderId ,$clientId, $companyId);
        return $return;
    }
 
    function getUsersNotLinkedToClientOrders($clientId){
        return $this->database->fetchAll('select wo.status as status, wo.amount as amount, u.id as userId, u.first_name as firstName, u.last_name as lastName, 0 as mdRate, "new" as uworStatus, wo.name as name, wo.id as id, -1 as uworId from '
                . ' client cl '
                . ' join company com '
                . '     on com.id = cl.company_id '
                . ' join work_order wo '
                . '     on cl.id = wo.client_id '
                . ' join users_company_rel ucr '
                . '     on ucr.company_id=com.id '
                . ' left join users u '
                . '     on ucr.user_id = u.id '
                . ' left join users_work_order_rel uwor '
                . '     on uwor.work_order_id = wo.id '
                . '     and uwor.user_id = u.id '
                . 'where uwor.id is null and cl.id = ? order by cl.id', $clientId);

    }
    
    
    function getProject($projectId){
        return $this->database->fetchAll('select pr.*, pp.param as param, pp.value as value, pp.id as param_id from project pr, project_param pp where pp.project_id = pr.id and pr.id = ?',$projectId);  
    }
 
    function getProjectParam($projectId, $paramId){
        return $this->database->fetchField('select pp.value as value from project pr, project_param pp where pp.project_id = pr.id and pr.id = ? and pp.param_id = ? ',$projectId, $paramId);  
    }
    
    function isUserAllowedToManageClient($userId, $clientId){
        //$rowNum = $this->database->query('select cl.* from client cl, company co where cl.company_id = co.id and co.owner_id = ? and cl.id = ?',$userId, $clientId)->getRowCount();
        $rowNum = $this->database->query('select cl.* from client cl, company co, users_company_rel ucr where cl.company_id = co.id and co.id = ucr.company_id and ucr.user_id = ? and cl.id = ? and ucr.role in (?,?,?) ',$userId, $clientId,'owner','owner','accountant')->getRowCount();
        if($rowNum>0)
            {return true;}
        else
            {return false;}
    }
    
    function isUserAllowedToChargeOnClient($userId, $clientId){
        //$rowNum = $this->database->query('select cl.* from client cl, company co where cl.company_id = co.id and co.owner_id = ? and cl.id = ?',$userId, $clientId)->getRowCount();
        $rowNum = $this->database->query('select cl.* from client cl, company co, users_company_rel ucr where cl.company_id = co.id and co.id = ucr.company_id and ucr.user_id = ? and cl.id = ? and ucr.role in (?,?,?) ',$userId, $clientId,'user','owner','accountant')->getRowCount();
        if($rowNum>0)
            {return true;}
        else
            {return false;}
    }
    
    function isUserAllowedToManageProject($userId, $projectId){
        $role = $this->getUserProjectRel($userId, $projectId);
        if (in_array($role, $this->rolesToManageProject))
            {return true;}
        else
            {return false;}
    }
    function isUserAllowedToChargeOnProject($userId, $projectId){
        $project = $this->getProject($projectId);
        $clientId = $project[0]["client_id"];
        return $this->isUserAllowedToChargeOnClient($userId, $clientId);
    }
    
    function getClientOfWorkOrder($workOrderId){
        return $this->database->fetch("select * from work_order where id = ?",$workOrderId);
    }
    
    function getWorkOrderOfUwor($uworId){
        return $this->database->fetch("select * from users_work_order_rel where id = ?",$uworId);
    }
    /*
    function isMyProject($userId, $projectId){
        $rowNum = $this->database->query('select * from users_project_rel where user_id = ? and project_id = ? and rel = ? ',$userId, $projectId,'user')->getRowCount();
        if($rowNum>0)
            {return true;}
        else
            {return false;}
    }
    */
    function isMyCompany($userId, $companyId){
        $rowNum = $this->database->query('select * from users_company_rel where user_id = ? and company_id = ? and role in (?)',$userId, $companyId,$this->rolesActiveForCompany)->getRowCount();
        if($rowNum>0){
            return true;
        }else{
            return false;
        }
    }
    
    function setPrefCompany($userId, $companyId){
        return $this->database->query("update users set pref_company = ? where id = ? ",$companyId, $userId);
    }
    function getCompanyIdByOwnerId($userId){
        return $this->database->fetchField("select max(id) from company where owner_id = ? ", $userId);
    }
    
    function getPrefCompany($userId){
        $prefCompanyId = $this->database->fetchField("select pref_company from users where id = ? ", $userId);
        if($this->isMyCompany($userId, $prefCompanyId)) {
            return $prefCompanyId;
        }else{
            $newCompanyId = $this->getCompanyIdByOwnerId($userId);
            $this->setPrefCompany($userId, $newCompanyId);
            return $newCompanyId;
        }
    }
    
    function getMyCompanies($userId){
        return $this->database->fetchAll('select distinct abc.id, abc.name, u.pref_company from ('
                . 'select c.id, c.name from users_company_rel ucr, company c where c.id = ucr.company_id and ucr.user_id = ? and ucr.role in (?) '
                . ' UNION '
                . 'select c2.id, c2.name from company c2 where c2.owner_id = ? '
                . ') abc left join users u on u.pref_company = abc.id and u.id = ? ',$userId, $this->rolesActiveForCompany, $userId, $userId);
    }
    
    function getUserIdByIntegrationId($integrationId){
        return $this->database->fetchField("select id  from users where integration_id = ? ",$integrationId);
    }
    
    function getUserDetails($userId){
        $row =  $this->database->fetchAll("select *  from users where id = ? ",$userId);
        return $row[0];
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
    
    function isAlreadyEmployee($userId, $companyId){
        $rowCount = $this->database->query("select * from users_company_rel where user_id = ? and company_id = ? and role in (?)",$userId, $companyId, $this->rolesActiveForCompany)->getRowCount();
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
    
    function updateWorkOrder($workOrderId, $finder, $value){
        $os = array("amount", "name", "status");
        if (in_array($finder, $os)) {
            return $this->database->query("update work_order set ".$finder." = ? where id = ?", $value, $workOrderId);
        }else{
            return false;
        }
    }
    
    function updateUwor($uworId, $finder, $value){
        $os = array("status", "md_rate");
        if (in_array($finder, $os)) {
            return $this->database->query("update users_work_order_rel set ".$finder." = ? where id = ?", $value, $uworId);
        }else{
            return false;
        }
    }
    
    function createUwor($userId, $workOrderId){
        $input["id"] = null;
        $input["user_id"] = $userId;
        $input["work_order_id"] = $workOrderId;
        $input["md_rate"] = 0;
        $input["status"] = 'active';
        return $this->database->table("users_work_order_rel")->insert($input);
    }
    
    function getUpr($uprId){
        return $this->database->fetch('select * from users_project_rel where id = ?',$uprId);
    }
    
    function updateUpr($uprId, $finder, $value){
        $os = array("status", "md_rate");
        if (in_array($finder, $os)) {
            return $this->database->query("update users_project_rel set ".$finder." = ? where id = ?", $value, $uprId);
        }else{
            return false;
        }
    }
    
    function createUpr($userId, $projectId){
        $input["id"] = null;
        $input["user_id"] = $userId;
        $input["project_id"] = $projectId;
        $input["md_rate"] = 0;
        $input["status"] = 'active';
        return $this->database->table("users_project_rel")->insert($input);
    }
    
    function updateProject($projectId, $finder, $value){
        $os = array("name");
        if (in_array($finder, $os)) {
            return $this->database->query("update project set ".$finder." = ? where id = ?", $value, $projectId);
        }else{
            return false;
        }
    }
    
    function updateProjectParam($projectParamId, $value){
        return $this->database->query("update project_param set value = ? where id = ?", $value, $projectParamId);
    }
    
    function updatePrimaryWorkOrder($projectId, $value){
        $this->database->query("update project_work_order_rel set role = ? where project_id = ? and role = ?", 'active', $projectId,'primary');
        $this->database->query("update project_work_order_rel set role = ? where project_id = ? and work_order_id = ?", 'primary', $projectId, $value);
        return true;
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
        $client["special_flag"] = '';
        return $this->database->table('client')->insert($client);
    }
    
    function createNewDummyClient($userId){
        $company = $this->getMyCompany($userId);
        $companyId = $company["id"];
        $client["id"] = null;
        $client["company_id"] = $companyId;
        $client["name"] = 'Dovolená a Sickdays';
        $client["ico"] = '';
        $client["phone"] = '';
        $client["email"] = '';
        $client["contact"] = '';
        $client["address"] = '';
        $client["special_flag"] = 'vacation';
        return $this->database->table('client')->insert($client);
    }
    
    function getCompanySpecialProjectId($companyId, $specialFlag){
        return $this->database->fetchField("select p.id from project p, client cl where p.client_id = cl.id and p.special_flag = ? and cl.company_id = ? ",$specialFlag, $companyId);
    }
    
    function createUserCompanyRel($userId, $companyId, $role){
        $input["user_id"] = $userId;
        $input["company_id"] = $companyId;
        $input["role"] = $role;
        $rownum = $this->database->query("select * from users_company_rel where user_id = ? and company_id = ?",$userId, $companyId)->getRowCount();
        if($rownum>0){
            return $this->database->query("UPDATE users_company_rel set role = ? where user_id = ? and company_id = ? ", $role, $userId, $companyId);
        }else{
            return $this->database->table("users_company_rel")->insert($input);
            //return $this->database->query("insert into users_company_rel (id, user_id, company_id, role) values (null,?,?,?)",$userId, $companyId, $role);   
        }
    }
    
    function getUserCompanyRel($userId, $companyId){
        return $this->database->fetchField("select role from users_company_rel where user_id = ? and company_id = ?",$userId, $companyId);
    }
    
    function getCompanyIdByProjectId($projectId){
        return $this->database->fetchField("select cl.company_id from client cl, project pr where cl.id = pr.client_id and pr.id = ?", $projectId);
    }
    
    function getUserCompanyDefaultMDRate($userId, $companyId){
        return $this->database->fetchField("select default_md_rate from users_company_rel where user_id = ? and company_id = ?",$userId, $companyId);
    }
    
    function getUserCompanyRelTranslated($userId, $companyId){
        $role = $this->database->fetchField("select role from users_company_rel where user_id = ? and company_id = ?",$userId, $companyId);
        if(isset($this->mapCompanyRoles[$role])){ 
            return $this->mapCompanyRoles[$role];
        }else{
            $this->mapCompanyRoles['user'];
            
        }
    }
    
    function getUserProjectRel($userId, $projectId){
        return $this->database->fetchField("select rel from users_project_rel where user_id = ? and project_id = ?",$userId, $projectId);
    }
    
    
    
    function upsertUserProjectRel($userId, $projectId, $mdRate, $rel){
        $input["user_id"] = $userId;
        $input["project_id"] = $projectId;
        $input["rel"] = $rel;
        $rownum = $this->database->query("select * from users_project_rel where user_id = ? and project_id = ?",$userId, $projectId)->getRowCount();
        if($rownum>0){
            return $this->database->query("UPDATE users_project_rel set rel = ?, md_rate = ? where user_id = ? and project_id = ? ", $rel, $mdRate, $userId, $projectId);
        }else{
            $input["md_rate"] = $this->getUserCompanyDefaultMDRate($userId, $this->getCompanyIdByProjectId($projectId));
            return $this->database->table("users_project_rel")->insert($input);
        }
    }
    
    function upsertProjectWorkOrderRel($workOrderId, $projectId, $role){
        $input["work_order_id"] = $workOrderId;
        $input["project_id"] = $projectId;
        $input["role"] = $role;
        $rownum = $this->database->query("select * from project_work_order_rel where work_order_id = ? and project_id = ?",$workOrderId, $projectId)->getRowCount();
        if($rownum>0){
            return $this->database->query("UPDATE project_work_order_rel set role = ? where work_order_id = ? and project_id = ? ", $role, $workOrderId, $projectId);
        }else{
            return $this->database->table("project_work_order_rel")->insert($input);
        }
    }
        
    
    function createNewProjectSpecial($userId, $clientId,$name,$flag){
        $project["id"] = null;
        $project["client_id"] = $clientId;
        $project["name"] = $name;
        $project["note"] = $name;
        $project["special_flag"] = $flag;
        $row = $this->database->table('project')->insert($project);
        
        $userProjectRel["id"] = null;
        $userProjectRel["user_id"] = $userId;
        $userProjectRel["project_id"] = $row["id"];
        $userProjectRel["rel"] = 'owner';
        $row2 = $this->database->table('users_project_rel')->insert($userProjectRel);
        
        return $row->toArray();
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
        $userProjectRel["rel"] = 'owner';
        $row2 = $this->database->table('users_project_rel')->insert($userProjectRel);
        
        return $row->toArray();
    }
    
    function createNewWorkOrder($clientId){
        $project["id"] = null;
        $project["client_id"] = $clientId;
        $project["name"] = 'Nová objednávka';
        $project["status"] = 'active';
        $project["amount"] = 0;
        $row = $this->database->table('work_order')->insert($project);
                
        return $row->toArray();
    }
    
    function addParamToProject($projectId, $param_id, $param, $value){
        $newParam = null;
        $newParam["id"] = null;
        $newParam["project_id"] = $projectId;
        $newParam["param_id"] = $param_id;
        $newParam["param"] = $param;
        $newParam["value"] = $value;
        return $this->database->table('project_param')->insert($newParam);
    }
    
    function updateUsersCosts($userId, $companyId, $internalCost, $defaultMDRate){
     return $this->database->query("UPDATE users_company_rel set internal_costs = ?, default_md_rate = ? where user_id = ? and company_id = ? ", $internalCost, $defaultMDRate, $userId, $companyId);
    }
    
}
