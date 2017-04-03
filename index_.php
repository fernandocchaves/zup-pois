<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);

require_once __DIR__.'/../vendor/autoload.php';

use Dflydev\Provider\DoctrineOrm\DoctrineOrmServiceProvider;
use MicroFlyCMS\Service\UserService;
use Silex\Application;
use Silex\Provider\DoctrineServiceProvider;
use Symfony\Component\Security\Http\Firewall;
use Symfony\Component\Yaml\Parser;
use Silex\Provider\SessionServiceProvider;

$app = new Application();
$yaml = new Parser();

$configs = $yaml->parse(file_get_contents( __DIR__ . '/../config/parameters.yml'));
$database = $configs['database']['default'];
$email = $configs['email']['default'];

$app->register(new Silex\Provider\SecurityServiceProvider(), array(
    'security.firewalls' => array()
));

$app->register(new Silex\Provider\VarDumperServiceProvider());
$app->register(new SessionServiceProvider());

$app->register(new Silex\Provider\SwiftmailerServiceProvider());

$app['swiftmailer.options'] = array(
    'host' => $email['host'],
    'port' => $email['port'],
    'username' => $email['username'],
    'password' => $email['password'],
    'encryption' => $email['encryption'],
    'auth_mode' => $email['auth_mode']
);

$app->register(new DoctrineServiceProvider(), array(
    'db.options' => array (
        'driver'    => $database['driver'],
        'host'      => $database['host'],
        'dbname'    => $database['dbname'],
        'user'      => $database['user'],
        'password'  => $database['pass'],
        'charset'   => $database['charset']
    ),
));

$app->register(new DoctrineOrmServiceProvider(), array(
    'orm.proxies_dir' => sys_get_temp_dir() . '/' . md5(__DIR__ . getenv('APPLICATION_ENV')),
    'orm.em.options' => array(
        'mappings' => array(
            array(
                'type' => 'annotation',
                'use_simple_annotation_reader' => false,
                'namespace' => 'MicroFlyCMS\Model',
                'path' => __DIR__ . '/src'
            )
        )
    ),
    'orm.proxies_namespace' => 'EntityProxy',
    'orm.auto_generate_proxies' => true,
    'orm.default_cache' => 'array'
));

$app['debug'] = true;
$app['session.storage.handler'] = null;

$app['user.service'] = function () use ($app){
    return new UserService($app['orm.em']);
};


$app->mount('/', new MicroFlyCMS\Controller\IndexController);

$app['debug'] = true;
$app->run();