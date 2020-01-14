<?php
$sections = new Sections();
$sectionActions = new SectionActions();
$admin = new AdminController();
$can_delete = ($admin->is_permitted('sections', 'delete') && $sections->id > 8);
$sections->get($id);
$sas = $sectionActions->find(array('section_id' => $sections->id));
?>
<div class="row">
  <div class="col-sm-9 col-sm-offset-3">
    <h2 class="text-primary">Edit Section <?=$sections->id?></h2>
  </div>
</div>
<form id="sectionEdit" class="form-horizontal" action="/admin/sections/save" method="post">
  <input type="hidden" name="id" value="<?=$sections->id?>"/>
  <div class="form-group">
    <label for="inpFname" class="col-sm-3 control-label">Name</label>
    <div class="col-sm-9">
      <input id="inpFname" type="text" class="form-control" name="name" value="<?=$sections->name?>" required/>
    </div>
  </div>
  <div class="form-group">
    <label for="inpTable" class="col-sm-3 control-label">Table</label>
    <div class="col-sm-9">
      <input id="inpTable" type="text" class="form-control" name="table" value="<?=$sections->table?>"/>
    </div>
  </div>
  <div class="form-group">
    <label for="inpTag" class="col-sm-3 control-label">Tag</label>
    <div class="col-sm-9">
      <input id="inpTag" type="text" class="form-control" name="tag" value="<?=$sections->tag?>" required/>
    </div>
  </div>
  <div class="form-group">
    <label for="inpReportsFrom" class="col-sm-3 control-label">Reports From</label>
    <div class="col-sm-9">
      <input id="inpReportsFrom" type="text" class="form-control" name="reports_from" value="<?=$sections->reports_from?>"/>
    </div>
  </div>
  <div class="form-group">
    <label for="inpDeleted" class="col-sm-3 control-label">Hide from nav?</label>
    <div class="col-sm-9">
      <input id="inpDeleted" type="checkbox" name="hide_nav" value="1" <?php if ($sections->hide_nav) echo 'checked'; ?>/>
    </div>
  </div>
  <div class="form-group">
    <label for="inpPriority" class="col-sm-3 control-label">Priority</label>
    <div class="col-sm-9">
      <input id="inpPriority" type="number" class="form-control" name="priority" value="<?=$sections->priority?>" min="0" required/>
    </div>
  </div>
  <div class="form-group">
    <label class="col-sm-3 control-label">Section Actions</label>
    <div class="col-sm-9">
      <a class="btn btn-primary pull-right" data-toggle="modal" href="#addSAModal">Add</a>
      <table id="sectionActionsGrid" class="datatable table table-striped no-pointer">
          <thead>
              <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Tag</th>
                <th>Hide from nav?</th>
                <th>Priority</th>
                <th></th>
              </tr>
          </thead>
          <tbody>
            <?php foreach ($sas as $row) : $sectionActions->get($row['id']); ?>
              <tr>
                <td><?=$sectionActions->id?></td>
                <td><?=$sectionActions->name?></td>
                <td><?=$sectionActions->tag?></td>
                <td><?=($sectionActions->hide_nav) ? 'Yes' : 'No'?></td>
                <td><?=$sectionActions->priority?></td>
                <td><a class="action-link edit" data-said="<?=$sectionActions->id?>" href="#editSAModal" data-toggle="modal"><i class="fa fa-lg fa-edit"></i></a><a class="action-link delete" data-said="<?=$sectionActions->id?>" href="#deleteSAModal" data-toggle="modal"><i class="fa fa-lg fa-trash"></i></a></td>
              </tr>
            <?php endforeach; ?>
          </tbody>
      </table>
    </div>
  </div>
  <div class="row">
    <div class="col-sm-9 col-sm-offset-3">
      <?php if ($can_delete) : ?>
        <a id="deleteObject" class="btn btn-danger" href="#">Delete</a>
      <?php endif; ?>
      <div class="btn-group pull-right">
        <input type="submit" class="btn btn-primary" value="Save"/>
        <a href="/admin/sections" class="btn btn-default">Cancel</a>
      </div>
    </div>
  </div>
</form>

<?php if ($can_delete) : ?>
  <form id="deleteForm" action="/admin/sections/delete" method="post">
    <input type="hidden" name="id" value="<?=$sections->id?>"/>
  </form>
<?php endif; ?>

