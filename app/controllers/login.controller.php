<?php
// Validation Library
use Respect\Validation\Validator as v;	

class LoginController extends Controller {

	public function index()
	{
		if ($this->app->request()->isPost()) {
            try {
				v::alnum()
					->noWhitespace()
                	->length(4,22)
                	->check($this->post('username'));

                try {
                	v::alnum()
                	->length(3,11)
                	->check($this->post('password'));

                	if ($this->auth->login($this->post('username'), $this->post('password'))) {
                        $this->app->flash('info', 'Your login was successfull');
                        $this->redirect('home');
                    }
                } catch (\InvalidArgumentException $e) {
                	$this->app->flashNow('error', $e->setName('Password')->getMainMessage());
                }
            } catch (\InvalidArgumentException $e) {
            	$this->app->flashNow('error', $e->setName('Username')->getMainMessage());
            }
		}
		$this->render('login/index');
	}

	public function signup()
	{
		if ($this->app->request()->isPost()) {
			$u = Model::factory('Users')->create();
			$u->name = $this->post('name');
			$u->email = $this->post('email');
			$u->username = $this->post('username');
			$u->password = $this->auth->getProvider()->hashPassword($this->post('password'));
			$u->ip_address = $this->app->request()->getIp();
			$u->save();
			
			$this->app->flash('info', 'Your registration was successfull');
			$this->redirect('home');
		}
		$this->render('login/signup');
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