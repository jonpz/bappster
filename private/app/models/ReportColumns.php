<?php

class ReportColumns extends Base {

  var $id;
  var $name;
  var $select;
  var $array_index;
  var $datatype;
  var $visible;
  var $nullable;
  var $section_id;

  function __construct() {
    $this->cols = array('id', 'name', 'select', 'array_index', 'datatype', 'visible', 'nullable', 'section_id');
    $this->app_table = 'report_cols';
    $sections = new Sections();
    $this->app_section_id = $sections->find(array('table' => $this->app_table))[0]['id'];
    $this->id = 0;
    $this->name = null;
    $this->select = null;
    $this->array_index = null;
    $this->datatype = null;
    $this->visible = 0;
    $this->nullable = 1;
    $this->section_id = null;
    parent::__construct();
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
    $select = 'distinct(r.id)';
    if (!empty($order)) {
      $select .= ', ' . implode(', ', array_keys($order));
    }
    $query = "SELECT $select FROM {$this->app_table} r left join sections s on r.section_id = s.id $where";
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
    $params = array(
      'limit' => $data['start'] . ', ' . $data['length'],
      'order' => array(),
      'filters' => array(),
    );
    $cols = array('r.id', 'r.name', 'r.array_index', 'r.datatype', 'r.visible', 'r.nullable', 's.name');
    if (!empty($data['search']['value'])) {
      $params['filters'][] = array(
        'cond' => array(),
      );
      $filter_cols = array('r.id', 'r.name', 'r.array_index', 'r.datatype', 's.name');
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
    $sections = new Sections();
    foreach ($gs as $row) {
      $this->get($row['id']);
      $sections->get($this->section_id);
      $return['data'][] = array(
        $this->id,
        $this->name,
        $this->array_index,
        $this->datatype,
        ($this->visible) ? 'Yes' : 'No',
        ($this->nullable) ? 'Yes' : 'No',
        $sections->name,
      );
    }
    return $return;
  }

}

?>
