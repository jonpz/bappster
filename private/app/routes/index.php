<?php

Flight::path(__dir__ . '/../controllers/');
Flight::path(__dir__ . '/../models/');

Flight::set('flight.views.path', __dir__ .'/../views');

include_once("admin.php");
include_once("api.php");

Flight::route('POST /login', function() {
  $users = new Users();
  if (!empty($_POST['action']) && $_POST['action'] == 'reset') {
    $users->forgotPasswordSend($_POST['email']);
    session_destroy();
    session_start();
    $_SESSION['success_msg'] = 'Email sent! Check your email to continue.';
    Flight::redirect('/login');
  } elseif (!empty($_POST['action']) && $_POST['action'] == 'set-password') {
    if (empty($_SESSION['user_id'])) {
      Flight::notFound();
      die();
    }
    if ($_POST['password'] != $_POST['passwordConfirm']) {
      $_SESSION['error_msg'] = "Passwords didn't match!";
      Flight::redirect(Flight::request()->referrer);
    } else {
      $users->edit(array('id' => $_SESSION['user_id'], 'password' => $_POST['password']));
      Flight::redirect('/home');
    }
  } else {
    $auth = $users->auth($_POST);
    if ($auth['success']) {
      $redirect = '/home';
      if (!empty($_GET['p'])) $redirect = $_GET['p'];
      Flight::redirect($redirect);
    } else {
      session_start();
      $_SESSION['error_msg'] = $auth['msg'];
      Flight::redirect('/login');
    }
  }
});
Flight::route('/login', function() {
  Flight::render('layouts/main/top', array('section' => 'login'));
  Flight::render('login');
  Flight::render('layouts/main/bottom');
});
Flight::route('/login/reset-password/@email/@token', function($email, $token) {
  $users = new Users();
  $valid = $users->validateResetLink($email, $token);
  $render = ($valid) ? 'login/set-password' : 'login/bad-link';
  Flight::render('layouts/main/top', array('section' => 'login'));
  Flight::render($render);
  Flight::render('layouts/main/bottom');
});
Flight::route('/logout', function() {
  $users = new Users();
  $users->unsetToken();
  session_destroy();
  Flight::redirect('/login');
});

Flight::route('/(@section:[a-z,\-]+(/@id:[0-9]+(/@action:[a-z,\-]+)))', function($section, $id, $action){
    $section = ($section) ? $section : "home";
    $app = new AppController();
    $app->authSession();
    if (($section && !file_exists(appPath() . '/app/views/' . $section . '.php')) || ($action && !file_exists(appPath() . '/app/views/' . $section . '/' . $action . '.php'))) {
      Flight::notFound();
      die();
    }
    $data = array('section' => $section, 'id' => $id, 'action' => $action, 'is_admin' => $app->is_permitted('admin', 'read'));
    Flight::render('layouts/main/top', $data);
    if ($action) Flight::render($section . '/' . $action, $data);
    else Flight::render($section, $data);
    Flight::render('layouts/main/bottom');
});

Flight::route('/(@section:[a-z,\-]+(/@action:[a-z,\-]+))', function($section, $action){
    $section = ($section) ? $section : "home";
    $app = new AppController();
    $app->authSession();
    if (($section && !file_exists(appPath() . '/app/views/' . $section . '.php')) || ($action && !file_exists(appPath() . '/app/views/' . $section . '/' . $action . '.php'))) {
      Flight::notFound();
      die();
    }
    $data = array('section' => $section, 'action' => $action, 'is_admin' => $app->is_permitted('admin', 'read'));
    Flight::render('layouts/main/top', $data);
    if ($action) Flight::render($section . '/' . $action, $data);
    else Flight::render($section, $data);
    Flight::render('layouts/main/bottom');
});
