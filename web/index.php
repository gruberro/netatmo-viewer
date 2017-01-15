<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Lokhman\Silex\Provider as ToolsProviders;

$app = new Silex\Application();

$app->register(new ToolsProviders\ConfigServiceProvider(), [
    'config.dir' => __DIR__ . '/../app/config',
]);

$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__ . '/../views',
));

$app->get('/', function() use($app) {
    $config = [];
    $config['client_id'] = $app['client_id'];
    $config['client_secret'] = $app['client_secret'];
    $config['username'] = $app['username'];
    $config['password'] = $app['password'];
    $config['scope'] = Netatmo\Common\NAScopes::SCOPE_READ_STATION;

    $client = new Netatmo\Clients\NAWSApiClient($config);

    $client->getAccessToken();

    return $app['twig']->render('dashboard.html.twig', array(
        'data' => $client->getData(),
    ));
});

$app->run();
