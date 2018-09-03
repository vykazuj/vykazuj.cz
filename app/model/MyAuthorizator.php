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
        $this->acl->addRole('moderator','premium');
        $this->acl->addRole('reprezentant','premium');
        $this->acl->addRole('admin','moderator');
        $this->acl->addRole('usatecz','admin');

        $this->acl->addResource('game');
        $this->acl->addResource('tournament');
        $this->acl->addResource('premiumFamily');
        $this->acl->addResource('basicFamily');
        $this->acl->addResource('customFamily');
        $this->acl->addResource('coupon');
        $this->acl->addResource('moderate');
        $this->acl->addResource('feedback');
        $this->acl->addResource('vseprezentace');
        $this->acl->addResource('reprezentantCoupons');
        $this->acl->addResource('allUsers');
        $this->acl->addResource('myUsers');
        
        $this->acl->allow('premium', 'game', 'play');
        $this->acl->allow('moderator', 'game', 'create');
        $this->acl->allow('reprezentant', 'game', 'create');
        
        $this->acl->allow('moderator', 'premiumFamily', 'select');
        $this->acl->allow('premium', 'basicFamily', 'select');
        $this->acl->allow('moderator', 'customFamily', 'select');
        
        $this->acl->allow('moderator', 'tournament', 'play');
        $this->acl->allow('admin', 'tournament', 'create');
        
        $this->acl->allow('admin', 'vseprezentace', 'show');
        
        $this->acl->allow('admin', 'coupon', 'create');
        $this->acl->allow('reprezentant', 'coupon', 'see');
        $this->acl->allow('moderator', 'coupon', 'see');
        
        $this->acl->allow('admin', 'feedback', 'validate');
        
        $this->acl->allow('moderator', 'game', 'moderate');
        $this->acl->allow('reprezentant', 'game', 'moderate');
        
        $this->acl->allow('reprezentant', 'reprezentantCoupons', 'create');
        $this->acl->allow('admin', 'reprezentantCoupons', 'reset');
        
        $this->acl->allow('reprezentant', 'myUsers', 'see');
        $this->acl->allow('moderator', 'myUsers', 'see');
        
        $this->acl->allow('admin', 'allUsers', 'see');
        
        $this->acl->allow('basic', 'allUsers', 'upsell');
        $this->acl->allow('reprezentant', 'allUsers', 'upsell');
        $this->acl->allow('moderator', 'allUsers', 'upsell');
        
        return $this->acl;
    }
    
    function getACL(){
        return $this->acl;
    }
            
    function isAllowed($role, $resource, $privilege){
        return $this->acl->isAllowed($role, $resource, $privilege);
    }

}