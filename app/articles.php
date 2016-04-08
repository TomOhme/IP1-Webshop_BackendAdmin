<?php
/**
 * Created by IntelliJ IDEA.
 * User: Tom
 * Date: 07.04.2016
 * Time: 15:36
 */

session_start();

if(!isset($_SESSION['username'])) {
    return header('Location: index.php');
}

include("header.php");
include("menu.php");
?>
    <div id="content" style="padding-left:50px; padding-right:50px;">
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

            <button id="articles_create" type="button" onclick="loadItem('update_article','content','-1');" class="btn btn-success">Neuer Artikel</button>
            <button id="articles_import" type="button" onclick="loadItem('import_article_overview','content','-1');" class="btn btn-success">Excel-Tabelle</button>

    </div>
</div>

<script type="text/javascript">

    //changeSite("articles");

    function changeSite(page) {
        $.ajax({
            url : 'pages.php?page=' + page,
            type: 'GET',
            success: function(data){
                $('#content').html(data);
            }
        });
    };

    function logout() {
        window.location = "http://127.0.0.1/magento_backendAdmin/app/index.php";
    };

</script>
</body>
</html>