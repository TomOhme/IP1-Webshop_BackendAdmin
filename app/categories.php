<?php
/**
 * Created by IntelliJ IDEA.
 * User: Tom
 * Date: 07.04.2016
 * Time: 15:36
 */
include("header.php");
include("menu.php");
?>
<div id="content" style="padding-left:50px; padding-right:50px;">
    <img src="img/loader.gif">
</div>
<script src="../js/jquery-2.2.2.min.js"></script>
<script src="../js/jquery.dataTables.js"></script>
<script src="../js/dataTables.bootstrap.js"></script>
<script src="../js/bootstrap.min.js"></script>
<script src="../js/notify.min.js"></script>
<script src="../js/dropzone.js"></script>
<script src="../js/bootstrapValidator.min.js"></script>
<script src="../js/form_validation.js"></script>
<script src="../js/bootstrap-colorpicker.min.js"></script>
<script src="../js/ckeditor/ckeditor.js"></script>
<script type="text/javascript">

    function changeSite(site)
    {
        $.ajax({
            url : 'pages.php?site=' + site,
            type: 'GET',
            success: function(data){
                $('#content').html(data);
            }
        });
    };

    function loadItem(site, placeholder, id)
    {
        $.ajax({
            url : 'pages.php?site=' + site + '&placeholder=' + placeholder + '&id=' + id,
            type: 'GET',
            success: function(data){
                $('#' + placeholder).html(data);

                if(site == 'update_article'){
                    update_article_validation();
                }else if(site == 'categories' & placeholder == 'content_edit'){
                    update_category_validation();
                }else if(site == 'users' & placeholder == 'content_edit'){
                    update_user_validation();
                    if(id == '-1'){
                        $('form').bootstrapValidator('enableFieldValidators', 'password', true);
                    }else{
                        $('form').bootstrapValidator('enableFieldValidators', 'password', false);
                    }
                }else if(site == 'import_article_csv'){
                    import_articles_validation();
                }
            }
        });
    };


    function logout(){

        $.ajax({
            url : 'rest/logout/',
            type: 'POST',
            success: function(data){
                location.reload();
            },
            error: function(){
                $.notify("Server error", "error");
            }
        });

    };

    $.fn.serializeObject = function()
    {
        var o = {};
        var a = this.serializeArray();
        $.each(a, function() {
            if (o[this.name] !== undefined) {
                if (!o[this.name].push) {
                    o[this.name] = [o[this.name]];
                }
                o[this.name].push(this.value || '');
            } else {
                o[this.name] = this.value || '';
            }
        });
        return o;
    };



    function isImage(name){
        var suffix = [".jpg", ".png", ".gif", ".jpeg"];
        for (i = 0; i < suffix.length; i++) {
            if(name.indexOf(suffix[i].toLowerCase(), name.length - suffix[i].length) !== -1){
                return true;
            }
        }
        return false;
    };

    changeSite('articles');


</script>
</body>
