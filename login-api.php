<?php
use \Psr\Http\Message\ResponseInterface as Response;
use \Psr\Http\Message\ServerRequestInterface as Request;

require 'vendor/autoload.php';
include 'config.php';

// INIT SLIM APP
$app = new \Slim\App();

$app->post('/login', function (Request $req, Response $res, array $args) {
  global $db, $secret_key;
  $login_success = false;

  // WRITE YOUR LOGIN CODE HERE:
  // ...
  // ...
  // ...

  $return_array = array();
  if ($login_success) {
    $jwt = new \Lindelius\JWT\JWT();
    $jwt->exp = time() + (60 * 60 * 2); // expire after 2 hours
    $jwt->iat = time();

    // YOU CAN ALSO PUT SOME INFO, LIKE:
    $jwt->user_id = 1;
    $jwt->is_admin = true;

    // AND THEN GENERATE THE TOKEN:
    $generated_token = $jwt->encode($secret_key);

    $return_array['success'] = true;
    $return_array['token'] = $generated_token;
  } else {
    $return_array['success'] = false;
    $return_array['msg'] = 'Wrong username or password!';
  }

  return $res->withJson($return_array);
});
