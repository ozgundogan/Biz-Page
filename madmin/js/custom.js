


// $('.dd').nestable({ /* config options */ });
// $('.dd').nestable('serialize');
// $('.dd').nestable({
//     callback: function(l,e){
//         // l is the main container
//         // e is the element that was moved
//     }
// });

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
    $a.menu.listItem($a.menu.getMenus($))
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
            $a.menu.listItem($a.menu.getMenus(),'.dd-list');
        },
        delete:function(elem){
            var id=$(elem).data('id');
            $.post($(elem).attr('href'),{"menuSil":id},function(res){
                if(res.code){
                    $.toaster({ priority : 'success', title : 'Başarılı', message :' İşlem başarılı' });
                }else{
                    $.toaster({ priority : 'danger', title : 'Başarısız', message : 'İşlem başarısız' });
                }
            },'json');
            $a.menu.listItem($a.menu.getMenus(),'.dd-list');
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
        getMenus:function(){
            var dizi;
            $.ajax({
                type: 'POST',
                url: "vendors/helper.php",
                data:{"nm":"ok"},
                dataType:'json',
                async:false,
                success: function(data) {
                    dizi=data.menuler;
                }
            });
            return dizi;
        },
        listItem: function(dizi,target){
            var output='';
            $.each(dizi,function(index,item){
                output += $a.menu.buildItem(item);
            });
            $(target).html(output);
        },
        buildItem: function(row){
            html="<li class='dd-item' data-id='"+row.id+"''>";
            html+="<div class='dd-handle'>"+row.title+"</div>";
            html+="<div class='btn-group mb-0 regulated'>";
            html += "<a data-toggle='modal' data-target='#menuModal' data-id='"+row.id+"' data-title='"+row.title+"' class='btn btn-xs btn-edit'><i class='fa fa-edit fa-lg'></i> Düzenle</a>";
            html += "<a href='test.php' data-id='"+row.id+"'  class='btn btn-xs btn-delete' onclick='$a.menu.delete(this); return false;'><i class='fa fa-lg fa-trash'></i> Sil </a>";
            html += "</div>";
            if(row.children){
                html +="<ol class='dd-list'>";
                $.each(row.children,function(index,item){
                    html += $a.menu.buildItem(item);
                });
                html += "</ol>";
            }
            html += "</li>";
            return html;
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
}
