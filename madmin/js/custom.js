var running = true;
var interval;

doToast();
start();
$(function() {
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
              var title    = 'Success';
              var message  = 'It worked!';

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
        alert("llal");
      }
      else{
        alert("llal");
      }
    }
  })

}
