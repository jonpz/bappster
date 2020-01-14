<?php

Flight::route('/admin', function(){
  Flight::redirect('/admin/users');
});

Flight::route('POST /admin/ajax/grid', function() {
  $admin = new AdminController();
  Flight::json($admin->getGrid($_POST));
});

Flight::route('/admin/@section/filter', function($section) {
  if (Flight::request()->ajax) {
    $admin = new AdminController();
    Flight::render('admin/' . $section . '/export/filter', array('ajax' => Flight::request()->ajax));
  } else Flight::notFound();
});

Flight::route('/admin/@section/filter-group', function($section) {
  if (Flight::request()->ajax) {
    $admin = new AdminController();
    Flight::render('admin/' . $section . '/export/filter-group', array('ajax' => Flight::request()->ajax));
  } else Flight::notFound();
});

Flight::route('POST /admin/@section/export', function($section) {
  $admin = new AdminController();
  if (!$admin->is_permitted('reports', 'read')) {
    Flight::notFound();
    die();
  }
  $reports = new Reports($section);
  $params = array(
    'cols' => json_decode($_POST['visible'], true),
    'order' => json_decode($_POST['order'], true),
    'filters' => json_decode($_POST['filters'], true),
  );
  $results = $reports->getResults($params);
  $header = array();
  foreach ($params['cols'] as $i) $header[] = $reports->rpt_cols[$i]['display'];
  header('Content-Type: application/excel');
  header('Content-Disposition: attachment; filename="' . $section . '-export-' . date('YmdHi') . '.csv"');
  $fp = fopen('php://output', 'w');
  fputcsv($fp, $header);
  foreach ($results as $row) fputcsv($fp, $row);
  fclose($fp);
});

Flight::route('POST /admin/@section/report', function($section) {
  $admin = new AdminController();
  if (!$admin->is_permitted('reports', 'read')) {
    Flight::notFound();
    die();
  }
  $reports = new Reports();
  $reports->get($_POST['id']);
  if (!$reports->id) {
    Flight::notFound();
    die();
  }
  $params = json_decode($reports->config, true);
  $name = str_replace(' ', '_', $reports->name);
  $reports = new Reports($section);
  $results = $reports->getResults($params);
  $header = array();
  foreach ($params['cols'] as $i) $header[] = $reports->rpt_cols[$i]['display'];
  header('Content-Type: application/excel');
  header('Content-Disposition: attachment; filename="' . $name . '-' . date('YmdHi') . '.csv"');
  $fp = fopen('php://output', 'w');
  fputcsv($fp, $header);
  foreach ($results as $row) fputcsv($fp, $row);
  fclose($fp);
});

Flight::route('POST /admin/users/import/@step', function($step) {
  $admin = new AdminController();
  Flight::render('admin/users/import/' . $step, array('post' => $_POST, 'files' => $_FILES));
});

Flight::route('POST /admin/@section/save', function($section) {
  $admin = new AdminController();
  $return = $admin->save($section, $_POST, $_FILES);
  if ($return['success'] && !Flight::request()->ajax) Flight::redirect('/admin/' . $section);
  elseif (Flight::request()->ajax) Flight::json($return);
  else die($return['msg']);
});

Flight::route('POST /admin/@section/delete', function($section) {
  $admin = new AdminController();
  $return = $admin->delete($section, $_POST);
  if ($return['success'] && !Flight::request()->ajax) Flight::redirect('/admin/' . $section);
  elseif (Flight::request()->ajax) Flight::json($return);
  else die($return['msg']);
});

Flight::route('/admin/(@section:[a-z,\-]+(/@id:[0-9]+(/@action:[a-z,\-]+(/@action_id:[0-9]+))))', function($section, $id, $action, $action_id){
  $section = ($section) ? $section : "home";
  $data = array('section' => $section, 'id' => $id, 'action' => $action, 'action_id' => $action_id);
  $admin = new AdminController();
  $admin->loadSection($data);
});

Flight::route('/admin/(@section:[a-z,\-]+(/@action:[a-z,\-]+(/@action_id:[0-9]+)))', function($section, $action, $action_id){
  $section = ($section) ? $section : "home";
  $data = array('section' => $section, 'action' => $action, 'action_id' => $action_id);
  $admin = new AdminController();
  $admin->loadSection($data);
});
