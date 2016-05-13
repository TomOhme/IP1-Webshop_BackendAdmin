<?php
/**
 * Created by IntelliJ IDEA.
 * User: Janis
 * Date: 02.05.2016
 * Time: 16:36
 */

include("../api/design.php");

session_start();

if(!isset($_SESSION['username'])) {
    return header('Location: index.php');
}

$dbDesign = new Design();
$myColor = $dbDesign -> getSelectedColor();
$logoPath = "skin/frontend/webshop/default/" . $dbDesign -> getImage("logo");
$jumboPath = $dbDesign -> getImage("jumbotron");


$pathStart = "../../";

if(isset($_POST["submit"]))
{
    $imgLogo = array_filter($_FILES['file-0']);
    $imgJumbotron = array_filter($_FILES['file-1']);
    $time = $_POST['time'];

    if(!empty($imgLogo))
    {
        $target_dir = "skin/frontend/webshop/default/images/";
        $target_img = $_FILES['file-0'];

        $errorMsg = $dbDesign -> updatePicture($target_img, $target_dir, "logo_bh_", $pathStart, $time, "logo");
    }

    if(!empty($imgJumbotron))
    {
        $target_dir = "media/wysiwyg/";
        $target_img = $_FILES['file-1'];

        $errorMsg = $dbDesign -> updatePicture($target_img, $target_dir, "jumbotron_", $pathStart, $time, "jumbotron");
    }
    $color = $_POST["color"];

    $dbDesign -> setSelectedColor($color);

    $dbDesign -> cleanCache();
}

?>
<div id="content">
    <!-- Alerts -->
    <div class="alert alert-success alert-dismissible" role="alert" style="display: none;" id="alertSuccess">
        <span class="glyphicon glyphicon glyphicon-ok-sign" aria-hidden="true"></span><p id="Success" style="display:inline;"></p>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    </div>
    
    <div class="alert alert-danger alert-dismissible" role="alert" style="display: none;" id="alertError">
        <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span><p id="Error" style="display:inline;"></p>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    </div>

    <div class="content">
        <form method="post" role="form" id="formDesign" enctype="multipart/form-data">
            <div class="panel panel-default">
                <div class="panel-body">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="panel panel-default"  style="height: 450px">
                                <div class="panel-body">
                                    <div class="col-sm-12">
                                        <img id="logoImg" src="<?php echo $pathStart . $logoPath ?>" />
                                    </div>
                                    <div class="form-group">
                                        <label for="LogoFile">Logo</label>
                                        <input type="file" id="LogoFile" name="file" accept=".png,.jpg,.jpeg,.gif">
                                        <p class="help-block">Das neue Logo ausw&auml;hlen.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="" class="col-sm-6">
                            <div class="panel panel-default" style="height: 450px">
                                <div class="panel-body">
                                    <div class="col-sm-12">
                                        <img id="JumbotronImg" src="<?php echo $pathStart . $jumboPath ?>" />
                                    </div>
                                    <div class="form-group">
                                        <label for="LogoFile">Titelbild</label>
                                        <input type="file" id="JumbotronFile" name="file" accept=".png,.jpg,.jpeg,.gif">
                                        <p class="help-block">Das neue Titelbild ausw&auml;hlen.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="panel panel-default">
                                <div class="panel-body">
                                    <div class="form-group">
                                        <label for="ColorPicker">Farbe</label>
                                        <div class="radio">
                                            <label class="radio-inline" style="width: 60px;">
                                                <input type="radio" name="color" id="colorBlue" value="blue" <?php if($myColor == "blue") {?>checked<?php } ?>> Blau
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="color" id="colorRed" value="red"  <?php if($myColor == "red") {?>checked<?php } ?>> Rot
                                            </label>
                                        </div>
                                        <div class="radio">
                                            <label class="radio-inline" style="width: 60px;">
                                                <input type="radio" name="color" id="colorGreen" value="green" <?php if($myColor == "green") {?>checked<?php } ?>> Gr&uuml;n
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="color" id="colorGray" value="gray" <?php if($myColor == "beige") {?>checked<?php } ?>> Grau
                                            </label>
                                        </div>
                                        <div class="radio">
                                            <label>
                                                <input type="radio" name="color" id="colorBeige" value="beige" <?php if($myColor == "gray") {?>checked<?php } ?>> Beige
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-4"></div>
                        <div class="col-sm-2" style="margin-top: 118px;">
                            <button type="button" class="btn btn-primary" onclick="updateSetting(this);">Design speichern</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<script type="text/javascript">
    function getImgPath(oFileInput, sTargetID) {
        document.getElementById(sTargetID).value = oFileInput.value;
    }

    function updateSetting(form){
        var data = new FormData();
        var uploadTime = (+new Date());
        var logo = false;
        var jumb = false;
        
        data.append('submit', 'submitted');
        data.append('time', uploadTime);
        jQuery.each(jQuery('#LogoFile')[0].files, function(i, file) {
            data.append('file-0', file);
            logo = true;
        });

        jQuery.each(jQuery('#JumbotronFile')[0].files, function(i, file) {
            data.append('file-1', file);
            jumb = true;
        });
        data.append('color',$('input[name=color]:checked', '#formDesign').val());
        $.ajax({
            url : 'design.php',
            type: 'POST',
            cache: false,
            contentType: false,
            processData: false,
            data: data,
            success: function (data)
            {
                $("#importExcel").modal('toggle');
                $('#Success').empty();
                $('#Success').html("<strong> Erfolgreich! </strong> Einstellungen übernommen!");
                $("#alertSuccess").toggle();
                $("#alertSuccess").fadeTo(10000, 500).slideUp(500, function () {
                    $("#alertSuccess").hide();
                });

                if(logo)
                {
                    $("#logoImg").attr( 'src', '../../magento/skin/frontend/webshop/default/images/logo_bh_' + uploadTime + '.png');
                }
                if(jumb)
                {
                    $("#JumbotronImg").attr('src', '../../magento/media/wysiwyg/jumbotron_' + uploadTime + '.png');
                }
            },
            error: function(data)
            {
                $("#importExcel").modal('toggle');
                $('#Error').empty();
                $('#Error').html("<strong> Fehler! </strong><?php $errorMsg ?> ");
                $("#alertError").toggle();
                $("#alertError").fadeTo(10000, 500).slideUp(500, function () {
                    $("#alertError").hide();
                });
            }
        });
    }
</script>