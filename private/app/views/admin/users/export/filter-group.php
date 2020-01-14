<div class="filter-group">
  <?php if ($ajax || !empty($group['oper'])) : ?>
    <select class="logic-operator form-control" name="logicOper">
      <option value="AND"<?php if (empty($group['oper']) || $group['oper'] === 'AND') : ?> selected="selected"<?php endif; ?>>AND</option>
      <option value="OR"<?php if (!empty($group['oper']) && $group['oper'] === 'OR') : ?> selected="selected"<?php endif; ?>>OR</option>
    </select>
  <?php endif; ?>
  <div class="filter-group-inner">
    <?php if ($ajax || !empty($group_i)) : ?>
      <div class="clearfix" style="margin-bottom:10px;">
        <button class="btn btn-danger pull-right filter-group-delete"><i class="fa fa-close"></i></button>
      </div>
    <?php endif; ?>
    <div class="filter-condition-container">
      <?php
      if (!empty($group['cond'])) {
        foreach ($group['cond'] as $cond) {
          Flight::render('admin/users/export/filter', array('ajax' => 0, 'cond' => $cond));
        }
      } else Flight::render('admin/users/export/filter', array('ajax' => $ajax, 'fromfg' => true));
      ?>
    </div>
    <button class="btn btn-primary filter-clause-add" title="Add Filter Condition" data-section="users"><i class="fa fa-plus"></i></button>
  </div>
</div>
