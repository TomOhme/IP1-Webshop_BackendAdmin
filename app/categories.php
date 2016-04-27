<?php
/**
 * Created by IntelliJ IDEA.
 * User: Tom Ohme
 * Date: 07.04.2016
 * Time: 15:36
 */

include("../api/ProductGroup.php");

session_start();

if(!isset($_SESSION['username'])) {
    return header('Location: index.php');
}

$soapProductGroup = new ProductGroup();
$soapProductGroup -> openSoap();
?>

    <link rel="stylesheet" href="../css/treeTable.css">
    <div id="content">
        <div id="left">
            </br><br>
            <div id="content_edit" class="col-sm-7">
                <form method="post" id="createCategoryForm" class="form-horizontal" novalidate="novalidate">
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
                            <?php $categories = $soapProductGroup->getTree(); ?>
                            <select name="category" id="category" class="form-control">
                                <option value=""></option>
                                <?php
                                    foreach($categories['children'] as $category) { ?>
                                        <option value="<?php echo $category['category_id']; ?>"> <?php echo $category['name']; ?> </option>
                                        <?php getNextSubCategoryDropdown($category); ?>
                                <?php } ?>
                                <?php
                                    function getNextSubCategoryDropdown($category) {
                                        if ($category['children'] != null) {
                                        foreach ($category['children'] as $subCategory) { ?>
                                            <option value="<?php echo $subCategory['category_id']; ?>"> <?php echo "- ". $subCategory['name']; ?> </option> <!-- TODO indent sub categories -->
                                            <?php if ($subCategory['children'] != null) {
                                                getNextSubCategoryDropdown($subCategory);
                                                ?>
                                            <?php }
                                            }
                                         }
                                     } ?>
                                <?php /*
                                    foreach($categories['children'] as $category) {
                                        echo '<option value="' . $category['name'] . '">' . $category['name'] . "</option>";
                                        foreach($category['children'] as $subCategory) {
                                            echo '<option value="' . $subCategory['name'] . '">- ' . $subCategory['name'] . "</option>";
                                        }
                                    }*/
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-9 col-sm-offset-3">
                            <button id="category_edit_save" class="btn btn-primary" role="button" onclick="categoryUpdateSave();">Speichern</button>
                            <button id="category_delete" class="btn" role="button" onclick="categoryDelete();">L&ouml;schen</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div id="right">
            <div class="tree well">
                <ul>
                    <?php $categories = $soapProductGroup->getTree();
                          foreach($categories['children'] as $category) { ?>
                                <li><span id="<?php echo $category['category_id'];?>"><i class="icon-folder-open"></i><?php echo $category['name']; ?></span>
                                <?php getNextSubCategory($category); ?>
                                </li>
                    <?php } ?>
                    <?php
                        function getNextSubCategory($category) {
                            if ($category['children'] != null) {
                                $numItems = count($category['children']);
                                $i = 0;
                                foreach ($category['children'] as $subCategory) {
                                    if (++$i === $numItems) { ?>
                                        <ul>
                                            <li><span id="<?php echo $subCategory['category_id']; ?>"><i
                                                        class="icon-leaf"></i> <?php echo $subCategory['name']; ?></span>
                                                <?php if ($subCategory['children'] != null) {
                                                    getNextSubCategory($subCategory);
                                                }
                                                ?>
                                            </li>
                                        </ul>
                                    <?php } else { ?>
                                        <ul>
                                            <li><span id="<?php echo $subCategory['category_id']; ?>"><i
                                                        class="icon-minus-sign"></i> <?php echo $subCategory['name']; ?></span>
                                                <?php if ($subCategory['children'] != null) {
                                                    getNextSubCategory($subCategory);
                                                }
                                                ?>
                                            </li>
                                        </ul>
                                    <?php }
                                }
                            }
                        }
                    ?>
                </ul>
            </div>
                <!--<li> example
                        <span><i class="icon-folder-open"></i> Parent</span>
                        <ul>
                            <li>
                                <span><i class="icon-minus-sign"></i> Child</span>
                                <ul>
                                    <li>
                                        <span><i class="icon-leaf"></i> Grand Child</span>
                                    </li>
                                </ul>
                            </li>
                            <li>
                                <span><i class="icon-minus-sign"></i> Child</span>
                                <ul>
                                    <li>
                                        <span><i class="icon-leaf"></i> Grand Child</span>
                                    </li>
                                    <li>
                                        <span><i class="icon-minus-sign"></i> Grand Child</span>
                                        <ul>
                                            <li>
                                                <span><i class="icon-minus-sign"></i> Great Grand Child</span>
                                                <ul>
                                                    <li>
                                                        <span><i class="icon-leaf"></i> Great great Grand Child</span>
                                                    </li>
                                                    <li>
                                                        <span><i class="icon-leaf"></i> Great great Grand Child</span>
                                                    </li>
                                                </ul>
                                            </li>
                                            <li>
                                                <span><i class="icon-leaf"></i> Great Grand Child</span>
                                            </li>
                                            <li>
                                                <span><i class="icon-leaf"></i> Great Grand Child</span>
                                            </li>
                                        </ul>
                                    </li>
                                    <li>
                                        <span><i class="icon-leaf"></i> Grand Child</span>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <span><i class="icon-folder-open"></i> Parent2</span>
                        <ul>
                            <li>
                                <span><i class="icon-leaf"></i> Child</span>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>-->
        </div>

    </div>



<script type="text/javascript">

    $(document).ready(function() {
        $("#category_delete").addClass("disabled");
    });

    $("#category").change(function(){
        if ($("#category").val() != "") {
            $("#category_delete").removeClass("disabled").addClass("btn-danger");
            $(".tree").find("span").css("background-color", "");
            $(".tree span").filter(function() {
                return ($(this).attr("id") === $("#category").val())
            }).css('background-color', 'yellow');
        } else {
            $("#category_delete").removeClass("btn-danger").addClass("disabled");
            $(".tree").find("span").css("background-color", "");

        }
    });

    /*function categoryUpdateSave() {
        var fData = $("#createCategoryForm").serialize();
        $.ajax({
            url : 'categories.php',
            type: 'POST',
            data: { productId : fData['name'],
                    categoryUpdateSave : 'categoryUpdateSave'
            },
            success: function (data) {
                //TODO reload product table and alert
            },
        });
    }

    function categoryDelete() {
        var fData = $("#createCategoryForm").serialize();
        $.ajax({
            url : 'categories.php',
            type: 'POST',
            data: { productId : fData['name'],
                    categoryDelete : 'categoryDelete'
            },
            success: function (data) {
                //TODO reload product table and alert
            },
        });
    }*/

    $(function () {
        $('.tree li:has(ul)').addClass('parent_li').find(' > span').attr('title', 'Collapse this branch');
        $('.tree li.parent_li > span').on('click', function (e) {
            var children = $(this).parent('li.parent_li').find(' > ul > li');
            if (children.is(":visible")) {
                children.hide('fast');
                $(this).attr('title', 'Expand this branch').find(' > i').addClass('icon-plus-sign').removeClass('icon-minus-sign');
            } else {
                children.show('fast');
                $(this).attr('title', 'Collapse this branch').find(' > i').addClass('icon-minus-sign').removeClass('icon-plus-sign');
            }
            e.stopPropagation();
        });
    });
</script>

</body>
</html>
