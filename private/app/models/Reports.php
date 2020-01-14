<?php

class Reports extends Base {

  var $id;
  var $name;
  var $config;
  var $section_id;
  var $rpt_cols;
  var $from;

  function __construct($sid = 0) {
    $this->cols = array('id', 'name', 'config', 'section_id');
    $this->app_table = 'reports';
    $sections = new Sections();
    $this->app_section_id = $sections->find(array('table' => $this->app_table))[0]['id'];
    $report_columns = new ReportColumns();
    $this->id = 0;
    $this->name = null;
    $this->config = null;
    $sections->get($sid);
    $this->section_id = $sections->id;
    parent::__construct();
    $this->rpt_cols = $this->getReportCols($this->section_id);
    $this->from = $sections->reports_from;
  }

  function getResults($params = array()) {
    $selects = array();
    $order = array();
    if (! empty($params['groups'])) {
      foreach ($params['groups'] as $row) {
        $order[] = preg_replace('/^.*\sas\s/', '', $this->rpt_cols[$row['col']]['select']) . ' ' . $row['drc'];
        $selects[] = $this->rpt_cols[$row['col']]['select'];
      }
    }
    if (! empty($params['order'])) {
      foreach ($params['order'] as $row) {
        $order[] = preg_replace('/^.*\sas\s/', '', $this->rpt_cols[$row['col']]['select']) . ' ' . $row['drc'];
      }
    }
    $order = ($order) ? 'order by ' . implode(', ', $order) : '';
    if (! isset($params['cols']) || empty($params['cols'])) return false;
    foreach ($params['cols'] as $i) {
      if (array_search($this->rpt_cols[$i]['select'], $selects) === false) $selects[] = $this->rpt_cols[$i]['select'];
    }
    $select = implode(', ', $selects);
    $where = '';
    $data = array();
    if (! empty($params['filters'])) {
      foreach ($params['filters'] as $filter_group) {
        if (isset($filter_group['oper'])) $where .= $filter_group['oper'] . ' ';
        else $where .= 'where ';
        $where .= '( ';
        foreach ($filter_group['cond'] as $condition) {
          if (isset($condition['oper'])) $where .= $condition['oper'] . ' ';
          $where .= $condition['col'] . ' ';
          $report_columns = new ReportColumns();
          $rcs = $report_columns->find(array('select' => $condition['col'], 'section_id' => $this->section_id));
          if ($rcs) $report_columns->get($rcs[0]['id']);
          if ($report_columns->nullable && empty($condition['val'])) {
            if ($condition['comp'] === '=') $where .= 'is null ';
            else $where .= 'is not null ';
          } else {
            $i = count($data);
            $where .= $condition['comp'] . ' :var' . $i . ' ';
            $data[':var' . $i] = $condition['val'];
          }
        }
        $where .= ') ';
      }
    }
    $query = 'select ' . $select . ' from ' . $this->from . ' ' . $where . ' ' . $order;
    if ($data) $result = $this->sql->query($query, $data);
    else $result = $this->sql->query($query);
    return $result;
  }

  function selectDistinct($col, $table) {
    return $this->sql->query("select distinct($col) from $table where $col is not null order by $col");
  }

  function getReportCols($section) {
    if (!(int) $section) {
      $sections = new Sections();
      $ss = $sections->find(array('tag' => $section));
      if (!$ss) return false;
      $id = $ss[0]['id'];
    } else $id = $section;
    return $this->sql->query('select `select`,name as display, datatype, visible, nullable, array_index as `index` from report_cols where section_id = ' . $id);
  }

}

?>
