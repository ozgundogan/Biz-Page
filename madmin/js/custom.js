
$(function() {
  $('.dd').nestable({
    group: 1
  }).on('change',function() {
    $.ajax({
      type: 'POST',
      url: "test.php",
      data: {
        list: $(this).nestable('serialize')
      },
      success: function(data) {
        $.toaster({ priority : 'success', title : 'Başarılı', message :' İşlem başarılı' });
      }
    });
    console.log($(this).nestable('serialize'));

  });
});

$('.dd').nestable({ /* config options */ });
$('.dd').nestable('serialize');
$('.dd').nestable({
  callback: function(l,e){
    // l is the main container
    // e is the element that was moved
  }
});

$('.editable').editable({
  mode: 'inline',
  type: 'number',
  step: '1.00',
  min: '0.00',
  max: '24'
});
$('#menuModal').on('show.bs.modal', function (event) {
  var button = $(event.relatedTarget) // Button that triggered the modal
  var id = button.data('id')
  var title=button.data('title')
  var modal = $(this)
  modal.find('[name=menuAdi]').val(title)
  modal.find('[name=menuId]').val(id)
})
function readURL(input,id) {
  if (input.files && input.files[0]) {
    var reader = new FileReader();
    reader.onload = function(e) {
      $("[name=file_]").val(e.target.result);
      $('#'+id).css('background-image', 'url('+e.target.result +')');
      $('#'+id).hide();
      $('#'+id).fadeIn(650);

    }
    reader.readAsDataURL(input.files[0]);
  }
}
function readUrlLogo(input) {
  if (input.files && input.files[0]) {
    console.log(input);
    var reader = new FileReader();
    reader.onload = function(e) {
      $("[name=file_]").val(e.target.result);
      $('#logoGoster').css('background-image', 'url('+e.target.result +')');
      $('#logoGoster').hide();
      $('#logoGoster').fadeIn(650);
      $('#logoName').val(e.target.result)

    }
    reader.readAsDataURL(input.files[0]);
  }
}
function slideEdit(id,gorselName,siraName){
  $("#myForm").ajaxForm({
    type: 'post',
    url :'test.php',
    data: {"sliderduzenle":"1","id": id,"gorselname":gorselName,"siraname":siraName},
    success:function (result) {
      if(result){
        $.toaster({ priority : 'success', title : 'Başarılı', message :' İşlem başarılı' });
        this.blur();
      }else{

        $.toaster({ priority : 'danger', title : 'Başarısız', message : 'İşlem başarısız' });
        this.blur();
      }
    }
  })
}
function slideSil(id,resimyolSil){
  $("#myForm").ajaxForm({
    type: 'post',
    url :'test.php',
    data:{"sid":id,"resimyol":resimyolSil},
    dataType:'json',
    succes:function(cevap){
      if(cevap.durum){
        $.toaster({ priority : 'success', title : 'Başarılı', message :' İşlem başarılı' });
        this.blur();
      }
      else{
        $.toaster({ priority : 'danger', title : 'Başarısız', message : 'İşlem başarısız' });
      }
    }
  })
}
var output='';
var html='';
var $a = $.a = a = {

  menu:{
    add:function(elem){
      var data = $(elem).serializeArray();
      $.post($(elem).attr('action'),data,function(res) {
        if (res.code) {
          //$('.dd').nestable('add', {"id":res.name}); nestable a direk ekleme yapmak içindi
          $.toaster({ priority : 'success', title : 'Başarılı', message :' İşlem başarılı' });
          $('#menuModal').modal('hide');
          $('body').removeClass('modal-open');
          $('.modal-backdrop').remove();
        }else{
          $.toaster({ priority : 'danger', title : 'Başarısız', message : 'İşlem başarısız' });
          $('#menuModal').modal('hide');
        }
      },'json');
    },
    status: function(elem){
      $.post($(elem).data('url'), {status : $(elem).val()}, function(data, textStatus, xhr) {
        if (data.code) {
          $.toaster({ priority : 'success', title : 'Başarılı', message :' İşlem başarılı' });
        }else{
          $.toaster({ priority : 'danger', title : 'Başarısız', message : 'İşlem başarısız' });
        }
      },'json');
    },
    getMenus: function(){
      dizi=[];

      $.ajax({
        type: 'POST',
        url: "vendors/test.php",
        data: {'mn':'ok'},
        dataType:'json',
        success: function(res) {
          dizi=res.menuler;
        }
      });
      return dizi;
    },
    lisItem: function(dizi,target){
      var output;
      $.each($dizi,function(index,item)){
          output+=buildItem(item);
      }
      $(target).html(output);
    },
    buildItem: function(row){
      if(row.children){
        html='<li class="dd-item" data-id="'row.id'">";
        html+='<li class="dd-item" data-id="'row.id'">";
        html+='<li class="dd-item" data-id="'row.id'">";
        html+='<li class="dd-item" data-id="'row.id'">";
      }else{
        html='<li class="dd-item" data-id="'row.id'">";
        html+='<li class="dd-item" data-id="'row.id'">";
        html+='<li class="dd-item" data-id="'row.id'">";
        html+='<li class="dd-item" data-id="'row.id'">";
      }

    }
  },
  index:{
    save:function(elem){

      var data = $(elem).serializeArray();
      console.log(data);
      $.post($(elem).attr('action'),data, function(res) {
        if(res)
        {
          $.toaster({ priority : 'success', title : 'Başarılı', message :' İşlem başarılı' });
        }
        else{
          $.toaster({ priority : 'danger', title : 'Başarısız', message : 'İşlem başarısız' });

        }
      },'json');
    }
  }
}
