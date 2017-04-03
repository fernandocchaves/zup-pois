<?php

error_reporting(E_ALL);
ini_set("display_errors", 1);

require_once __DIR__.'/vendor/autoload.php';

use Dflydev\Provider\DoctrineOrm\DoctrineOrmServiceProvider;
use Silex\Application;
use Silex\Provider\DoctrineServiceProvider;
use Symfony\Component\HttpFoundation\Request;
use ZupPois\Service\PoisService;

$app = new Application();

$app->register(new DoctrineServiceProvider(), array(
    'db.options' => array(
        'driver'   => 'pdo_sqlite',
        'path'     => __DIR__.'/database.db',
    ),
));

$app->register(new DoctrineOrmServiceProvider(), array(
    'orm.proxies_dir' => sys_get_temp_dir() . '/' . md5(__DIR__ . getenv('APPLICATION_ENV')),
    'orm.em.options' => array(
        'mappings' => array(
            array(
                'type' => 'annotation',
                'use_simple_annotation_reader' => false,
                'namespace' => 'ZupPois\Entities',
                'path' => __DIR__ . '/src'
            )
        )
    ),
    'orm.proxies_namespace' => 'EntityProxy',
    'orm.auto_generate_proxies' => true,
    'orm.default_cache' => 'array'
));

$app->register(new JDesrosiers\Silex\Provider\JmsSerializerServiceProvider(), array(
    "serializer.srcDir" => __DIR__ . "/vendor/jms/serializer/src",
));

$app['pois.service'] = function () use ($app) {
    return new PoisService($app['orm.em']);
};

$app->before(function (Request $request) use ($app) {
    if (false === strpos($request->headers->get('Content-Type'), 'application/json')) {
        $error = array('message' => 'Content-Type inválido. Requerido application/json');
        return $app->json($error, 400);
    }
});

$app->mount('/', new ZupPois\Controller\IndexController);

$app['debug'] = true;
$app->run();