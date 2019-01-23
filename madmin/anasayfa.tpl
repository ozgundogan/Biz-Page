<!--BEGIN: main -->
<div class="main-panel">
  <form id="myForm" action="test.php" method="POST" enctype="multipart/form-data">
    <div class="content-wrapper">
      <div class="col-md-12 grid-margin stretch-card">
        <div class="card">
          <div class="card-body">
            <h4 class="card-title">Slider Tablosu</h4>
            <p class="card-description">
              Slider <code>.table-hover</code>
            </p>
            <button type="button" class="btn btn-gradient-success" style="margin:25px;">Yeni</button>
            <table class="table table-hover">
              <thead>
                <tr>
                  <th>Id</th>
                  <th>Sıra</th>
                  <th>Resim</th>
                  <th></th>
                  <th></th>
                  <th></th>
                  <th></th>
                </tr>
              </thead>
              <tbody>
                <!-- BEGIN: satirlar-->
                <tr>
                  <td>{sliderid}</td>
                  <input value="{sliderid}" type="hidden">
                  <td><input type="number" name="slidersira{sliderid}" value='{slidersira}' style="width:30px;"></td>
                  <td><div id="ad-{resimgoster}" class="imagePreview" style="background-image:url('{defaultGorsel}');"></div></td>
                  <td>
                    <label for="{resimgoster}" class="btn btn-gradient-danger">Resim Seç</label>
                    <input name="slidergorsel{resimgoster}" onchange="readURL(this,'ad-{resimgoster}');" id="{resimgoster}" accept=".png, .jpg, .jpeg" style="visibility:hidden;" type="file">
                  </td>
                  <td>
                    <button onclick="slideEdit('{sliderid}','slidergorsel{resimgoster}','slidersira{sliderid}');" class="btn btn-gradient-danger">Düzenle</button>
                  </td>
                  <td style="width:100px;">
                    <button onclick="slideSil('{sliderid}');" class="btn btn-gradient-danger" >Sil</button>
                  </td>
                </tr>
                <!-- END: satirlar-->
              </tbody>
            </table>
            <input type="submit" name="sliderkaydet" class="btn btn-gradient-danger" style="margin:1em;visibility:hidden">
          </div>
        </div>
      </div>
    </form>
  </div>
  <!--END: main -->
