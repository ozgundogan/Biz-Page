<!--BEGIN: main -->

<div class="main-panel">
  <form action="" method="get">
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
                </tr>
              </thead>
              <tbody>
                <!-- BEGIN: satirlar-->
                <tr>
                  <td>{sliderid}</td>
                  <td name="slidersira"  class="editable" >{slidersira}</td>
                  <td  name="sliderresim"><div id="ad-{resimgoster}" class="imagePreview"></div></td>
                  <td style="width:190px">
                    <label for="{resimgoster}" class="btn btn-gradient-danger">Resim Seç</label>
                    <input name="slidergorsel" onchange="readURL(this,'ad-{resimgoster}');" id="{resimgoster}"  style="visibility:hidden;" type="file"> </td>
                    <td style="width:100px;"><button type="button" class="btn btn-gradient-danger">Sil</button></td>
                  </tr>
                  <!-- END: satirlar-->
                </tbody>
              </table>
              <input type="button" name="sliderkaydet" class="btn btn-gradient-danger" style="margin:1em"><a href="test.php?sliderkaydet=1"> Kaydet </a></button>
            </div>
          </div>
        </div>
      </form>
    </div>
    <!--END: main -->
