<?php

// /$$$$$$$  /$$   /$$ /$$$$$$$         /$$$$$$  /$$$$$$$  /$$$$$$       /$$$$$$$   /$$$$$$  /$$$$$$ /$$       /$$$$$$$$ /$$$$$$$  /$$$$$$$  /$$        /$$$$$$  /$$$$$$$$ /$$$$$$$$
// | $$__  $$| $$  | $$| $$__  $$       /$$__  $$| $$__  $$|_  $$_/      | $$__  $$ /$$__  $$|_  $$_/| $$      | $$_____/| $$__  $$| $$__  $$| $$       /$$__  $$|__  $$__/| $$_____/
// | $$  \ $$| $$  | $$| $$  \ $$      | $$  \ $$| $$  \ $$  | $$        | $$  \ $$| $$  \ $$  | $$  | $$      | $$      | $$  \ $$| $$  \ $$| $$      | $$  \ $$   | $$   | $$      
// | $$$$$$$/| $$$$$$$$| $$$$$$$/      | $$$$$$$$| $$$$$$$/  | $$        | $$$$$$$ | $$  | $$  | $$  | $$      | $$$$$   | $$$$$$$/| $$$$$$$/| $$      | $$$$$$$$   | $$   | $$$$$   
// | $$____/ | $$__  $$| $$____/       | $$__  $$| $$____/   | $$        | $$__  $$| $$  | $$  | $$  | $$      | $$__/   | $$__  $$| $$____/ | $$      | $$__  $$   | $$   | $$__/   
// | $$      | $$  | $$| $$            | $$  | $$| $$        | $$        | $$  \ $$| $$  | $$  | $$  | $$      | $$      | $$  \ $$| $$      | $$      | $$  | $$   | $$   | $$      
// | $$      | $$  | $$| $$            | $$  | $$| $$       /$$$$$$      | $$$$$$$/|  $$$$$$/ /$$$$$$| $$$$$$$$| $$$$$$$$| $$  | $$| $$      | $$$$$$$$| $$  | $$   | $$   | $$$$$$$$
// |__/      |__/  |__/|__/            |__/  |__/|__/      |______/      |_______/  \______/ |______/|________/|________/|__/  |__/|__/      |________/|__/  |__/   |__/   |________/

// /$$                                                                                                     /$$      
// | $$                                                                                                    | $$      
// | $$$$$$$  /$$   /$$        /$$$$$$   /$$$$$$  /$$$$$$/$$$$  /$$$$$$/$$$$  /$$   /$$  /$$$$$$   /$$$$$$ | $$$$$$$ 
// | $$__  $$| $$  | $$       /$$__  $$ /$$__  $$| $$_  $$_  $$| $$_  $$_  $$| $$  | $$ |____  $$ /$$__  $$| $$__  $$
// | $$  \ $$| $$  | $$      | $$  \__/| $$  \ $$| $$ \ $$ \ $$| $$ \ $$ \ $$| $$  | $$  /$$$$$$$| $$  \__/| $$  \ $$     https://github.com/rommyarb
// | $$  | $$| $$  | $$      | $$      | $$  | $$| $$ | $$ | $$| $$ | $$ | $$| $$  | $$ /$$__  $$| $$      | $$  | $$
// | $$$$$$$/|  $$$$$$$      | $$      |  $$$$$$/| $$ | $$ | $$| $$ | $$ | $$|  $$$$$$$|  $$$$$$$| $$      | $$$$$$$/
// |_______/  \____  $$      |__/       \______/ |__/ |__/ |__/|__/ |__/ |__/ \____  $$ \_______/|__/      |_______/ 
//            /$$  | $$                                                       /$$  | $$                              
//           |  $$$$$$/                                                      |  $$$$$$/                              
//            \______/                                                        \______/                               


// _____ _____ _____ _____ _____ _____ _____ _____ _____ _____ _____ _____ _____ _____ _____ _____ _____ _____ _____ _____ _____ _____ _____ _____ _____ _____ _____ _____ 
// \____\\____\\____\\____\\____\\____\\____\\____\\____\\____\\____\\____\\____\\____\\____\\____\\____\\____\\____\\____\\____\\____\\____\\____\\____\\____\\____\\____\



