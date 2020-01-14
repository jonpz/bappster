if($('body.admin-emails').length){
  var t = $('#emailsGrid'), dtp = {
    dom:'<"row"<"col-sm-6 dth-left"l><"col-sm-6 dth-right"f>><"row"<"col-sm-12"tr>><"row"<"col-sm-5 dth-left"i><"col-sm-7 dth-right"p>>',
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
      document.location.href = '/admin/emails/'+d[0]+'/update';
    });
  }
  if($('#emailImagesGrid').length) {
    var t2 = $('#emailImagesGrid');
    t2.DataTable({
      dom:'<"row"<"col-sm-6 dth-left"l><"col-sm-6 dth-right">><"row"<"col-sm-12"tr>><"row"<"col-sm-5 dth-left"i><"col-sm-7 dth-right"p>>',
      columnDefs:[{
        targets:[2],
        orderable:false,
      }],
    });
    $('.action-link').click(function(){
      var tr = $(this).closest('tr'), d = t2.DataTable().row(tr[0]).data();
      $('#inpDEIID').val(d[0].replace('image_',''));
      $('#inpDEIFile').html(d[1]);
    });
    $('.ei-form').submit(function(e){
      e.preventDefault();
      $('.modal.in').modal('hide');
      var d = new FormData(this);
      $.ajax({
        method:'post',
        url:$(this).attr('action'),
        data:d,
        contentType:false,
        processData:false,
        success:function(r){
          if(r.success) location.reload();
          else alert('There was an error on the server.');
        },
      });
    });
  }
  $('#sendTestEmail').click(function(e){
    e.preventDefault();
    var id = $('#eID').val();
    $('#sendEmailModal').modal('hide');
    $.ajax({
      method:'post',
      url:'/admin/emails/'+id+'/sendTest',
      data:{to_address:$('#sendTestTo').val()},
      success:function(r){
        if(r.success){
          $('#success').collapse('show');
          setTimeout(function(){$('#success').collapse('hide');},5000);
        }else{
          $('#error .message').text(r.msg);
          $('#error').collapse('show');
          setTimeout(function(){$('#error').collapse('hide');},5000);
        }
      },
    });
  });
}
