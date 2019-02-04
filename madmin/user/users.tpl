<!-- BEGIN: main -->
<div class="row">
  <div class="col-lg-12 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">
        <h4 class="card-title">Kullanıcılar</h4>
        <p class="card-description">
          Liste: <code>kullanıcılar</code>
        </p><a href="?msayfa=register" type="button" class="btn btn-secondary">Yeni Kullanıcı</a>
        <table class="table table-hover">
          <thead>
            <tr>
              <th>Id</th>
              <th>Kullanıcı Adı</th>
              <th>E-posta</th>
              <th></th>
            </tr>
          </thead>
          <tbody>
            <!-- BEGIN: satir -->
            <tr>
              <td>{id}</td>
              <td>{username}</td>
              <td>{eposta}</td>
              <td ><a class="btn btn-primary btn-fw" href="?msayfa=edit&id={id}">Düzenle</a></td>
            </tr>
            <!-- END: satir -->
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
<!-- END: main -->
