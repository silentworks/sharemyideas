<?php
use Valitron\Validator;

abstract class Controller extends Application
{
    /* @var \Cartalyst\Sentry\Sentry $auth */
    protected $auth;

	public function __construct()
	{
		parent::__construct();

        $this->user = Sentry::getUser();

        $this->isLoggedIn = Sentry::check();
	}

	public function redirect($name, $routeName = true)
	{
		$url = $routeName ? $this->app->urlFor($name) : $name;
		$this->app->redirect($url);
	}

	public function get($value = null)
	{
		return $this->app->request()->get($value);
	}

	public function post($value = null)
	{
		return $this->app->request()->post($value);
	}

	public function response($body)
	{
		$response = $this->app->response();
		$response['Content-Type'] = 'application/json';
		$response->body(json_encode(array($body)));
	}

	public function render($template, $data = array(), $status = null)
	{
		if ($len = strpos(strrev($template), '.')) {
			$template = substr( $template, 0, -($len+1) );
		}
		$this->app->view()->appendData(array(
            'auth' => $this->auth,
            'isLoggedIn' => $this->isLoggedIn
        ));
		$this->app->render($template . EXT, $data, $status);
	}

	protected function validator($data, $fields = array(), $lang = 'en')
	{
		return new Validator($data, $fields, $lang, VALIDATION_LANG_PATH);
	}

    public function successFlash($msg = '')
    {
        $this->app->flash('info', $msg);
    }

    public function errorFlash($msg = '')
    {
        $this->app->flash('error', $msg);
    }

	protected function errorOutput(array $errors = array(), $single = false)
	{
		$outputErrors = array();
		foreach ($errors as $key => $value) {
			if ($single) {
				$outputErrors[$key] = ucfirst($key) . ' ' . $value[0];
			} else {
				$outputErrors[] = ucfirst($key) . ' ' . $value[0];
			}
		}
		return $outputErrors;
	}
}