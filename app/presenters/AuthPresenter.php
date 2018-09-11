<?php

namespace App\Presenters;

use NetteOpauth;

class AuthPresenter extends BasePresenter{
	/** @var NetteOpauth\NetteOpauth */
	protected $opauth;
        private $database;
        
        function __construct(\Nette\Database\Context $database)
        {
            $this->database = $database;
        }

	/**
	 * @param NetteOpauth\NetteOpauth
	 */
	public function injectOpauth(NetteOpauth\NetteOpauth $opauth)
	{
		$this->opauth = $opauth;
	}

	/**
	 * Redirection method to oauth provider
	 *
	 * @param string|NULL $strategy strategy used depends on selected provider - 'fake' for localhost testing
	 */
	public function actionAuth($strategy)
	{
		$this->opauth->auth($strategy);
	}

	/**
	 * @param string
	 */
	public function actionCallback($strategy)
	{
		if ($strategy === NULL) {
			$this->flashMessage("Authentication failed.", "danger");
			$this->redirect('Homepage:default');
		}
		$identity = $this->opauth->callback($strategy);
                        $this->flashMessage($strategy, "info");
                        
                foreach($identity->data as $key => $value){
                    if(!is_array($value)){
                        //$this->flashMessage($key." = ".$value, "info");
                    }
                }
                
                $myRegistrator = new \MyRegistrator($this->database);
                
                if(!$myRegistrator->isExternalRegistered(strtolower($strategy), $identity->getId())){
                    $answer = $myRegistrator->registerFromExternalSource($identity, strtolower($strategy));
                }

		// Here is a good place for transformation of 3rd part identities to your app identity.
		// Like pairing with your app accounts.

		$this->user->loginExternal($strategy, $identity->getId());
		$this->redirect("Charge:default");
	}

	/**
	 * Basic logout action - feel free to use your own in different presenter
	 */
	public function actionLogout()
	{
		$this->getUser()->logout(TRUE);
		$this->redirect("Homepage:default");
	}
}
