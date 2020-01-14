<?php

class AppController {

  function authSession() {
    $obj = new Users();
    $auth = $obj->authSession();
    if (!$auth['success']) {
      $_SESSION['error_msg'] = $auth['msg'];
      Flight::redirect('/login/?p=' . Flight::request()->url);
    }
  }

  function is_permitted($section = null, $action = null) {
    if (empty($_SESSION['user_id']) || empty($_SESSION['perms']) || empty($section)) return false;
    if (empty($action)) $action = 'read';
    $sobj = new Sections();
    $aobj = new SectionActions();
    $ss = $sobj->find(array('tag' => $section));
    if (!$ss) return false;
    $as = $aobj->find(array('tag' => $action, 'section_id' => $ss[0]['id']));
    if (!$as) return false;
    if (!in_array($as[0]['id'], $_SESSION['perms'])) return false;
    return true;
  }

}
