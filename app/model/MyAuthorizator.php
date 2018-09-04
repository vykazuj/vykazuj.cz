<?php

class MyAuthorizator implements Nette\Security\IAuthorizator
{   
    /** @var Nette\Security\Permission; */    
    private $acl;
    public function __construct() {
        $this->acl = new Nette\Security\Permission;
        // pokud chceme, můžeme role a zdroje načíst z databáze
        $this->acl->addRole('guest');
        $this->acl->addRole('basic');
        $this->acl->addRole('premium','basic');

        $this->acl->addResource('game');
        $this->acl->addResource('tournament');
        
        $this->acl->allow('premium', 'game', 'play');
        $this->acl->allow('moderator', 'game', 'create');
        $this->acl->allow('reprezentant', 'game', 'create');
        return $this->acl;
    }
    
    function getACL(){
        return $this->acl;
    }
            
    function isAllowed($role, $resource, $privilege){
        return $this->acl->isAllowed($role, $resource, $privilege);
    }

}