<?php

class Base {

  protected $sql;
  protected $cols;
  protected $app_table;
  protected $app_section_id;

  function __construct() {
    $this->sql = Flight::sql();
  }

  // getters and setters

  function get($id) {
    $this->id = $id;
    $this->read();
    return true;
  }

  function getAll() {
    return $this->sql->query('select * from ' . $this->app_table);
  }

  function getArray($data) {
    if (!empty($data['id'])) $id = (int) $data['id'];
    elseif ((int) $data) $id = (int) $data;
    else return false;
    $return = $this->sql->query('select * from ' . $this->app_table . ' where id = ?', array($id));
    if (!empty($return[0])) $return = $return[0];
    return $return;
  }

  function add($data = array()) {
    $return = array();
    $this->fromArray($data);
    $this->id = $this->create();
    if ($this->id) $return = array('success' => 1, 'id' => $this->id);
    else $return = array('success' => 0, 'msg' => (is_array($this->sql->getErrorMsg())) ? implode(', ', $this->sql->getErrorMsg()) : $this->sql->getErrorMsg());
    $this->__construct();
    return $return;
  }

  function edit($data = array()) {
    $return = array();
    $this->get($data['id']);
    $this->fromArray($data);
    $ret = $this->update();
    if ($ret) $return = array('success' => 1, 'id' => $this->id);
    else $return = array('success' => 0, 'msg' => (is_array($this->sql->getErrorMsg())) ? implode(', ', $this->sql->getErrorMsg()) : $this->sql->getErrorMsg());
    $this->__construct();
    return $return;
  }

  function remove($data) {
    if (!empty($data['id'])) $id = (int) $data['id'];
    elseif ((int) $data) $id = (int) $data;
    else return false;
    $return = array();
    $this->get($id);
    $ret = $this->delete();
    if ($ret) $return = array('success' => 1, 'id' => $id);
    else $return = array('success' => 0, 'msg' => (is_array($this->sql->getErrorMsg())) ? implode(', ', $this->sql->getErrorMsg()) : $this->sql->getErrorMsg());
    return $return;
  }

  /**
    * search() - returns an array of id's of records matching given criteria
    * if limit is set, it also returns a keyed element of the array called
    * 'total' for use with pagination.
    * params:
    * order - an array of columns you want to order by in order of priority,
    * set up as such:
    * array(
    *   'column1' => 'direction(ASC or DEC)',
    *   'column2' => 'direction',
    * )
    * limit - a string containing the sql limit statement (not including the
    * LIMIT keyword), i.e. '0,10'
    * filters - a complicated multidimensional array that defines the where
    * clause of the sql. the first dimension of the array define the
    * parenthetical statements of the where clause, and have an 'oper'
    * element (absent on the first element), for the AND or OR relationship
    * to the previous statement and an array element called 'cond' that holds
    * the 'oper' element (again absent on the first element), a 'col' element
    * to define which column the filter is for, a 'comp' element that is a
    * string of the comparison (i.e. '>', '<=', 'LIKE', etc.), and a 'val'
    * element for the value. here's an example:
    * filters = array(
    *   array(
    *     'cond' => array(
    *       array(
    *         'col' => 'last_name',
    *         'comp' => '=',
    *         'val' => 'smith',
    *       ),
    *       array(
    *         'oper' => 'or',
    *         'col' => 'last_name',
    *         'comp' => '=',
    *         'val' => 'johnson',
    *       ),
    *     )
    *   ),
    *   array(
    *     'oper' => 'and',
    *     'cond' => array(
    *       'col' => 'password',
    *       'comp' => 'is null',
    *     )
    *   )
    * )
    * would produce a where statement like this:
    * 'where ( last_name = "smith" or last_name = "johnson" ) and ( password is null )'
    * (sql injection protections are in place though so those values are actually
    * passed in a data array)
    * returns a 2d array of just the id column of all matches, not a flat array
    * example: [['id' => 1],...]
    */

  function search($params = array()) {
    $data = array();
    $where = '';
    $order = array();
    $limit = '';
    foreach ($params as $key => $value) {
      if ($key == 'order') {
        $order = $value;
      } elseif ($key == 'limit') {
        $limit = $value;
      } elseif ($key == 'filters') {
        foreach ($value as $filter_group) {
          if (isset($filter_group['oper'])) $where .= $filter_group['oper'] . ' ';
          else $where .= 'where ';
          $where .= '( ';
          foreach ($filter_group['cond'] as $condition) {
            if (isset($condition['oper'])) $where .= $condition['oper'] . ' ';
            if (!strpbrk($condition['col'], '()')) $where .= '`' . $condition['col'] . '` ';
            else $where .= $condition['col'] . ' ';
            $i = count($data);
            $where .= $condition['comp'] . ' ';
            if (isset($condition['val'])) {
              $where .= ':var' . $i . ' ';
              $data[':var' . $i] = $condition['val'];
            }
          }
          $where .= ') ';
        }
      }
    }
    $query = "SELECT id FROM {$this->app_table} $where";
    if (!empty($order)) {
      $query .= " ORDER BY ";
      $i = 1;
      foreach ($order as $orderCol => $dir) {
        $query .= "`$orderCol` $dir";
        if ($i < count($order)) $query .= ", ";
        $i++;
      }
    }
    if (strlen($limit)) {
      $nolimit_query = $query;
      $query .= " LIMIT $limit";
    }
    $return = $this->sql->query($query, $data);
    if (strlen($limit)) {
      $nolimit_results = $this->sql->query($nolimit_query, $data);
      $return['total'] = $nolimit_results;
    }
    return $return;
  }

