<?php
$admin = new AdminController();
$return = $admin->importMapColumns($post);
?>
<div>
  <h4 class="text-muted text-uppercase border-bottom">Step 3</h4>
  <?php if ($return) : ?>
    <div id="processingAlert" class="alert alert-info">
      <h4><i class="fa fa-lg fa-spin fa-refresh"></i> &nbsp;Processing data</h4>
      <em>This could take a few minutes...</em>
    </div>
    <script type="text/javascript">
    $.ajax({
      type:'post',
      url:'/admin/users/import/step-3-ajax',
      success:function(r){
        $('#processingAlert').after(r).remove();
        $('#importWizard').slick('reinit');
      },
      cache:false,
    });
    </script>
  <?php else : ?>
    <div class="alert alert-danger">There was an error!</div>
  <?php endif; ?>
</div>
