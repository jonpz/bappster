<?php

class AdminController {

  function __construct() {
    $obj = new Users();
    $auth = $obj->authSession();
    if (!$auth['success']) {
      session_start();
      $_SESSION['error_msg'] = $auth['msg'];
      Flight::redirect('/login/?p=' . Flight::request()->url);
      die();
    }
    if (!$this->is_permitted('admin', 'read')) {
      Flight::halt(401, '<h1>401 Unauthorized</h1><h3>You do not have permission to view this directory or page.</h3>');
      die();
    }
  }

  function is_permitted($section = null, $action = null) {
    if (empty($_SESSION['user_id']) || empty($_SESSION['perms']) || empty($section)) return false;
    if (empty($action)) $action = 'read';
    $sobj = new Sections();
    $aobj = new SectionActions();
    $sobj->get($section);
    if (!$sobj->id) return false;
    $as = $aobj->find(array('tag' => $action, 'section_id' => $sobj->id));
    if (!$as) return false;
    if (!in_array($as[0]['id'], $_SESSION['perms'])) return false;
    return true;
  }

  function loadSection($data) {
    if (empty($data['section'])
        || !$this->is_permitted($data['section'], $data['action'])
        || ($data['section'] && !file_exists(appPath() . '/app/views/admin/' . $data['section'] . '.php'))
        || ($data['action'] && !file_exists(appPath() . '/app/views/admin/' . $data['section'] . '/' . $data['action'] . '.php'))) {
      Flight::halt(401, '<h1>401 Unauthorized</h1><h3>You do not have permission to view this directory or page.</h3>');
      die();
    }
    Flight::render('layouts/admin/top', array('section' => $data['section'], 'action' => ($data['action']) ? $data['action'] : null));
    if ($data['action']) Flight::render('admin/' . $data['section'] . '/' . $data['action'], $data);
    else Flight::render('admin/' . $data['section'], $data);
    Flight::render('layouts/admin/bottom');
  }

  function getGrid($data) {
    if (empty($data['section']) || !$this->is_permitted($data['section'])) return false;
    $sections = new Sections();
    $sections->get($data['section']);
    if (!$sections->id) return false;
    $obj = new $sections->model_name();
    return $obj->getGrid($data);
  }

  function save($section, $data, $files) {
    foreach ($data as $key => $val) {
      if ($val === '') $data[$key] = null;
    }
    $sections = new Sections();
    $sections->get($section);
    if (!$sections->id) return false;
    $obj = new $sections->model_name();
    switch ($section) {
      case 'users':
        if (empty($data['deleted'])) $data['deleted'] = 0;
        break;
      case 'sections':
        if (empty($data['hide_nav'])) $data['hide_nav'] = 0;
        break;
      case 'section-actions':
        if (empty($data['hide_nav'])) $data['hide_nav'] = 0;
        break;
      case 'report-columns':
        if (empty($data['visible'])) $data['visible'] = 0;
        if (empty($data['nullable'])) $data['nullable'] = 0;
        break;
      case 'roles':
        if (empty($data['section_action_id'])) $data['section_action_id'] = array();
        break;
    }
    if ($data['id']) $return = $obj->edit($data, $files);
    else $return = $obj->add($data, $files);
    return $return;
  }

  function delete($section, $data) {
    $sections = new Sections();
    $sections->get($section);
    if (!$sections->id) return false;
    $obj = new $sections->model_name();
    return $obj->remove($data['id']);
  }

  // function importUploadFile($files) {
  //   $_SESSION['import'] = array('filename' => date('YmdHis-') . $files['file']['name']);
  //   $full_path = appPath() . '/files/imports/' . $_SESSION['import']['filename'];
  //   if (! rename($files['file']['tmp_name'], $full_path)) return false;
  //   else {
  //     if (!($fh = fopen($full_path, 'r'))) return false;
  //     $_SESSION['import']['headings'] = array_map(function($val) { return trim($val); }, fgetcsv($fh));
  //     fclose($fh);
  //     return true;
  //   }
  // }
  //
  // function importMapColumns($data) {
  //   if (empty($data['heading'])) return false;
  //   $_SESSION['import']['colmap'] = $data['heading'];
  //   return true;
  // }
  //
  // function importProcessFile() {
  //   $full_path = appPath() . '/files/imports/' . $_SESSION['import']['filename'];
  //   $_SESSION['import']['data'] = array(
  //     'new' => array(),
  //     'edit' => array(),
  //     'delete' => array(),
  //     'nochange' => array(),
  //   );
  //   $import_ids = array();
  //   $employees = new Employees();
  //   $row = 0;
  //   if (!($fh = fopen($full_path, 'r'))) return false;
  //   while ($data = fgetcsv($fh)) {
  //     if ($row) {
  //       $keyed_data = array();
  //       foreach ($data as $i => $val) {
  //         // this line is dedicated to harris m. jones :)
  //         if ($_SESSION['import']['colmap'][$i]) $keyed_data[$_SESSION['import']['colmap'][$i]] = ($_SESSION['import']['colmap'][$i] === 'pay_rate' && trim($val) !== '#N/A') ? str_replace('$', '', trim($val)) : ((trim($val) && trim($val) !== '#N/A') ? trim($val) : (($_SESSION['import']['colmap'][$i] === 'supervisor_id') ? 0 : null));
  //       }
  //       $employees->get($keyed_data['id']);
  //       if ($employees->id) {
  //         $clean = true;
  //         foreach ($keyed_data as $col => $val) {
  //           if ($employees->{$col} != $val) $clean = false;
  //           elseif ($col !== 'id') unset($keyed_data[$col]);
  //         }
  //         if ($clean) $_SESSION['import']['data']['nochange'][] = $employees->id;
  //         else {
  //           if (!$employees->teamlead_id && !empty($keyed_data['supervisor_id'])) $keyed_data['teamlead_id'] = $keyed_data['supervisor_id'];
  //           $_SESSION['import']['data']['edit'][] = $keyed_data;
  //         }
  //       } else {
  //         $keyed_data['teamlead_id'] = $keyed_data['supervisor_id'];
  //         $_SESSION['import']['data']['new'][] = $keyed_data;
  //       }
  //       $import_ids[] = $keyed_data['id'];
  //     }
  //     $row++;
  //   }
  //   $_SESSION['import']['data']['delete'] = array_map(function($row) { return $row['id']; }, $employees->getNotIn($import_ids));
  //   return true;
  // }
  //
  // function importCommit() {
  //   $employees = new Employees();
  //   foreach ($_SESSION['import']['data']['new'] as $data) {
  //     $return = $employees->add($data);
  //     if ($return['success'] === false) return false;
  //   }
  //   foreach ($_SESSION['import']['data']['edit'] as $data) {
  //     if ($data['id'] != 33076) {
  //       $return = $employees->edit($data);
  //       if ($return['success'] === false) return false;
  //     }
  //   }
  //   foreach ($_SESSION['import']['data']['delete'] as $id) {
  //     $return = $employees->edit(array('id' => $id, 'deleted' => 1));
  //     if ($return['success'] === false) return false;
  //   }
  //   return $employees->updateTeams();
  // }

}

?>
