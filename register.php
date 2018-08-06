<?php
use \Psr\Http\Message\ResponseInterface as Response;
use \Psr\Http\Message\ServerRequestInterface as Request;

require 'vendor/autoload.php';
include 'config.php';

// INIT SLIM APP
$app = new \Slim\App();

$app->post('/register', function (Request $req, Response $res, array $args) {
  global $db, $secret_key;

  // get http request params
  $data = $req->parsedBody();
  $q = $db->insert('users', $data);
  $arr = array();
  if ($q) {
    $arr['success'] = true;
  } else {
    $arr['success'] = false;
    $arr['msg'] = $db->getLastError();
  }

  return $res->withJson($arr);
});

$app->run();
