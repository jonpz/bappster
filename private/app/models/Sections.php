<?php

class Sections extends Base {

  var $id;
  var $name;
  var $table;
  var $tag;
  var $model_name;
  var $reports_from;
  var $hide_nav;
  var $priority;

  function __construct() {
    $this->cols = array('id', 'name', 'table', 'tag', 'model_name', 'reports_from', 'hide_nav', 'priority');
    $this->app_table = 'sections';
    $this->app_section_id = 4;
    $this->id = 0;
    $this->name = null;
    $this->table = null;
    $this->tag = null;
    $this->model_name = null;
    $this->reports_from = null;
    $this->hide_nav = 0;
    $this->priority = 0;
    parent::__construct();
  }

  function get($id) {
    if ((int) $id) $this->id = $id;
    else {
      $search = $this->find(array('tag' => $id));
      if ($search) $this->id = $search[0]['id'];
    }
    if (!$this->id) return false;
    $this->read();
    return true;
  }

  function getGrid($data) {
    $params = array(
      'limit' => $data['start'] . ', ' . $data['length'],
      'order' => array(),
      'filters' => array(),
    );
    $cols = array('id', 'name', 'table', 'tag', 'hide_nav', 'priority');
    if (!empty($data['search']['value'])) {
      $params['filters'][] = array(
        'cond' => array(),
      );
      $filter_cols = array('id', 'name', 'table');
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
        $this->table,
        $this->tag,
        ($this->hide_nav) ? 'Yes' : 'No',
        $this->priority,
      );
    }
    return $return;
  }

}

?>
