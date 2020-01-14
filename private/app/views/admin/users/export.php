<?php
$reports = new Reports('users');
$admin = new AdminController();
$rpt_cols = $reports->rpt_cols;
$section_id = $reports->section_id;
$rs = $reports->find(array('section_id' => $reports->section_id, 'order' => array('name' => 'asc')));
$reports->get($action_id);
?>
<h2 class="text-primary"><?php if ($reports->id) : ?>Edit Users Report: <?php echo $reports->name; else : ?>Export Users<?php endif; ?></h2>
<?php if ($admin->is_permitted('reports', 'read') && $rs && !$reports->id) : ?>
  <form id="rptIDFrm" method="post" action="/admin/users/report">
    <div class="col-xs-6">
      <label for="reportSel">Select a saved report:</label>
      <select id="reportSel" class="form-control" name="id">
        <option value="">None<?php if ($admin->is_permitted('reports', 'create')) : ?> (use form below)<?php endif; ?></option>
        <?php foreach ($rs as $row) : $reports->get($row['id']); ?>
          <option value="<?=$reports->id?>"><?=$reports->name?></option>
        <?php endforeach; ?>
      </select>
    </div>
  </form>
  <?php
  $reports->get($action_id);
endif;
?>
<div class="col-xs-6 pull-right text-right">
  <div class="btn-group">
    <?php if ($reports->id) : ?><a href="/admin/users/export" class="btn btn-default">Back to all exports</a><?php endif; ?>
    <?php if ($admin->is_permitted('reports', 'create') || ($reports->id && $admin->is_permitted('reports', 'update'))) : ?><button id="addButton" class="btn btn-default" data-toggle="modal" data-target="#addModal" data-id="<?=$action_id?>">Save Current &nbsp;<i class="fa fa-save"></i></button><?php endif; ?>
    <?php if (($admin->is_permitted('reports', 'update') || $admin->is_permitted('reports', 'delete')) && $rs && !$reports->id) : ?><button class="btn btn-default" data-toggle="modal" data-target="#savedModal">Manage Saved &nbsp;<i class="fa fa-list"></i></button><?php endif; ?>
    <button id="exportBtn" class="btn btn-primary">Export &nbsp;<i class="fa fa-download"></i></button>
  </div>
</div>
<?php if ($admin->is_permitted('reports', 'read') && $rs && !$reports->id) : ?>
  <div class="clearfix" style="margin-bottom:15px;"></div>
