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

    function updateMyDetails($userId, $name, $surname, $phone, $email){
        return $this->database->query('UPDATE users set ', [
            'first_name' => $name,
            'last_name' => $surname,
            'phone' => $phone,
            'email' => $email,]
            , 'WHERE id = ?', $userId
        );
        
        //return $this->database->query('update users set first_name= ?, last_name= ?, phone= ?, email=?  where id = ?',$name, $surname, $phone, $email, $userId);
    }

    function updateMyPassword($userId, $password){
        $pw = Passwords::hash($password);
        return $this->database->query('update users set password= ? where id = ?',$pw, $userId);
    }
}