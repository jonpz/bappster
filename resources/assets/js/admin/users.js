if($('body.admin-users').length){
  var t = $('#usersGrid'), dtp = {
    dom:'<"row"<"col-sm-6 dth-left"l><"col-sm-6 dth-right"f>><"row"<"col-sm-12"tr>><"row"<"col-sm-5 dth-left"i><"col-sm-7 dth-right"p>>',
    order:[1,'asc'],
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
      document.location.href = '/admin/users/'+d[0]+'/update';
    });
  }
  if($('#importWizard').length){
    $('#importWizard').slick({
      infinite:false,
      adaptiveHeight:true,
    });
    $('.upload').click(function(e){
      e.preventDefault();
      $(this).find('.spinner').removeClass('hidden');
      $('#inpFile').trigger('click');
    });
    $('#inpFile').change(function(){
      var fd = new FormData(), $el = $(this);
      fd.append('file',$el[0].files[0]);
      $.ajax({
        type:'post',
        url:'/admin/users/import/step-2',
        data:fd,
        xhr:function(){
          var myXhr = $.ajaxSettings.xhr();
          if(myXhr.upload) myXhr.upload.addEventListener('progress',function(e){
            if(e.lengthComputable) $el.siblings('.progress').find('.progress-bar').attr({
              'aria-valuemax':e.total,
              'aria-valuenow':e.loaded,
            }).width((e.loaded/e.total*100)+'%');
          },false);
          return myXhr;
        },
        beforeSend:function(){
          $el.siblings('.progress').removeClass('hidden');
          $el.siblings('.upload-filename').text($el[0].files[0]['name']);
        },
        success:function(r){
          $el.siblings('.upload').find('.spinner').addClass('hidden');
          $el.siblings('.progress').addClass('hidden');
          $('#importWizard').slick('slickRemove',2);
          $('#importWizard').slick('slickRemove',1);
          $('#importWizard').slick('slickAdd',r);
          setTimeout(function(){$('#importWizard').slick('slickNext');},250);
        },
        cache:false,
        contentType:false,
        processData:false,
      });
    });
    $('#importWizard').on('submit','#importMapColumns',function(e){
      e.preventDefault();
      $.ajax({
        type:'post',
        url:'/admin/users/import/step-3',
        data:$(this).serializeArray(),
        success:function(r){
          $('#importWizard').slick('slickRemove',2);
          $('#importWizard').slick('slickAdd',r);
          setTimeout(function(){$('#importWizard').slick('slickNext');},250);
        },
        cache:false,
      });
    });
    $('#importWizard').on('click','#importCommit',function(){
      $.ajax({
        type:'post',
        url:'/admin/users/import/step-4',
        success:function(r){
          $('#importWizard').slick('slickRemove',2);
          $('#importWizard').slick('slickRemove',1);
          $('#importWizard').slick('slickRemove',0);
          $('#importWizard').slick('slickAdd',r);
          $('.slick-prev, .slick-next').hide();
        },
        cache:false,
      });
    });
  }
}
