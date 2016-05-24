<?php
/**
 * Created by IntelliJ IDEA.
 * User: Patrick Althaus
 * Date: 09.05.2016
 * Time: 13:10
 */
session_start();
require_once("../api/dbconnect.php");
?>

<!-- include summernote css/js-->
<link href="../plugins/dist/summernote.css" rel="stylesheet">
<script src="../plugins/dist/summernote.js"></script>

<!-- alert -->
<div class="alert alert-success alert-dismissible" role="alert" style="display: none;" id="alertSendNewsletterSuccess">
    <span class="glyphicon glyphicon glyphicon-ok-sign" aria-hidden="true"></span><p id="sendNewsletterSuccess" style="display:inline;"></p>
    Newsletter erfolgreich gesendet
    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
</div>
<div class="alert alert-danger alert-dismissible" role="alert" style="display: none;" id="alertSendNewsletterError">
    <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span><p id="sendNewsletterError" style="display:inline;"></p>
    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
</div>

<div id="content">
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
                        <p class="help-block">In diesem Feld k&ouml;nnen Sie die Zeit und das Datum wählen, zu welchem der Newsletter verschickt werden soll.</p>
                    </div>
                    <label class="col-sm-12 control-label">Betreff</label>
                    <div class="col-sm-12">
                        <input style="margin-bottom: 15px;" type="text" class="form-control" name="title" id="title">
                        <p class="help-block">In diesem Feld k&ouml;nnen Sie den Betreff der Newsletter-Mails ver&auml;ndern.</p>
                    </div>
                    <label class="col-sm-12 control-label">Inhalt</label>
                    <div class="col-sm-12">
                        <textarea rows="10" id="inhalt"></textarea>
                        <p class="help-block">In diesem Feld k&ouml;nnen Sie den Inhalt ihres Newsletters gestalten.</p>
                    </div>
                    <div class="col-sm-12">
                        <label class="control-label">Sonderangebote integrieren?</label>
                        <input type="checkbox" name="specialproduct" id="specialproduct">
                    </div>
                    <br><button type="button" onclick="sendNewsletter();" style="margin-left: 15px;" class="btn btn-primary">Speichern</button>
                </div>
            </form>
        </td>
        <td style="width: 1000px;">
        </td>
    </table>
</div>

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
    var date = new Date();
    date.setDate(date.getDate());

    $('#dtpicker').datetimepicker({
        format: "DD.MM.YYYY - HH.mm.ss",
        locale: 'de',
        minDate: date
    });

    function sendNewsletter() {

        var datetime = document.getElementById("dtpicker").value;
        var title = document.getElementById("title").value;
        var content = document.getElementById("inhalt").value;

        var data = new FormData();

        if($('#specialproduct:checked').val()=='on'){
            var specialpr = true;
        }
        else{
            var specialpr = false;
        }

        data.append("datetime", datetime);
        data.append("title", title);
        data.append("content", content);
        data.append("specialpr", specialpr);

        if (datetime == '' || title == '' || (content == '' && specialpr == false)) {
            $("#alertSendNewsletterError").fadeTo(10000, 500).slideUp(500, function(){
                $("#alertSendNewsletterError").hide();
            });
        } else {
            $.ajax({
                url: 'sendNewsletter.php',
                type: 'POST',
                cache: false,
                contentType: false,
                processData: false,
                data: data,
                success: function(data) {
                    $("#alertSendNewsletterSuccess").fadeTo(10000, 500).slideUp(500, function(){
                        $("#alertSendNewsletterSuccess").hide();
                    });
                }
            });
        }
        return false;


    };
</script>