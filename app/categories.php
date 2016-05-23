<?php
/**
 * Created by IntelliJ IDEA.
 * User: Tom Ohme
 * Date: 07.04.2016
 * Time: 15:36
 */

require_once("../api/ProductGroup.php");

session_start();

if(!isset($_SESSION['username'])) {
    return header('Location: index.php');
}

$soapProductGroup = new ProductGroup();
$soapProductGroup -> openSoap();

$values = array();
if(isset($_POST['productData']) && isset($_POST['categoryUpdateSave'])){
    parse_str($_POST['productData'], $values);
    //var_dump($values);
    $soapProductGroup->createCategory(trim($values['categoryName']), $values['categoryId']);
}

if(isset($_POST['productData']) && isset($_POST['categoryDelete'])){
    parse_str($_POST['productData'], $values);
    $soapProductGroup->deleteCategory($values['categoryId']);
}

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
                            <input type="text" class="form-control" required="true" maxlength="50" id="categoryName" name="categoryName" placeholder="Name" value="" data-bv-field="name">
                        </div>
                    </div>
                    <div class="form-group">
                        <label id="categoryNameLabel" class="col-sm-3 control-label">Kategorie</label>
                        <div class="col-sm-6">
                            <?php
                                $categories = $soapProductGroup->getTree();
                            ?>
                            <select name="categoryId" id="categoryId" class="form-control">
                                <option value="2">-</option> <!-- value 2 for default category -->
                                <?php
                                    getNextCategoryDropdown($categories);
                                ?>
                                <?php
                                    function getNextCategoryDropdown($category) {
                                        if ($category['children'] != null) {
                                            foreach ($category['children'] as $subCategory) {
                                                $tabs = '';
                                                if($subCategory['level'] > 2){
                                                    for($i = 0; $i < ($subCategory['level']-2)*4; $i++){
                                                        $tabs .= '&nbsp;';
                                                    }

                                                }
                                                ?>
                                                <option value="<?php echo $subCategory['category_id']; ?>"> <?php echo $tabs . $subCategory['name']; ?> </option>
                                                <?php
                                                if ($subCategory['children'] != null) {
                                                    getNextCategoryDropdown($subCategory);
                                                } else {
                                                }
                                            }
                                        }
                                    }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-9 col-sm-offset-3">
                            <button type="button" id="category_edit_save" class="btn btn-primary" onclick="categoryUpdateSave();">Speichern</button>
                            <button type="button" id="category_delete" class="btn" onclick="categoryDelete();">L&ouml;schen</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div id="right">
            <div class="tree well">
                <ul>
                    <?php
                        $categories = $soapProductGroup->getTree();
                        getMainCategory($categories);
                    ?>
                    <?php
                        function getMainCategory($categories) {
                            foreach ($categories['children'] as $category) { ?>
                                <li>
                                    <span id="<?php echo $category['category_id']; ?>"><i class="icon-folder-open"></i><?php echo $category['name']; ?></span>
                                    <?php getSubCategory($category); ?>
                                </li>
                                <?php
                            }
                        }
                    ?>

                    <?php
                        function getSubCategory($category) {
                            if ($category['children'] != null) {
                                $numItems = count($category['children']);
                                $i = 0;
                                foreach ($category['children'] as $subCategory) {
                                    if (++$i !== $numItems) { ?>
                                        <ul>
                                            <li><span id="<?php echo $subCategory['category_id']; ?>"><i class="icon-minus-sign"></i> <?php echo $subCategory['name']; ?></span>
                                                <?php if ($subCategory['children'] != null) {
                                                    getSubCategory($subCategory);
                                                }
                                                ?>
                                            </li>
                                        </ul>
                                    <?php } else { ?>
                                        <ul>
                                            <li><span id="<?php echo $subCategory['category_id']; ?>"><i class="icon-leaf"></i> <?php echo $subCategory['name']; ?></span>
                                                <?php if ($subCategory['children'] != null) {
                                                    getSubCategory($subCategory);
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
            <!--<li> example https://jsfiddle.net/jhfrench/GpdgF/
 -                        <span><i class="icon-folder-open"></i> Parent</span>
 -                        <ul>
 -                            <li>
 -                                <span><i class="icon-minus-sign"></i> Child</span>
 -                                <ul>
 -                                    <li>
 -                                        <span><i class="icon-leaf"></i> Grand Child</span>
 -                                    </li>
 -                                </ul>
 -                            </li>
 -                            <li>
 -                                <span><i class="icon-minus-sign"></i> Child</span>
 -                                <ul>
 -                                    <li>
 -                                        <span><i class="icon-leaf"></i> Grand Child</span>
 -                                    </li>
 -                                    <li>
 -                                        <span><i class="icon-minus-sign"></i> Grand Child</span>
 -                                        <ul>
 -                                            <li>
 -                                                <span><i class="icon-minus-sign"></i> Great Grand Child</span>
 -                                                <ul>
 -                                                    <li>
 -                                                        <span><i class="icon-leaf"></i> Great great Grand Child</span>
 -                                                    </li>
 -                                                    <li>
 -                                                        <span><i class="icon-leaf"></i> Great great Grand Child</span>
 -                                                    </li>
 -                                                </ul>
 -                                            </li>
 -                                            <li>
 -                                                <span><i class="icon-leaf"></i> Great Grand Child</span>
 -                                            </li>
 -                                            <li>
 -                                                <span><i class="icon-leaf"></i> Great Grand Child</span>
 -                                            </li>
 -                                        </ul>
 -                                    </li>
 -                                    <li>
 -                                        <span><i class="icon-leaf"></i> Grand Child</span>
 -                                    </li>
 -                                </ul>
 -                            </li>
 -                        </ul>
 -                    </li>
 -                    <li>
 -                        <span><i class="icon-folder-open"></i> Parent2</span>
 -                        <ul>
 -                            <li>
 -                                <span><i class="icon-leaf"></i> Child</span>
 -                            </li>
 -                        </ul>
 -                    </li>
 -                </ul>
 -            </div>-->
        </div>

    </div>



<script type="text/javascript">

    $(document).ready(function() {
        $("#category_delete").addClass("disabled");
    });

    $("#categoryId").change(function(){
        if ($("#categoryId").val() != "" && $("#categoryId").val() != 2) {
            $("#category_delete").removeClass("disabled").addClass("btn-danger");
            $(".tree").find("span").css("background-color", "");
            $(".tree span").filter(function() {
                return ($(this).attr("id") === $("#categoryId").val())
            }).css('background-color', 'yellow');

            $("#categoryNameLabel").empty();
            $("#categoryNameLabel").append("&Uuml;berkategorie");
        } else {
            $("#category_delete").removeClass("btn-danger").addClass("disabled");
            $(".tree").find("span").css("background-color", "");

            $("#categoryNameLabel").empty();
            $("#categoryNameLabel").append("Kategorie");
        }
    });

    function categoryUpdateSave() {
        var fData = $("#createCategoryForm").serialize();
        categoryName = $("#categoryName").val();
        if(categoryName == ''){
            $("#categoryName").notify("Bitte geben Sie einen Kategorienamen an.", {
                    position:"right",
                    className: "error"}
                );
        } else if(categoryName.length > 50){
            $("#categoryName").notify("Der Kategorienamen darf nicht länger als 50 Zeichen lang sein.", {
                position: "right",
                className: "error"
            });
        } else {
            $.ajax({
                url : 'categories.php',
                type: 'POST',
                data: { productData : fData,
                        categoryUpdateSave : 'categoryUpdateSave'
                },
                success: function (data) {
                    changeSiteUpdate('categories'); //TODO better return echo products and fill content with data
                    //TODO alert success
                },
            });
        }
    }

    function categoryDelete() {
        var fData = $("#createCategoryForm").serialize();
        $.ajax({
            url : 'categories.php',
            type: 'POST',
            data: { productData : fData,
                    categoryDelete : 'categoryDelete'
            },
            success: function (data) {
                changeSiteUpdate('categories'); //TODO better return echo products and fill content with data
                //TODO alert success
            },
        });
    }

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
