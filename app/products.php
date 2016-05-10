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

function formatDate($date){
    return  date_format(date_create($date), "d.m.Y");
}

function formatPrice($price){
    return "Fr. " . number_format($price, 2, ',', "'");
}

function formatAmount($amount){
    setlocale(LC_ALL, "de_CH");
    return number_format($amount,0, ".", "'");
}
?>
    <link rel="stylesheet" href="../css/custom.css">
    <div id="content">
        <!-- Alerts -->
        <div class="alert alert-success alert-dismissible" role="alert" style="display: none;" id="alertExcelImportSuccess">
        <span class="glyphicon glyphicon glyphicon-ok-sign" aria-hidden="true"></span><p id="excelImportSuccess" style="display:inline;"></p>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        </div>

        <div class="alert alert-danger alert-dismissible" role="alert" style="display: none;" id="alertExcelImportError">
            <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span><p id="excelImportError" style="display:inline;"></p>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        </div>

        <div class="alert alert-success alert-dismissible" role="alert" style="display: none;" id="alertProductDeleteSuccess">
            <span class="glyphicon glyphicon glyphicon-ok-sign" aria-hidden="true"></span><p id="productDeleteSuccess" style="display:inline;"></p>
            Produkt wurde erfolgreich gel&ouml;scht
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        </div>
        <!-- Fertig mit Alerts -->

        <div id="content_table">
            <div class="table-responsive rwd-article">
                <div id="data-table_wrapper" class="dataTables_wrapper form-inline dt-bootstrap no-footer">
                    <div class="row" style="padding-bottom: 20px;">
                        <div class="col-sm-6" style="padding-top: 10px;">
                            <div id="data-table_filter" class="dataTables_filter pull-left">
                                <!--<label><b>Suchen</b><input type="search" class="form-control input-sm" placeholder="" aria-controls="data-table" style="width: 250px;"></label>-->
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
                                        <td class="sorting_disabled" rowspan="1" colspan="1" aria-label="Preis" style="width: 250px;">Preis</td>
                                        <td class="sorting_disabled" rowspan="1" colspan="1" aria-label="Löschen" style="width: 100px;">L&ouml;schen</td>
                                    </tr>
                                </thead>

                                <tbody>
                                    <?php
                                    $products = $soapProduct -> getAllProducts();
                                    $i = 1;
                                    foreach ($products as $product) {
                                        $product = $soapProduct -> getProductByID($product['product_id']);
                                        $productImg = $soapProduct -> getProductImage($product['product_id']);
                                        $productStock = $soapProduct -> getProductStock($product['product_id']);
                                        $productDiscount = $soapProduct -> getDiscount($product['product_id']);
                                        ?>
                                        <tr role="row" class="odd" id="<?php echo $product['product_id']; ?>"><!--odd/even default -->
                                            <td onclick="loadItem('updateProduct', '<?php echo $product['product_id']; ?>');" class='sorting_1'><?php echo $i ?></td>
                                            <td onclick="loadItem('updateProduct', '<?php echo $product['product_id']; ?>');"><?php echo $product['name'] ?></td>
                                            <td onclick="loadItem('updateProduct', '<?php echo $product['product_id']; ?>');" class="col-sm-3 hidden-xs">
                                                <?php
                                                    $numItems = count($product['category_ids']);
                                                    $counter = 0;
                                                    foreach($product['category_ids'] as $productGroupId) {
                                                        //if($counter++ < 1) continue ;
                                                        $productGroup = $soapProductGroup->getCategory($productGroupId);
                                                        if (++$counter !== $numItems) {
                                                            echo $productGroup['name'] . ", ";
                                                        } else {
                                                            echo $productGroup['name'];
                                                        }
                      
                                                    }
                                                ?>
                                            </td>
                                            <td onclick="loadItem('updateProduct', '<?php echo $product['product_id']; ?>');" class="col-sm-3 hidden-xs"><img src="<?php if (isset($productImg[0]['url'])) { echo $productImg[0]['url']; } else { echo "../img/noImg.jpg"; } ?>" width="70px" class="img-thumbnail" alt="Thumbnail Image"></td>
                                            <td onclick="loadItem('updateProduct', '<?php echo $product['product_id']; ?>');"><?php echo formatAmount($productStock[0]['qty']); ?></td>
                                            <td onclick="loadItem('updateProduct', '<?php echo $product['product_id']; ?>');"><?php if ($product['special_price'] != null) { ?> <p style="text-decoration: line-through;"> <?php echo formatPrice($product['price']); ?> </p> <?php echo formatPrice($product['special_price']); ?>  <?php } else { ?>  <?php echo formatPrice($product['price']); } ?> </td>
                                            <td onclick="deleteProduct('<?php echo $product['product_id'] ?>');"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span></td>
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
                                <form action="" class="dropzone dz-clickable" id="picture"><div class="dz-default dz-message" id="pictureDiv"><span>Ziehen Sie Ihre Bilder hierhin oder klicken Sie hier, um ein Bild hochzuladen.</span></div></form>
                            </div>
                        </div>
                    </div>
                    <form method="post" id="productForm" class="form-horizontal registerForm bv-form" novalidate="novalidate">
                        <button type="submit" class="bv-hidden-submit" style="display: none; width: 0px; height: 0px;"></button>
                        <input type="hidden" class="form-control" id="sku" name="sku" value="-1">
                        <!-- Titel Input -->
                        <div class="form-group has-feedback">
                            <label class="col-sm-3 control-label">Titel</label>
                            <div class="col-sm-6">
                                <input id="article_update_title" type="text" class="form-control" name="title" value="" placeholder="Titel" data-bv-field="title" required><i class="form-control-feedback" data-bv-icon-for="title" style="display: none;"></i>
                                <small class="help-block" data-bv-validator="notEmpty" data-bv-for="title" data-bv-result="NOT_VALIDATED" style="display: none;">Bitte Artikelname angeben</small><small class="help-block" data-bv-validator="remote" data-bv-for="title" data-bv-result="NOT_VALIDATED" style="display: none;">Ein Artikel mit diesem Titel existiert bereits</small><small class="help-block" data-bv-validator="stringLength" data-bv-for="title" data-bv-result="NOT_VALIDATED" style="display: none;">Artikelname muss zwischen 2 und 50 Zeichen sein</small>
                            </div>
                        </div>


                        <!-- Kategorie Select -->
                        <div class="form-group">
                            <label class="col-sm-3 control-label">Kategorie</label>
                            <div class="col-sm-6">
                                <?php $categories = $soapProductGroup->getTree(); ?>
                                <select multiple="multiple" name="category" id="category" class="form-control" required>
                                    <?php getNextSubCategoryDropdown($categories); ?>
                                    <?php
                                    function getNextSubCategoryDropdown($category) {
                                        if ($category['children'] != null) {
                                            foreach ($category['children'] as $subCategory) { ?>
                                                <option value="<?php echo $subCategory['category_id']; ?>"> <?php echo $subCategory['name']; ?> </option> <!-- TODO indent sub categories -->
                                                <?php if ($subCategory['children'] != null) {
                                                    getNextSubCategoryDropdown($subCategory);
                                                    ?>
                                                <?php }
                                            }
                                        }
                                    } ?>
                                </select>
                            </div>
                        </div>

                        <!-- Beschreibung Input -->
                        <div class="form-group has-feedback">
                            <label class="col-sm-3 control-label">Beschreibung</label>
                            <div class="col-sm-6">
                                <textarea id="article_update_description" class="form-control" rows="5" name="short_description" placeholder="Beschreibung" data-bv-field="description" required></textarea><i class="form-control-feedback" data-bv-icon-for="description" style="display: none;"></i>
                                <small class="help-block" data-bv-validator="stringLength" data-bv-for="description" data-bv-result="NOT_VALIDATED" style="display: none;">Beschreibung darf nicht länger als 250 Zeichen sein</small>
                            </div>
                        </div>

                        <!-- Anzahl Input -->
                        <div class="form-group has-feedback">
                            <label class="col-sm-3 control-label">Anzahl / Einheit</label>
                            <div class="col-sm-3">
                                <input id="article_update_amount" type="text" class="form-control" name="stock" value="" placeholder="Anzahl" data-bv-field="stock" required><i class="form-control-feedback" data-bv-icon-for="stock" style="display: none;"></i>
                                <small class="help-block" data-bv-validator="notEmpty" data-bv-for="stock" data-bv-result="NOT_VALIDATED" style="display: none;">Bitte Anzahl angeben</small><small class="help-block" data-bv-validator="digits" data-bv-for="stock" data-bv-result="NOT_VALIDATED" style="display: none;">Anzahl kann nur Zahlen enthalten</small>
                            </div>
                            <!-- Einheit Input -->
                            <div class="col-sm-3">
                                <select name="unit" id="unit" class="form-control">
                                    <option value="Stueck">St&uuml;ck</option>
                                    <option value="Liter">Liter</option>
                                    <option value="Gramm">Gramm</option>
                                    <option value="Kilogramm">Kilogramm</option>
                                </select>
                            </div>
                        </div>

                        <!-- Preis Input -->
                        <div class="form-group has-feedback">
                            <label class="col-sm-3 control-label">Preis</label>
                            <div class="col-sm-6">
                                <input id="article_update_price" type="text" class="form-control" name="price" value="" placeholder="Preis" data-bv-field="price" required><i class="form-control-feedback" data-bv-icon-for="price" style="display: none;"></i>
                                <small class="help-block" data-bv-validator="notEmpty" data-bv-for="price" data-bv-result="NOT_VALIDATED" style="display: none;">Bitte Preis angeben</small><small class="help-block" data-bv-validator="regexp" data-bv-for="price" data-bv-result="NOT_VALIDATED" style="display: none;">Preis kann nur Zahlen enthalten</small>
                            </div>
                        </div>

                        <!-- Spezial Preis Input -->
                        <div class="form-group has-feedback">
                            <label class="col-sm-3 control-label">Spezial Preis</label>
                            <div class="col-sm-6">
                                <input id="article_update_specialPrice" type="text" class="form-control" name="specialPrice" value="" placeholder="Spezial Preis" data-bv-field="price"><i class="form-control-feedback" data-bv-icon-for="price" style="display: none;"></i>
                                <small class="help-block" data-bv-validator="notEmpty" data-bv-for="specialPrice" data-bv-result="NOT_VALIDATED" style="display: none;">Bitte Spezial Preis angeben</small><small class="help-block" data-bv-validator="regexp" data-bv-for="specialPrice" data-bv-result="NOT_VALIDATED" style="display: none;">Spezial Preis kann nur Zahlen enthalten</small>
                            </div>
                        </div>

                        <!-- Spezial Preis From Date -->
                        <div class="form-group has-feedback">
                            <label class="col-sm-3 control-label"></label>
                            <div class='col-md-5' style="padding-left: 30px;">
                                <div class="form-group">
                                    <div class='input-group date' id='datetimepickerFrom'>
                                        <input id="article_update_specialFromDate" name="specialFromDate" type='text' value='' class="form-control" placeholder="Von"/>
                                        <span class="input-group-addon">
                                            <span class="glyphicon glyphicon-calendar"></span>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Spezial Preis To Date -->
                        <div class="form-group has-feedback">
                            <label class="col-sm-3 control-label"></label>
                            <div class='col-md-5' style="padding-left: 30px; margin-top: -20px;">
                                <div class="form-group">
                                    <div class='input-group date' id='datetimepickerTo'>
                                        <input id="article_update_specialToDate" name="specialToDate" type='text' value='' class="form-control" placeholder="Bis"/>
                                        <span class="input-group-addon">
                                            <span class="glyphicon glyphicon-calendar"></span>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Hidden inputs -->
                        <input type="hidden" id="productId" name="productId" value="">
                    </form>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" name="productUpdateSave" onclick="productUpdateSave();">Speichern</button>
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
                    <ol>
                        <li>Laden Sie die Excel Vorlage hier herunter <a href="Import.xlsx" download="Beispiel-Import.xlsx" class="btn btn-primary btn-xs active" role="button">Excel Download</a></li>
                        <li>F&uuml;llen Sie die Vorlage mit Ihren Produkten, l&ouml;schen Sie keine Elemente aus der Vorlage heraus!</li>
                        <li>Laden Sie die Datei wie &uuml;ber die Funktion unten hoch, Ihre Produkte werden automatisch eingef&uuml;gt.</li>
                    </ol>

                    <form id="excelUpload" role="form" method="post" enctype="multipart/form-data">
                        <div class="form-group">
                            <label for="ProductFile">Dateiimport</label>
                            <input type="file" id="ProductFile" name="file" accept=".xlsx">
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

</div>

    <script type="text/javascript">

        $('#category').multiSelect({ keepOrder:true });

        $("#picture").dropzone({ url: "/file/post" });

        $('#datetimepickerFrom').datetimepicker();
        $('#datetimepickerTo').datetimepicker();

        $('#article_update_specialPrice').on('input', function() {
            checkSpecialPrice();
        });

        function loadItem(page, productId) {
            clearModalFields();
            if (page == 'createProduct') {
                checkSpecialPrice();
                $("#productModal").modal('toggle');
            } else if (page == 'updateProduct') {
                updateProduct(page, productId);
            }
        }

        function updateProduct(page, productId) {
            $.ajax({
                url: 'updateProduct.php',
                type: 'POST',
                data: { productId : productId,
                        product : page
                },
                success: function(result) {
                    var data = result;
                    var json = JSON.parse(data);
                    var img = document.createElement("img");
                    if(typeof json.updateImg[0] != 'undefined') {
                        img.setAttribute("src", json.updateImg[0].url);
                    } else {
                        img.setAttribute("src", "../img/noImg.jpg");
                    }
                    img.style.width = "auto";
                    img.style.height = "auto";
                    img.style.maxWidth = " 150px";
                    img.style.maxHeight = "150px";
                    $('#pictureDiv').empty();
                    $('#pictureDiv').append(img);
                    //hidden field productId
                    $('#productId').val(json.id);
                    $("#article_update_title").val(json.updateProduct.name);
                    /*$.each(json.allCategory.children, function (i, item) {
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
                    });*/
                    //set current product categories selected
                    $.each(json.updateCategory, function (i, item) {
                        $("#category").multiSelect('select', item['category_id']);
                    });
                    //$("#category").val(json.updateCategory.name);
                    $("#article_update_description").val(json.updateProduct['short_description']);
                    $("#article_update_amount").val(json.updateStock[0].qty);
                    $("#article_update_price").val(json.updateProduct.price);
                    $("#article_update_specialPrice").val(json.updateProduct['special_price']);
                    checkSpecialPrice();
                    $("#article_update_specialFromDate").val(json.updateProduct['special_from_date']);
                    $("#article_update_specialToDate").val(json.updateProduct['special_to_date']);
                    $("#productModal").modal('toggle');
                }
            });
        }

        function productUpdateSave() {
            var fData = $("#productForm").serialize();
            var categoryIds = $('select#category').val();
            $.ajax({
                url : 'updateProduct.php',
                type: 'POST',
                data: { productData : fData,
                        category_ids : categoryIds,
                        productUpdateSave : 'productUpdateSave'
                },
                success: function (data) {
                    $('#productModal').modal('hide');
                    setTimeout(function() {
                        changeSiteUpdate('products'); //TODO better return echo products and fill content with data
                    }, 1000);
                    //TODO alert success
                },
            });
        }

        function deleteProduct(productId) {
            //var tr = $(this).closest('tr');
            $.ajax({
                url: 'updateProduct.php',
                type: 'POST',
                data: { productId : productId,
                        product : 'delete'
                },
                success: function(result) {
                    /*tr.find('td').fadeOut(1000,function(){
                        tr.remove();
                    });*/
                    $('#'+productId).remove();
                    $("#alertProductDeleteSuccess").fadeTo(10000, 500).slideUp(500, function(){
                        $("#alertProductDeleteSuccess").hide();
                    });
                }
            });
        }

        function clearModalFields() {
            $('#pictureDiv').empty();
            $('#pictureDiv').append("<span>Ziehen Sie Ihre Bilder hierhin oder klicken Sie hier, um ein Bild hochzuladen.</span>");
            $("#article_update_title").val('');
            $('#category').multiSelect('deselect_all');
            $('#article_update_description').val('');
            $("#article_update_amount").val('');
            $("#article_update_price").val('');
            $("#article_update_specialPrice").val('');
            $("#article_update_specialFromDate").val('');
            $("#article_update_specialToDate").val('');
        }

        function showDatetimepicker() {
            $('#datetimepickerFrom').show();
            $('#datetimepickerTo').show();
        }

        function hideDatetimepicker() {
            $('#datetimepickerFrom').hide();
            $('#datetimepickerTo').hide();
        }

        function checkSpecialPrice() {
            if($("#article_update_specialPrice").val() !== "") {
                showDatetimepicker();
            } else {
                hideDatetimepicker();
            }
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
                    $("#importExcel").modal('toggle');
                    $('#excelImportSuccess').empty();
                    //changeSite("products");
                    $('#excelImportSuccess').html("<strong> Erfolgreich! </strong> Alle Produkte wurden erfolgreich importiert.");
                    $("#alertExcelImportSuccess").toggle();
                    $("#alertExcelImportSuccess").fadeTo(10000, 500).slideUp(500, function(){
                        $("#alertExcelImportSuccess").hide();
                    });
                },
                error: function(data){
                    $('#excelImportError').empty();
                    $("#importExcel").modal('toggle');
                    $('#excelImportError').html("<strong> Fehler! </strong>"+data['responseText']);
                    $("#alertExcelImportError").toggle();
                    $("#alertExcelImportError").fadeTo(10000, 500).slideUp(500, function(){
                        $("#alertExcelImportError").hide();
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

        Dropzone.options.mydropzone = {
            maxFiles: 1,
            maxFilesize: 10, //mb
            acceptedFiles: 'image/*',
            addRemoveLinks: true,
            autoProcessQueue: false,// used for stopping auto processing uploads
            autoDiscover: false,
            paramName: 'prod_pic',
            previewsContainer: '#dropzonePreview', //used for specifying the previews div
            clickable: false, //used this but now i cannot click on previews div to showup the file select dialog box

            accept: function(file, done) {
                console.log("uploaded");
                done();
                //used for enabling the submit button if file exist
                $( "#submitbtn" ).prop( "disabled", false );
            },

            init: function() {
                this.on("maxfilesexceeded", function(file){
                    alert("No more files please!Only One image file accepted.");
                    this.removeFile(file);
                });
                var myDropzone = this;
                $("#submitbtn").on('click',function(e) {
                    e.preventDefault();
                    myDropzone.processQueue();

                });

                this.on("reset", function (file) {
                    //used for disabling the submit button if no file exist
                    $( "#submitbtn" ).prop( "disabled", true );
                });

            }
            /*
            uploadprogress: function(file, progress, bytesSent) {
                // Display the progress
            }*/

        };

    </script>
</body>
</html>