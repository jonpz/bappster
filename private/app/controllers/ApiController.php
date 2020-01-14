<?php
class ApiController {
  function __construct() {
    $users = new Users();
    $auth = $users->auth(array('email' => $_SERVER['PHP_AUTH_USER'], 'password' => $_SERVER['PHP_AUTH_PW']));
    if (empty($auth['success'])) {
      Flight::halt(401,'Unauthorized, please provide a valid username and password.');
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

  function makeCall($method, $section, $id, $post, $files) {
    switch ($method) {
      case 'DELETE':
        $action = 'delete';
        $function = 'remove';
        break;
      case 'POST':
        $action = ($id) ? 'update' : 'create';
        $function = ($id) ? 'edit' : 'add';
        break;
      default:
        $action = 'read';
        $function = ($id) ? 'getArray' : 'getAll';
        break;
    }
    if ($this->is_permitted($section, $action)) {
      $sections = new Sections();
      $sections->get($section);
      if (!$sections->id) return false;
      $obj = new $sections->model_name();
      if (empty($post['id']) && $id) $post['id'] = $id;
      return $obj->$function($post, $files);
    } else {
      Flight::halt(403, 'Forbidden');
      die();
    }
  }
}
?>
