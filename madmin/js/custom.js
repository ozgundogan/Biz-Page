
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
                var priority = 'success';
                var title    = 'Başarılı';
                var message  = 'Sıralama başarılı';
                $.toaster({ priority : priority, title : title, message : message });
            }
        });
        console.log($(this).nestable('serialize'));

    });

    $('.editable').editable({
        mode: 'inline',
        type: 'number',
        step: '1.00',
        min: '0.00',
        max: '24'
    });
});
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

function slideEdit(id,gorselName,siraName){
    $("#myForm").ajaxForm({
        type: 'post',
        url :'test.php',
        data: {"sliderduzenle":"1","id": id,"gorselname":gorselName,"siraname":siraName},
        success:function (result) {
            if(result){
                var priority = 'success';
                var title    = 'Başarılı';
                var message  = '';

                $.toaster({ priority : priority, title : title, message : message });
                this.blur();
            }else{
                alert("no");
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
                var priority = 'success';
                var title    = 'Başarılı';
                var message  = '';

                $.toaster({ priority : priority, title : title, message : message });
                this.blur();
            }
            else{
                alert("llal");
            }
        }
    })
}

$('.dd').nestable({ /* config options */ });
$('.dd').nestable('serialize');
$('.dd').nestable({
    callback: function(l,e){
        // l is the main container
        // e is the element that was moved
    }
});
var $a = $.a = a = {
    menu:{
        add:  function(elem){
            var data=$(elem).serializeArray();
            $.post($(elem).attr('action'),data,function(res){
                if(res.code){
                    var priority = 'success';
                    var title    = 'Başarılı';
                    var message  = '';

                    $.toaster({ priority : priority, title : title, message : message });
                }
                else{
                    var priority = 'danger';
                    var title    = 'Warning';
                    var message  = 'olmadı';

                    $.toaster({ priority : priority, title : title, message : message });

                }
            },'json');
        }
    }
}
