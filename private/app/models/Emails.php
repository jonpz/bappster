<?php

class Emails extends Base {

  var $id;
  var $tag;
  var $subject;
  var $html;
  var $text;
  var $vars;
  var $mailer;

  function __construct() {
    $this->cols = array('id', 'tag', 'subject', 'html', 'text', 'vars');
    $this->app_table = 'emails';
    $sections = new Sections();
    $this->app_section_id = $sections->find(array('table' => $this->app_table))[0]['id'];
    $this->id = 0;
    $this->tag = null;
    $this->subject = null;
    $this->html = null;
    $this->text = null;
    $this->vars = null;
    parent::__construct();
  }

  function sendEmail($id, $data = array()) {
    $ts = $this->find(array('tag' => 'template'));
    if (empty($ts[0]['id']) || $id == $ts[0]['id']) return false;
    $this->mailer = new PHPMailer\PHPMailer\PHPMailer();
    if ($this->html) $this->mailer->isHTML(true);
    if (empty($data['to_address'])) return false;
    $return = array();
    if (!(int) $id) {
      $search = $this->find(array('tag' => $id));
      if (!empty($search[0]['id'])) $this->get($search[0]['id']);
      if (!$this->id) return false;
    } else $this->get($id);
    $this->mailer->setFrom('no-reply@example.com', 'Example App');
    if (!empty($data['to_address']) && !empty($data['to_name'])) $this->mailer->addAddress($data['to_address'], $data['to_name']);
    elseif (!empty($data['to_address'])) $this->mailer->addAddress($data['to_address']);
    if (!empty($data['from_address']) && !empty($data['from_name'])) $this->mailer->addReplyTo($data['from_address'], $data['from_name']);
    elseif (!empty($data['from_address'])) $this->mailer->addReplyTo($data['from_address']);
    $subject = $this->subject;
    $html_head = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"><html xmlns="http://www.w3.org/1999/xhtml"><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8" /><!--[if !mso]><!--><meta http-equiv="X-UA-Compatible" content="IE=edge" /><!--<![endif]--><meta name="viewport" content="width=device-width, initial-scale=1.0"><!--[if (gte mso 9)|(IE)]><style type="text/css">table {border-collapse: collapse;}</style><![endif]--></head><body>';
    $html_foot = '</body></html>';
    $html = $this->html;
    $text = $this->text;
    $emailImages = new EmailImages();
    $eis = $emailImages->find(array('email_id' => $id));
    if ($eis) {
      foreach ($eis as $row) {
        $emailImages->get($row['id']);
        $this->mailer->AddEmbeddedImage(webRoot() . '/upload/emails/' . $emailImages->file, 'image_' . $row['id']);
        $html = str_ireplace(':image_' . $row['id'], '<img src="cid:image_' . $row['id'] . '" />', $html);
      }
    }
    if ($this->vars) $this->vars = json_decode($this->vars, false);
    if (!empty($data) && $this->vars) {
      foreach ($this->vars as $var) {
        if (!empty($data[$var])) {
          $subject = str_ireplace(':' . $var, $data[$var], $subject);
          $html = str_ireplace(':' . $var, $data[$var], $html);
          $text = str_ireplace(':' . $var, $data[$var], $text);
        }
      }
    }
    $this->get($ts[0]['id']);
    $eis = $emailImages->find(array('email_id' => $ts[0]['id']));
    if ($eis) {
      foreach ($eis as $row) {
        $emailImages->get($row['id']);
        $this->mailer->AddEmbeddedImage(webRoot() . '/upload/emails/' . $emailImages->file, 'image_' . $row['id']);
        $this->html = str_ireplace(':image_' . $row['id'], '<img src="cid:image_' . $row['id'] . '" />', $this->html);
      }
    }
    $html = str_ireplace(':content', $html, $this->html);
    $text = str_ireplace(':content', $text, $this->text);
    $this->mailer->Subject = $subject;
    $this->mailer->Body = ($html) ? $html_head . $html . $html_foot : $text;
    if ($html && $text) $this->mailer->AltBody = $text;
    if (!$this->mailer->send()) {
      $return['success'] = 0;
      $return['msg'] = $this->mailer->ErrorInfo;
    } else $return['success'] = 1;
    return $return;
  }

  function getGrid($data) {
    $params = array(
      'limit' => $data['start'] . ', ' . $data['length'],
      'order' => array(),
      'filters' => array(),
    );
    $cols = array('id', 'tag', 'subject', 'vars');
    if (!empty($data['search']['value'])) {
      $params['filters'][] = array(
        'cond' => array(),
      );
      $filter_cols = array('id', 'tag', 'subject');
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
        $this->tag,
        $this->subject,
        $this->vars,
      );
    }
    return $return;
  }

}

?>
