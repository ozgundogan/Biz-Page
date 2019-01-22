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
          console.log(id);
          reader.readAsDataURL(input.files[0]);
      }
  }
