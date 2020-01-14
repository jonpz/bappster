<?php
$emails = new Emails();
$emails->get($id);
$emailImages = new EmailImages();
$eis = $emailImages->find(array('email_id' => $emails->id, 'order' => array('id' => 'asc')));
$admin = new AdminController();
$can_delete = ($admin->is_permitted('emails', 'delete') && $emails->id != 1);
$is_add = $admin->is_permitted('emails', 'create');
?>
<div class="row">
  <div class="col-sm-9 col-sm-offset-3">
    <h2 class="text-primary">Edit Email <?=$emails->id?></h2>
  </div>
</div>
<form id="sectionEdit" class="form-horizontal" action="/admin/emails/save" method="post">
  <input type="hidden" name="id" value="<?=$emails->id?>"/>
  <div class="form-group">
    <label for="inpTag" class="col-sm-3 control-label">Tag</label>
    <div class="col-sm-9">
      <input id="inpTag" type="text" class="form-control" name="tag" value="<?=$emails->tag?>" required <?php if (!$is_add) echo 'disabled'; ?>/>
    </div>
  </div>
  <div class="form-group">
    <label for="inpSubject" class="col-sm-3 control-label">Subject</label>
    <div class="col-sm-9">
      <input id="inpSubject" type="text" class="form-control" name="subject" value="<?=$emails->subject?>"/>
    </div>
  </div>
  <div class="form-group">
    <label for="inpHTML" class="col-sm-3 control-label">HTML</label>
    <div class="col-sm-9">
      <textarea id="inpHTML" class="form-control" name="html" rows="7"><?=$emails->html?></textarea>
    </div>
  </div>
  <div class="form-group">
    <label for="inpText" class="col-sm-3 control-label">Text</label>
    <div class="col-sm-9">
      <textarea id="inpText" class="form-control" name="text" rows="7"><?=$emails->text?></textarea>
    </div>
  </div>
  <div class="form-group">
    <label for="inpVars" class="col-sm-3 control-label">Variables (JSON)</label>
    <div class="col-sm-9">
      <input id="inpVars" type="text" class="form-control" name="vars" value='<?=$emails->vars?>' <?php if (!$is_add) echo 'disabled'; ?>/>
    </div>
  </div>
  <div class="form-group">
    <label class="col-sm-3 control-label">Email Images</label>
    <div class="col-sm-9">
      <a class="btn btn-primary pull-right" data-toggle="modal" href="#addEIModal">Add</a>
      <table id="emailImagesGrid" class="datatable table table-striped no-pointer">
          <thead>
              <tr>
                <th>Variable</th>
                <th>File</th>
                <th></th>
              </tr>
          </thead>
          <tbody>
            <?php foreach ($eis as $row) : $emailImages->get($row['id']); ?>
              <tr>
                <td>image_<?=$emailImages->id?></td>
                <td><?php if ($emailImages->file) : ?><img src="/upload/emails/<?=$emailImages->file?>" class="img-responsive"/><?php endif; ?></td>
                <td><a class="action-link delete" data-said="<?=$emailImages->id?>" href="#deleteEIModal" data-toggle="modal"><i class="fa fa-lg fa-trash"></i></a></td>
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
        <a href="/admin/emails" class="btn btn-default">Cancel</a>
      </div>
    </div>
  </div>
</form>

<?php if ($can_delete) : ?>
  <form id="deleteForm" action="/admin/emails/delete" method="post">
    <input type="hidden" name="id" value="<?=$emails->id?>"/>
  </form>
<?php endif; ?>

<div id="addEIModal" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <form class="form-horizontal ei-form" method="post" action="/admin/email-images/save" enctype="multipart/form-data">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Add Email Image</h4>
        </div>
        <div class="modal-body">
          <input type="hidden" name="id" value="0"/>
          <input type="hidden" name="email_id" value="<?=$emails->id?>"/>
          <div class="form-group">
            <label for="inpAEIFile" class="col-sm-3 control-label">File</label>
            <div class="col-sm-9">
              <input id="inpAEIFile" type="file" name="file" value="" required/>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <div class="btn-group">
            <button type="button" class="btn btn-default eimodal-dismiss" data-dismiss="modal">Cancel</button>
            <input type="submit" value="Save" class="btn btn-primary"/>
          </div>
        </div>
      </div>
    </form>
  </div>
</div>

<div id="deleteEIModal" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <form class="form-horizontal ei-form" method="post" action="/admin/email-images/delete">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Delete Email Image</h4>
        </div>
        <div class="modal-body">
          <p>Are you sure you want to delete the image below? This action cannot be undone.</p>
          <input id="inpDEIID" type="hidden" name="id" value=""/>
          <div class="form-group">
            <label for="inpDEIFile" class="col-sm-3 control-label">File</label>
            <div class="col-sm-9">
              <span id="inpDEIFile" style="line-height:1.9;"></span>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <div class="btn-group">
            <button type="button" class="btn btn-default eimodal-dismiss" data-dismiss="modal">Cancel</button>
            <input type="submit" value="Delete" class="btn btn-danger"/>
          </div>
        </div>
      </div>
    </form>
  </div>
</div>
