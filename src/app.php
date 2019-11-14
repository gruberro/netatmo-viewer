<?php

use Lokhman\Silex\Provider as ToolsProviders;
use Symfony\Component\HttpFoundation\Request;

$app = new Silex\Application();

$app->register(new ToolsProviders\ConfigServiceProvider(), [
    'config.dir' => __DIR__ . '/../app/config',
]);

$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__ . '/../views',
));

$app->get('/', function () use ($app) {
    $config = [];
    $config['client_id'] = $app['client_id'];
    $config['client_secret'] = $app['client_secret'];
    $config['username'] = $app['username'];
    $config['password'] = $app['password'];
    $config['scope'] = Netatmo\Common\NAScopes::SCOPE_READ_STATION;

    $client = new Netatmo\Clients\NAWSApiClient($config);

    $client->getAccessToken();

    if (count($app['stationNames']) === 0 && isset($app['stationName'])) {
        $stationNames = [$app['stationName']];
    } else {
        $stationNames = $app['stationNames'];
    }

    $mainStations = [];
    $modules = [];
    foreach ($client->getData()['devices'] as $device) {
        if (!in_array($device['station_name'], $stationNames)) {
            continue;
        }

        $mainStations[] = $device;

        foreach ($device['modules'] as $module) {
            if (!in_array($module['type'], ['NAModule1', 'NAModule4'])) {
                continue;
            }

            $modules[] = $module;
        }
    }

    usort($modules, function ($a, $b) {
        return strcmp($a['type'], $b['type']) * -1;
    });

    return $app['twig']->render('dashboard.html.twig', array(
        'data' => array_merge($mainStations, $modules),
        'stationNames' => $stationNames,
    ));
});

$app->error(function (\Exception $e, Request $request, $code) use ($app) {
    if ($app['debug']) {
        return;
    }

    return $app['twig']->render('error.html.twig', array(
        'exception' => $e,
        'request' => $request,
        'code' => $code,
    ));
});

return $app;
