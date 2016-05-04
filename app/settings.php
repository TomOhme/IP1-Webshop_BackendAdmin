<div id="dummy">
<?php
/**
 * Created by IntelliJ IDEA
 * User: Tom Ohme
 * Date: 07.04.2016
 * Time: 15:37
 */
session_start();

$ini_array = parse_ini_file("../php.ini");
$user = $ini_array["DBUSER"];
$pwd = $ini_array["DBPWD"];

$mysqli = new mysqli("localhost", $user, $pwd, "magento");
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}
$select = "SELECT `content` FROM `cms_page` WHERE `identifier`= 'ueber-uns'; ";
$result = $mysqli->query($select);
$row = mysqli_fetch_assoc($result);
$mysqli->close();
//var_dump($row["content"]);
//$title = $row['Title'];
//$img = $row['Description'];
//$aboutUs = 1;
//$opening = 1;
//$lat = $row['lat'];
//$lng = $row['lng'];

?>
</div>

<script src="../plugins/tinymce/tinymce.min.js"></script>
<script>
    tinymce.init({
        // Bereiche für TinyMCE
        selector: 'textarea',
        menubar: false,
        statusbar: false,
        // Sprache
        language: 'de'
    });
</script>

<div id="content" style="padding-left:50px; padding-right:50px;">
    <div class="row">
            <table>
                <td style="width: 1000px;">
                    <form method="post"  role="form" enctype="multipart/form-data" name="contact">
                    <h1>Kontaktseite</h1>
                    <div class="form-group" class="col-sm-7">
                        <label class="col-sm-12 control-label">Titel der Kontaktseite</label>
                        <div class="col-sm-12">
                            <input type="text" class="form-control" name="title" id="title" placeholder="Webshop xy" value="Webshop xy">
                        </div>
                        <label class="col-sm-12 control-label">Titelbild</label>
                        <div class="col-sm-12">
                            <input type="file" name="fileToUpload" id="fileToUpload">
                        </div>
                        <label class="col-sm-12 control-label">Über Uns</label>
                        <div class="col-sm-12">
                            <textarea rows="5" class="form-control editme" name="aboutUs" id="aboutUs" placeholder="Über Uns">
                            </textarea>
                        </div>
                        <label class="col-sm-12 control-label">Öffnungszeiten</label>
                        <div class="col-sm-12">
                            <textarea rows="3" class="form-control editme" name="opening" id="opening" placeholder="Öffnungszeiten">
                            </textarea>
                        </div>
                        <label class="col-sm-12 control-label">Standort</label>
                        <!--<div id="us2" style="width: 500px; height: 400px; margin-left: 15px;">--><img src="http://maps.googleapis.com/maps/api/staticmap?center=46.9479739,7.447446799999966&amp;zoom=15&amp;size=400x400&amp;markers=color:blue|46.9479739,7.447446799999966&amp;sensor=false" height="400" width="400" style="margin-left: 15px;"/><!--</div>--><br>
                        <!--<script>
                            $('#us2').locationpicker({
                                location: {latitude: 46.9479739, longitude: 7.447446799999966},
                                zoom: 10,
                                inputBinding: {
                                    latitudeInput: $('#us2-lat'),
                                    longitudeInput: $('#us2-lon')
                                }
                            });
                        </script><br>-->
                        <input type="hidden" id="us2-lat" value="46.9479739"/>
                        <input type="hidden" id="us2-lon" value="7.447446799999966"/>
                        <br><button type="button"  onclick="updateContact();"style="margin-left: 15px;" class="btn btn-primary">
                    </div>
                    </form>
                </td>
            </table>
    </div>
<script type="text/javascript">

    function updateContact() {

        tinyMCE.triggerSave()

        var title = document.getElementById("title").value;
        var fileToUpload = document.getElementById("fileToUpload").value;
        var aboutUs = document.getElementById("aboutUs").value;
        var opening = document.getElementById("opening").value;
        var lat = document.getElementById("us2-lat").value;
        var lon = document.getElementById("us2-lon").value;

        if (title == '' || fileToUpload == '' || aboutUs == '' || opening == '' || lat == '' || lon == '') {
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
</div>

<script src="../js/jquery-2.2.2.min.js"></script>
<script type="text/javascript" src='https://maps.google.com/maps/api/js?&libraries=places'></script>
<script src="../plugins/jquery-locationpicker/src/locationpicker.jquery.js"></script>