<div id="addSAModal" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <form class="form-horizontal sa-form" method="post" action="/admin/section-actions/save">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Add Section Action</h4>
        </div>
        <div class="modal-body">
          <input type="hidden" name="id" value="0"/>
          <input type="hidden" name="section_id" value="<?=$sections->id?>"/>
          <div class="form-group">
            <label for="inpASAName" class="col-sm-3 control-label">Name</label>
            <div class="col-sm-9">
              <input id="inpASAName" type="text" class="form-control" name="name" value="" required/>
            </div>
          </div>
          <div class="form-group">
            <label for="inpASATag" class="col-sm-3 control-label">Tag</label>
            <div class="col-sm-9">
              <input id="inpASATag" type="text" class="form-control" name="tag" value="" required/>
            </div>
          </div>
          <div class="form-group">
            <label for="inpASAHideNav" class="col-sm-3 control-label">Hide from nav?</label>
            <div class="col-sm-9">
              <input id="inpASAHideNav" type="checkbox" name="hide_nav" value="1"/>
            </div>
          </div>
          <div class="form-group">
            <label for="inpASAPriority" class="col-sm-3 control-label">Priority</label>
            <div class="col-sm-9">
              <input id="inpASAPriority" type="number" class="form-control" name="priority" value="0" min="0" required/>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <div class="btn-group">
            <button type="button" class="btn btn-default samodal-dismiss" data-dismiss="modal">Cancel</button>
            <input type="submit" value="Save" class="btn btn-primary"/>
          </div>
        </div>
      </div>
    </form>
  </div>
</div>

<div id="editSAModal" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <form class="form-horizontal sa-form" method="post" action="/admin/section-actions/save">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Edit Section Action</h4>
        </div>
        <div class="modal-body">
          <input id="inpESAID" type="hidden" name="id" value=""/>
          <input type="hidden" name="section_id" value="<?=$sections->id?>"/>
          <div class="form-group">
            <label for="inpESAName" class="col-sm-3 control-label">Name</label>
            <div class="col-sm-9">
              <input id="inpESAName" type="text" class="form-control" name="name" value="" required/>
            </div>
          </div>
          <div class="form-group">
            <label for="inpESATag" class="col-sm-3 control-label">Tag</label>
            <div class="col-sm-9">
              <input id="inpESATag" type="text" class="form-control" name="tag" value="" required/>
            </div>
          </div>
          <div class="form-group">
            <label for="inpESAHideNav" class="col-sm-3 control-label">Hide from nav?</label>
            <div class="col-sm-9">
              <input id="inpESAHideNav" type="checkbox" name="hide_nav" value="1"/>
            </div>
          </div>
          <div class="form-group">
            <label for="inpESAPriority" class="col-sm-3 control-label">Priority</label>
            <div class="col-sm-9">
              <input id="inpESAPriority" type="number" class="form-control" name="priority" value="0" min="0" required/>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <div class="btn-group">
            <button type="button" class="btn btn-default samodal-dismiss" data-dismiss="modal">Cancel</button>
            <input type="submit" value="Save" class="btn btn-primary"/>
          </div>
        </div>
      </div>
    </form>
  </div>
</div>

<div id="deleteSAModal" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <form class="form-horizontal sa-form" method="post" action="/admin/section-actions/delete">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Delete Section Action</h4>
        </div>
        <div class="modal-body">
          <p>Are you sure you want to delete the action below? This action cannot be undone.</p>
          <input id="inpDSAID" type="hidden" name="id" value=""/>
          <div class="form-group">
            <label for="inpDSAName" class="col-sm-3 control-label">Name</label>
            <div class="col-sm-9">
              <span id="inpDSAName" style="line-height:1.9;"></span>
            </div>
          </div>
          <div class="form-group">
            <label for="inpDSATag" class="col-sm-3 control-label">Tag</label>
            <div class="col-sm-9">
              <span id="inpDSATag" style="line-height:1.9;"></span>
            </div>
          </div>
          <div class="form-group">
            <label for="inpDSAHideNav" class="col-sm-3 control-label">Hide from nav?</label>
            <div class="col-sm-9">
              <span id="inpDSAHideNav" style="line-height:1.9;"></span>
            </div>
          </div>
          <div class="form-group">
            <label for="inpDSAPriority" class="col-sm-3 control-label">Priority</label>
            <div class="col-sm-9">
              <span id="inpDSAPriority" style="line-height:1.9;"></span>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <div class="btn-group">
            <button type="button" class="btn btn-default samodal-dismiss" data-dismiss="modal">Cancel</button>
            <input type="submit" value="Delete" class="btn btn-danger"/>
          </div>
        </div>
      </div>
    </form>
  </div>
</div>
