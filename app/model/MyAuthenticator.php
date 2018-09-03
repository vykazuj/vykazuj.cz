<?php

use Nette\Security as NS;

class MyAuthenticator implements NS\IAuthenticator
{
    public $database;

    function __construct(Nette\Database\Context $database)
    {
        $this->database = $database;
    }

    function authenticate(array $credentials)
    {
        list($username, $password) = $credentials;
                
        $row = $this->database->table('users')
            ->where('username', $username)->fetch();
        //$row = $this->database->fetch("select * from users where username = ? and status = ? ",$username, "active");
        if (!$row) {
            throw new NS\AuthenticationException('Nesprávné jméno či heslo.');
        }
        if (!NS\Passwords::verify($password, $row->password)) {
            throw new NS\AuthenticationException('Neplatné přihlašovací jméno či heslo.');
        }
        if ($row["status"]=="registered") {
            throw new NS\AuthenticationException('Účet není aktivní. Potvrďte jí podle instrukcí v e-mailu.');
        }
        return new NS\Identity($row->id, $row->role, ['username' => $row->username, 'first_name' => $row->first_name, 'last_name' => $row->last_name]);
    }
}
