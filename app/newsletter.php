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
<table>
    <td style="width: 600px;">
        <form method="post"  role="form" enctype="multipart/form-data" name="newsletter_queue">
            <h1>Newsletter versenden</h1>
            <div class="form-group" class="col-sm-7">
                <label class="col-sm-12 control-label">Zu versenden um</label>
                <div class="col-sm-12">
                    <div class='input-group date' id='datetimepickerFrom'>
                        <div>
                            <input class="form-control" id="dtpicker" type="text">
                        </div>
                        <span class="input-group-addon">
                            <span class="glyphicon glyphicon-calendar"></span>
                        </span>
                    </div>
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

    $('#dtpicker').datetimepicker({
        format: "DD.MM.YYYY - HH.mm.ss",
        locale: 'de'
    });

    function sendNewsletter() {

        var datetime = document.getElementById("dtpicker").value;
        var title = document.getElementById("title").value;
        var content = document.getElementById("inhalt").value;
        alert(datetime);

        if (datetime == '' || title == '' || content == '') {
            alert("Please Fill All Fields");
        } else {
            // AJAX code to submit form.
            $.ajax({
                url: "sendNewsletter.php",
                type: "POST",
                data: {datetime: datetime, title: title, content: content},
                success: function() {
                    alert("Erfolgreich geändert!");
                }
            });
        }
        return false;


    };
</script>