if($('body.admin-audit-logs').length){
  var t = $('#auditLogsGrid'), dtp = {
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
  }
}
