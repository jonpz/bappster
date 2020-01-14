<?php
$roles = new Roles();
$roles->get($id);
$sections = new Sections();
$sectionActions = new SectionActions();
$admin = new AdminController();
$can_delete = ($admin->is_permitted('roles', 'delete') && $roles->id != 1);
$ss = $sections->find(array('order' => array('priority' => 'desc')));
?>
<div class="row">
  <div class="col-sm-9 col-sm-offset-3">
    <h2 class="text-primary">Edit Role <?=$roles->id?></h2>
  </div>
</div>
<form id="roleEdit" class="form-horizontal" action="/admin/roles/save" method="post">
  <input type="hidden" name="id" value="<?=$roles->id?>"/>
  <div class="form-group">
    <label for="inpFname" class="col-sm-3 control-label">Name</label>
    <div class="col-sm-9">
      <input id="inpFname" type="text" class="form-control" name="name" value="<?=$roles->name?>" required/>
    </div>
  </div>
  <div class="form-group">
    <label for="inpPriority" class="col-sm-3 control-label">Priority</label>
    <div class="col-sm-9">
      <input id="inpPriority" type="number" class="form-control" name="priority" value="<?=$roles->priority?>" min="0" required/>
    </div>
  </div>
  <div class="form-group">
    <label for="inpLname" class="col-sm-3 control-label">Permissions</label>
    <div class="col-sm-9">
      <?php foreach ($ss as $i => $row) : $sections->get($row['id']); ?>
        <?php if ($i) : ?><hr/><?php endif; ?>
        <h4><?=$sections->name?></h4>
        <?php
        $as = $sectionActions->find(array('section_id' => $sections->id));
        foreach ($as as $row) :
          $sectionActions->get($row['id']);
          ?>
          <label class="checkbox-inline">
            <input class="checkbox" type="checkbox" name="section_action_id[]" value="<?=$sectionActions->id?>" <?php if (in_array($sectionActions->id, $roles->section_action_id)) echo 'checked'; ?>/> <?=$sectionActions->name?>
          </label>
        <?php endforeach; ?>
      <?php endforeach; ?>
    </div>
  </div>
  <div class="row">
    <div class="col-sm-9 col-sm-offset-3">
      <?php if ($can_delete) : ?>
        <a id="deleteObject" class="btn btn-danger" href="#">Delete</a>
      <?php endif; ?>
      <div class="btn-group pull-right">
        <input type="submit" class="btn btn-primary" value="Save"/>
        <a href="/admin/roles" class="btn btn-default">Cancel</a>
      </div>
    </div>
  </div>
</form>

<?php if ($can_delete) : ?>
  <form id="deleteForm" action="/admin/roles/delete" method="post">
    <input type="hidden" name="id" value="<?=$roles->id?>"/>
  </form>
<?php endif; ?>
