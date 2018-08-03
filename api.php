<?php
// error_reporting(0); // DISABLE ERROR AND WARNING REPORTS

use \Psr\Http\Message\ResponseInterface as Response;
use \Psr\Http\Message\ServerRequestInterface as Request;

require 'vendor/autoload.php';
include 'config.php';

// VERIFY TOKEN
$headers = getallheaders();
$auth = isset($headers['Authorization']) ? $headers['Authorization'] : null;
if ($auth == null) {
  throw404();
}
$auth = explode(' ', $auth);
$token = $auth[1];
$decoded = \Lindelius\JWT\JWT::decode($token);
if (!$decoded->verify($secret_key)) {
  throw404();
}

// INIT SLIM APP
$app = new \Slim\App();

// C R U D

// CREATE
$app->post('/create/{table_name}', function (Request $req, Response $res, array $args) {
  global $db;
  $table_name = $args['table_name'];
  $data = $req->getParsedBody();

  $q = $db->insert($table_name, $data);
  $arr = array();
  if ($q) {
    $arr['success'] = true;
  } else {
    $arr['success'] = false;
    $arr['msg'] = $db->getLastError();
  }
  return $res->withJson($arr);
});

// READ ALL
$app->get('/get/{table_name}', function (Request $req, Response $res, array $args) {
  global $db;
  $table_name = $args['table_name'];
  $rows = $db->get($table_name);
  return $res->withJson($rows);
});

// READ ONE
$app->get('/get_one/{table_name}/{id}', function (Request $req, Response $res, array $args) {
  global $db;
  $table_name = $args['table_name'];
  $id = $args['id'];

  $db->where('id', $id);
  $row = $db->getOne($table_name);
  return $res->withJson($row);
});

// (READ) SEARCH
$app->get('/search/{table_name}/{column_name}/{value}', function (Request $req, Response $res, array $args) {
  global $db;
  $table_name = $args['table_name'];
  $column_name = $args['column_name'];
  $value = $args['value'];

  $db->where($column_name, '%' . $value . '%', 'like');
  $results = $db->get($table_name);
  return $res->withJson($results);
});

// (READ) WHERE
$app->get('/get/{table_name}/{column_name}/{value}', function (Request $req, Response $res, array $args) {
  global $db;
  $db->where($args['column_name'], $args['value']);
  $results = $db->get($args['table_name']);
  return $res->withJson($results);
});

// UPDATE
$app->post('/update/{table_name}/{id}', function (Request $req, Response $res, array $args) {
  global $db;
  $table_name = $args['table_name'];
  $id = $args['id'];
  $data = $req->getParsedBody();

  $db->where('id', $id);
  $q = $db->update($table_name, $data);
  $arr = array();
  if ($q) {
    $arr['success'] = true;
  } else {
    $arr['success'] = false;
    $arr['msg'] = $db->getLastError();
  }
  return $res->withJson($arr);
});

// DELETE
$app->post('/delete/{table_name}/{id}', function (Request $req, Response $res, array $args) {
  global $db;
  $db->where('id', $args['id']);
  $q = $db->delete($args['table_name']);
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
