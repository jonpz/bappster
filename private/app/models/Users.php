<?php

class Users extends Base {

  var $id;
  var $first_name;
  var $last_name;
  var $email;
  protected $hash;
  var $role_id;
  var $last_login;
  var $token;
  protected $token_exp;
  var $created_at;
  var $deleted;
  var $perms;

  function __construct() {
    $this->cols = array('id', 'first_name', 'last_name', 'email', 'hash', 'role_id', 'last_login', 'token', 'token_exp', 'created_at', 'deleted');
    $this->app_table = 'users';
    $sections = new Sections();
    $this->app_section_id = $sections->find(array('table' => $this->app_table))[0]['id'];
    $this->id = 0;
    $this->first_name = null;
    $this->last_name = null;
    $this->email = null;
    $this->hash = null;
    $this->role_id = null;
    $this->last_login = null;
    $this->token = null;
    $this->token_exp = null;
    $this->created_at = null;
    $this->deleted = 0;
    $this->perms = array();
    parent::__construct();
  }

  function get($id) {
    $this->id = $id;
    $this->read();
    $sas = $this->sql->query('select section_action_id from role_permissions where role_id = ' . $this->role_id);
    if ($sas) $this->perms = array_map(function($row) { return (int) $row['section_action_id']; }, $sas);
    return true;
  }

  function getAll() {
    return $this->sql->query('select id, first_name, last_name, email, role_id, last_login, created_at, deleted from ' . $this->app_table);
  }

  function getArray($data) {
    if (!empty($data['id'])) $id = (int) $data['id'];
    elseif ((int) $data) $id = (int) $data;
    else return false;
    $return = $this->sql->query('select id, first_name, last_name, email, role_id, last_login, created_at, deleted from ' . $this->app_table . ' where id = ?', array($id));
    if (!empty($return[0])) $return = $return[0];
    return $return;
  }

  function add($data = array()) {
    $error = array();
    $return = array();
    // hash the password (blowfish + random salt)
    if (!empty($data['password'])) {
      $salt = strtr(base64_encode(mcrypt_create_iv(16, MCRYPT_DEV_URANDOM)), '+', '.');
      $salt = sprintf("$2a$%02d$", 10) . $salt;
      $data['hash'] = crypt($data['password'], $salt);
    }
    // import data, verify requireds
    $this->fromArray($data);
    if (!strlen($this->email)) $error[] = "Email is required";
    //  if no error, run it, return results
    if (empty($error)) {
      $this->id = $this->create();
      if ($this->id) {
        $return = array('success' => 1, 'id' => $this->id);
      } else {
        $return = array('success' => 0, 'msg' => (is_array($this->sql->getErrorMsg())) ? implode(', ', $this->sql->getErrorMsg()) : $this->sql->getErrorMsg());
      }
    } else {
      $return = array('success' => 0, 'msg' => implode(', ', $error));
    }
    $this->__construct();
    return $return;
  }

  function edit($data = array()) {
    $error = array();
    $return = array();
    $this->get($data['id']);
    // hash the password (blowfish + random salt)
    if (!empty($data['password'])) {
      $salt = strtr(base64_encode(mcrypt_create_iv(16, MCRYPT_DEV_URANDOM)), '+', '.');
      $salt = sprintf("$2a$%02d$", 10) . $salt;
      $data['hash'] = crypt($data['password'], $salt);
      $data['auth_code'] = null;
      $data['token'] = null;
      $data['token_exp'] = null;
    }
    // import data, verify requireds
    $this->fromArray($data);
    if (!strlen($this->id)) $error[] = "ID is required";
    if (!strlen($this->email)) $error[] = "Email is required";
    //  if no error, run it, return results
    if (empty($error)) {
      $ret = $this->update();
      if ($ret) {
        $return = array('success' => 1, 'id' => $this->id);
      } else {
        $return = array('success' => 0, 'msg' => (is_array($this->sql->getErrorMsg())) ? implode(', ', $this->sql->getErrorMsg()) : $this->sql->getErrorMsg());
      }
    } else {
      $return = array('success' => 0, 'msg' => implode(', ', $error));
    }
    $this->__construct();
    return $return;
  }

