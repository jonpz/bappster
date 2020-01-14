<?php

class EmailImages extends Base {

  var $id;
  var $file;
  var $email_id;

  function __construct() {
    $this->cols = array('id', 'file', 'email_id');
    $this->app_table = 'email_images';
    $this->app_section_id = null;
    $this->id = 0;
    $this->file = null;
    $this->email_id = null;
    parent::__construct();
  }

  function add($data = array(), $files = array()) {
    $return = array();
    // import data
    $this->fromArray($data);
    if (!empty($files['file']['name'])) {
      $filename = str_replace(' ', '_', $files['file']['name']);
      if (move_uploaded_file($files['file']['tmp_name'], webRoot() . "/upload/emails/" . $filename)) {
        if (@is_array(getimagesize(webRoot() . "/upload/emails/" . $filename))) {
          $ext = pathinfo(webRoot() . "/upload/emails/" . $filename, PATHINFO_EXTENSION);
          if (preg_match('/(jpe?g)?(png)?(gif)?/i', $ext)) $this->file = $filename;
          else $return = array('success' => 0, 'msg' => "The image must have a 'png','jpg' or 'gif' extension");
        } else $return = array('success' => 0, 'msg' => "Could not get file size for the uploaded image");
      } else $return = array('success' => 0, 'msg' => "Error storing upload from temp file");
    }
    if (empty($return)) $this->id = $this->create(false, true);
    if ($this->id) {
      $return = array('success' => 1, 'id' => $this->id);
      $new_file = $this->id . '-' . $this->file;
      rename(webRoot() . '/upload/emails/' . $this->file, webRoot() . '/upload/emails/' . $new_file);
      $this->file = $new_file;
      $this->update(false, true);
    } elseif (empty($return)) $return = array('success' => 0, 'msg' => (is_array($this->sql->getErrorMsg())) ? implode(', ', $this->sql->getErrorMsg()) : $this->sql->getErrorMsg());
    $this->__construct();
    return $return;
  }

  function remove($data) {
    if (!empty($data['id'])) $id = (int) $data['id'];
    elseif ((int) $data) $id = (int) $data;
    else return false;
    $return = array();
    $this->get($id);
    unlink(webRoot() . '/upload/emails/' . $this->file);
    $ret = $this->delete(false, true);
    if ($ret) $return = array('success' => 1, 'id' => $id);
    else $return = array('success' => 0, 'msg' => (is_array($this->sql->getErrorMsg())) ? implode(', ', $this->sql->getErrorMsg()) : $this->sql->getErrorMsg());
    return $return;
  }

}

?>
