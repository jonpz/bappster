<div class="row">
  <div class="col-sm-9 col-sm-offset-3">
    <h2 class="text-primary">Add Email</h2>
  </div>
</div>
<form id="emailEdit" class="form-horizontal" action="/admin/emails/save" method="post">
  <input type="hidden" name="id" value="0"/>
  <div class="form-group">
    <label for="inpTag" class="col-sm-3 control-label">Tag</label>
    <div class="col-sm-9">
      <input id="inpTag" type="text" class="form-control" name="tag" value="" required/>
    </div>
  </div>
  <div class="form-group">
    <label for="inpSubject" class="col-sm-3 control-label">Subject</label>
    <div class="col-sm-9">
      <input id="inpSubject" type="text" class="form-control" name="subject" value=""/>
    </div>
  </div>
  <div class="form-group">
    <label for="inpHTML" class="col-sm-3 control-label">HTML</label>
    <div class="col-sm-9">
      <textarea id="inpHTML" class="form-control" name="html" rows="7"></textarea>
    </div>
  </div>
  <div class="form-group">
    <label for="inpText" class="col-sm-3 control-label">Text</label>
    <div class="col-sm-9">
      <textarea id="inpText" class="form-control" name="text" rows="7"></textarea>
    </div>
  </div>
  <div class="form-group">
    <label for="inpVars" class="col-sm-3 control-label">Variables (JSON)</label>
    <div class="col-sm-9">
      <input id="inpVars" type="text" class="form-control" name="vars" value=""/>
    </div>
  </div>
  <div class="row">
    <div class="col-sm-9 col-sm-offset-3">
      <div class="btn-group pull-right">
        <input type="submit" class="btn btn-primary" value="Save"/>
        <a href="/admin/emails" class="btn btn-default">Cancel</a>
      </div>
    </div>
  </div>
</form>
