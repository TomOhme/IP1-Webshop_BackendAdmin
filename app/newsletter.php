<?php
/**
 * Created by IntelliJ IDEA.
 * User: Evenus
 * Date: 09.05.2016
 * Time: 13:10
 */
?>
<link rel="stylesheet" type="text/css" href="../plugins/datetimepicker-master/jquery.datetimepicker.css"/>
<script src="../plugins/datetimepicker-master/jquery.js"></script>
<script src="../plugins/datetimepicker-master/build/jquery.datetimepicker.full.min.js"></script>
<table>
    <td style="width: 500px;">
        <form method="post"  role="form" enctype="multipart/form-data" name="newsletter_queue">
            <h1>Newsletter versenden</h1>
            <div class="form-group" class="col-sm-7">
                <label class="col-sm-12 control-label">Zu versenden um</label>
                <div class="col-sm-12">
                    <input id="datetimepicker" type="text">
                </div>
                <label class="col-sm-12 control-label">Betreff</label>
                <div class="col-sm-12">
                    <input type="text" class="form-control" name="title" id="title">
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

    function sendNewsletter() {
        alert("test");
    };
</script>