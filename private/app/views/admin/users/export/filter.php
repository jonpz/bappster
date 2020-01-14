<?php
$roles = new Roles();
$rs = $roles->find(array('order' => array('priority' => 'desc')));
?>
<div class="filter-condition <?php if (! $ajax && empty($cond)) echo 'hidden'; ?>">
  <?php if (empty($fromfg) && ($ajax || !empty($cond['oper']))) : ?>
    <select class="fc-logic-oper form-control">
      <option value="AND"<?php if (empty($cond['oper']) || $cond['oper'] === 'AND') : ?> selected="selected"<?php endif; ?>>AND</option>
      <option value="OR"<?php if (!empty($cond['oper']) && $cond['oper'] === 'OR') : ?> selected="selected"<?php endif; ?>>OR</option>
    </select>
  <?php endif; ?>
  <div class="filter-condition-inner row">
    <button class="btn btn-danger pull-right filter-condition-delete"><i class="fa fa-close"></i></button>
    <div class="col-xs-4">
      <select class="fc-col form-control">
        <option data-inputclass="text" value="u.email"<?php if (isset($cond) && $cond['col'] === 'u.email') : ?> selected="selected"<?php endif; ?>>Email</option>
        <option data-inputclass="role_id" value="u.role_id"<?php if (isset($cond) && $cond['col'] === 'u.role_id') : ?> selected="selected"<?php endif; ?>>User Role</option>
        <option data-inputclass="datetime" value="u.last_login"<?php if (isset($cond) && $cond['col'] === 'u.last_login') : ?> selected="selected"<?php endif; ?>>Last Login</option>
        <option data-inputclass="datetime" value="u.created_at"<?php if (isset($cond) && $cond['col'] === 'u.created_at') : ?> selected="selected"<?php endif; ?>>Created At</option>
        <option data-inputclass="boolean" value="u.deleted"<?php if (isset($cond) && $cond['col'] === 'u.deleted') : ?> selected="selected"<?php endif; ?>>Deleted?</option>
      </select>
    </div>
    <div class="col-xs-4">
      <select class="fc-oper form-control">
        <option value="="<?php if (isset($cond) && $cond['comp'] === '=') : ?> selected="selected"<?php endif; ?>>is</option>
        <option value="<>" class="not-equal"<?php if (isset($cond) && $cond['comp'] === '<>') : ?> selected="selected"<?php endif; ?>>is not</option>
        <option value="like" class="like"<?php if (isset($cond) && $cond['comp'] === 'like') : ?> selected="selected"<?php endif; ?>>contains</option>
        <option value="not like" class="like"<?php if (isset($cond) && $cond['comp'] === 'not like') : ?> selected="selected"<?php endif; ?>>does not contain</option>
        <option value=">" class="hidden opt"<?php if (isset($cond) && $cond['comp'] === '>') : ?> selected="selected"<?php endif; ?>>is greater than</option>
        <option value=">=" class="hidden opt"<?php if (isset($cond) && $cond['comp'] === '>=') : ?> selected="selected"<?php endif; ?>>is greater than or equal to</option>
        <option value="<" class="hidden opt"<?php if (isset($cond) && $cond['comp'] === '<') : ?> selected="selected"<?php endif; ?>>is less than</option>
        <option value="<=" class="hidden opt"<?php if (isset($cond) && $cond['comp'] === '<=') : ?> selected="selected"<?php endif; ?>>is less than or equal to</option>
      </select>
    </div>
    <?php
    mt_srand((double)microtime()*10000);
    $classselid = strtoupper(md5(uniqid(rand(), true)));
    ?>
    <div class="col-xs-4">
      <div class="fc-value-container role_id hidden">
        <select class="fc-value form-control select2 select2-<?=$classselid?>">
          <?php foreach ($rs as $row) : $roles->get($row['id']); ?>
            <option value="<?php echo $roles->id; ?>"<?php if (!empty($cond['val']) && in_array($cond['col'], array('u.role_id')) && $cond['val'] == $roles->id) : ?> selected="selected"<?php endif; ?>><?php echo $roles->name; ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="fc-value-container text">
        <input class="fc-value form-control" type="text"<?php if (!empty($cond['val']) && in_array($cond['col'], array('u.email'))) : ?> value="<?php echo $cond['val']; ?>"<?php endif; ?>/>
      </div>
      <div class="fc-value-container number hidden">
        <input class="fc-value form-control" type="number"<?php if (!empty($cond['val']) && in_array($cond['col'], array())) : ?> value="<?php echo $cond['val']; ?>"<?php endif; ?> min="0"/>
      </div>
      <div class="fc-value-container boolean hidden">
        <select class="fc-value form-control">
          <option value="1"<?php if (isset($cond) && in_array($cond['col'], array('u.deleted')) && $cond['val'] == 1) : ?> selected="selected"<?php endif; ?>>True</option>
          <option value="0"<?php if (isset($cond) && in_array($cond['col'], array('u.deleted')) && $cond['val'] == 0) : ?> selected="selected"<?php endif; ?>>False</option>
        </select>
      </div>
      <div class="fc-value-container datetime hidden">
        <?php
        mt_srand((double)microtime()*10000);
        $timeinpid = strtoupper(md5(uniqid(rand(), true)));
        ?>
        <input id="<?php echo $timeinpid; ?>" type="text" class="fc-value form-control datetimepicker"<?php if (isset($cond) && in_array($cond['col'], array('u.created_at', 'u.last_login'))) : ?> value="<?php echo $cond['val']; ?>"<?php endif; ?>/>
      </div>
    </div>
  </div>
</div>
<?php if ($ajax) : ?>
  <script type="text/javascript">
  $('.select2-<?=$classselid?>').select2({
    width:'100%',
    minimumResultsForSearch:25,
  });
  $('#<?php echo $timeinpid; ?>').flatpickr({enableTime:true});
  </script>
<?php endif; ?>
