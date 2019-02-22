<!--BEGIN: main -->
<div class="main-panel">
    <div class="content-wrapper">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3">
                        <button type="button" class="btn btn-gradient-primary m-2" data-toggle="modal" data-target="#menuModal">Menu Ekle</button>
                    </div>
                </div>
                <div class="dd">
                    <ol class="dd-list">
                    </ol>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal" id="menuModal" tabindex="-1" role="dialog" aria-labelledby="menuModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="test.php" onsubmit="$a.menu.add(this); return false;">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Menu Ekle/Düzenle</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                        <div class="form-group">
                            <label for="recipient-name" class="col-form-label">Menu Adi:</label>
                            <input type="text" name="menuAdi" class="form-control">
                            <input type="hidden" name="menuIslem" value="menu">
                            <input type="hidden" name="menuId">
                        </div>
                </div>
                <div class="modal-footer">
                    <input type="submit" class="btn btn-gradient-primary mr-2" value="Kaydet">
                </div>
            </form>
        </div>
    </div>
</div>
<script type="text/JavaScript">
$(document).ready(function(){
        $a.menu.listItem($a.menu.getMenus(),'.dd-list');

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
</script>
<!--END: main -->
