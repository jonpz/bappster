$('.datepicker').flatpickr();
$('.datetimepicker:not(.time-disabled)').flatpickr({enableTime:true});
$('.datetimepicker.time-disabled').flatpickr({enableTime:false});
$('.sortable').sortable({group:'sortable',filter:'.disable'});
if($('.select2').length) $('.select2').select2({
  width:'100%',
  minimumResultsForSearch:25,
});
var updateFilter = function(){
  var f = [], ge = $('.filter-group');
  ge.each(function(){
    var g = {cond:[]}, oe = $(this).find('.logic-operator'), ce = $(this).find('.filter-condition:not(.hidden)');
    if(oe.length) g.oper = oe.val();
    ce.each(function(){
      var coe = $(this).find('.fc-logic-oper'),
        comp = $(this).find('.fc-oper'),
        valinp = $(this).find('.fc-value-container:not(.hidden) .fc-value').val(),
        val = (comp.val()==='like'||comp.val()==='not like')?'%'+valinp+'%':valinp,
        c = {
          col:$(this).find('.fc-col').val(),
          comp:$(this).find('.fc-oper').val(),
          val:val,
        };
      if(coe.length) c.oper = coe.val();
      g.cond.push(c);
    });
    if(g.cond.length) f.push(g);
  });
  $('#inpFilters').val(JSON.stringify(f));
};
$('#columns .sortable').on('sort',function(){
  var a = [];
  $('#grouping .item, #totaling .item').addClass('disable');
  $('#visibleCols .item').each(function(){
    var i = $(this).data('colid');
    a.push(i);
    $('#grouping .item[data-colid='+i+']').removeClass('disable');
    $('#totaling .item[data-colid='+i+']').removeClass('disable');
  });
  $('#groupAvailable').prepend($('#groupCols .item.disable'));
  $('#groupAvailable').prepend($('#groupAvailable .item:not(.disable)'));
  $('#totalAvailable').prepend($('#totalAvailable .item:not(.disable)'));
  $('#inpVisible').val(JSON.stringify(a));
}).triggerHandler('sort');
$('#ordering .sortable').on('sort',function(){
  var a = [];
  $('#sortCols .item').each(function(){
    var i = $(this).data('colid');
    a.push({col:i,drc:$(this).data('direction')});
    $('#grouping .item[data-colid='+i+']').addClass('disable');
  });
  $('#groupAvailable').prepend($('#groupCols .item.disable'));
  $('#groupAvailable').prepend($('#groupAvailable .item:not(.disable)'));
  $('#inpOrder').val(JSON.stringify(a));
  $('#sortAvailable .item').each(function(){
    var i = $(this).data('colid');
    if($('#visibleCols .item[data-colid='+i+']').length) $('#groupAvailable .item.disable[data-colid='+i+']').removeClass('disable').prependTo('#groupAvailable');
  });
}).triggerHandler('sort');
$('#grouping .sortable').on('sort',function(){
  var a = [];
  $('#groupCols .item').each(function(){
    var i = $(this).data('colid');
    a.push({col:i,drc:$(this).data('direction')});
    $('#ordering .item[data-colid='+i+']').addClass('disable');
  });
  $('#sortAvailable').prepend($('#sortCols .item.disable'));
  $('#sortAvailable').prepend($('#sortAvailable .item:not(.disable)'));
  $('#groups').val(JSON.stringify(a));
  $('#groupAvailable .item').each(function(){
    var i = $(this).data('colid');
    $('#sortAvailable .item.disable[data-colid='+i+']').removeClass('disable').prependTo('#sortAvailable');
  });
}).triggerHandler('sort');
$('.sort-drc').click(function(){
  var l = $(this).closest('.item')
    ,o = l.data('direction')
    ,n = (o=='asc')?'desc':'asc'
  ;
  $(this).find('.fa').toggleClass('fa-chevron-up fa-chevron-down');
  l.data('direction',n);
  $(this).closest('.sortable').triggerHandler('sort');
});
$('#totaling .sortable').on('sort',function(){
  var a = [];
  $('#totalCols .item').each(function(){
    var i = $(this).data('colid');
    a.push(i);
  });
  $('#totals').val(JSON.stringify(a));
}).triggerHandler('sort');
$('#filters').on('click','.filter-clause-add',function(){
  if($(this).prev('.filter-condition-container').find('.filter-condition.hidden').length){
    $(this).prev('.filter-condition-container').find('.filter-condition.hidden').removeClass('hidden').find('.filter-condition-delete').removeAttr('disabled');
    $('.filter-group-add').removeAttr('disabled');
  }else{
    var el = $(this);
    $.get('/admin/'+el.data('section')+'/filter',function(r){
      el.prev('.filter-condition-container').append(r);
      el.prev('.filter-condition-container').find('.filter-condition:last .fc-logic-oper').trigger('change');
    });
    var d = el.prev('.filter-condition-container').find('.filter-condition:first .filter-condition-delete');
    if(d.length) d.attr('disabled',true);
  }
  updateFilter();
});
$('#filters').on('click','.filter-condition-delete',function(){
  var cont = $(this).closest('.filter-condition-container')
    ,el = $(this).closest('.filter-condition')
  ;
  if(cont.find('.filter-condition').length>1){
    el.remove();
    if(cont.find('.filter-condition').length===1&&cont.find('.filter-condition-delete')&&$('.filter-group-container .filter-group').length===1) cont.find('.filter-condition-delete').removeAttr('disabled');
  }else{
    el.addClass('hidden');
    $('.filter-group-add').attr('disabled',true);
  }
  updateFilter();
});
$('.filter-group-add').click(function(){
  var el = $(this);
  $.get('/admin/'+el.data('section')+'/filter-group',function(r){
    var t = el.prev('.filter-group-container');
    t.append(r);
    t.find('.filter-group:last .filter-condition-delete').remove();
    t.find('.filter-group:first .filter-condition-delete:first').attr('disabled',true);
    updateFilter();
  });
});
$('#filters').on('click','.filter-group-delete',function(){
  $(this).closest('.filter-group').remove();
  if($('.filter-group').length===1&&$('.filter-condition').length===1) $('.filter-condition-delete').removeAttr('disabled');
  updateFilter();
});
$('#filters').on('change','.fc-col',function(){
  var v = $(this).find('option:selected').data('inputclass'), el = $(this).closest('.filter-condition-inner').find('.fc-value-container.'+v);
  $(this).closest('.filter-condition-inner').find('.fc-value-container').addClass('hidden');
  el.removeClass('hidden');
  if(el.hasClass('datetime')||el.hasClass('number')) $(this).closest('.filter-condition-inner').find('.fc-oper .opt, .fc-oper .not-equal').removeClass('hidden');
  else{
    $(this).closest('.filter-condition-inner').find('.fc-oper .opt, .fc-oper .like').addClass('hidden');
    $(this).closest('.filter-condition-inner').find('.fc-oper .not-equal').removeClass('hidden');
    if(v=='boolean') $(this).closest('.filter-condition-inner').find('.fc-oper .not-equal').addClass('hidden');
    else if(v=='text') $(this).closest('.filter-condition-inner').find('.fc-oper .like').removeClass('hidden');
  }
  $(this).closest('.filter-condition-inner').find('.fc-oper').val('=').trigger('change');
});
$('#filters').on('change','.fc-value, .fc-oper, .fc-logic-oper, .logic-operator',updateFilter);
$('.fc-col').trigger('change');
if($('.filter-group .filter-condition:not(.hidden)').length){
  $('.fc-oper').each(function(){
    $(this).val($(this).find('option[selected]').attr('value')).trigger('change');
  });
  if($('.filter-group .filter-condition:not(.hidden)').length>1) $('.filter-group:first .filter-condition:first .filter-condition-delete').attr('disabled',true);
}else $('.filter-group-add').attr('disabled',true);
updateFilter();
$('#exportBtn').click(function(){
  if($('#rptIDFrm').length&&$('#reportSel').val()) $('#rptIDFrm').submit();
  else $('#rptFrm').submit();
});
$('#deleteObject').click(function(e){
  e.preventDefault();
  $('#deleteForm').submit();
});
$('#addButton').click(function(){
  var a = {
    cols:JSON.parse($('#inpVisible').val()),
    order:JSON.parse($('#inpOrder').val()),
    filters:JSON.parse($('#inpFilters').val()),
  };
  $('#inpConfig').val(JSON.stringify(a));
});
$('.modal-form').submit(function(e){
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
if($('#reportsGrid').length) {
  var rt = $('#reportsGrid');
  rt.DataTable({
    columnDefs:[{
      targets:[2],
      orderable:false,
    }],
  });
  $('.action-link.delete').click(function(){
    var tr = $(this).closest('tr'), d = rt.DataTable().row(tr[0]).data();
    $('#savedModal').modal('hide');
    $('#inpDID').val(d[0]);
    $('#inpDName').text(d[1]);
  });
}
$(document).ajaxSuccess(function(e,r,s){
  if(r.responseText.match(/<html[^>]+class="login"/)) location.reload();
});
var dtError = function(e,settings,techNote,message){
  if(e.jqXHR.responseText.match(/<html[^>]+class="login"/)) location.reload();
  else alert('There has been an error retrieving the data for the table. Server response: '+e.jqXHR.responseText);
};
$.fn.dataTable.ext.errMode = dtError;
