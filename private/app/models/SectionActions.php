<?php

class SectionActions extends Base {

  var $id;
  var $name;
  var $tag;
  var $section_id;
  var $hide_nav;
  var $priority;

  function __construct() {
    $this->cols = array('id', 'name', 'tag', 'section_id', 'hide_nav', 'priority');
    $this->app_table = 'section_actions';
    $this->app_section_id = null;
    $this->id = 0;
    $this->name = null;
    $this->tag = null;
    $this->section_id = null;
    $this->hide_nav = 0;
    $this->priority = 0;
    parent::__construct();
  }

  function add($data = array()) {
    $return = array();
    // import data
    $this->fromArray($data);
    // run it, return results
    $this->id = $this->create(false, true);
    if ($this->id) {
      $return = array('success' => 1, 'id' => $this->id);
    } else {
      $return = array('success' => 0, 'msg' => (is_array($this->sql->getErrorMsg())) ? implode(', ', $this->sql->getErrorMsg()) : $this->sql->getErrorMsg());
    }
    $this->__construct();
    return $return;
  }

  function edit($data = array()) {
    $return = array();
    $this->get($data['id']);
    // import data
    $this->fromArray($data);
    // run it, return results
    $ret = $this->update(false, true);
    if ($ret) {
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
    $return = array();
    $this->get($id);
    $ret = $this->delete(false, true);
    if ($ret) $return = array('success' => 1, 'id' => $id);
    else $return = array('success' => 0, 'msg' => (is_array($this->sql->getErrorMsg())) ? implode(', ', $this->sql->getErrorMsg()) : $this->sql->getErrorMsg());
    return $return;
  }

}

?>
