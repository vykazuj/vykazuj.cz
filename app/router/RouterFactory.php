<?php

namespace App;

use Nette;
use Nette\Application\Routers\Route;
use Nette\Application\Routers\RouteList;


class RouterFactory
{
	use Nette\StaticClass;

	/**
	 * @return Nette\Application\IRouter
	 */
	public static function createRouter()
	{
		$router = new RouteList;
		$router[] = new Route('auth/logout', 'Auth:logout');
		$router[] = new Route('auth/callback', 'Auth:callback');
		$router[] = new Route('auth/<strategy>', 'Auth:auth');
		//$router[] = new Route('auth/<strategy>/oauth2callback', 'Auth:auth');
		$router[] = new Route('auth/<strategy>/oauth_callback', 'Auth:auth');
		$router[] = new Route('auth/<strategy>/int_callback', 'Auth:auth');
		$router[] = new Route('<presenter>/<action>', 'Homepage:default');
		return $router;
	}
}
