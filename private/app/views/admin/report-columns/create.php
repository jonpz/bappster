<?php
$sections = new Sections();
$ss = $sections->find(array('order' => array('priority' => 'desc')));
?>
<div class="row">
  <div class="col-sm-9 col-sm-offset-3">
    <h2 class="text-primary">Add Report Column</h2>
  </div>
</div>
<form id="reportColsEdit" class="form-horizontal" action="/admin/report-columns/save" method="post">
  <input type="hidden" name="id" value="0"/>
  <div class="form-group">
    <label for="inpFname" class="col-sm-3 control-label">Name</label>
    <div class="col-sm-9">
      <input id="inpFname" type="text" class="form-control" name="name" value="" required/>
    </div>
  </div>
  <div class="form-group">
    <label for="inpSelect" class="col-sm-3 control-label">Select</label>
    <div class="col-sm-9">
      <input id="inpSelect" type="text" class="form-control" name="select" value="" required/>
    </div>
  </div>
  <div class="form-group">
    <label for="inpArrayIndex" class="col-sm-3 control-label">Array Index</label>
    <div class="col-sm-9">
      <input id="inpArrayIndex" type="text" class="form-control" name="array_index" value="" required/>
    </div>
  </div>
  <div class="form-group">
    <label for="inpDatatype" class="col-sm-3 control-label">Datatype</label>
    <div class="col-sm-9">
      <input id="inpDatatype" type="text" class="form-control" name="datatype" value="" required/>
    </div>
  </div>
  <div class="form-group">
    <label for="inpVisible" class="col-sm-3 control-label">Visible?</label>
    <div class="col-sm-9">
      <input id="inpVisible" type="checkbox" name="visible" value="1"/>
    </div>
  </div>
  <div class="form-group">
    <label for="inpNullable" class="col-sm-3 control-label">Nullable?</label>
    <div class="col-sm-9">
      <input id="inpNullable" type="checkbox" name="nullable" value="1"/>
    </div>
  </div>
  <div class="form-group">
    <label for="inpSection" class="col-sm-3 control-label">Section</label>
    <div class="col-sm-9">
      <select id="inpSection" class="form-control" name="section_id" required>
        <option value="" hidden disabled selected>Please select one...</option>
        <?php foreach ($ss as $row) : $sections->get($row['id']); ?>
          <option value="<?=$sections->id?>"><?=$sections->name?></option>
        <?php endforeach; ?>
      </select>
    </div>
  </div>
  <div class="row">
    <div class="col-sm-9 col-sm-offset-3">
      <div class="btn-group pull-right">
        <input type="submit" class="btn btn-primary" value="Save"/>
        <a href="/admin/report-columns" class="btn btn-default">Cancel</a>
      </div>
    </div>
  </div>
</form>
