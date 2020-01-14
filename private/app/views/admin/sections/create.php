<div class="row">
  <div class="col-sm-9 col-sm-offset-3">
    <h2 class="text-primary">Add Section</h2>
  </div>
</div>
<form id="sectionEdit" class="form-horizontal" action="/admin/sections/save" method="post">
  <input type="hidden" name="id" value="0"/>
  <div class="form-group">
    <label for="inpFname" class="col-sm-3 control-label">Name</label>
    <div class="col-sm-9">
      <input id="inpFname" type="text" class="form-control" name="name" value="" required/>
    </div>
  </div>
  <div class="form-group">
    <label for="inpTable" class="col-sm-3 control-label">Table</label>
    <div class="col-sm-9">
      <input id="inpTable" type="text" class="form-control" name="table" value=""/>
    </div>
  </div>
  <div class="form-group">
    <label for="inpTag" class="col-sm-3 control-label">Tag</label>
    <div class="col-sm-9">
      <input id="inpTag" type="text" class="form-control" name="tag" value="" required/>
    </div>
  </div>
  <div class="form-group">
    <label for="inpReportsFrom" class="col-sm-3 control-label">Reports From</label>
    <div class="col-sm-9">
      <input id="inpReportsFrom" type="text" class="form-control" name="reports_from" value=""/>
    </div>
  </div>
  <div class="form-group">
    <label for="inpDeleted" class="col-sm-3 control-label">Hide from nav?</label>
    <div class="col-sm-9">
      <input id="inpDeleted" type="checkbox" name="hide_nav" value="1"/>
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
        <a href="/admin/sections" class="btn btn-default">Cancel</a>
      </div>
    </div>
  </div>
</form>
