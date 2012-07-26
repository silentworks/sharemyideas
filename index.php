<?php
require_once "vendor/autoload.php";
require_once 'vendor/jamie/idiorm/idiorm.php';
require_once 'vendor/jamie/paris/paris.php';

require_once "config.php";
require_once "app/application.php";

// Controllers
require_once "app/controller.php";
require_once "app/controllers/login.controller.php";
require_once "app/controllers/ideas.controller.php";

// Models
require_once "app/models/users.model.php";
require_once "app/models/ideas.model.php";
require_once "app/models/comments.model.php";

define('APPLICATION', 'Share My Ideas');
define('VERSION', '1.0.0');
define('EXT', '.twig');

$app = new Slim(array(
	'view' => 'View_Twig',
));

// Authentication
$config = array(
    'auth.type' => 'form',
    'login.url' => '/login',
    'security.urls' => array(
        array('path' => '/comment/'),
        array('path' => '/api/.+'),
        array('path' => '/account/'),
    ),
);

$authConfig = array(
    'provider' => 'PDO',
    'dsn' => sprintf('mysql:host=%s;dbname=%s', $db[$activeGroup]['hostname'], $db[$activeGroup]['database']),
    'dbuser' => $db[$activeGroup]['username'],
    'dbpass' => $db[$activeGroup]['password'],
);

$strong = new Strong($authConfig);
$app->add(new Middleware_Auth_Strong($config, $strong));

// Asset Management
$app->view()->twigExtensions = array(
    'Twig_Extensions_Slim',
);

$c = new Application($app);

$loginController = new LoginController();
$ideasController = new IdeasController();

// routes
$c->app->get('/', array($ideasController, 'index'))->name('home');
$c->app->map('/login/', array($loginController, 'index'))->via('GET', 'POST')->name('login');
$c->app->map('/register/', array($loginController, 'signup'))->via('GET', 'POST')->name('signup');
$c->app->map('/account/', array($loginController, 'profile'))->via('GET', 'POST')->name('profile');
$c->app->map('/account/settings/', array($loginController, 'settings'))->via('GET', 'POST')->name('settings');
$c->app->map('/forgot/', array($loginController, 'forgot'))->via('GET', 'POST')->name('forgot_password');
$c->app->get('/logout/', array($loginController, 'logout'))->name('logout');

$c->app->get('/idea(/:id)', array($ideasController, 'idea'))->name('idea');
$c->app->post('/idea/save', array($ideasController, 'save'))->name('idea_save');
$c->app->get('/ideas/latest', array($ideasController, 'latest'))->name('latest_ideas');
$c->app->get('/ideas/mostrated', array($ideasController, 'mostrated'))->name('mostrated_ideas');

// Api Routes
//$c->app->get('/api/ideas', array($ideasController, 'allIdeas'));

$c->run();