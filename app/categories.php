<?php
/**
 * Created by IntelliJ IDEA.
 * User: Tom
 * Date: 07.04.2016
 * Time: 15:36
 */

session_start();

if(!isset($_SESSION['username'])) {
    return header('Location: index.php');
}
?>

<div id="content">
    <br><br>
    <div id="content_edit" class="col-sm-7">
        <form mehtod="post" id="create" class="form-horizontal categoryForm bv-form" novalidate="novalidate"><button type="submit" class="bv-hidden-submit" style="display: none; width: 0px; height: 0px;"></button>
        <input type="hidden" class="form-control" id="id" name="id" value="-1">
            <div class="form-group has-feedback">
                <label class="col-sm-3 control-label">Bezeichnung</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" id="name" name="name" placeholder="Name" value="" data-bv-field="name"><i class="form-control-feedback" data-bv-icon-for="name" style="display: none;"></i>
                    <small class="help-block" data-bv-validator="notEmpty" data-bv-for="name" data-bv-result="NOT_VALIDATED" style="display: none;">Bitte einen Kategorienamen angeben</small><small class="help-block" data-bv-validator="stringLength" data-bv-for="name" data-bv-result="NOT_VALIDATED" style="display: none;">Kategoriename muss zwischen 2 und 50 Zeichen sein</small>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-3 control-label">&Uuml;berkategorie</label>
                <div class="col-sm-6">
                    <select name="categoryId" id="categoryId" class="form-control">
                        <option value="1">Apfel</option>
                        <option value="2">- Apfel</option>
                        <option value="3">- Apfel</option>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-9 col-sm-offset-3">
                    <button id="category_edit_save" class="btn btn-primary" role="button">Speichern</button>
                    <button id="category_edit_cancel" class="btn btn-primary" role="button" onclick="abort();">Abbrechen</button>
                </div>
            </div>
        </form>
    </div>

    <img src="img/loader.gif">
</div>

</body>
</html>
