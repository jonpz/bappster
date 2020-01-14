<?php
$users = new Users();
$roles = new Roles();
$users->get($_SESSION['user_id']);
$roles->get($users->role_id);
$user_priority = $roles->priority;
$users->get($id);
$roles->get($users->role_id);
$form_priority = $roles->priority;
$rs = $roles->find(array('order' => array('priority' => 'desc')));
?>
<div class="row">
  <div class="col-sm-9 col-sm-offset-3">
    <h2 class="text-primary">Edit User <?=$users->id?></h2>
  </div>
</div>
<form id="userEdit" class="form-horizontal" action="/admin/users/save" method="post">
  <input type="hidden" name="id" value="<?=$users->id?>"/>
  <div class="form-group">
    <label for="inpFname" class="col-sm-3 control-label">First Name</label>
    <div class="col-sm-9">
      <input id="inpFname" type="text" class="form-control" name="first_name" value="<?=$users->first_name?>" required <?php if ($form_priority > $user_priority) echo 'disabled'; ?>/>
    </div>
  </div>
  <div class="form-group">
    <label for="inpLname" class="col-sm-3 control-label">Last Name</label>
    <div class="col-sm-9">
      <input id="inpLname" type="text" class="form-control" name="last_name" value="<?=$users->last_name?>" required <?php if ($form_priority > $user_priority) echo 'disabled'; ?>/>
    </div>
  </div>
  <div class="form-group">
    <label for="inpEmail" class="col-sm-3 control-label">Email</label>
    <div class="col-sm-9">
      <input id="inpEmail" type="text" class="form-control" name="email" value="<?=$users->email?>" required <?php if ($form_priority > $user_priority) echo 'disabled'; ?>/>
    </div>
  </div>
  <div class="form-group">
    <label for="inpPasswd" class="col-sm-3 control-label">Password</label>
    <div class="col-sm-9">
      <input id="inpPasswd" type="password" class="form-control" name="password" <?php if ($form_priority > $user_priority) echo 'disabled'; ?>/>
    </div>
  </div>
  <div class="form-group">
    <label for="inpPasswdC" class="col-sm-3 control-label">Confirm</label>
    <div class="col-sm-9">
      <input id="inpPasswdC" type="password" class="form-control" name="password_conf" <?php if ($form_priority > $user_priority) echo 'disabled'; ?>/>
      <small><em>Leave these fields blank to leave password unaltered.</em></small>
    </div>
  </div>
  <div class="form-group">
    <label for="inpRole" class="col-sm-3 control-label">Role</label>
    <div class="col-sm-9">
      <select id="inpRole" class="form-control" name="role_id" <?php if ($form_priority > $user_priority) echo 'disabled'; ?>>
        <option value="" <?php if (!$users->role_id) echo 'selected'; ?>>None</option>
        <?php foreach ($rs as $row) : $roles->get($row['id']); ?>
          <option value="<?=$roles->id?>" <?php if ($users->role_id === $roles->id) echo 'selected'; ?> <?php if ($roles->priority > $user_priority) echo 'disabled'; ?>><?=$roles->name?></option>
        <?php endforeach; ?>
      </select>
    </div>
  </div>
  <div class="form-group">
    <label for="inpDeleted" class="col-sm-3 control-label">Deleted?</label>
    <div class="col-sm-9">
      <input id="inpDeleted" type="checkbox" name="deleted" value="1" <?php if ($users->deleted) echo 'checked'; ?> <?php if ($form_priority > $user_priority) echo 'disabled'; ?>/>
    </div>
  </div>
  <div class="row">
    <div class="col-sm-9 col-sm-offset-3">
      <div class="btn-group pull-right">
        <?php if ($form_priority <= $user_priority) : ?><input type="submit" class="btn btn-primary" value="Save"/><?php endif; ?>
        <a href="/admin/users" class="btn btn-default">Cancel</a>
      </div>
    </div>
  </div>
</form>
