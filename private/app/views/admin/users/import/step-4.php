<div>
  <h4 class="text-muted text-uppercase border-bottom">Complete</h4>
  <div id="importingAlert" class="alert alert-info">
    <h4><i class="fa fa-lg fa-spin fa-refresh"></i> &nbsp;Importing data</h4>
    <em>This could take a few minutes...</em>
  </div>
  <script type="text/javascript">
  $.ajax({
    type:'post',
    url:'/admin/users/import/step-4-ajax',
    success:function(r){
      $('#importingAlert').after(r).remove();
    },
    cache:false,
  });
  </script>
</div>
