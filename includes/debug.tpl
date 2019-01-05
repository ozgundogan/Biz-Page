<!-- BEGIN: main -->
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/css/bootstrap.min.css"
          integrity="sha384-9gVQ4dYFwwWSjIDZnLEWnxCjeSWFphJiwGPXr1jddIhOegiu1FwO5qRGvFXOdJZ4" crossorigin="anonymous">
    <title>Debug Log</title>
    <style type="text/css">
        .modal-body pre {margin: 0; font-family: monospace;}
        .modal-body a:link {color: #009; text-decoration: none; background-color: #fff;}
        .modal-body a:hover {text-decoration: underline;}
        .xtable {border-collapse: collapse; border: 0; width: 100%; box-shadow: 1px 2px 3px #ccc;}
        .modal-body {text-align: center;}
        .modal-body table {text-align: left; width: 100% !important;}
        .modal-body th {text-align: center !important;}
        .modal-body td, .modal-body th {border: 1px solid #666; font-size: 75%; vertical-align: baseline; padding: 4px 5px;}
        .modal-body .p {text-align: left;}
        .modal-body .e {background-color: #ccf; width: 300px; font-weight: bold;}
        .modal-body .h {background-color: #99c; font-weight: bold;}
        .modal-body .v {background-color: #ddd; max-width: 300px; overflow-x: auto;}
        .modal-body .v i {color: #999;}
    </style>
</head>
<body>
<div class="container">
    <div class="col-md-12" style="margin-top:20px">
        <div class="jumbotron">
            <h3 class="display-5">Debug Log
                <small> v{version}</small>
            </h3>
            <hr class="my-4">
            <div class="row">
                <div class="col-md-8">
                    <div class="card" style="margin-bottom:20px">
                        <div class="card-body">
                            <h6 class="card-title">Status</h6>
                            <div class="row">
                                <div class="form-group col-md-4">
                                    <label>Start Time</label>
                                    <input type="text" class="form-control" value="{start}" readonly>
                                </div>
                                <div class="form-group col-md-4">
                                    <label>End Time</label>
                                    <input type="text" class="form-control" value="{end}" readonly>
                                </div>
                                <div class="form-group col-md-4">
                                    <label>Elapsed Time</label>
                                    <input type="text" class="form-control"
                                           value="{elapsedsecond} second. ({elapsed})" readonly>
                                </div>
                                <div class="form-group col-md-6">
                                    <label>User IP</label>
                                    <input type="text" class="form-control" value="{userip}" readonly>
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Loaded Views</label>
                                    <input type="text" class="form-control" value="{views}" readonly>
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Database Provider</label>
                                    <input type="text" class="form-control" value="{dbprovider}" readonly>
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Database Status</label>
                                    <input type="text" class="form-control" value="{dbstatus}" readonly>
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Database Server</label>
                                    <input type="text" class="form-control" value="{dbserver}" readonly>
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Database Name</label>
                                    <input type="text" class="form-control" value="{dbname}" readonly>
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Database Username</label>
                                    <input type="text" class="form-control" value="{dbusername}" readonly>
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Database Charset</label>
                                    <input type="text" class="form-control" value="{dbcharset}" readonly>
                                </div>
                                <div class="form-group col-md-6">
                                    <label>PHP Version</label>

                                    <div class="input-group">
                                        <input type="text" class="form-control" value="{phpversion}" readonly>
                                        <div class="input-group-prepend">
                                            <button type="button" class="btn btn-primary btn-block" data-toggle="modal" data-target="#phpinfo">
                                                PHP Info
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Error Levels</label>
                                    <input type="text" class="form-control" value="{errorlevels}" readonly>
                                </div>

                            </div>
                        </div>

                    </div>
                    <div class="card" style="margin-bottom:20px">
                        <div class="card-body">
                            <h6 class="card-title">System Error Messages</h6>
                            <ul class="list-group list-group-flush">
                                <!-- BEGIN: message -->
                                <li class="list-group-item">{errornumber}{errormessage}</li>
                                <!-- END: message -->
                            </ul>
                        </div>
                    </div>
                    <div class="card"style="margin-bottom:20px">
                        <div class="card-body">
                            <h6 class="card-title">PHP Error Messages</h6>
                            <!-- BEGIN: phpmessage -->
                            <div class="panel-group card" style="margin-bottom:5px">
                                <div class="panel panel-default">
                                    <div class="panel-heading" style="padding:10px;padding-bottom:0px">
                                        <h6 class="panel-title">
                                            <a data-toggle="collapse" href="#collapse{errorid}" style="color: #6c757d;text-decoration: none;">{phperror}</a>
                                        </h6>
                                    </div>
                                    <div id="collapse{errorid}" class="panel-collapse collapse" style="padding:10px">
                                        <div class="panel-body" style="color: #6c757d;"><strong>Error Number: </strong>{phpnum} &nbsp;&nbsp;&nbsp;<strong>Error Code: </strong>{phptype}<br><strong>File : </strong>{phpfile}<br><strong>Line : </strong>{phpline}</div>
                                    </div>
                                </div>
                            </div>
                            <!-- END: phpmessage -->
                            <!-- BEGIN: nophpmessage -->
                            <p>No PHP errors</p>
                            <!-- END: nophpmessage -->


                        </div>
                    </div>
                    <!-- BEGIN: debugreturn -->
                    <div class="card" style="margin-bottom:20px">
                        <div class="card-body">
                            <h6 class="card-title">Return Parameters</h6>
                            <pre>{debugreturn}</pre>

                        </div>
                    </div>
                    <!-- END: debugreturn -->
                </div>
                <div class="col-md-4">
                    <div class="card" style="margin-bottom:20px">
                        <div class="card-body">
                            <h6 class="card-title">Teknolobi Files</h6>
                            <!-- BEGIN: tfilelist -->
                            <div class="form-group">
                                <label>{filename}</label>
                                <input type="text" class="form-control form-control-sm" value="{filetime}"
                                       readonly>
                            </div>
                            <!-- END: tfilelist -->
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-body">
                            <h6 class="card-title">Other Files</h6>
                            <!-- BEGIN: nfilelist -->
                            <div class="form-group">
                                <label>{filename}</label>
                                <input type="text" class="form-control form-control-sm" value="{filetime}"
                                       readonly>
                            </div>
                            <!-- END: nfilelist -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="phpinfo" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
    <div class="modal-dialog  modal-lg bg-transparent" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">PHP Info</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                {phpinfo}
            </div>
        </div>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"
        integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo"
        crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js"
        integrity="sha384-cs/chFZiN24E4KMATLdqdvsezGxaGsi4hLGOzlXwp5UZB1LY//20VyM2taTB4QvJ"
        crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js"
        integrity="sha384-uefMccjFJAIv6A+rW+L4AHf99KvxDjWSu1z9VI8SKNVmz4sk7buKt/6v9KI65qnm"
        crossorigin="anonymous"></script>
<script>
    $( document ).ready(function() {
        $('.modal-body').html($('.modal-body .center').html());
        //$('.modal-body .center table').css('width','100%');
    });
</script>
</body>
</html>
<!-- END: main -->