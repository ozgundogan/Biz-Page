<!--BEGIN: main -->
<div class="main-panel">
    <div class="content-wrapper">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3">
                        <button type="button" class="btn btn-gradient-primary m-2" data-toggle="modal" data-target="#menuModal">Yeni</button>
                    </div>
                </div>
                <div class="dd">
                    <ol class="dd-list">
                        {menuler}
                    </ol>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="menuModal" tabindex="-1" role="dialog" aria-labelledby="menuModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="test.php" onsubmit="$a.menu.add(this); return false;">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Menu Ekle</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                        <div class="form-group">
                            <label for="recipient-name" class="col-form-label">Menu Adi:</label>
                            <input type="text" name="menuAdi" class="form-control">
                            <input type="hidden" name="menuKaydet" value="menu">
                        </div>
                </div>
                <div class="modal-footer">
                    <input type="submit" class="btn btn-gradient-primary mr-2"  data-dissmiss="close" value="Kaydet">
                </div>
            </form>
        </div>
    </div>
</div>
<!--END: main -->
