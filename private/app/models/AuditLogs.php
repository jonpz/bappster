<?php

class AuditLogs extends Base {

  var $id;
  var $user_id;
  var $section_action_id;
  var $action;
  var $row_id;
  var $old_data;
  var $new_data;
  var $created_at;
  var $exec_time;
  var $is_permfail;

  function __construct() {
    $this->cols = array('id', 'user_id', 'section_action_id', 'action', 'row_id', 'old_data', 'new_data', 'created_at', 'exec_time', 'is_permfail');
    $this->app_table = 'audit_logs';
    $sections = new Sections();
    $this->app_section_id = $sections->find(array('table' => $this->app_table))[0]['id'];
    $this->id = 0;
    $this->user_id = null;
    $this->section_action_id = null;
    $this->action = null;
    $this->row_id = null;
    $this->old_data = null;
    $this->new_data = null;
    $this->created_at = null;
    $this->exec_time = null;
    $this->is_permfail = 0;
    parent::__construct();
  }

  function add($data = array()) {
    $return = array();
    $this->fromArray($data);
    $this->id = $this->create(false, false);
    if ($this->id) {
      $return = array('success' => 1, 'id' => $this->id);
    } else {
      $return = array('success' => 0, 'msg' => (is_array($this->sql->getErrorMsg())) ? implode(', ', $this->sql->getErrorMsg()) : $this->sql->getErrorMsg());
    }
    $this->__construct();
    return $return;
  }

  function remove($data) {
    if (!empty($data['id'])) $id = (int) $data['id'];
    elseif ((int) $data) $id = (int) $data;
    else return false;
    $this->get($id);
    return $this->delete(false, false);
  }

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
            $where .= $condition['col'] . ' ';
            $i = count($data);
            $where .= $condition['comp'];
            if (isset($condition['val'])) {
              $where .= ' :var' . $i . ' ';
              $data[':var' . $i] = $condition['val'];
            }
          }
          $where .= ') ';
        }
      }
    }
    $select = 'distinct(a.id)';
    if (!empty($order)) {
      $select .= ', ' . str_replace(', a.id', '', implode(', ', array_keys($order)));
    }
    $query = "SELECT $select FROM {$this->app_table} a left join users u on a.user_id = u.id left join section_actions sa on a.section_action_id = sa.id left join sections s on sa.section_id = s.id $where";
    if (!empty($order)) {
      $query .= " ORDER BY ";
      $i = 1;
      foreach ($order as $orderCol => $dir) {
        $query .= "$orderCol $dir";
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

  function getGrid($data) {
    $users = new Users();
    $sections = new Sections();
    $sectionActions = new SectionActions();
    $params = array(
      'limit' => $data['start'] . ', ' . $data['length'],
      'order' => array(),
      'filters' => array(),
    );
    $cols = array('user_name', 'section_action', 'a.row_id', 'a.old_data', 'a.new_data', 'a.created_at');
    if (!empty($data['search']['value'])) {
      $params['filters'][] = array(
        'cond' => array(),
      );
      $filter_cols = array('concat(u.first_name, " ", u.last_name)', 'if(a.section_action_id, concat(sa.name, " ", s.name), a.action)', 'a.row_id');
      foreach ($filter_cols as $filter_col) {
        $cond = array();
        if (!empty($params['filters'][0]['cond'])) $cond['oper'] = 'or';
        $cond['col'] = $filter_col;
        $cond['comp'] = 'like';
        $cond['val'] = '%' . $data['search']['value'] . '%';
        $params['filters'][0]['cond'][] = $cond;
      }
    }
    if (!empty($data['order'])) foreach ($data['order'] as $order) {
      if ($cols[$order['column']] === 'user_name') {
        $params['order']['u.last_name'] = $order['dir'];
        $params['order']['u.first_name'] = $order['dir'];
      } elseif ($cols[$order['column']] === 'section_action') {
        $params['order']['(if(a.section_action_id, concat(sa.name, " ", s.name), a.action))'] = $order['dir'];
      } else $params['order'][$cols[$order['column']]] = $order['dir'];
    }
    $params['order']['a.id'] = 'desc';
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
      $users->get($this->user_id);
      $sectionActions->get($this->section_action_id);
      $sections->get($sectionActions->section_id);
      if ($this->old_data) $this->old_data = json_decode($this->old_data, true);
      if ($this->new_data) $this->new_data = json_decode($this->new_data, true);
      $return['data'][] = array(
        $users->first_name . ' ' . $users->last_name,
        ($sectionActions->name && $sections->name) ? $sectionActions->name . ' ' . $sections->name : $this->action,
        $this->row_id,
        ($this->old_data) ? '<pre>' . print_r($this->old_data, true) . '</pre>' : '',
        ($this->new_data) ? '<pre>' . print_r($this->new_data, true) . '</pre>' : '',
        ($this->created_at) ? date('F j, Y g:i a', strtotime($this->created_at)) : '',
      );
    }
    return $return;
  }

}

?>
