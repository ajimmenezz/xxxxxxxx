<?php

defined('BASEPATH') or exit('No direct script access allowed');

$active_group = 'adist3';
$query_builder = TRUE;

$isProduction = false;
$host = $_SERVER['SERVER_NAME'];
if ($host === 'siccob.solutions' || $host === 'www.siccob.solutions') {
    $isProduction = true;
}

$isSandbox = strpos($_SERVER['SERVER_NAME'], 'sandbox.siccob.solutions');

$connectionParamsADV2 = [
    'dsn' => '',
    'hostname' => 'siccob.solutions',
    'username' => 'prod_usr',
    'password' => 'adist01.',
    'database' => 'adist_prod',
    'dbdriver' => 'mysqli',
    'dbprefix' => '',
    'pconnect' => FALSE,
    'db_debug' => (ENVIRONMENT !== 'production'),
    'cache_on' => FALSE,
    'cachedir' => '',
    'char_set' => 'utf8',
    'dbcollat' => 'utf8_general_ci',
    'swap_pre' => '',
    'encrypt' => FALSE,
    'compress' => FALSE,
    'stricton' => FALSE,
    'failover' => array(),
    'save_queries' => TRUE
];

if ($isSandbox || !$isProduction) {
    $connectionParamsADV2['database'] = 'adist_usertest';
}

$db['adist3'] = $connectionParamsADV2;

$db['pruebasAdist2'] = $connectionParamsADV2;

$db['adist2'] = $connectionParamsADV2;

if ($isSandbox !== FALSE) {
    $database = 'adistv3_sandbox';
} else {
    $database = 'adist3_prod';
}

$db['pruebas'] = array(
    'dsn' => '',
    'hostname' => 'localhost',
    'username' => 'prod3_usr',
    'password' => 'S1cc0bS.',
    'database' => $database,
    'dbdriver' => 'mysqli',
    'dbprefix' => '',
    'pconnect' => FALSE,
    'db_debug' => (ENVIRONMENT !== 'production'),
    'cache_on' => FALSE,
    'cachedir' => '',
    'char_set' => 'utf8',
    'dbcollat' => 'utf8_general_ci',
    'swap_pre' => '',
    'encrypt' => FALSE,
    'compress' => FALSE,
    'stricton' => FALSE,
    'failover' => array(),
    'save_queries' => TRUE
);

$db['SAE'] = array(
    'dsn' => '',
    'hostname' => '192.168.0.40',
    'username' => 'sa',
    'password' => 'SAE$2016',
    'database' => 'Empresa03',
    'dbdriver' => 'sqlsrv',
    'dbprefix' => '',
    'pconnect' => FALSE,
    'db_debug' => (ENVIRONMENT !== 'production'),
    'cache_on' => FALSE,
    'cachedir' => '',
    'char_set' => 'utf8',
    'dbcollat' => 'utf8_general_ci',
    'swap_pre' => '',
    'encrypt' => FALSE,
    'compress' => FALSE,
    'stricton' => FALSE,
    'failover' => array(),
    'save_queries' => TRUE,
    'port' => 1433
);

$db['SAE7'] = array(
    'dsn' => '',
    'hostname' => '192.168.0.7, 55555', //Produccion
    //    'hostname' => '192.168.0.35, 51051', //Pruebas
    'username' => 'adist',
    'password' => 'course3Goose,',
    'database' => 'SAE7EMPRESA3',
    'dbdriver' => 'sqlsrv',
    'dbprefix' => '',
    'pconnect' => FALSE,
    'db_debug' => (ENVIRONMENT !== 'production'),
    'cache_on' => FALSE,
    'cachedir' => '',
    'char_set' => 'utf8',
    'dbcollat' => 'utf8_general_ci',
    'swap_pre' => '',
    'encrypt' => FALSE,
    'compress' => FALSE,
    'stricton' => FALSE,
    'failover' => array(),
    'save_queries' => TRUE
);

$pwdGapsi = 'S1cc0b';
if ($isProduction !== FALSE) {
    $hostNameGapsi = '192.168.0.30, 50742';
    $userNameGapsi = 'sa';
} else {
    $hostNameGapsi = '127.0.0.1, 50420';
    $userNameGapsi = 'sagapsi';
}

$db['Gapsi'] = array(
    'dsn' => '',
    'hostname' => $hostNameGapsi,
    'username' => $userNameGapsi,
    'password' => $pwdGapsi,
    'database' => 'DB_9DEFD2_dbGastosSiccob',
    'dbdriver' => 'sqlsrv',
    'dbprefix' => '',
    'pconnect' => FALSE,
    'db_debug' => (ENVIRONMENT !== 'production'),
    'cache_on' => FALSE,
    'cachedir' => '',
    'char_set' => 'utf8',
    'dbcollat' => 'utf8_general_ci',
    'swap_pre' => '',
    'encrypt' => FALSE,
    'compress' => FALSE,
    'stricton' => FALSE,
    'failover' => array(),
    'save_queries' => TRUE
);

$db['Sicsa'] = array(
    'dsn' => '',
    'hostname' => '192.168.0.30, 50742',
    'username' => 'sa',
    'password' => 'S1cc0b',
    'database' => 'DB_SICSA',
    'dbdriver' => 'sqlsrv',
    'dbprefix' => '',
    'pconnect' => FALSE,
    'db_debug' => (ENVIRONMENT !== 'production'),
    'cache_on' => FALSE,
    'cachedir' => '',
    'char_set' => 'utf8',
    'dbcollat' => 'utf8_general_ci',
    'swap_pre' => '',
    'encrypt' => FALSE,
    'compress' => FALSE,
    'stricton' => FALSE,
    'failover' => array(),
    'save_queries' => TRUE
);
