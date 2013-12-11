<?php

class Application {
	public $app;

	public function __construct(\Slim\Slim $slim = null)
	{
		$this->app = !empty($slim) ? $slim : \Slim\Slim::getInstance();
	}

	public function run()
	{
		$this->app->run();
	}
}