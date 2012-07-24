<?php

class LoginController extends Controller {
	public function __construct()
	{
		parent::__construct();
	}

	public function index()
	{
		if ($this->app->request()->isPost()) {
			if ($this->auth->login($this->post('username'), $this->post('password'))) {
				$this->app->flash('info', 'Your login was successfull');
				$this->redirect('home');
			}
			$this->app->flashNow('error', 'Username or Password incorrect.');
		}
		$this->render('login/index');
	}

	public function logout()
	{
		$this->app->flash('info', 'Come back sometime soon');
		$this->auth->logout(true);
		$this->redirect('login');
	}

	public function forgot()
	{
		if ($this->auth->loggedIn()) {
			$this->redirect('/', false);
		}
		$this->render('login/forgot');
	}
}