  /**
   * find()
   * a simpler version of search with the same return format. it takes an array
   * of params. order and limit work the same, but instead of the filters param,
   * you just pass it a column name param set to the value you're searching for.
   * example:
   * ['deleted' => 0, 'limit' => ...]
   * setting the optional fuzzy param to true changes the = to LIKE and the ANDs
   * to ORs for the column params
   */

  function find($params = array(), $fuzzy = false) {
    $data = array();
    $where = array();
    $order = array();
    $limit = '';
    foreach ($params as $key => $value) {
      // order by clause
      if ($key == 'order') {
        $order = $value;
      } elseif ($key == 'limit') {
        $limit = $value;
      // *_not params
      } elseif (strpos($key, '_not')) {
        $col = str_replace('_not', '', $key);
        $data[":$key"] = $value;
        $where[] = "`$col` <> :$key";
      // *_null params
      } elseif (strpos($key, '_null')) {
        $col = str_replace('_null', '', $key);
        $where[] = ($value) ? "`$col` IS NULL" : "`$col` IS NOT NULL";
      // default search params
      } else {
        if ($fuzzy) {
          $data[":$key"] = "%$value%";
          $where[] = "`$key` LIKE :$key";
        } else {
          $data[":$key"] = $value;
          $where[] = "`$key` = :$key";
        }
      }
    }
    $query = "SELECT id FROM {$this->app_table}";
    if ($fuzzy) $whereGlue = ' OR ';
    else $whereGlue = ' AND ';
    if (!empty($where)) {
      $query .= " WHERE " . implode($whereGlue, $where);
    }
    if (!empty($order)) {
      $query .= " ORDER BY ";
      $i = 1;
      foreach ($order as $orderCol => $dir) {
        $query .= "`$orderCol` $dir";
        if ($i < count($order)) $query .= ", ";
        $i++;
      }
    }
    if (strlen($limit)) {
      $nolimit_query = $query;
      $query .= " LIMIT $limit";
    }
    $return = $this->sql->query($query, $data);
    if (strlen($limit)) {
      $nolimit_results = $this->sql->query($nolimit_query, $data);
      $return['total'] = $nolimit_results;
    }
    return $return;
  }

  // helper functions

  protected function toDataArray() {
    $result = array();
    foreach ($this->cols as $col) {
      $result[":$col"] = $this->{$col};
    }
    return $result;
  }

  protected function fromArray($array = array()) {
    foreach ($this->cols as $col) {
      if (array_key_exists($col, $array)) {
        if (is_string($array["$col"])) $this->{$col} = (strlen(trim($array["$col"]))) ? trim($array["$col"]) : null;
        else $this->{$col} = $array["$col"];
      }
    }
    return true;
  }

  // crud

  protected function create($check_permission = true, $write_audit = true) {
    $start = microtime(true);
    $actions = new SectionActions();
    $as = $actions->find(array('section_id' => $this->app_section_id, 'tag' => 'create'));
    if ((!empty($as[0]['id']) && !empty($_SESSION['perms']) && in_array($as[0]['id'], $_SESSION['perms'])) || !$check_permission) {
      $data = $this->toDataArray();
      $insert_cols = $this->cols;
      unset($data[':id']);
      unset($insert_cols[0]);
      $query = "INSERT INTO `{$this->app_table}` (`" . implode('`, `', $insert_cols) . "`) VALUES (:" . implode(', :', $insert_cols) . ")";
      $return = $this->sql->query($query, $data);
      $end = microtime(true);
      if ($write_audit && $return) {
        $audits = new AuditLogs();
        $audit_data = array(
          'row_id' => $return,
          'new_data' => json_encode($this->sql->query("SELECT * FROM `{$this->app_table}` WHERE id = $return")[0]),
          'exec_time' => $end - $start,
        );
        if (!empty($_SESSION['user_id'])) $audit_data['user_id'] = $_SESSION['user_id'];
        if (empty($as[0]['id'])) $audit_data['action'] = 'create ' . $this->app_table;
        else $audit_data['section_action_id'] = $as[0]['id'];
        $audits->add($audit_data);
      }
    } else {
      $return = false;
      $audits = new AuditLogs();
      $audit_data = array(
        'new_data' => json_encode(array('$_SERVER' => $_SERVER, '$_POST' => $_POST)),
        'is_permfail' => true,
      );
      if (!empty($_SESSION['user_id'])) $audit_data['user_id'] = $_SESSION['user_id'];
      if (empty($as[0]['id'])) $audit_data['action'] = 'create ' . $this->app_table;
      else $audit_data['section_action_id'] = $as[0]['id'];
      $audits->add($audit_data);
    }
    return $return;
  }

