<?php
$app->container->singleton('LoginController', function () {
    return new LoginController();
});

$app->container->singleton('IdeasController', function () {
    return new IdeasController();
});

// routes
$app->get('/', function () use ($app) {
    $app->IdeasController->index();
})->name('home');

$app->map('/login', function () use ($app) {
    $app->LoginController->index();
})
->via('GET', 'POST')
->name('login');

$app->get('/logout', function () use ($app) {
    $app->LoginController->logout();
})->name('logout');

$app->map('/register', function () use ($app) {
    $app->LoginController->signUp();
})
->via('GET', 'POST')
->name('signup');

$app->map('/forgot', function () use ($app) {
    $app->LoginController->forgot();
})
->via('GET', 'POST')
->name('forgot_password');

$app->group('/account', function () use($app) {
    $app->map('/', function () use ($app) {
        //$app->LoginController->profile();
    })
    ->via('GET', 'POST')
    ->name('profile');

    $app->map('/settings', function () use ($app) {
        //$app->LoginController->settings();
    })
    ->via('GET', 'POST')
    ->name('settings');
});

$app->group('/ideas', function () use ($app) {
    $app->get('/latest', function () use($app) {
        //$app->IdeasController->latest();
    })->name('ideas.latest');

    $app->get('/mostrated', function () use($app) {
        //$app->IdeasController->mostrated();
    })->name('ideas.mostrated');
});

$app->group('/idea', function () use ($app) {
    $app->get('(/:id)', function ($id = null) use ($app) {
        $app->IdeasController->idea($id);
    })->name('idea');

    $app->post('save', function () use ($app) {
        $app->IdeasController->save();
    })->name('idea.save');
});