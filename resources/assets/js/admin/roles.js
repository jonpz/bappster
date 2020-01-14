if($('body.admin-roles').length){
  var t = $('#rolesGrid'), dtp = {
    dom:'<"row"<"col-sm-6 dth-left"l><"col-sm-6 dth-right"f>><"row"<"col-sm-12"tr>><"row"<"col-sm-5 dth-left"i><"col-sm-7 dth-right"p>>',
    order:[2,'desc'],
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
      document.location.href = '/admin/roles/'+d[0]+'/update';
    });
  }
}