// header("Access-Control-Allow-Origin: *"); // if you want to allow different domain request

// use PHPMailer\PHPMailer\Exception; // if you want to use PHPMailer exception
use PHPMailer\PHPMailer\PHPMailer;
use \Psr\Http\Message\ResponseInterface as Response;
use \Psr\Http\Message\ServerRequestInterface as Request;

require 'vendor/autoload.php';
include 'config.php';

$app = new \Slim\App();

// C R U D :

// CREATE (INSERT)
$app->post('/insert/{table_name}', function (Request $req, Response $res, array $args) {
  verifyToken();
  global $db;
  $table_name = $args['table_name'];
  $data = $req->getParsedBody();

  $arr = [];
  try {
    $db->$table_name[] = $data;
    $arr['success'] = true;
  } catch (Exception $e) {
    $arr['success'] = false;
    $arr['msg'] = $e;
  } finally {
    return $res->withJson($arr);
  }
});

// READ ALL
$app->post('/get/{table_name}', function (Request $req, Response $res, array $args) {
  // if you're using auth to get access:
  // verifyToken();

  global $db, $table_users;
  $table_name = $args['table_name'];

  // if you want to protect users table:
  // if ($table_name == $table_users) {
  //   throw401();
  // }

  $rows = [];
  try {
    $rows = $db->$table_name->select()->run();
  } catch (Exception $e) {
    // do nothing
  } finally {
    return $res->withJson($rows);
  }
});

// (READ) WHERE '='
$app->post('/get/{table_name}/{column_name}/{value}', function (Request $req, Response $res, array $args) {
  verifyToken();
  global $db;
  $table_name = $args['table_name'];
  $column_name = $args['column_name'];
  $value = $args['value'];

  $rows = [];
  try {
    $rows = $db->$table_name
      ->select()
      ->by($column_name, strtolower($value))
      ->run();
  } catch (Exception $e) {
    // do nothing
  } finally {
    return $res->withJson($rows);
  }
});

// READ ONE (by id)
$app->post('/get_one/{table_name}/{id}', function (Request $req, Response $res, array $args) {
  verifyToken();
  global $db;
  $table_name = $args['table_name'];
  $id = $args['id'];

  $row = [];
  try {
    $row = $db->$table_name
      ->select()
      ->one()
      ->by('id', $id)
      ->run();
  } catch (Exception $e) {
    // do nothing
  } finally {
    return $res->withJson($row);
  }
});

// (READ) SEARCH 'LIKE'
$app->post('/search/{table_name}/{column_name}/{value}', function (Request $req, Response $res, array $args) {
  verifyToken();
  global $db;
  $table_name = $args['table_name'];
  $column_name = $args['column_name'];
  $value = $args['value'];

  $rows = [];
  try {
    $rows = $db->$table_name
      ->select()
      ->where('lower(' . $column_name . ') LIKE :search', [':search' => '%' . strtolower($value) . '%'])
      ->run();
  } catch (Exception $e) {
    // do nothing
  } finally {
    return $res->withJson($rows);
  }
});

// UPDATE
$app->post('/update/{table_name}/{id}', function (Request $req, Response $res, array $args) {
  verifyToken();
  global $db;
  $table_name = $args['table_name'];
  $id = $args['id'];
  $data = $req->getParsedBody();

  $arr = [];
  try {
    $db->$table_name[id] = $data;
    $arr['success'] = true;
  } catch (Exception $e) {
    $arr['success'] = false;
    $arr['msg'] = $e;
  } finally {
    return $res->withJson($arr);
  }
});

// DELETE
$app->post('/delete/{table_name}/{id}', function (Request $req, Response $res, array $args) {
  verifyToken();
  global $db;
  $table_name = $args['table_name'];
  $id = $args['id'];

  $arr = [];
  try {
    unset($db->$table_name[id]);
    $arr['success'] = true;
  } catch (Exception $e) {
    $arr['success'] = false;
    $arr['msg'] = $e;
  } finally {
    return $res->withJson($arr);
  }
});

//////////////////////////////////////////////////////////////////////////////////////////

