<!-- BEGIN: main -->
<div class="main-panel">
  <div class="content-wrapper">
    <div class="row">
      <div class="col-md-12 grid-margin stretch-card">
        <div class="card">
          <div class="card-body">
            <h4 class="card-title">Form</h4>
            <p class="card-description">
              Kullanıcı Ekleme
            </p>
            <div class="row">
              <div class="col-md-4">
                <label for="image" class="btn btn-gradient-danger" style="cursor:pointer;">Resim Seç</label>
                <div id="showImage" style="background-imag:url("../images/face7.jpg");"></div>
                <input type="file" onchange="readURL(this,'showImage');" accept=".png, .jpg, .jpeg" id="image" style="visibility:hidden">
                <input type=hidden name="file_">
              </div>
              <div class="col-md-8">
                <form action="test.php"  method="post" class="forms-sample">
                  <div class="form-group row">
                    <label for="exampleInputUsername2" class="col-sm-3 col-form-label">Ad</label>
                    <div class="col-sm-9">
                      <input type="text" class="form-control" name="name" id="exampleInputUsername2" placeholder="">
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="exampleInputEmail2" class="col-sm-3 col-form-label">Soyad</label>
                    <div class="col-sm-9">
                      <input type="email" class="form-control" name="surname" id="exampleInputEmail2" placeholder="">
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="exampleInputEmail2" class="col-sm-3 col-form-label">Kullanıcı Adı</label>
                    <div class="col-sm-9">
                      <input type="email" class="form-control"name="username" id="exampleInputEmail2" placeholder="">
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="exampleInputMobile" class="col-sm-3 col-form-label">Telefon</label>
                    <div class="col-sm-9">
                      <input type="text" class="form-control" name="tel" id="exampleInputMobile" placeholder="">
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="exampleInputPassword2" class="col-sm-3 col-form-label">Parola</label>
                    <div class="col-sm-9">
                      <input type="password" class="form-control" name="pass" id="exampleInputPassword2" placeholder="">
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="exampleInputConfirmPassword2" name="passagain" class="col-sm-3 col-form-label">Parola Tekrar</label>
                    <div class="col-sm-9">
                      <input type="password"  class="form-control" id="exampleInputConfirmPassword2" placeholder="">
                    </div>
                  </div>
                  <div class="form-check form-check-flat form-check-primary">
                    <label class="form-check-label">
                      <input type="checkbox" class="form-check-input">
                      Beni Hatırla
                    </label>
                  </div>
                  <button type="submit" class="btn btn-gradient-primary mr-2">Gönder</button>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- END: main -->
