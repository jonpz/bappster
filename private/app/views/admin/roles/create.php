<div class="row">
  <div class="col-sm-9 col-sm-offset-3">
    <h2 class="text-primary">Add Role</h2>
  </div>
</div>
<form id="roleEdit" class="form-horizontal" action="/admin/roles/save" method="post">
  <input type="hidden" name="id" value="0"/>
  <div class="form-group">
    <label for="inpFname" class="col-sm-3 control-label">Name</label>
    <div class="col-sm-9">
      <input id="inpFname" type="text" class="form-control" name="name" value="" required/>
    </div>
  </div>
  <div class="form-group">
    <label for="inpPriority" class="col-sm-3 control-label">Priority</label>
    <div class="col-sm-9">
      <input id="inpPriority" type="number" class="form-control" name="priority" value="0" min="0" required/>
    </div>
  </div>
  <div class="row">
    <div class="col-sm-9 col-sm-offset-3">
      <div class="btn-group pull-right">
        <input type="submit" class="btn btn-primary" value="Save"/>
        <a href="/admin/roles" class="btn btn-default">Cancel</a>
      </div>
    </div>
  </div>
</form>
