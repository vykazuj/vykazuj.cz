<?php

namespace App\Presenters;
use Nette\Application\UI;
use Nette\Security\User;
use Nette\Security as NS;
use Nette\Forms;
use Nette\Database;
use Nette\Database\Context;
use Nette\Security\Passwords;

class HomepagePresenter extends BasePresenter
{
	public function renderDefault()
	{
		$this->template->anyVariable = 'any value';
	}
        protected function createComponentLoginForm()
        {
            $form = new UI\Form();
            
            $form->addText('name', 'Jméno:')
                  ->setRequired('Zadejte prosím jméno')
                  ->setHtmlAttribute('class', 'form-control')
                  ->setHtmlAttribute('placeholder', 'Login')
                  ->setHtmlAttribute('required')
                  ->setHtmlAttribute('autofocus');
            
            $form->addPassword('password', 'Heslo:')
                  ->setRequired('Zadejte prosím heslo')
                  ->setHtmlAttribute('class', 'form-control')
                  ->setHtmlAttribute('placeholder', 'Heslo')
                  ->setHtmlAttribute('required')
                  ->setHtmlAttribute('autofocus');
            
            $form->addSubmit('login', 'Odeslat')
                  ->setHtmlAttribute('class', 'login_submit');
            $form->addProtection('Vypršel časový limit, odešlete formulář znovu');
            $form->onSuccess[] = [$this, 'loginFormSucceeded'];
            return $form;
        }

        // volá se po úspěšném odeslání formuláře
        public function loginFormSucceeded(UI\Form $form, $values)
        {
            try {
                $this->getUser()->login($values['name'], $values['password']);
                $myAuthorizator = new \MyAuthorizator();
		$this->getUser()->setAuthorizator($myAuthorizator);
                $this->redirect('Charge:');

            } catch (NS\AuthenticationException $e) {
                $this->flashMessage($e->getMessage(),"error");
                $this->redirect('Homepage:');
            } 
        
        }        
        
        
}
