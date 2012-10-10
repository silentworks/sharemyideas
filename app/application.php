<?php

class Application {
	public $app;

	public function __construct(Slim\Slim $slim = null)
	{
		$this->app = !empty($slim) ? $slim : \Slim\Slim::getInstance();

		/*
		 * ORM
		 * initialize connection and database name
		 */
		$this->db = ORM::get_db();

		$this->setup();
	}

	public function setup()
	{
		// Users Table
		$this->db->exec("
			CREATE TABLE IF NOT EXISTS `users` (
			  `id` int(11) NOT NULL AUTO_INCREMENT,
			  `username` varchar(120) DEFAULT NULL,
			  `password` varchar(255) NOT NULL,
			  `name` varchar(180) DEFAULT NULL,
			  `email` varchar(220) DEFAULT NULL,
			  `ip_address` varchar(16) NOT NULL,
			  `active` int(11) DEFAULT '0',
			  PRIMARY KEY (`id`)
			) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
		");

		// Ideas Table
		$this->db->exec("
			CREATE TABLE IF NOT EXISTS `ideas` (
			  `id` int(11) NOT NULL AUTO_INCREMENT,
			  `title` varchar(120) DEFAULT NULL,
			  `content` text,
			  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
			  `ip_address` varchar(16) NOT NULL,
			  `user_id` int(11) NOT NULL,
			  `display` int(11) DEFAULT '1',
			  PRIMARY KEY (`id`)
			) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
		");

		// Comments Table
		$this->db->exec("
			CREATE TABLE IF NOT EXISTS `comments` (
			  `id` int(11) NOT NULL AUTO_INCREMENT,
			  `comment` text NOT NULL,
			  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
			  `idea_id` int(11) NOT NULL,
			  `user_id` int(11) NOT NULL,
			  `ip_address` varchar(16) NOT NULL,
			  PRIMARY KEY (`id`)
			) ENGINE=MyISAM DEFAULT CHARSET=utf8;
		");

		// Comments Table
		$this->db->exec("
			CREATE TABLE IF NOT EXISTS `ratings` (
			  `id` varchar(11) NOT NULL,
			  `total_votes` int(11) NOT NULL DEFAULT '0',
			  `total_value` int(11) NOT NULL DEFAULT '0',
			  `vote_limit` int(11) NOT NULL DEFAULT '0',
			  `used_ips` longtext,
			  PRIMARY KEY (`id`)
			) ENGINE=MyISAM DEFAULT CHARSET=utf8;
		");
	}

	public function run()
	{
		$this->app->run();
	}
}