<?php

namespace App\Presenters;

class AuthPresenter extends BasePresenter
{
	/** @var NetteOpauth\NetteOpauth */
	protected $opauth;

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
	public function actionAuth($strategy = NULL)
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

		// Here is a good place for transformation of 3rd part identities to your app identity.
		// Like pairing with your app accounts.

		$this->user->login($identity);
		$this->redirect("Homepage:default");
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