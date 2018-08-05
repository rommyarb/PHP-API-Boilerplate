<?php
use \Psr\Http\Message\ResponseInterface as Response;
use \Psr\Http\Message\ServerRequestInterface as Request;

require 'vendor/autoload.php';
include 'config.php';

// INIT SLIM APP
$app = new \Slim\App();

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
