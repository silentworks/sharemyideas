<?php

use Cartalyst\Sentry\Throttling\UserBannedException;
use Cartalyst\Sentry\Throttling\UserSuspendedException;
use Cartalyst\Sentry\Users\UserExistsException;
use Cartalyst\Sentry\Users\UserNotActivatedException;
use Cartalyst\Sentry\Users\UserNotFoundException;

class LoginController extends Controller {

	public function index()
	{
		if ($this->app->request()->isPost()) {
            $v = $this->validator($this->post());
            $v->rule('required', array('email', 'password'));
            $v->rule('length', 'email', 4, 22);
            $v->rule('length', 'password', 3, 11);
            if ($v->validate()) {
                try {
                    $credentials = array(
                        'email' => $this->post('email'),
                        'password' => $this->post('password'),
                    );
                    $remember = $this->post('remember');
                    $user = Sentry::authenticate($credentials, $remember);

                    if ($user) {
                        $this->successFlash('Your login was successful. Please wait while we redirect you...');
                        $this->redirect('home');
                    }
                } catch (UserNotFoundException $e) {
                    $this->errorFlash('Email and Password provided did not match any records.');
                } catch (UserNotActivatedException $e) {
                    $this->errorFlash('User is not activated.');
                }
                catch (UserSuspendedException $e) {
                    $this->errorFlash('User is currently suspended.');
                }
                catch (UserBannedException $e) {
                    $this->errorFlash('User is currently banned.');
                }
            }
            $this->app->flashNow('error', $this->errorOutput($v->errors()));
		}
		$this->render('login/index');
	}

	public function signUp()
	{
		if ($this->app->request()->isPost()) {
            $v = $this->validator($this->post());
            $v->rule('required', array('email', 'password'));
            $v->rule('email', 'email');
            $v->rule('length', 'password', 3, 11);
            if ($v->validate()) {
                try {
                    $credentials = array(
                        'email' => $this->post('email'),
                        'password' => $this->post('password'),
                    );

                    $user = Sentry::register($credentials, true);

                    if ($user) {
                        /* Login right after signup */
                        Sentry::authenticate($credentials);

                        $this->successFlash('Your registration was successful.');
                        $this->redirect('home');
                    } else {
                        $this->errorFlash('User information was not updated successfully.');
                    }
                } catch (UserExistsException $e) {
                    $this->errorFlash('User with this login already exists.');
                } catch (UserNotFoundException $e) {
                    $this->errorFlash('User was not found.');
                }
            }
            $this->app->flashNow('error', $this->errorOutput($v->errors()));
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
		if (!Sentry::check()) {
			$this->redirect('/', false);
		}
		$this->render('login/forgot');
	}
}