<?php

use Nette\Security\Passwords;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ClientHandler
 *
 * @author jan
 */
class SettingsHandler {
    public $database;
    
    function __construct(Nette\Database\Context $database)
    {
        $this->database = $database;
    }

    function getMe($userId){
        return $this->database->fetchAll('select * from users where id = ?',$userId);
    }
}