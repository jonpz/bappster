if($('body.admin-sections').length){
  var t = $('#sectionsGrid'), dtp = {
    dom:'<"row"<"col-sm-6 dth-left"l><"col-sm-6 dth-right"f>><"row"<"col-sm-12"tr>><"row"<"col-sm-5 dth-left"i><"col-sm-7 dth-right"p>>',
    order:[5,'desc'],
    ajax:{
      url:'/admin/ajax/grid',
      data:{
        section:t.data('section'),
      },
      method:'post',
    },
    processing:true,
    serverSide:true,
    ordering:true,
    // responsive:true,
  };
  if(t.length){
    t.DataTable(dtp);
    t.on('click','tr',function(){
      var d = t.DataTable().row(this).data();
      document.location.href = '/admin/sections/'+d[0]+'/update';
    });
  }
  if($('#sectionActionsGrid').length) {
    var t2 = $('#sectionActionsGrid');
    t2.DataTable({
      dom:'<"row"<"col-sm-6 dth-left"l><"col-sm-6 dth-right">><"row"<"col-sm-12"tr>><"row"<"col-sm-5 dth-left"i><"col-sm-7 dth-right"p>>',
      order:[4,'desc'],
      columnDefs:[{
        targets:[5],
        orderable:false,
      }],
    });
    $('.action-link').click(function(){
      var tr = $(this).closest('tr'), d = t2.DataTable().row(tr[0]).data();
      if($(this).hasClass('edit')){
        $('#inpESAID').val(d[0]);
        $('#inpESAName').val(d[1]);
        $('#inpESATag').val(d[2]);
        $('#inpESAHideNav').attr('checked',(d[3]=='Yes')).trigger('change');
        $('#inpESAPriority').val(d[4]);
      }else{
        $('#inpDSAID').val(d[0]);
        $('#inpDSAName').text(d[1]);
        $('#inpDSATag').text(d[2]);
        $('#inpDSAHideNav').text(d[3]);
        $('#inpDSAPriority').text(d[4]);
      }
    });
    $('.sa-form').submit(function(e){
      e.preventDefault();
      $('.modal.in').modal('hide');
      $.ajax({
        method:'post',
        url:$(this).attr('action'),
        data:$(this).serializeArray(),
        success:function(r){
          if(r.success) location.reload();
          else alert('There was an error on the server.');
        },
      });
    });
  }
}
