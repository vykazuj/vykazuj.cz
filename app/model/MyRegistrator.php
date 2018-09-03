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
        
        unset($input["password2"]);
        unset($input["agree"]);
        unset($input["agree2"]);
        unset($input["agree3"]);
        //unset($input["agree_ladder"]);
        $input["status"] = 'registered';
        $input["email_confirmation"] = $randstring;
        $input["password"]= Passwords::hash($input["password"]);
        
        $objDateTime = new DateTime('NOW');
        $input["created"]= $objDateTime->format('c');
        
        $roleDefaultSettings = $input["role"];
        /*
        if(!isset($input["role"])){$roleDefaultSettings = 'basic'; $input["role"] = 'premium';}
        else{$roleDefaultSettings = $input["role"];}
        
        if($input["role"] == 'basic'){$input["role"] = 'premium';}
        */
        
        if($this->database->table('users')->where('username',$input['username'])->fetch()){ return 'Uživatelské jméno '.$input['username'].' již v databázi existuje. Zvolte si prosím jiné.';}
        
        try
        {
            //$this->database->query('INSERT INTO users ?', $input);
            $userId = $this->database->table("users")->insert($input);
            
            if($roleDefaultSettings == 'basic'){ $countCzechia= 2; $countSimple = 0; $countCustom = 0; $countScenario = 0;}
            if($roleDefaultSettings == 'premium'){  $countCzechia= 2; $countSimple = 10; $countCustom = 2; $countScenario = 0;}
            if($roleDefaultSettings == 'moderator'){  $countCzechia= 2; $countSimple = 999999; $countCustom = 999999; $countScenario = 999999;}
            
            $couponId = "registration"; 
            $duration = 30;
            
            $this->database->query("INSERT into game_allowance_groups (user_id, coupon_id, valid_from, valid_to, purpose, count, count_max) VALUES (?, ?, now(), DATE_ADD(NOW(), INTERVAL ? DAY), ?, ?, ?)", $userId, $couponId, $duration, "czechiaGame", $countCzechia, $countCzechia);
            $this->database->query("INSERT into game_allowance_groups (user_id, coupon_id, valid_from, valid_to, purpose, count, count_max) VALUES (?, ?, now(), DATE_ADD(NOW(), INTERVAL ? DAY), ?, ?, ?)", $userId, $couponId, $duration, "customGame", $countCustom, $countCustom);
            $this->database->query("INSERT into game_allowance_groups (user_id, coupon_id, valid_from, valid_to, purpose, count, count_max) VALUES (?, ?, now(), DATE_ADD(NOW(), INTERVAL ? DAY), ?, ?, ?)", $userId, $couponId, $duration, "scenarioGame", $countScenario, $countScenario);     
            $this->database->query("INSERT into game_allowance_groups (user_id, coupon_id, valid_from, valid_to, purpose, count, count_max) VALUES (?, ?, now(), DATE_ADD(NOW(), INTERVAL ? DAY), ?, ?, ?)", $userId, $couponId, $duration, "simpleGame", $countSimple, $countSimple);
            
            //odeslání emailu
            $to = $input["email"];
            $subject = "Potvrzení registrace - Život podle Vás";

            $message = "
            <html>
            <head>
            <title>Potvrzení registrace - Život podle Vás</title>
            </head>
            <style>
            body{
                font-family: \"Roboto Condensed\", sans-serif;
                font-size: 12px;
                background: #EFEFEF;
                color: #333;
                padding: 10px 10px 10px 10px;
                }
            a { text-decoration: underline;
                font-family: \"Roboto Condensed\", sans-serif;
                font-size: 12px;
                background: #EFEFEF;
                color: #333;
                padding: 10px 10px 10px 10px;
                }
            a:hover{ 
                text-decoration: none;
                }
            </style>
            <body>
            <p>Dobrý den,</p>
            <p>děkujeme Vám za registraci do naší hry. Abyste mohli začít hrát, stačí udělat poslední krok, kterým je potvrzení Vaší emailové adresy. Nejjednodušeji to uděláte kliknutím na tento odkaz:</p>
            <br>
            <p><a href='http://www.vsfg.cz/homepage/activate-account?code=".$randstring."'>".$randstring."</a></p>
            <br>
            <p>Pokud Vám odkaz nefunguje, registraci můžete dokončit zadáním tohoto kódu v sekci Registrace na hlavní stránce naší hry. </p>
            <br>
            <p><a href='http://www.vsfg.cz/homepage/show-page?page_name=registration#confirmation'>www.vsfg.cz</a></p>
            <br>
            <p>Dokončením registrace dáváte svůj souhlas se zpracováním osobních údajů. Detail souhlasu si můžete přečíst na našich stránkách zde: <a href='http://www.vsfg.cz/images/GDPR.pdf'>Souhlas se zpracováním osobních údajů</a></p>
            <br>
            <p>Pakliže Vám e-mail přišel bez Vašeho vyžádání, tak se Vám omlouváme a můžete jej v klidu ignorovat.</p>
            <br>
            <p>Díky,</p>
            <p>tým hry <b>Život podle Vás</b></p>
            </body>
            </html>
            ";

            // Always set content-type when sending HTML email
            $headers = "MIME-Version: 1.0" . "\r\n";
            $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

            // More headers
            $headers .= 'From: <info@vsfg.cz>' . "\r\n";

            mail($to,$subject,$message,$headers);

        }
        catch(\PDOException $e)
        {
            return 'Registrace zákazníka se nepovedla. Kontaktujte admina s chybovou hláškou: '.$e->getMessage();
        }
        return 'Na email Vám byl odeslaný potvrzovací kód pro dokončení registrace.';

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