<?php endif; ?>
<?php
if ($admin->is_permitted('reports', 'create') || ($reports->id && $admin->is_permitted('reports', 'update'))) :
  $visible = array();
  foreach ($rpt_cols as $i => $col) if ($col['visible']) $visible[] = $i;
  if ($reports->id) $params = json_decode($reports->config, true);
  else $params = array(
      'cols' => $visible,
      'order' => array(),
      'groups' => array(),
      'totals' => array(),
      'filters' => array(array(
        'cond' => array(
          array(
            'col' => 'u.deleted',
            'comp' => '=',
            'val' => 0,
          ),
        )
      )),
    );
  ?>
  <ul class="nav nav-tabs">
    <li class="active">
      <a data-toggle="tab" href="#columns">Columns</a>
    </li>
    <li>
      <a data-toggle="tab" href="#ordering">Ordering</a>
    </li>
    <li>
      <a data-toggle="tab" href="#filters">Filters</a>
    </li>
  </ul>
  <div class="tab-content">
    <div id="columns" class="tab-pane fade in active">
      <div class="col-xs-6">
        <h3><i class="fa fa-eye"></i> Visible</h3>
        <div id="visibleCols" class="sortable max-height">
          <?php foreach ($params['cols'] as $colid) : $row = $rpt_cols[$colid]; ?>
            <div class="item" data-colid="<?php echo $colid; ?>">
              <?php echo $row['display']; ?>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
      <div class="col-xs-6">
        <h3 class="text-right">Hidden <i class="fa fa-eye-slash"></i></h3>
        <div id="hiddenCols" class="sortable max-height">
          <?php foreach ($rpt_cols as $colid => $row) : if (! in_array($colid, $params['cols'])) : ?>
            <div class="item" data-colid="<?php echo $colid; ?>">
              <?php echo $row['display']; ?>
            </div>
          <?php endif; endforeach; ?>
        </div>
      </div>
    </div>
    <div id="ordering" class="tab-pane fade">
      <div class="col-xs-6">
        <h3><i class="fa fa-sort-amount-asc"></i> Order By</h3>
        <div id="sortCols" class="sortable max-height">
          <?php foreach ($params['order'] as $order) : $row = $rpt_cols[$order['col']]; ?>
            <div class="item" data-colid="<?php echo $order['col']; ?>" data-direction="<?php echo $order['drc']; ?>">
              <a class="btn btn-primary pull-right sort-drc"><i class="fa <?php echo ($order['drc'] === 'asc') ? 'fa-chevron-up' : 'fa-chevron-down'; ?>"></i></a>
              <?php echo $row['display']; ?>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
      <div class="col-xs-6">
        <h3 class="text-right">Available <i class="fa fa-list"></i></h3>
        <div id="sortAvailable" class="sortable max-height">
          <?php
          foreach ($rpt_cols as $colid => $row) :
            if (strpos($row['select'], '0 as') === false && ! in_array($colid, array_map(function($row) { return $row['col']; }, $params['order']))) :
              ?>
              <div class="item" data-colid="<?php echo $colid; ?>" data-direction="asc">
                <a class="btn btn-primary pull-right sort-drc"><i class="fa fa-chevron-up"></i></a>
                <?php echo $row['display']; ?>
              </div>
              <?php
            endif;
          endforeach;
          ?>
        </div>
      </div>
    </div>
    <div id="filters" class="tab-pane fade">
      <div class="filter-group-container">
        <?php
        if (count($params['filters'])) {
          foreach ($params['filters'] as $group_i => $group) {
            Flight::render('admin/users/export/filter-group', array('ajax' => 0, 'group' => $group, 'group_i' => $group_i));
          }
        } else Flight::render('admin/users/export/filter-group', array('ajax' => 0));
        ?>
      </div>
      <button class="btn btn-primary filter-group-add" title="Add Filter Group" data-section="users"><i class="fa fa-plus"></i></button>
    </div>
  </div>

  <form id="rptFrm" method="post" autocomplete="off" action="/admin/users/export">
    <input id="inpVisible" type="hidden" name="visible" value="" />
    <input id="inpOrder" type="hidden" name="order" value="" />
    <input id="inpFilters" type="hidden" name="filters" value="" />
  </form>

  <div id="addModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
      <form class="form-horizontal modal-form" method="post" action="/admin/reports/save">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title"><?=($action_id) ? 'Edit' : 'Add'?> Saved Report</h4>
          </div>
          <div class="modal-body">
            <input type="hidden" name="id" value="<?=(int)$action_id?>"/>
            <input type="hidden" name="section_id" value="<?=$section_id?>"/>
            <input id="inpConfig" type="hidden" name="config" value=""/>
            <div class="form-group">
              <label for="inpName" class="col-sm-3 control-label">Name</label>
              <div class="col-sm-9">
                <input id="inpName" type="text" class="form-control" name="name" value="<?=$reports->name?>" required/>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <div class="btn-group">
              <button type="button" class="btn btn-default modal-dismiss" data-dismiss="modal">Cancel</button>
              <input type="submit" value="Save" class="btn btn-primary"/>
            </div>
          </div>
        </div>
      </form>
    </div>
  </div>
<?php
endif;
if (($admin->is_permitted('reports', 'update') || $admin->is_permitted('reports', 'delete')) && $rs) :
  ?>
  <div id="savedModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Saved Reports</h4>
        </div>
        <div class="modal-body">
          <table id="reportsGrid" class="datatable table table-striped no-pointer">
              <thead>
                  <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th></th>
                  </tr>
              </thead>
              <tbody>
                <?php foreach ($rs as $row) : $reports->get($row['id']); ?>
                  <tr>
                    <td><?=$reports->id?></td>
                    <td><?=$reports->name?></td>
                    <td>
                      <?php if ($admin->is_permitted('reports', 'update')) : ?><a class="action-link edit" href="/admin/users/export/<?=$reports->id?>"><i class="fa fa-lg fa-edit"></i></a><?php endif; ?>
                      <?php if ($admin->is_permitted('reports', 'delete')) : ?><a class="action-link delete" data-id="<?=$reports->id?>" href="#deleteModal" data-toggle="modal"><i class="fa fa-lg fa-trash"></i></a><?php endif; ?>
                    </td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
          </table>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default modal-dismiss" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>
  <?php if ($admin->is_permitted('reports', 'delete')) : ?>
    <div id="deleteModal" class="modal fade" role="dialog">
      <div class="modal-dialog">
        <form class="form-horizontal modal-form" method="post" action="/admin/reports/delete">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal">&times;</button>
              <h4 class="modal-title">Delete Saved Report</h4>
            </div>
            <div class="modal-body">
              <p>Are you sure you want to delete the report below? This action cannot be undone.</p>
              <input id="inpDID" type="hidden" name="id" value=""/>
              <div class="form-group">
                <label for="inpDName" class="col-sm-3 control-label">Name</label>
                <div class="col-sm-9">
                  <span id="inpDName" style="line-height:1.9;"></span>
                </div>
              </div>
            </div>
            <div class="modal-footer">
              <div class="btn-group">
                <button type="button" class="btn btn-default modal-dismiss" data-dismiss="modal">Cancel</button>
                <input type="submit" value="Delete" class="btn btn-danger"/>
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>
  <?php endif; ?>
<?php endif; ?>
