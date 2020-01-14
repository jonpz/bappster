<?php

class Roles extends Base {

  var $id;
  var $name;
  var $priority;
  var $section_action_id;

  function __construct() {
    $this->cols = array('id', 'name', 'priority');
    $this->app_table = 'roles';
    $sections = new Sections();
    $this->app_section_id = $sections->find(array('table' => $this->app_table))[0]['id'];
    $this->id = 0;
    $this->name = null;
    $this->priority = 0;
    $this->section_action_id = array();
    parent::__construct();
  }

  function get($id) {
    $this->id = $id;
    $this->read();
    $sas = $this->sql->query('select section_action_id from role_permissions where role_id = ' . $this->id);
    if ($sas) $this->section_action_id = array_map(function($row) { return (int) $row['section_action_id']; }, $sas);
    return true;
  }

  function add($data = array()) {
    $error = array();
    $return = array();
    // import data, verify requireds
    $this->fromArray($data);
    if (!strlen($this->name)) $error[] = "Name is required";
    //  if no error, run it, return results
    if (empty($error)) {
      $this->id = $this->create();
      if ($this->id) {
        if (!empty($data['section_action_id'])) {
          foreach ($data['section_action_id'] as $said) {
            $this->sql->query('insert into role_permissions (role_id, section_action_id) values (?, ?)', array($this->id, $said));
          }
        }
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
    // import data, verify requireds
    $this->fromArray($data);
    if (!strlen($this->id)) $error[] = "ID is required";
    if (!strlen($this->name)) $error[] = "Name is required";
    //  if no error, run it, return results
    if (empty($error)) {
      $ret = $this->update();
      if ($ret) {
        $this->sql->query('delete from role_permissions where role_id = ' . $this->id);
        if (!empty($data['section_action_id'])) {
          foreach ($data['section_action_id'] as $said) {
            $this->sql->query('insert into role_permissions (role_id, section_action_id) values (?, ?)', array($this->id, $said));
          }
        }
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

  function getGrid($data) {
    $params = array(
      'limit' => $data['start'] . ', ' . $data['length'],
      'order' => array(),
      'filters' => array(),
    );
    $cols = array('id', 'name', 'priority');
    if (!empty($data['search']['value'])) {
      $params['filters'][] = array(
        'cond' => array(),
      );
      $filter_cols = array('id', 'name');
      foreach ($filter_cols as $filter_col) {
        $cond = array();
        if (!empty($params['filters'][0]['cond'])) $cond['oper'] = 'or';
        $cond['col'] = $filter_col;
        $cond['comp'] = 'like';
        $cond['val'] = '%' . $data['search']['value'] . '%';
        $params['filters'][0]['cond'][] = $cond;
      }
    }
    if (!empty($data['order'])) foreach ($data['order'] as $order) $params['order'][$cols[$order['column']]] = $order['dir'];
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
        $this->name,
        $this->priority,
      );
    }
    return $return;
  }

}

?>
