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

$dbColor = new Design();

$myColor = $dbColor -> getSelectedColor();

if(isset($_POST["submit"]))
{
    $imgLogo = array_filter($_FILES['file-0']);
    $imgJumbotron = array_filter($_FILES['file-1']);

    if(!empty($imgLogo))
    {
        $target_dir = "../../skin/frontend/webshop/default/images/";
        $target_file = $target_dir . basename($_FILES['file-0']["name"]);
        $uploadOk = 1;
        $imageFileType = pathinfo($target_file, PATHINFO_EXTENSION);

        $errorMsg = "";

        $check = getimagesize($_FILES['file-0']["tmp_name"]);

        if ($check == false) {
            $uploadOk = 0;
            $errorMsg .= "Die Datei ist kein Bild!\n";
        }

        if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
            $uploadOk = 0;
            $errorMsg .= "Nur JPG, PNG & GIF Dateien sind erlaubt.\n";
        }

        if ($_FILES['file-0']["size"] > 500000) {
            $uploadOk = 0;
            $errorMsg .= "Das Bild ist zu gross.\n";
        }

        if ($uploadOk == 0) {
            $errorMsg .= "Bild wurde nicht hochgeladen";

        } else {
            foreach (glob($target_dir . "logo_bh.png") as $file) {
                unlink($file);
            }

            move_uploaded_file($_FILES['file-0']["tmp_name"], $target_dir . "logo_bh.png");
        }
    }

    if(!empty($imgJumbotron))
    {
        $target_dir = "../../media/wysiwyg/";
        $target_file = $target_dir . basename($_FILES['file-1']["name"]);
        $uploadOk = 1;
        $imageFileType = pathinfo($target_file, PATHINFO_EXTENSION);

        $errorMsg = "";

        $check = getimagesize($_FILES['file-1']["tmp_name"]);

        if ($check == false) {
            $uploadOk = 0;
            $errorMsg .= "Die Datei ist kein Bild!\n";
        }

        if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
            $uploadOk = 0;
            $errorMsg .= "Nur JPG, PNG & GIF Dateien sind erlaubt.\n";
        }

        if ($_FILES['file-1']["size"] > 500000) {
            $uploadOk = 0;
            $errorMsg .= "Das Bild ist zu gross.\n";
        }

        if ($uploadOk == 0) {
            $errorMsg .= "Bild wurde nicht hochgeladen";

        } else {
            foreach (glob($target_dir . "logo_bh.png") as $file) {
                unlink($file);
            }

            move_uploaded_file($_FILES['file-1']["tmp_name"], $target_dir . "jumbotron.png");
        }
    }
    $color = $_POST["color"];
    $destCss = "../../skin/frontend/webshop/default/css/webshop.css";

    $dbColor -> setSelectedColor($color);

    if($color == "blue")
    {
        $targetCss = "../css/blue.css";

        unlink($destCss);

        copy($targetCss, $destCss);
    }
    else if($color == "red")
    {
        $targetCss = "../css/red.css";

        unlink($destCss);

        copy($targetCss, $destCss);
    }
    else if($color == "green")
    {
        $targetCss = "../css/green.css";

        unlink($destCss);

        copy($targetCss, $destCss);
    }
    else if($color == "beige")
    {
        $targetCss = "../css/beige.css";

        unlink($destCss);

        copy($targetCss, $destCss);
    }
    else if($color == "gray")
    {
        $targetCss = "../css/gray.css";

        unlink($destCss);

        copy($targetCss, $destCss);
    }

    foreach (glob("../../var/cache/*", GLOB_ONLYDIR) as $dir)
    {
        foreach(glob($dir . "/*") as $file)
        {
            unlink($file);
        }

        rmdir($dir);
    }
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

            <div class="row">
                <div class="col-sm-6">
                    <div class="col-sm-12">
                        <img id="logoImg" src="../../skin/frontend/webshop/default/images/logo_bh.png?<?php echo date("his"); ?>" />
                    </div>
                    <div class="form-group">
                        <label for="LogoFile">Logo</label>
                        <input type="file" id="LogoFile" name="file" accept=".png,.jpg,.jpeg,.gif">
                        <p class="help-block">Das neue Logo ausw&auml;hlen.</p>
                    </div>
                </div>
                <div id="" class="col-sm-6">
                    <div class="col-sm-12">
                        <img id="JumbotronImg" src="../../media/wysiwyg/jumbotron.png?<?php echo date("his"); ?>" />
                    </div>
                    <div class="form-group">
                        <label for="LogoFile">Titelbild</label>
                        <input type="file" id="JumbotronFile" name="file" accept=".png,.jpg,.jpeg,.gif">
                        <p class="help-block">Das neue Jumbotron ausw&auml;hlen.</p>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-6">
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
            <div class="row">
                <div class="col-sm-6">
                </div>
                <div class="col-sm-6" style="margin-top: 10px; padding-left: 0px;">
                    <button type="button" class="btn btn-primary" onclick="updateSetting(this);">Design speichern</button>
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
        data.append('submit', 'submitted');
        jQuery.each(jQuery('#LogoFile')[0].files, function(i, file) {
            data.append('file-'+i, file);
        });
        JQuery.each(jQuery('#JumbotronFile')[0].files, function(i, file) {
            data.append('file-'+i, file);
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

                $("#logoImg").attr( 'src', '../../skin/frontend/webshop/default/images/logo_bh.png?' + (+new Date()) );
                $("#JumbotronImg").attr('src', '../../media/wysiwyg/jumbotron.png?' + (+new Date()));
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