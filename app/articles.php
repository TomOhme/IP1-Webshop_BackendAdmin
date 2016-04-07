<?php
/**
 * Created by IntelliJ IDEA.
 * User: Tom
 * Date: 07.04.2016
 * Time: 15:36
 */

session_start();

//überprüft ob username in der Session vorhanden
if(isset($_SESSION['username'])) {

}

include("header.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, minimum-scale=1.0, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <link rel="apple-touch-icon" sizes="300x300" href="apple-touch-icon-300x300.png" />
    <link rel="icon" sizes="300x300" href="apple-touch-icon-300x300.png">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>EasyAdmin Login</title>
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/bootstrap-theme.min.css">
    <link rel="stylesheet" href="../css/dropzone.css">
    <link rel="stylesheet" href="../css/dataTables.bootstrap.css">
    <link rel="stylesheet" href="../css/bootstrapValidator.css">
    <link rel="stylesheet" href="../css/bootstrap-colorpicker.min.css">
    <link rel="stylesheet" href="../css/custom.css">
    <link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
    <link rel="icon" href="favicon.ico" type="image/x-icon">
    <script src="../js/jquery-2.2.2.min.js"></script>
    <script src="../js/notify.min.js"></script>
    <script src="../js/bootstrap.min.js"></script>
    <meta name="apple-mobile-web-app-title" content="EasyAdmin">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <style>html{ -webkit-text-size-adjust: 100%;}</style>
</head>
<body>
<nav class="navbar navbar-default" role="navigation">
    <div class="container-fluid">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="#" onclick="changeSite('articles');"><img src="../img/Logo.png" width="25" height="25" alt="BackendAdmin">&nbsp;BackendAdmin</a>
        </div>

        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul id="nav_site" class="nav navbar-nav">
                <li onclick="changeSite('articles');"><a href="#">Artikel</a></li>
                <li onclick="changeSite('categories');"><a href="#">Kategorien</a></li>
                <!--  <li onclick='changeSite("orders");'><a href="#">Bestellungen <span class="badge">1</span></a></li> -->
                <li onclick="changeSite('sales');"><a href="#">Bestellungen</a></li>
                <li onclick="changeSite('settings');"><a href="#">Einstellungen</a></li>
            </ul>
            <ul class="nav navbar-nav navbar-right">
                <li title="Eingeloggter Benutzer"><a class="loggedinuser" href="#"><img src="../img/profile.png" width="25" height="25">&nbsp;????</a></li>
                <li><a href="http://linux203.cs.technik.fhnw.ch/magento/" target="_blank" class="visible-xs"><img src="../img/shop.png" width="25" height="25" title="Zum Webshop">&nbsp;Zum Webshop</a></li>
                <li><a href="http://linux203.cs.technik.fhnw.ch/magento/" target="_blank" class="hidden-xs"><img src="../img/shop.png" width="25" height="25" title="Zum Webshop"></a></li>
                <li onclick="logout();" class="visible-xs" id="action_logout"><a href="#"><img src="../img/logout.png" width="25" height="25" title="Logout">&nbsp;Logout</a></li>
                <li onclick="logout();" class="hidden-xs" id="action_logout2"><a href="#"><img src="../img/logout.png" width="25" height="25" title="Logout"></a></li>
            </ul>
        </div>
    </div>
</nav>
<div id="content" style="padding-left:10px; padding-right:10px;">
    <script type="text/javascript">
        loadItem('articles', 'content_table', 1);
    </script>

    <button id="articles_create" type="button" onclick="loadItem('update_article','content','-1');" class="btn btn-success">Neuer Artikel</button>
    <button id="articles_import" type="button" onclick="loadItem('import_article_overview','content','-1');" class="btn btn-success">Excel-Tabelle</button>

    <br><br>
    <div id="content_table">
        <div class="table-responsive rwd-article">
            <div id="data-table_wrapper" class="dataTables_wrapper form-inline dt-bootstrap no-footer">
                <div class="row">
                    <div class="col-sm-6"></div>
                    <div class="col-sm-6">
                        <div id="data-table_filter" class="dataTables_filter">
                            <label>Suchen<input type="search" class="form-control input-sm" placeholder="" aria-controls="data-table"></label>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <table class="table table-hover table-striped table-bordered dataTable no-footer" id="data-table" style="width: 100%;" role="grid" aria-describedby="data-table_info">
                            <thead class="tablebold">
                            <tr role="row">
                                <td class="sorting_asc" tabindex="0" aria-controls="data-table" rowspan="1" colspan="1" aria-sort="ascending" aria-label="ID: aktivieren, um Spalte absteigend zu sortieren" style="width: 136px;"><div style="width: 48px;">ID</div></td>
                                <td class="sorting" tabindex="0" aria-controls="data-table" rowspan="1" colspan="1" aria-label="Titel: aktivieren, um Spalte aufsteigend zu sortieren" style="width: 242px;">Titel</td>
                                <td class="col-sm-3 hidden-xs sorting" tabindex="0" aria-controls="data-table" rowspan="1" colspan="1" aria-label="Kategorie: aktivieren, um Spalte aufsteigend zu sortieren" style="width: 335px;">Kategorie</td>
                                <td class="col-sm-3 hidden-xs sorting_disabled" rowspan="1" colspan="1" aria-label="Bild" style="width: 335px;">Bild</td><td class="sorting_disabled" rowspan="1" colspan="1" aria-label="Bestand" style="width: 156px;">Bestand</td>
                                <td class="sorting_disabled" rowspan="1" colspan="1" aria-label="Preis" style="width: 105px;">Preis</td>
                            </tr>
                            </thead>

                            <tbody>
                            <tr onclick="loadItem('update_article','content', '556ef5fe881b3');" role="row" class="odd">
                                <td class="sorting_1">328</td>
                                <td>Roter Boskoop</td>
                                <td class="col-sm-3 hidden-xs">Äpfel</td>
                                <td class="col-sm-3 hidden-xs"><img src="????.png" width="70px" class="img-thumbnail" alt="Thumbnail Image"></td>
                                <td>100</td>
                                <td>3.95</td>
                            </tr><tr onclick="loadItem('update_article','content', '556ef6c0d5778');" role="row" class="even">
                                <td class="sorting_1">329</td>
                                <td>Bananen</td>
                                <td class="col-sm-3 hidden-xs">Früchte</td>
                                <td class="col-sm-3 hidden-xs"><img src="????.jpg" width="70px" class="img-thumbnail" alt="Thumbnail Image"></td>
                                <td>34</td>
                                <td>0.50</td>
                            </tr><tr onclick="loadItem('update_article','content', '556ef6f58d622');" role="row" class="odd">
                                <td class="sorting_1">330</td>
                                <td>Karotten</td>
                                <td class="col-sm-3 hidden-xs">Gemüse</td>
                                <td class="col-sm-3 hidden-xs"><img src="????.jpg" width="70px" class="img-thumbnail" alt="Thumbnail Image"></td>
                                <td>15</td>
                                <td>1.00</td>
                            </tr><tr onclick="loadItem('update_article','content', '556ef7663c56');" role="row" class="even">
                                <td class="sorting_1">331</td>
                                <td>Butter</td>
                                <td class="col-sm-3 hidden-xs">Test</td>
                                <td class="col-sm-3 hidden-xs"><img src="????.jpg" width="70px" class="img-thumbnail" alt="Thumbnail Image"></td>
                                <td>42</td>
                                <td>3.00</td>
                            </tr><tr onclick="loadItem('update_article','content', '560bb3682c7db');" role="row" class="odd">
                                <td class="sorting_1">332</td>
                                <td>Birne</td>
                                <td class="col-sm-3 hidden-xs">Früchte</td>
                                <td class="col-sm-3 hidden-xs">    </td>
                                <td>100</td>
                                <td>2.50</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-5">
                        <div class="dataTables_info" id="data-table_info" role="status" aria-live="polite">1 bis 5 von 5 Einträgen</div>
                    </div><div class="col-sm-7">
                    <div class="dataTables_paginate paging_simple_numbers" id="data-table_paginate">
                        <ul class="pagination">
                            <li class="paginate_button previous disabled" id="data-table_previous">
                                <a href="#" aria-controls="data-table" data-dt-idx="0" tabindex="0">Zurück</a>
                            </li>
                            <li class="paginate_button active">
                                <a href="#" aria-controls="data-table" data-dt-idx="1" tabindex="0">1</a>
                            </li>
                            <li class="paginate_button next disabled" id="data-table_next">
                                <a href="#" aria-controls="data-table" data-dt-idx="2" tabindex="0">Nächste</a>
                            </li>
                        </ul>
                    </div>
                </div>
                </div>
            </div>
        </div>

        <script type="text/javascript">
            $(document).ready(function() {

                $('#data-table').DataTable({
                    "language": {
                        "sEmptyTable":      "Keine Daten in der Tabelle vorhanden",
                        "sInfo":            "_START_ bis _END_ von _TOTAL_ Einträgen",
                        "sInfoEmpty":       "0 bis 0 von 0 Einträgen",
                        "sInfoFiltered":    "(gefiltert von _MAX_ Einträgen)",
                        "sInfoPostFix":     "",
                        "sInfoThousands":   ".",
                        "sLengthMenu":      "_MENU_ Einträge anzeigen",
                        "sLoadingRecords":  "Wird geladen...",
                        "sProcessing":      "Bitte warten...",
                        "sSearch":          "Suchen",
                        "sZeroRecords":     "Keine Einträge vorhanden.",
                        "oLanguage": {
                            "sProcessing": "loading data..."
                        },
                        "oPaginate": {
                            "sFirst":       "Erste",
                            "sPrevious":    "Zurück",
                            "sNext":        "Nächste",
                            "sLast":        "Letzte"
                        },
                        "oAria": {
                            "sSortAscending":  ": aktivieren, um Spalte aufsteigend zu sortieren",
                            "sSortDescending": ": aktivieren, um Spalte absteigend zu sortieren"
                        }
                    },
                    "bLengthChange": false,
                    "pageLength": 10,
                    "aoColumnDefs": [
                        { "bSortable": false, "aTargets": [ 3, 4, 5 ] } ]
                });
            } );
        </script>
    </div>
</div>
<script src="../js/jquery-2.2.2.min.js"></script>
<script src="../js/jquery.dataTables.js"></script>
<script src="../js/dataTables.bootstrap.js"></script>
<script src="../js/bootstrap.min.js"></script>
<script src="../js/notify.min.js"></script>
<script src="../js/dropzone.js"></script>
<script src="../js/bootstrapValidator.min.js"></script>
<script src="../js/form_validation.js"></script>
<script src="../js/bootstrap-colorpicker.min.js"></script>
<script src="../js/ckeditor.js"></script>
<script type="text/javascript">

    function changeSite(site) {
        $.ajax({
            url : 'pages.php?site=' + site,
            type: 'GET',
            success: function(data){
                $('#content').html(data);
            }
        });
    };

    function loadItem(site, placeholder, id) {
        $.ajax({
            url : 'pages.php?site=' + site + '&placeholder=' + placeholder + '&id=' + id,
            type: 'GET',
            success: function(data){
                $('#' + placeholder).html(data);
                if(site == 'update_article'){
                    update_article_validation();
                }else if(site == 'categories' & placeholder == 'content_edit'){
                    update_category_validation();
                }else if(site == 'users' & placeholder == 'content_edit'){
                    update_user_validation();
                    if(id == '-1'){
                        $('form').bootstrapValidator('enableFieldValidators', 'password', true);
                    }else{
                        $('form').bootstrapValidator('enableFieldValidators', 'password', false);
                    }
                }else if(site == 'import_article_csv'){
                    import_articles_validation();
                }
            }
        });
    };


    function logout(){
        $.ajax({
            url : 'rest/logout/',
            type: 'POST',
            success: function(data){
                location.reload();
            },
            error: function(){
                $.notify("Server error", "error");
            }
        });
    };

    $.fn.serializeObject = function() {
        var o = {};
        var a = this.serializeArray();
        $.each(a, function() {
            if (o[this.name] !== undefined) {
                if (!o[this.name].push) {
                    o[this.name] = [o[this.name]];
                }
                o[this.name].push(this.value || '');
            } else {
                o[this.name] = this.value || '';
            }
        });
        return o;
    };



    function isImage(name){
        var suffix = [".jpg", ".png", ".gif", ".jpeg"];
        for (i = 0; i < suffix.length; i++) {
            if(name.indexOf(suffix[i].toLowerCase(), name.length - suffix[i].length) !== -1){
                return true;
            }
        }
        return false;
    };

    changeSite('articles');

</script>
</body>
</html>