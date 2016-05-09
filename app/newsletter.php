<?php
/**
 * Created by IntelliJ IDEA.
 * User: Evenus
 * Date: 09.05.2016
 * Time: 13:10
 */
session_start();
include("../api/dbconnect.php");
?>

<!-- include summernote css/js-->
<link href="../plugins/dist/summernote.css" rel="stylesheet">
<script src="../plugins/dist/summernote.js"></script>

<link rel="stylesheet" type="text/css" href="../plugins/datetimepicker-master/jquery.datetimepicker.css"/>
<script src="../plugins/datetimepicker-master/build/jquery.datetimepicker.full.min.js"></script>
<table>
    <td style="width: 600px;">
        <form method="post"  role="form" enctype="multipart/form-data" name="newsletter_queue">
            <h1>Newsletter versenden</h1>
            <div class="form-group" class="col-sm-7">
                <label class="col-sm-12 control-label">Zu versenden um</label>
                <div class="col-sm-12">
                    <input style="margin-bottom: 15px;" id="datetimepicker" type="text">
                </div>
                <label class="col-sm-12 control-label">Betreff</label>
                <div class="col-sm-12">
                    <input style="margin-bottom: 15px;" type="text" class="form-control" name="title" id="title">
                </div>
                <label class="col-sm-12 control-label">Inhalt</label>
                <div class="col-sm-12">
                    <textarea rows="10" id="inhalt">Hello Summernote</textarea>
                </div>
                <br><button type="button" onclick="sendNewsletter();" style="margin-left: 15px;" class="btn btn-primary">Speichern</button>
            </div>
        </form>
    </td>
    <td style="width: 1000px;">
    </td>
</table>

<script type="text/javascript">
    jQuery('#datetimepicker').datetimepicker({
        format:'d.m.Y H:i',
        inline:true,
        lang:'de'
    });

    $(document).ready(function() {
        $('#inhalt').summernote({
            toolbar: [
                // [groupName, [list of button]]
                ['style', ['bold', 'italic', 'underline', 'clear']],
                ['font', ['strikethrough', 'superscript', 'subscript']],
                ['fontsize', ['fontsize']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['height', ['height']]
            ]
        });
    });

    function sendNewsletter() {

        var title = document.getElementById("title").value;
        var fileToUpload = document.getElementById("fileToUpload").value;
        var aboutUs = document.getElementById("aboutUs").value;
        var opening = document.getElementById("opening").value;
        var lat = document.getElementById("us2-lat").value;
        var lon = document.getElementById("us2-lon").value;

        if (title == '' || aboutUs == '' || opening == '' || lat == '' || lon == '') {
            alert("Please Fill All Fields");
        } else {
            // AJAX code to submit form.
            $.ajax({
                url: "updateContact.php",
                type: "POST",
                data: {title: title, fileToUpload: fileToUpload, aboutUs: aboutUs, opening: opening, lat: lat, lon: lon},
                success: function() {
                    alert("Erfolgreich geändert!");
                }
            });
        }
        return false;


    };
</script>