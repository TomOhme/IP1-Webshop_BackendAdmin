<?php
/**
 * Created by IntelliJ IDEA.
 * User: Tom Ohme, Yanick Schraner
 * Date: 07.04.2016
 * Time: 15:36
 */

include("../api/product.php");
include("../api/ProductGroup.php");

session_start();

if(!isset($_SESSION['username'])) {
    return header('Location: index.php');
}

$soapProduct = new Product();
$soapProductGroup = new ProductGroup();
$soapProduct -> openSoap();
$soapProductGroup -> openSoap();
?>
    <div id="content">
        <!-- Alerts -->
        <div class="alert alert-success alert-dismissible" role="alert" style="display: none;" id="alertExcelImportSuccess">
        <span class="glyphicon glyphicon glyphicon-ok-sign" aria-hidden="true"></span>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <strong>Erfolgreich!</strong><p id="excelImportSuccess"></p>
        </div>

        <div class="alert alert-danger alert-dismissible" role="alert" style="display: none;" id="alertExcelImportError">
            <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <strong>Fehler!</strong><p id="excelImportError"></p>
        </div>
        <!-- Fertig mit Alerts -->

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
                            <button id="create_article" type="button" onclick="loadItem('createProduct','newProductId')" class="btn btn-primary" data-toggle="modal" data-target="#myModal">Neuer Artikel</button>

                            <button id="import_article" type="button" class="btn btn-primary" data-toggle="modal" data-target="#importExcel">Excel-Tabelle</button>
                        </div>
                    </div>
                    <div class="row">
                        <class="col-sm-12">
                            <table class="table table-responsive table-hover table-striped table-bordered dataTable no-footer" id="data-table" style="width: 100%;" role="grid" aria-describedby="data-table_info">
                                <thead class="tablebold">
                                    <tr role="row">
                                        <td class="sorting_asc" tabindex="0" aria-controls="data-table" rowspan="1" colspan="1" aria-sort="ascending" aria-label="ID: aktivieren, um Spalte absteigend zu sortieren" style="width: 50px;"><div style="width: 48px;">ID</div></td>
                                        <td class="sorting" tabindex="0" aria-controls="data-table" rowspan="1" colspan="1" aria-label="Titel: aktivieren, um Spalte aufsteigend zu sortieren" style="width: 200px;">Titel</td>
                                        <td class="col-sm-3 hidden-xs sorting" tabindex="0" aria-controls="data-table" rowspan="1" colspan="1" aria-label="Kategorie: aktivieren, um Spalte aufsteigend zu sortieren" style="width: 330px;">Kategorie</td>
                                        <td class="col-sm-3 hidden-xs sorting_disabled" rowspan="1" colspan="1" aria-label="Bild" style="width: 200px;">Bild</td>
                                        <td class="sorting_disabled" rowspan="1" colspan="1" aria-label="Bestand" style="width: 150px;">Bestand</td>
                                        <td class="sorting_disabled" rowspan="1" colspan="1" aria-label="Preis" style="width: 100px;">Preis</td>
                                        <td class="sorting_disabled" rowspan="1" colspan="1" aria-label="Rabatt" style="width: 100px;">Rabatt</td>
                                        <td class="sorting_disabled" rowspan="1" colspan="1" aria-label="Löschen" style="width: 100px;">L&ouml;schen</td>
                                    </tr>
                                </thead>

                                <tbody>
                                    <?php
                                    $products = $soapProduct -> getAllProducts();
                                    $i = 1;
                                    foreach ($products as $product) {
                                        $productImg = $soapProduct -> getProductImage($product['product_id']);
                                        $productStock = $soapProduct -> getProductStock($product['product_id']);
                                        ?>
                                        <tr onclick="loadItem('updateProduct', '<?php echo $product['product_id'] ?>');" role="row" class="odd"><!--odd/even default -->
                                            <td class='sorting_1'><?php echo $i ?></td>
                                            <td><?php echo $product['name'] ?></td>
                                            <td class="col-sm-3 hidden-xs">
                                                <?php
                                                    $numItems = count($product['category_ids']);
                                                    $j = 0;
                                                    foreach($product['category_ids'] as $productGroupId) {
                                                        $productGroup = $soapProductGroup->getCategory($productGroupId);
                                                        if (++$j !== $numItems) {
                                                            echo $productGroup['name'] . ", ";
                                                        } else {
                                                            echo $productGroup['name'];
                                                        }
                                                    }
                                                ?>
                                            </td>
                                            <td class="col-sm-3 hidden-xs"><img src="<?php echo $productImg[0]['url'] ?>" width="70px" class="img-thumbnail" alt="Thumbnail Image"></td>
                                            <td><?php echo $productStock[0]['qty'] ?></td>
                                            <td><?php echo $product['price'] ?></td>
                                            <td></td>
                                            <td><span class="glyphicon glyphicon-remove" aria-hidden="true"></span></td>
                                        </tr>
                                        <?php
                                        $i++;
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-5">
                            <div class="dataTables_info" id="data-table_info" role="status" aria-live="polite"></div>
                        </div>
                        <div class="col-sm-7">
                            <div class="dataTables_paginate paging_simple_numbers" id="data-table_paginate">
                                <ul class="pagination">
                                    <li class="paginate_button previous disabled" id="data-table_previous"></li>
                                    <li class="paginate_button active"></li>
                                    <li class="paginate_button next disabled" id="data-table_next"></a>
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
    <div class="modal fade" id="productModal" role="dialog" style="display: none;">
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
                                <select name="category" id="category" class="form-control"></select>
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

                        <!-- Hidden inputs -->
                        <input type="hidden" id="hiddenInput1" name="hiddenInput1" value="">
                        <input type="hidden" id="hiddenInput2" name="hiddenInput2" value="">
                        <input type="hidden" id="hiddenInput3" name="hiddenInput3" value="">
                        <input type="hidden" id="hiddenInput4" name="hiddenInput4" value="">
                    </form>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" onclick="productUpdateSave();">Speichern</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Abbrechen</button>
                </div>
            </div>

        </div>
    </div>

    <div class="modal fade" tabindex="-1" role="dialog" id="importExcel" role="dialog" style="display: none;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Produkte via Excel importieren</h4>
                </div>
                <div class="modal-body">
                    <button type="button" class="btn btn-info"><a href="Import.csv" download="Beispiel-Import.csv">Excel Download</a></button>
                    <p>Bitte w&auml;hlen Sie die Excel Datei mit den zu importierenden Daten aus</p>
                    <form id="excelUpload" role="form" method="post" enctype="multipart/form-data">
                        <div class="form-group">
                            <label for="ProductFile">Dateiimport</label>
                            <input type="file" id="ProductFile" name="file" accept=".csv">
                            <p class="help-block">Die Excel Datei mit den Eingetragenen Produkten ausw&auml;hlen.</p>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Schliessen</button>
                        <button type="button" class="btn btn-primary" onclick="uploadExcel(this);">Hochladen</button>
                    </form>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

    <script type="text/javascript">

        function loadItem(page, productId) {
            clearModalFields();
            if (page == 'createProduct') {
                $("#productModal").modal('toggle');
            } else if (page == 'updateProduct') {
                updateProduct(productId);
            }
        }

        function updateProduct(productId) {
            $.ajax({
                url: 'updateProduct.php',
                type: 'POST',
                data: { productId : productId },
                success: function(result) {
                    var data = result;
                    var json = JSON.parse(data);
                    //$("#picture").val(json.updateImg[0].url); //TODO show image in form, not with value
                    $("#article_update_title").val(json.updateProduct.name);
                    $.each(json.allCategory.children, function (i, item) {
                        $('#category').append($('<option>', {
                            value: item.name,
                            text: item.name
                        }));
                        $.each(item.children, function (i, item) {
                            $('#category').append($('<option>', {
                                value: item.name,
                                text: "- " + item.name
                            }));
                        });
                    });
                    $("#category select").val(json.updateCategory.name); //TODO select current category in category dropdown list
                    $("#article_update_description").val(json.updateProduct.description);
                    $("#article_update_amount").val(json.updateStock[0].qty);
                    $("#article_update_price").val(json.updateProduct.price);
                    $("#productModal").modal('toggle');
                }
            });
        }

        function productUpdateSave() {
            $.ajax({
                url: 'updateProduct.php',
                type: 'POST',
                data: { productId : productId },
                //TODO wenn noch keine Id -> create sonst update product
            });
        }

        function deleteProduct(productId) {
            //TODO delete product
        }

        function clearModalFields() {
            //TODO clear Picture
            $("#article_update_title").val('');
            $('#category').empty();
            $('#article_update_description').val('');
            $("#article_update_amount").val('');
            $("#article_update_price").val('');
        }

        //TODO js function for required fields

        function uploadExcel(form) {
            var data = new FormData();
            jQuery.each(jQuery('#ProductFile')[0].files, function(i, file) {
                data.append('file-'+i, file);
            });
            $.ajax({
                url: "../api/excelUpload.php",
                type: 'POST',
                cache: false,
                contentType: false,
                processData: false,
                data: data,
                success: function (data) {
                    $('#excelImportSuccess').append(data['responseText']);
                    $('#importExcel').modal('toggle');
                    $("#alertExcelImportSuccess").alert();
                    $("#alertExcelImportSuccess").fadeTo(10000, 500).slideUp(500, function(){
                        $("#alertExcelImportSuccess").alert('close');
                    });
                },
                error: function(data){
                    $('#excelImportError').append(data['responseText']);
                    $('#importExcel').modal('toggle');
                    $("#alertExcelImportSuccess").alert();
                    $("#alertExcelImportError").fadeTo(10000, 500).slideUp(500, function(){
                        $("#alertExcelImportError").alert('close');
                    });
                }
            });
        }

        $(document).ready(function() {
            $('#data-table').DataTable({
                "language": {
                    "sEmptyTable":      "Keine Daten in der Tabelle vorhanden",
                    "sInfo":            "_START_ bis _END_ von _TOTAL_ Eintr&auml;gen",
                    "sInfoEmpty":       "0 bis 0 von 0 Eintr&auml;gen",
                    "sInfoFiltered":    "(gefiltert von _MAX_ Eintr&auml;gen)",
                    "sInfoPostFix":     "",
                    "sInfoThousands":   ".",
                    "sLengthMenu":      "_MENU_ Eintr&auml;ge anzeigen",
                    "sLoadingRecords":  "Wird geladen...",
                    "sProcessing":      "Bitte warten...",
                    "sSearch":          "Suchen",
                    "sZeroRecords":     "Keine Eintr&auml;ge vorhanden.",
                    "oLanguage": {
                        "sProcessing": "loading data..."
                    },
                    "oPaginate": {
                        "sFirst":       "Erste",
                        "sPrevious":    "Zur&uuml;ck",
                        "sNext":        "N&auml;chste",
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
        });

    </script>
</body>
</html>