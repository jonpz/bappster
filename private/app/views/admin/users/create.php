<?php
$roles = new Roles();
$rs = $roles->find(array('order' => array('priority' => 'desc')));
$users = new Users();
$users->get($_SESSION['user_id']);
$roles->get($users->role_id);
$user_priority = $roles->priority;
?>
<div class="row">
  <div class="col-sm-9 col-sm-offset-3">
    <h2 class="text-primary">Add User</h2>
  </div>
</div>
<form id="userEdit" class="form-horizontal" action="/admin/users/save" method="post">
  <input type="hidden" name="id" value="0"/>
  <div class="form-group">
    <label for="inpFname" class="col-sm-3 control-label">First Name</label>
    <div class="col-sm-9">
      <input id="inpFname" type="text" class="form-control" name="first_name" value="" required/>
    </div>
  </div>
  <div class="form-group">
    <label for="inpLname" class="col-sm-3 control-label">Last Name</label>
    <div class="col-sm-9">
      <input id="inpLname" type="text" class="form-control" name="last_name" value="" required/>
    </div>
  </div>
  <div class="form-group">
    <label for="inpEmail" class="col-sm-3 control-label">Email</label>
    <div class="col-sm-9">
      <input id="inpEmail" type="text" class="form-control" name="email" value="" required/>
    </div>
  </div>
  <div class="form-group">
    <label for="inpPasswd" class="col-sm-3 control-label">Password</label>
    <div class="col-sm-9">
      <input id="inpPasswd" type="password" class="form-control" name="password"/>
    </div>
  </div>
  <div class="form-group">
    <label for="inpPasswdC" class="col-sm-3 control-label">Confirm</label>
    <div class="col-sm-9">
      <input id="inpPasswdC" type="password" class="form-control" name="password_conf"/>
      <small><em>Leave these fields blank to leave password unaltered.</em></small>
    </div>
  </div>
  <div class="form-group">
    <label for="inpRole" class="col-sm-3 control-label">Role</label>
    <div class="col-sm-9">
      <select id="inpRole" class="form-control" name="role_id">
        <option value="">None</option>
        <?php foreach ($rs as $row) : $roles->get($row['id']); ?>
          <option value="<?=$roles->id?>" <?php if ($roles->priority > $user_priority) echo 'disabled'; ?>><?=$roles->name?></option>
        <?php endforeach; ?>
      </select>
    </div>
  </div>
  <div class="row">
    <div class="col-sm-9 col-sm-offset-3">
      <div class="btn-group pull-right">
        <input type="submit" class="btn btn-primary" value="Save"/>
        <a href="/admin/users" class="btn btn-default">Cancel</a>
      </div>
    </div>
  </div>
</form>
