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

?>
    <div id="content">
        <br><br>
        <div id="content_table">
            <div class="table-responsive rwd-article">
                <div id="data-table_wrapper" class="dataTables_wrapper form-inline dt-bootstrap no-footer">
                    <div class="row" style="padding-bottom: 20px;">
                        <div class="col-sm-6" style="padding-top: 10px;">
                            <div id="data-table_filter" class="dataTables_filter pull-left">
                                <label><b>Suchen</b><input type="search" class="form-control input-sm" placeholder="" aria-controls="data-table" style="width: 250px;"></label>
                            </div>
                        </div>
                        <div class="col-sm-6 text-right">
                            <!-- Trigger the modal with a button -->
                            <button id="articles_create" type="button" onclick="loadItem('create_article','content,'-1')" class="btn btn-primary" data-toggle="modal" data-target="#myModal" style="width:160px; height:45px;"; >Neuer Artikel</button>

                            <button id="articles_import" type="button" onclick="loadItem('import_article_overview','content','-1');" class="btn btn-primary" style="width:160px; height:45px";>Excel-Tabelle</button>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <table class="table table-responsive table-hover table-striped table-bordered dataTable no-footer" id="data-table" style="width: 100%;" role="grid" aria-describedby="data-table_info">
                                <thead class="tablebold">
                                    <tr role="row">
                                        <td class="sorting_asc" tabindex="0" aria-controls="data-table" rowspan="1" colspan="1" aria-sort="ascending" aria-label="ID: aktivieren, um Spalte absteigend zu sortieren" style="width: 136px;"><div style="width: 48px;">ID</div></td>
                                        <td class="sorting" tabindex="0" aria-controls="data-table" rowspan="1" colspan="1" aria-label="Titel: aktivieren, um Spalte aufsteigend zu sortieren" style="width: 242px;">Titel</td>
                                        <td class="col-sm-3 hidden-xs sorting" tabindex="0" aria-controls="data-table" rowspan="1" colspan="1" aria-label="Kategorie: aktivieren, um Spalte aufsteigend zu sortieren" style="width: 335px;">Kategorie</td>
                                        <td class="col-sm-3 hidden-xs sorting_disabled" rowspan="1" colspan="1" aria-label="Bild" style="width: 335px;">Bild</td><td class="sorting_disabled" rowspan="1" colspan="1" aria-label="Bestand" style="width: 156px;">Bestand</td>
                                        <td class="sorting_disabled" rowspan="1" colspan="1" aria-label="Preis" style="width: 105px;">Preis</td>
                                        <td class="sorting_disabled" rowspan="1" colspan="1" aria-label="Rabatt" style="width: 105px;">Rabatt</td>
                                    </tr>
                                </thead>

                                <tbody>
                                    <tr onclick="loadItem('update_article','content', '556ef5fe881b3');" role="row" class="odd">
                                        <td class="sorting_1">1</td>
                                        <td>Apfel</td>
                                        <td class="col-sm-3 hidden-xs">&Auml;pfel</td>
                                        <td class="col-sm-3 hidden-xs"><img src="../img/testBild.png" width="70px" class="img-thumbnail" alt="Thumbnail Image"></td>
                                        <td>1</td>
                                        <td>3.95</td>
                                        <td>10%</td>
                                    </tr><tr onclick="loadItem('update_article','content', '556ef6c0d5778');" role="row" class="even">
                                        <td class="sorting_1">2</td>
                                        <td>Apfel</td>
                                        <td class="col-sm-3 hidden-xs">&Auml;pfel</td>
                                        <td class="col-sm-3 hidden-xs"><img src="../img/testBild.png" width="70px" class="img-thumbnail" alt="Thumbnail Image"></td>
                                        <td>1</td>
                                        <td>3.95</td>
                                        <td>10%</td>
                                    </tr><tr onclick="loadItem('update_article','content', '556ef6f58d622');" role="row" class="odd">
                                        <td class="sorting_1">3</td>
                                        <td>Apfel</td>
                                        <td class="col-sm-3 hidden-xs">&Auml;pfel</td>
                                        <td class="col-sm-3 hidden-xs"><img src="../img/testBild.png" width="70px" class="img-thumbnail" alt="Thumbnail Image"></td>
                                        <td>1</td>
                                        <td>3.95</td>
                                        <td>10%</td>
                                    </tr><tr onclick="loadItem('update_article','content', '556ef7663c56');" role="row" class="even">
                                        <td class="sorting_1">4</td>
                                        <td>Apfel</td>
                                        <td class="col-sm-3 hidden-xs">&Auml;pfel</td>
                                        <td class="col-sm-3 hidden-xs"><img src="../img/testBild.png" width="70px" class="img-thumbnail" alt="Thumbnail Image"></td>
                                        <td>1</td>
                                        <td>3.95</td>
                                        <td>10%</td>
                                    </tr><tr onclick="loadItem('update_article','content', '560bb3682c7db');" role="row" class="odd">
                                        <td class="sorting_1">5</td>
                                        <td>Apfel</td>
                                        <td class="col-sm-3 hidden-xs">&Auml;pfel</td>
                                        <td class="col-sm-3 hidden-xs"><img src="../img/testBild.png" width="70px" class="img-thumbnail" alt="Thumbnail Image"></td>
                                        <td>1</td>
                                        <td>3.95</td>
                                        <td>10%</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-5">
                            <div class="dataTables_info" id="data-table_info" role="status" aria-live="polite">1 bis 5 von 5 Eintr&auml;gen</div>
                        </div>
                        <div class="col-sm-7">
                            <div class="dataTables_paginate paging_simple_numbers" id="data-table_paginate">
                                <ul class="pagination">
                                    <li class="paginate_button previous disabled" id="data-table_previous">
                                        <a href="#" aria-controls="data-table" data-dt-idx="0" tabindex="0">Zur&uuml;ck</a>
                                    </li>
                                    <li class="paginate_button active">
                                        <a href="#" aria-controls="data-table" data-dt-idx="1" tabindex="0">1</a>
                                    </li>
                                    <li class="paginate_button next disabled" id="data-table_next">
                                        <a href="#" aria-controls="data-table" data-dt-idx="2" tabindex="0">N&auml;chste</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal create/update article-->
    <div class="modal fade" id="myModal" role="dialog" style="display: none;">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Artikel erfassen</h4>
                </div>
                <div class="modal-body">

                    <div class="form-horizontal">
                        <div class="form-group">
                            <label class="col-sm-3 control-label">Bilder</label>
                            <div class="col-sm-6">
                                <form action="upload.php" class="dropzone dz-clickable" id="picture"><div class="dz-default dz-message"><span>Ziehen Sie Ihre Bilder hierhin oder klicken Sie hier, um ein Bild hochzuladen.</span></div></form>
                            </div>
                        </div>
                    </div>
                    <form mehtod="post" id="create" class="form-horizontal registerForm bv-form" novalidate="novalidate"><button type="submit" class="bv-hidden-submit" style="display: none; width: 0px; height: 0px;"></button>
                        <input type="hidden" class="form-control" id="sku" name="sku" value="-1">
                        <!-- Titel Input -->
                        <div class="form-group has-feedback">
                            <label class="col-sm-3 control-label">Titel</label>
                            <div class="col-sm-6">
                                <input id="article_update_title" type="text" class="form-control" name="title" value="" placeholder="Titel" data-bv-field="title"><i class="form-control-feedback" data-bv-icon-for="title" style="display: none;"></i>
                                <small class="help-block" data-bv-validator="notEmpty" data-bv-for="title" data-bv-result="NOT_VALIDATED" style="display: none;">Bitte Artikelname angeben</small><small class="help-block" data-bv-validator="remote" data-bv-for="title" data-bv-result="NOT_VALIDATED" style="display: none;">Ein Artikel mit diesem Titel existiert bereits</small><small class="help-block" data-bv-validator="stringLength" data-bv-for="title" data-bv-result="NOT_VALIDATED" style="display: none;">Artikelname muss zwischen 2 und 50 Zeichen sein</small></div>
                        </div>

                        <!-- Kategorie Select -->
                        <div class="form-group">
                            <label class="col-sm-3 control-label">Kategorie</label>
                            <div class="col-sm-6">
                                <select name="category" id="category" class="form-control">
                                    <option value="1">Apfel</option>
                                    <option value="1">- Apfell</option>
                                    <option value="1">Apfel</option>
                                </select>
                            </div>
                        </div>

                        <!-- Beschreibung Input -->
                        <div class="form-group has-feedback">
                            <label class="col-sm-3 control-label">Beschreibung</label>
                            <div class="col-sm-6">
                                <textarea id="article_update_description" class="form-control" rows="5" name="description" placeholder="Beschreibung" data-bv-field="description"></textarea><i class="form-control-feedback" data-bv-icon-for="description" style="display: none;"></i>
                                <small class="help-block" data-bv-validator="stringLength" data-bv-for="description" data-bv-result="NOT_VALIDATED" style="display: none;">Beschreibung darf nicht länger als 250 Zeichen sein</small></div>
                        </div>

                        <!-- Anzahl Input -->
                        <div class="form-group has-feedback">
                            <label class="col-sm-3 control-label">Anzahl</label>
                            <div class="col-sm-6">
                                <input id="article_update_amount" type="text" class="form-control" name="stock" value="" placeholder="Anzahl" data-bv-field="stock"><i class="form-control-feedback" data-bv-icon-for="stock" style="display: none;"></i>
                                <small class="help-block" data-bv-validator="notEmpty" data-bv-for="stock" data-bv-result="NOT_VALIDATED" style="display: none;">Bitte Anzahl angeben</small><small class="help-block" data-bv-validator="digits" data-bv-for="stock" data-bv-result="NOT_VALIDATED" style="display: none;">Anzahl kann nur Zahlen enthalten</small></div>
                        </div>

                        <!-- Preis Input -->
                        <div class="form-group has-feedback">
                            <label class="col-sm-3 control-label">Preis</label>
                            <div class="col-sm-6">
                                <input id="article_update_price" type="text" class="form-control" name="price" value="" placeholder="Preis" data-bv-field="price"><i class="form-control-feedback" data-bv-icon-for="price" style="display: none;"></i>
                                <small class="help-block" data-bv-validator="notEmpty" data-bv-for="price" data-bv-result="NOT_VALIDATED" style="display: none;">Bitte Preis angeben</small><small class="help-block" data-bv-validator="regexp" data-bv-for="price" data-bv-result="NOT_VALIDATED" style="display: none;">Preis kann nur Zahlen enthalten</small></div>
                        </div>

                        <!-- Button Speichern/Abbrechen/Löschen -->
                        <div class="form-group">
                            <div class="col-sm-9 col-sm-offset-3">
                                <button id="article_update_save" class="btn btn-primary" role="button">Speichern</button>
                                <button id="article_update_abort" class="btn" role="button" onclick="changeSite('articles');">Abbrechen</button>
                            </div>
                        </div>

                        <!-- Hidden inputs -->
                        <input type="hidden" id="hiddenInput1" name="hiddenInput1" value="">
                        <input type="hidden" id="hiddenInput2" name="hiddenInput2" value="">
                        <input type="hidden" id="hiddenInput3" name="hiddenInput3" value="">
                        <input type="hidden" id="hiddenInput4" name="hiddenInput4" value="">
                    </form>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>

        </div>
    </div>

    <script type="text/javascript">

        function loadItem(page) {
            //Inputparameter updateArticle id
            if (page == 'create_article') {
                $("#myModal").modal();
            } else if (page == 'update_article') {
                $("#myModal").modal(); //load with article id
            } else if (page == 'import_article_overview') {

            }
        }

        /*function loadItem(page, placeholder, id) {
            $.ajax({
                url : 'pages.php?page=' + page + '&placeholder=' + placeholder + '&id=' + id,
                type: 'GET',
                success: function(data){
                    $('#' + placeholder).html(data);

                    if(site == 'update_article'){
                        update_article_validation();
                    } else if(site == 'categories' & placeholder == 'content_edit'){
                        update_category_validation();
                    } else if(site == 'users' & placeholder == 'content_edit'){
                        update_user_validation();
                        if(id == '-1'){
                            $('form').bootstrapValidator('enableFieldValidators', 'password', true);
                        } else{
                            $('form').bootstrapValidator('enableFieldValidators', 'password', false);
                        }
                    } else if(site == 'import_article_csv'){
                        import_articles_validation();
                    }
                }
            });
        };*/

    </script>
</body>
</html>