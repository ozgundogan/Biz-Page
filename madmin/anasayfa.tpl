<!-- BEGIN: main -->

<div class="main-panel">
  <form action="test.php" method="post">
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
                <!--BEGIN: sliders -->
                <tr>
                  <td>{sliderid}</td>
                  <td class="editable" name="slidersira">{slidersira}</td>
                  <td class="editable" name="sliderresim">{sliderresim}</td>
                  <td style="width:190px"> <button type="button" class="btn btn-gradient-danger btn-icon-text">
                    Resim Yükle
                  </button></td>
                  <td style="width:100px;"><button type="button" class="btn btn-gradient-danger">Sil</button></td>
                </tr>
                <!--END: sliders -->
              </tbody>
            </table>
          <button type="button" name="sliderkaydet" class="btn btn-gradient-danger" style="margin:1em"> Kaydet</button>
          </div>
        </div>
      </div>
    </form>
  </div>
  <!-- END: main -->
