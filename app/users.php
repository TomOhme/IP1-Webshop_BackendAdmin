<?php
/**
 * Created by IntelliJ IDEA
 * User: Patrick
 * Date: 22.04.2016
 * Time: 20:44
 */
require_once("../api/users.php");
include("../config.php");

session_start();

if(!isset($_SESSION['username'])) {
 return header('Location: index.php');
}

$user = DBUSER;
$pwd = DBPWD;
$mysqli = new mysqli("localhost", $user, $pwd, "magento");
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

function formatDate($date){
    return  date_format(date_create($date), "d.m.Y");
}

$soap = new User();
$soap->openSoap();
?>

<div id="content" style="padding-left:50px; padding-right:50px;">
    <!-- Alerts -->
    <div class="alert alert-success alert-dismissible" role="alert" style="display: none;" id="alertSuccessfulSafe">
        <span class="glyphicon glyphicon glyphicon-ok-sign" aria-hidden="true"></span><p style="display:inline;"> Der Benutzer wurde erfolgreich gel&ouml;scht!</p>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    </div>
    <!-- Fertig mit Allerts -->
    <br><br>
        <div id="content_table">
            <div class="table-responsive rwd-article">
                <div id="data-table_wrapper" class="dataTables_wrapper form-inline dt-bootstrap no-footer" style="width:100%;">
                    <div class="row">
                        <div class="col-sm-6"></div>
                        <div class="col-sm-6">
                            <div id="data-table_filter" class="dataTables_filter">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <table class="table table-hover table-striped table-bordered dataTable no-footer" id="data-table" style="width: 100%;" role="grid" aria-describedby="data-table_info">
                                <thead class="tablebold">
                                <tr role="row">
                                    <td class="sorting_asc" aria-controls="data-table" rowspan="1" colspan="1" aria-sort="ascending" aria-label="ID: aktivieren, um Spalte absteigend zu sortieren" style="width: 150px;">Vorname</td>
                                    <td class="sorting" aria-controls="data-table" rowspan="1" colspan="1" aria-label="Titel: aktivieren, um Spalte aufsteigend zu sortieren" style="width: 200px;">Nachname</td>
                                    <td class="sorting" aria-controls="data-table" rowspan="1" colspan="1" aria-label="Titel: aktivieren, um Spalte aufsteigend zu sortieren" style="width: 450px;">Adresse</td>
                                    <td class="sorting" aria-controls="data-table" rowspan="1" colspan="1" aria-label="Titel: aktivieren, um Spalte aufsteigend zu sortieren" style="width: 100px;">PLZ</td>
                                    <td class="sorting_disabled" aria-controls="data-table" rowspan="1" colspan="1" aria-label="Ort" style="width: 150px;">Ort</td>
                                    <td class="sorting_disabled" aria-controls="data-table" rowspan="1" colspan="1" aria-label="Telefonnr" style="width: 150px;">Telefonnr.</td>
                                    <td class="sorting_disabled" aria-controls="data-table" rowspan="1" colspan="1" aria-label="E-Mail" style="width: 242px;">E-Mail</td>
                                    <td class="sorting_disabled" aria-controls="data-table" rowspan="1" colspan="1" aria-label="Bday" style="width: 140px;">Geburtsdatum</td>
                                    <td class="sorting_disabled" aria-controls="data-table" rowspan="1" colspan="1" aria-label="Newsletter" style="width: 140px;">Newsletter</td>
                                    <td class="sorting_disabled" aria-controls="data-table" rowspan="1" colspan="1" aria-label="Löschen" style="width: 140px;">L&ouml;schen</td>
                                </tr>
                                </thead>

                                <tbody>

                                <?php
                                $users = $soap->getAllUsers();
                                $count = count($users);
                                $i = 0;

                                foreach ($users as $user)
                                {
                                    $userid = $user['customer_id'];

                                    if($i % 2 == 0)
                                    {
                                        ?><tr role="row" class="even" id="<?php echo $userid; ?>"><?php
                                    }
                                    else
                                    {
                                        ?><tr role="row" class="odd" id="<?php echo $userid; ?>"><?php
                                    }


                                    $newsletter = "SELECT `subscriber_id` FROM `newsletter_subscriber` WHERE `subscriber_id` = (".$user['customer_id'].")";
                                    $sid = $mysqli->query($newsletter);
                                    $sidr = mysqli_fetch_assoc($sid);
                                    ?>

                                    <td class="sorting_1"><?php if(!is_null($user['firstname'])){ echo $user['firstname']; } ?></td>
                                    <td class="sorting_1"><?php if(!is_null($user['lastname'])){ echo $user['lastname']; } ?></td>
                                    <td class="sorting_1"><?php if(!is_null($user['0'])){ echo $user['0']; } ?></td>
                                    <td class="sorting_1"><?php if(!is_null($user['1'])){ echo $user['1']; } ?></td>
                                    <td class="sorting_1"><?php if(!is_null($user['2'])){ echo $user['2']; } ?></td>
                                    <td class="sorting_1"><?php if(!is_null($user['3'])){ echo $user['3']; } ?></td>
                                    <td class="sorting_1"><?php if(!is_null($user['email'])){ echo $user['email']; } ?></td> <!-- email -->
                                    <td class="sorting_1"><?php if(!is_null($user['4'])){ echo formatDate($user['4']); } ?></td> <!-- date of birth -->
                                    <td class="sorting_1"><?php if(!isset($user['5']) || $user['5']=='3'){$user['5'] = NULL;}
                                        if (isset($sidr['subscriber_id'])) { echo "Ja"; } else { echo "Nein"; }?></td> <!-- newsletter -->
                                    <td><span class="glyphicon glyphicon-remove" aria-hidden="true" onclick="delete_user(<?php echo $userid; ?>)"></span></td>
                                    </tr><?php
                                }

                                $mysqli->close();
                                ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-5">
                        </div><div class="col-sm-7">
                        <div class="dataTables_paginate paging_simple_numbers" id="data-table_paginate">
                            <ul class="pagination">
                                <li class="paginate_button previous disabled" id="data-table_previous"></li>
                                <li class="paginate_button active"></li>
                                <li class="paginate_button next disabled" id="data-table_next"></li>
                            </ul>
                        </div>
                    </div>
                    </div>
                </div>
            </div>
        <script type="text/javascript">

            function delete_user(userId) {
                $.ajax({
                    url: 'deleteUser.php',
                    type: 'POST',
                    data: { userId : userId },
                    success: function(result) {
                        $('#'+userId).remove();
                        $("#alertSuccessfulSafe").fadeTo(10000, 500).slideUp(500, function(){
                            $("#alertSuccessfulSafe").hide();
                        });
                    }
                });

            }

            $(document).ready(function() {

                $('#data-table').DataTable({
                    "language": {
                        "sEmptyTable":      "Keine Daten in der Tabelle vorhanden",
                        "sInfo":            "_START_ bis _END_ von _TOTAL_ Einträgen",
                        "sInfoEmpty":       "0 bis 0 von 0 Einträgen",
                        "sInfoFiltered":    "(gefiltert von _MAX_ Einträgen)",
                        "sInfoPostFix":     "",
                        "sInfoThousands":   ".",
                        "sLengthMenu":      "_MENU_ Einträge anzeigen",
                        "sLoadingRecords":  "Wird geladen...",
                        "sProcessing":      "Bitte warten...",
                        "sSearch":          "Suchen",
                        "sZeroRecords":     "Keine Einträge vorhanden.",
                        "oLanguage": {
                            "sProcessing": "loading data..."
                        },
                        "oPaginate": {
                            "sFirst":       "Erste",
                            "sPrevious":    "Zurück",
                            "sNext":        "Nächste",
                            "sLast":        "Letzte"
                        },
                        "oAria": {
                            "sSortAscending":  ": aktivieren, um Spalte aufsteigend zu sortieren",
                            "sSortDescending": ": aktivieren, um Spalte absteigend zu sortieren"
                        }
                    },
                    "bLengthChange": false,
                    "pageLength": 10,
                    "aoColumnDefs": [
                        { "bSortable": false, "aTargets": [ 3, 4, 5 ] } ]
                });
            } );


        </script>
    </div>
</div>