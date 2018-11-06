<?php

use Nette\Security as NS;
use Nette\Database\Context;
use Nette\SmartObject;
use Nette\Security\Passwords;

class MyRegistrator
{
    public $database;

    function __construct(Nette\Database\Context $database)
    {
        $this->database = $database;
    }

    function register(array $input)
    {
        $characters = '123456789ABCDEFGHIJKLMNPQRSTUVWXYZ';
        $randstring = '';
        for ($i = 0; $i < 6; $i++) {
            $randstring = $randstring.$characters[rand(0, strlen($characters)-1)];
        }
        
        $integrationId = '';
        for ($i = 0; $i < 10; $i++) {
            $integrationId = $integrationId.$characters[rand(0, strlen($characters)-1)];
        }
        
        
        unset($input["password2"]);
        
        //unset($input["agree_ladder"]);                
        $input["integration_id"] = $integrationId;
        $input["status"] = 'registered';
        $input["source"] = 'vykazuj';
        $input["source_id"] = '';
        $input["email_confirmation"] = $randstring;
        $input["password"]= Passwords::hash($input["password"]);
        
        $objDateTime = new DateTime('NOW');
        $input["created"]= $objDateTime->format('c');
        
        $roleDefaultSettings = 'basic';
        
        if($this->database->table('users')->where('username',$input['username'])->fetch()){ return 'Uživatelské jméno '.$input['username'].' již v databázi existuje. Zvolte si prosím jiné.';}
        
        try
        {
            //$this->database->query('INSERT INTO users ?', $input);
            $userId = $this->database->table("users")->insert($input);
            
        }
        catch(\PDOException $e)
        {
            return 'Registrace zákazníka se nepovedla. Kontaktujte admina s chybovou hláškou: '.$e->getMessage();
        }
        return 'Na email Vám byl odeslaný potvrzovací kód pro dokončení registrace.';

    }

    function isExternalRegistered($source, $source_id){
        $rowCount = $this->database->query('select * from users where source = ? and source_id = ?',$source, $source_id)->getRowCount();
        if($rowCount > 0){
            return true;
        }
        else{
            return false;
        }
    }
    
    function registerFromExternalSource($input, $source)
    {
        if($source==="google"){
                
            $characters = '123456789ABCDEFGHIJKLMNPQRSTUVWXYZ';
            $integrationId = '';
            for ($i = 0; $i < 10; $i++) {
                $integrationId = $integrationId.$characters[rand(0, strlen($characters)-1)];
            }
            //unset($input["agree_ladder"]);                
            $user["integration_id"] = $integrationId;
            $user["status"] = 'active';
            $user["first_name"] = $input->first_name;
            $user["username"] = $source.$input->id;
            $user["last_name"] = $input->last_name;
            $user["source"] = $source;
            $user["source_id"] = $input->id;
            $user["email"] = $input->email;
            $user["image"] = $input->getImage();
            $objDateTime = new DateTime('NOW');
            $user["created"]= $objDateTime->format('c');

            $company["id"] = null;
            $company["name"] = $input->first_name.' '.$input->last_name;
            $company["ico"] = 'ičo';
            $company["address"] = 'adresa';
            
            
            try
            {
                //$this->database->query('INSERT INTO users ?', $input);
                $user = $this->database->table("users")->insert($user);
                $company["owner_id"] = $user["id"];
                $companyId = $this->database->table("company")->insert($company);
                $myClientHandler = new ClientHandler($this->database);
                $myClientHandler->createNewClient($user["id"]);
                $myClientHandler->createUserCompanyRel($user["id"], $companyId, 'owner');
            }
            catch(\PDOException $e)
            {
                return 'Registrace zákazníka se nepovedla. Kontaktujte admina s chybovou hláškou: '.$e->getMessage();
            }
            return true;
        }
    }
    
    
    function getAttributes($userId){
        return $this->database->table("users")->get($userId);
    }
    
    function changeAttribute($attribute, $value, $userId)
    {
        try
        {
            $this->database->query('UPDATE users SET '.$attribute.' = ? WHERE id = ?', $value, $userId);
        }
        catch(\PDOException $e)
        {
            return 'Změna údajů se nepovedla. Kontaktujte admina s chybovou hláškou: '.$e->getMessage();
        }

    }
    
    function createNewPassword($userId)
    {
        try
        {
            $this->database->query('UPDATE users SET '.$attribute.' = ? WHERE id = ?', $value, $userId);
        }
        catch(\PDOException $e)
        {
            return 'Změna údajů se nepovedla. Kontaktujte admina s chybovou hláškou: '.$e->getMessage();
        }

    }
    
    function checkMailAndUsername($userName, $email)
    {
        try
        {
            $rowCount = $this->database->query('SELECT * FROM users WHERE username = ? and email = ?', $userName, $email)->getRowCount();
            if($rowCount>0){
                return $this->database->fetchField('SELECT id FROM users WHERE username = ? and email = ?', $userName, $email);
            }else{
                return 0;
            }
        }
        catch(\PDOException $e)
        {
            return 'Změna údajů se nepovedla. Kontaktujte admina s chybovou hláškou: '.$e->getMessage();
        }

    }
}
