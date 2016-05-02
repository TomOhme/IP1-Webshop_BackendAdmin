<?php
/**
 * Created by IntelliJ IDEA.
 * User: Janis
 * Date: 02.05.2016
 * Time: 16:36
 */

session_start();

if(!isset($_SESSION['username'])) {
    return header('Location: index.php');
}

$myColor = "";

$myFile = fopen("color.txt", "r") or die();
$myColor = fgets($myFile);
fclose($myFile);

if(isset($_POST["submit"]))
{
    $img = array_filter($_FILES["uploadImgBtn"]);

    if(!empty($img)) {
        $target_dir = "../../skin/frontend/webshop/default/images/";
        $target_file = $target_dir . basename($_FILES["uploadImgBtn"]["name"]);
        $uploadOk = 1;
        $imageFileType = pathinfo($target_file, PATHINFO_EXTENSION);

        $errorMsg = "";

        $check = getimagesize($_FILES["uploadImgBtn"]["tmp_name"]);

        if ($check == false) {
            $uploadOk = 0;
            $errorMsg .= "Die Datei ist kein Bild!\n";
        }

        if ($_FILES["uploadImgBtn"]["size"] > 500000) {
            $uploadOk = 0;
            $errorMsg .= "Das Bild ist zu gross.\n";
        }

        if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
            $uploadOk = 0;
            $errorMsg .= "Nur JPG, PNG & GIF Dateien sind erlaubt.\n";
        }

        if ($uploadOk == 0) {
            $errorMsg .= "Bild wurde nicht hochgeladen";

        } else {
            foreach (glob("../../skin/frontend/webshop/default/images/logo_bh.png") as $file) {
                unlink($file);
            }

            move_uploaded_file($_FILES["uploadImgBtn"]["tmp_name"], "../../skin/frontend/webshop/default/images/logo_bh.png");
        }
    }

    $color = $_POST["color"];
    $destCss = "../../skin/frontend/webshop/default/css/webshop.css";

    $myFile = fopen("color.txt", "w") or die();
    fwrite($myFile, $color);
    fclose($myFile);

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

    if($uploadOk == 0) {
        ?>

        <script type="text/javascript">
            function () {
                $("#importExcel").modal('toggle');
                $('#Success').empty();
                $('#Success').html("<strong> Erfolgreich! </strong> Einstellungen übernommen!");
                $("#alertSuccess").toggle();
                $("#alertSuccess").fadeTo(10000, 500).slideUp(500, function () {
                    $("#alertSuccess").hide();
                });
            }
        </script>
        <?php
    }
    else
    {
        ?>
        <script type="text/javascript">
            function () {
                $('#Error').empty();
                $("#importExcel").modal('toggle');
                $('#Error').html("<strong> Fehler! </strong><?php $errorMsg ?> ");
                $("#alertError").toggle();
                $("#alertError").fadeTo(10000, 500).slideUp(500, function () {
                    $("#alertError").hide();
                });
            }
        </script>
        <?php
    }

}

?>
<div id="content" style="padding-left:50px; padding-right:50px;">
    <!-- Alerts -->
    <div class="alert alert-success alert-dismissible" role="alert" style="display: none;" id="alertSuccess">
        <span class="glyphicon glyphicon glyphicon-ok-sign" aria-hidden="true"></span><p id="Success" style="display:inline;"></p>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    </div>
    
    <div class="alert alert-danger alert-dismissible" role="alert" style="display: none;" id="alertError">
        <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span><p id="Error" style="display:inline;"></p>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    </div>

    <div class="">
        <form action="" method="post" enctype="application/x-www-form-urlencoded">
            <table>
                <td style="width: 800px;">
                    <div id="" class="col-sm-12">

                        <div id="" class="col-sm-12">
                            <img src="../../skin/frontend/webshop/default/images/logo_bh.png" height="100px" />
                        </div>
                        <label class="col-sm-12 control-label">Logo</label>
                        <div class="col-sm-12">
                            <table>
                                <tr>
                                    <td><input type="text" class="file-upload" id="uploadImgPath" name="logo" placeholder="" value=""></td>
                                    <td>
                                        <button class="file-upload">
                                            <input type="file" id="uploadImgBtn" name="uploadImgBtn" onchange="getImgPath(this, 'uploadImgPath');" class="file-input">Durchsuchen...
                                        </button>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </td>
                <td style="width: 800px;">
                    <div id="" class="col-sm-12">
                        <label class="col-sm-12 control-label">Farbe</label>
                        <div class="col-sm-12">
                            <table>
                                <tr>
                                    <td style="width: 100px;"><input type="radio" name="color" value="red" <?php if($myColor == "red") {?>checked<?php } ?>>Rot</input></td>
                                    <td><input type="radio" name="color" value="blue" <?php if($myColor == "blue") {?>checked<?php } ?>>Blau</input></td>
                                </tr>
                                <tr>
                                    <td style="width: 100px;"><input type="radio" name="color" value="green" <?php if($myColor == "green") {?>checked<?php } ?>>Grün</input></td>
                                    <td><input type="radio" name="color" value="beige" <?php if($myColor == "beige") {?>checked<?php } ?>>Beige</input></td>
                                </tr>
                                <tr>
                                    <td><input type="radio" name="color" value="gray" <?php if($myColor == "gray") {?>checked<?php } ?>>Grau</input></td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-sm-6" style="margin-top: 10px">
                            <input onclick="updateSetting()" type="submit" name="submit" value="Speichern" >
                        </div>
                    </div>
                </td>
            </table>
        </form>
    </div>
</div>

<script type="text/javascript">
    function getImgPath(oFileInput, sTargetID)
    {
        document.getElementById(sTargetID).value = oFileInput.value;
    }
</script>