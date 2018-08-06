<?php
use \Psr\Http\Message\ResponseInterface as Response;
use \Psr\Http\Message\ServerRequestInterface as Request;

require 'vendor/autoload.php';
include 'config.php';

// INIT SLIM APP
$app = new \Slim\App();

// C R U D

// CREATE
$app->post('/create/{table_name}', function (Request $req, Response $res, array $args) {
  verifyToken();
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
$app->post('/get/{table_name}', function (Request $req, Response $res, array $args) {
  verifyToken();
  global $db;
  $table_name = $args['table_name'];
  $rows = $db->get($table_name);
  return $res->withJson($rows);
});

// READ ONE
$app->post('/get_one/{table_name}/{id}', function (Request $req, Response $res, array $args) {
  verifyToken();
  global $db;
  $table_name = $args['table_name'];
  $id = $args['id'];

  $db->where('id', $id);
  $row = $db->getOne($table_name);
  return $res->withJson($row);
});

// (READ) SEARCH 'LIKE'
$app->post('/search/{table_name}/{column_name}/{value}', function (Request $req, Response $res, array $args) {
  verifyToken();
  global $db;
  $table_name = $args['table_name'];
  $column_name = $args['column_name'];
  $value = $args['value'];

  $db->where($column_name, '%' . $value . '%', 'like');
  $results = $db->get($table_name);
  return $res->withJson($results);
});

// (READ) WHERE '='
$app->post('/get/{table_name}/{column_name}/{value}', function (Request $req, Response $res, array $args) {
  verifyToken();
  global $db;
  $db->where($args['column_name'], $args['value']);
  $results = $db->get($args['table_name']);
  return $res->withJson($results);
});

// UPDATE
$app->post('/update/{table_name}/{id}', function (Request $req, Response $res, array $args) {
  verifyToken();
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
  verifyToken();
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

//////////////////////////////////////////////////////////////////////////////////////////

// REGISTER
$app->post('/register', function (Request $req, Response $res, array $args) {
  global $db, $secret_key;
  $arr = array();

  // get http request params
  $data = $req->parsedBody();
  $username = $data['username'];

  // check if username exists
  $db->where('username', $username);
  $username_exists = $db->getOne('users')->count();
  if ($username_exists) {
    $arr['success'] = false;
    $arr['msg'] = 'username already exist';
  } else {
    $hashed_password = password_hash($data['password'], PASSWORD_DEFAULT);
    $insertData = array(
      'fullname' => $data['fullname'],
      'username' => $username,
      'gender' => $data['gender'],
      'hashed_password' => $hashed_password,
      'created_at' => date(),
    );
    $q = $db->insert('users', $data);
    if ($q) {
      $arr['success'] = true;
    } else {
      $arr['success'] = false;
      $arr['msg'] = $db->getLastError();
    }
  }
  return $res->withJson($arr);
});

//////////////////////////////////////////////////////////////////////////////////////////////

// LOGIN
$app->post('/login', function (Request $req, Response $res, array $args) {
  global $db, $secret_key;

  // HTTP POST Request with params 'username' & 'password'
  $data = $req->parsedBody();
  $username = $data['username'];
  $password = $data['password'];

  $db->where('username', $username);
  $user = $db->getOne('users'); // GET user details from table 'users'
  $arr = array(); //prepare return array
  if ($user > 0) {
    $hashed_password = $user['hashed_password'];
    if (password_verify($password, $hashed_password)) {
      // username & password matched!
      $jwt = new \Lindelius\JWT\JWT();
      $jwt->exp = time() + (60 * 60 * 2); // expire after 2 hours
      $jwt->iat = time();

      // YOU CAN ALSO PUT SOME INFO, LIKE:
      $jwt->user_id = $user->id;
      $jwt->is_admin = $user->is_admin;

      // AND THEN GENERATE THE TOKEN:
      $generated_token = $jwt->encode($secret_key);

      $arr['success'] = true;
      $arr['token'] = $generated_token;
    } else {
      $arr['success'] = false;
      $arr['msg'] = 'Wrong password!';
    }
  } else {
    $arr['success'] = false;
    $arr['msg'] = 'Username is not registered';
  }

  return $res->withJson($arr);
});

$app->run();