  protected function read() {
    $data = array(':id' => $this->id);
    $query = "SELECT * FROM `{$this->app_table}` WHERE id = :id";
    $result = $this->sql->query($query, $data);
    $this->__construct();
    if (count($result)) $this->fromArray($result[0]);
    return $this->id;
  }

  protected function update($check_permission = true, $write_audit = true) {
    $start = microtime(true);
    $actions = new SectionActions();
    $as = $actions->find(array('section_id' => $this->app_section_id, 'tag' => 'update'));
    if ((!empty($as[0]['id']) && !empty($_SESSION['perms']) && in_array($as[0]['id'], $_SESSION['perms'])) || !$check_permission) {
      if ($write_audit) $audit_old_data = $this->sql->query("SELECT * FROM {$this->app_table} WHERE id = {$this->id}")[0];
      $data = $this->toDataArray();
      $update_cols = $this->cols;
      unset($update_cols[0]);
      $set = array();
      foreach ($update_cols as $col) {
        $set[] = "`$col` = :$col";
      }
      $query = "UPDATE `{$this->app_table}` SET " . implode(', ', $set) . " WHERE id = :id";
      $return = $this->sql->query($query, $data);
      $end = microtime(true);
      if ($write_audit && $return) {
        $audits = new AuditLogs();
        $audit_new_data = $this->sql->query("SELECT * FROM `{$this->app_table}` WHERE id = {$this->id}")[0];
        $audit_data = array(
          'row_id' => $this->id,
          'old_data' => json_encode($audit_old_data),
          'new_data' => json_encode($audit_new_data),
          'exec_time' => $end - $start,
        );
        if (!empty($_SESSION['user_id'])) $audit_data['user_id'] = $_SESSION['user_id'];
        if (empty($as[0]['id'])) $audit_data['action'] = 'update ' . $this->app_table;
        else $audit_data['section_action_id'] = $as[0]['id'];
        if ($audit_old_data !== $audit_new_data) $audits->add($audit_data);
      }
    } else {
      $return = false;
      $audits = new AuditLogs();
      $audit_data = array(
        'new_data' => json_encode(array('$_SERVER' => $_SERVER, '$_POST' => $_POST)),
        'is_permfail' => true,
      );
      if (!empty($_SESSION['user_id'])) $audit_data['user_id'] = $_SESSION['user_id'];
      if (empty($as[0]['id'])) $audit_data['action'] = 'update ' . $this->app_table;
      else $audit_data['section_action_id'] = $as[0]['id'];
      $audits->add($audit_data);
    }
    return $return;
  }

  protected function delete($check_permission = true, $write_audit = true) {
    $start = microtime(true);
    $actions = new SectionActions();
    $as = $actions->find(array('section_id' => $this->app_section_id, 'tag' => 'delete'));
    if ((!empty($as[0]['id']) && !empty($_SESSION['perms']) && in_array($as[0]['id'], $_SESSION['perms'])) || !$check_permission) {
      if ($write_audit) $audit_old_data = $this->sql->query("SELECT * from {$this->app_table} WHERE id = {$this->id}")[0];
      $data = array(':id' => $this->id);
      $query = "DELETE FROM `{$this->app_table}` WHERE id = :id";
      $return = $this->sql->query($query, $data);
      $end = microtime(true);
      if ($write_audit && $return) {
        $audits = new AuditLogs();
        $audit_data = array(
          'row_id' => $this->id,
          'old_data' => json_encode($audit_old_data),
          'exec_time' => $end - $start,
        );
        if (!empty($_SESSION['user_id'])) $audit_data['user_id'] = $_SESSION['user_id'];
        if (empty($as[0]['id'])) $audit_data['action'] = 'delete ' . $this->app_table;
        else $audit_data['section_action_id'] = $as[0]['id'];
        $audits->add($audit_data);
      }
      $this->__construct();
    } else {
      $return = false;
      $audits = new AuditLogs();
      $audit_data = array(
        'new_data' => json_encode(array('$_SERVER' => $_SERVER, '$_POST' => $_POST)),
        'is_permfail' => true,
      );
      if (!empty($_SESSION['user_id'])) $audit_data['user_id'] = $_SESSION['user_id'];
      if (empty($as[0]['id'])) $audit_data['action'] = 'delete ' . $this->app_table;
      else $audit_data['section_action_id'] = $as[0]['id'];
      $audits->add($audit_data);
    }
    return $return;
  }

}