  // authentication
  function auth($data = array()) {
    $return = array();
    $find = $this->find(array('email' => $data['email'], 'deleted' => 0));
    if ($find) {
      $this->get($find[0]['id']);
      if ($this->hash == crypt($data['password'], $this->hash)) {
        $this->last_login = date('Y-m-d H:i:s');
        $this->setToken();
        $return = array('success' => 1, 'id' => $this->id);
      } else {
        $this->unsetToken();
        $return = array('success' => 0, 'msg' => 'Email or password incorrect!');
      }
    } else {
      $this->unsetToken();
      $return = array('success' => 0, 'msg' => 'Email or password incorrect!');
    }
    return $return;
  }

  function authSession() {
    $return = array();
    $find = $this->find(array('id' => (isset($_SESSION['user_id'])) ? $_SESSION['user_id'] : '', 'deleted' => 0));
    if ($find) {
      $this->get($find[0]['id']);
      if (isset($_SESSION['token']) && $_SESSION['token'] === $this->token && $this->token_exp + 3600 > time()) {
        $this->setToken();
        $return = array('success' => 1);
      } else {
        $this->unsetToken();
        session_destroy();
        $return = array('success' => 0, 'msg' => 'Session invalid or expired.');
      }
    } else {
      $this->unsetToken();
      session_destroy();
      $return = array('success' => 0, 'msg' => 'Session invalid or expired.');
    }
    return $return;
  }

  function setToken() {
    $this->token = bin2hex(openssl_random_pseudo_bytes(16));
    $this->token_exp = time();
    $this->update(false, false);
    $_SESSION['user_id'] = (int) $this->id;
    $_SESSION['token'] = $this->token;
    $_SESSION['perms'] = $this->perms;
  }

  function unsetToken() {
    $this->token = NULL;
    $this->token_exp = NULL;
    $this->update(false, false);
    unset($_SESSION['user_id']);
    unset($_SESSION['token']);
    unset($_SESSION['perms']);
  }

  public function forgotPasswordSend($email) {
    $s = $this->find(array('email' => $email, 'deleted' => 0));
    if ($s) {
      $this->get($s[0]['id']);
      $this->setToken();
      $emails = new Emails();
      $data = array(
        'to_name' => $this->first_name . ' ' . $this->last_name,
        'to_address' => $this->email,
        'first_name' => $this->first_name,
        'last_name' => $this->last_name,
        'reset_link' => ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . '/login/reset-password/' . urlencode($this->email) . '/' . $this->token,
      );
      $return = $emails->sendEmail('reset_password', $data);
      if (!$return['success']) return false;
    }
    return true;
  }

  function validateResetLink($email, $token) {
    if (empty($email) || empty($token)) return false;
    $valid = $this->sql->query('select id from users where deleted = 0 and email = ? and token = ? and token_exp + 86400 >= unix_timestamp(now())', array($email, $token));
    if (!$valid) return false;
    $_SESSION['user_id'] = $valid[0]['id'];
    return true;
  }

  function getGrid($data) {
    $params = array(
      'limit' => $data['start'] . ', ' . $data['length'],
      'order' => array(),
      'filters' => array(),
    );
    $cols = array('id', 'name', 'email', 'last_login', 'deleted');
    if (!empty($data['search']['value'])) {
      $params['filters'][] = array(
        'cond' => array(),
      );
      $filter_cols = array('id', 'concat(first_name, " ", last_name)', 'email');
      foreach ($filter_cols as $filter_col) {
        $cond = array();
        if (!empty($params['filters'][0]['cond'])) $cond['oper'] = 'or';
        $cond['col'] = $filter_col;
        $cond['comp'] = 'like';
        $cond['val'] = '%' . $data['search']['value'] . '%';
        $params['filters'][0]['cond'][] = $cond;
      }
    }
    if (!empty($data['order'])) {
      foreach ($data['order'] as $order) {
        if ($cols[$order['column']] === 'name') {
          $params['order']['last_name'] = $order['dir'];
          $params['order']['first_name'] = $order['dir'];
        } else $params['order'][$cols[$order['column']]] = $order['dir'];
      }
    }
    $gs = $this->search($params);
    $return = array(
      'draw' => $data['draw'],
      'recordsTotal' => count($gs['total']),
      'recordsFiltered' => count($gs['total']),
      'data' => array(),
    );
    unset($gs['total']);
    foreach ($gs as $row) {
      $this->get($row['id']);
      $return['data'][] = array(
        $this->id,
        $this->first_name . ' ' . $this->last_name,
        $this->email,
        ($this->last_login) ? date('F j, Y g:i a', strtotime($this->last_login)) : '',
        ($this->deleted) ? 'Yes' : 'No',
      );
    }
    return $return;
  }

}

?>