// REGISTER
$app->post('/register', function (Request $req, Response $res, array $args) {
  global $db, $secret_key, $table_users;
  $arr = array();

  // get http request params
  $data = $req->getParsedBody();
  $username = $data['username'];

  try {
    // check if username exists
    $exists = $db->$table_users->count()->by('username', $username)->run();
    if ($exists) {
      $arr['success'] = false;
      $arr['msg'] = 'username already exist';
    } else {
      $hashed_password = password_hash($data['password'], PASSWORD_DEFAULT);
      $currentDateTime = date('Y-m-d H:i:s');
      $insertData = array(
        'fullname' => $data['fullname'],
        'username' => $username,
        'gender' => $data['gender'],
        'hashed_password' => $hashed_password,
        'created_at' => $currentDateTime,
        'modified_at' => $currentDateTime,
      );
      $db->$table_users[] = $insertData;
      $arr['success'] = true;
    }
  } catch (Exception $e) {
    $arr['success'] = false;
    $arr['msg'] = $e;
  } finally {
    return $res->withJson($arr);
  }

});

// LOGIN
$app->post('/login', function (Request $req, Response $res, array $args) {
  global $db, $secret_key, $table_users;

  // HTTP POST Request with params 'username' & 'password'
  $data = $req->getParsedBody();
  $username = $data['username'];
  $password = $data['password'];

  $user = $db->$table_users->select()->one()->by('username', $username)->run();
  $arr = array(); //prepare return array
  try {
    if ($user) { // if user exists
      $hashed_password = $user->hashed_password;
      if (password_verify($password, $hashed_password)) {
        // username & password matched!
        $jwt = new \Lindelius\JWT\JWT();
        // $jwt->exp = time() + 7200; // expire after 2 hours (7200 seconds)
        $jwt->iat = time(); //

        // YOU CAN ALSO PUT SOME INFO, LIKE:
        $jwt->user_id = $user->id;
        // $jwt->is_admin = $user->is_admin';

        // AND THEN GENERATE THE TOKEN:
        $generated_token = $jwt->encode($secret_key);

        $arr['success'] = true;
        $arr['token'] = $generated_token; // put the token into array
      } else {
        $arr['success'] = false;
        $arr['msg'] = 'Wrong password!';
      }
    } else {
      $arr['success'] = false;
      $arr['msg'] = 'Username is not registered';
    }
  } catch (Exception $e) {
    $arr['success'] = false;
    $arr['msg'] = $e;
  } finally {
    return $res->withJson($arr);
  }
});

// IF YOU NEED TO SEND EMAIL:
function sendEmail($to, $subject, $body)
{
  // SET UP your mail server
  $emailHost = "your.mailhost.com";
  $emailUsername = "youruser@yourdomain.com";
  $emailPassword = "yoursupersecretpassword";

  $senderEmail = "sender@yourdomain.com";
  $senderFullname = "Your Fullname";

  $mail = new PHPMailer(true); // Passing `true` enables exceptions
  //Server settings
  // $mail->SMTPDebug = 2; // Enable verbose debug output (to enable: ...->SMTPDebug= 2)
  $mail->isSMTP(); // Set mailer to use SMTP
  $mail->Host = $emailHost; // Specify main and backup SMTP servers
  $mail->SMTPAuth = true; // Enable SMTP authentication
  $mail->Username = $emailUsername; // SMTP username
  $mail->Password = $emailPassword; // SMTP password
  $mail->SMTPSecure = 'ssl'; // Enable TLS encryption, `ssl` also accepted
  $mail->Port = 465; // TCP port to connect to

  //Recipients
  $mail->setFrom($senderEmail, $senderFullname);
  // $mail->addAddress('joe@example.net', 'Joe User');     // Add a recipient
  $mail->addAddress($to); // Name is optional
  // $mail->addReplyTo('info@example.com', 'Information');
  // $mail->addCC('cc@example.com');
  // $mail->addBCC('bcc@example.com');

  //Attachments
  // $mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
  // $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name

  //Content
  $mail->isHTML(true); // Set email format to HTML
  $mail->Subject = $subject;
  $mail->Body = $body;
  // $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

  $mail->send();
}

$app->run();
