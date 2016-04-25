<?php
/**
 * Created by IntelliJ IDEA
 * User: Janis
 * Date: 08.04.2016
 * Time: 20:44
 */
include("../api/users.php");

session_start();

if(!isset($_SESSION['username'])) {
 return header('Location: index.php'); 
}

$soap = new User();
$soap -> openSoap();
?>

<div id="content" style="padding-left:50px; padding-right:50px;">
    <script type="text/javascript">
        loadItem('users', 'content_table', 1);
    </script>

    <br><br>
        <div id="content_table">
            <div class="table-responsive rwd-article">
                <div id="data-table_wrapper" class="dataTables_wrapper form-inline dt-bootstrap no-footer" style="width:1600px;">
                    <div class="row">
                        <div class="col-sm-6"></div>
                        <div class="col-sm-6">
                            <div id="data-table_filter" class="dataTables_filter">
                                <label>Suchen<input type="search" class="form-control input-sm" placeholder="" aria-controls="data-table"></label>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <table class="table table-hover table-striped table-bordered dataTable no-footer" id="data-table" style="width: 100%;" role="grid" aria-describedby="data-table_info">
                                <thead class="tablebold">
                                <tr role="row">
                                    <td class="sorting_asc" aria-controls="data-table" rowspan="1" colspan="1" aria-sort="ascending" aria-label="ID: aktivieren, um Spalte absteigend zu sortieren" style="width: 136px;"><div style="width: 48px;">Vorname</div></td>
                                    <td class="sorting" aria-controls="data-table" rowspan="1" colspan="1" aria-label="Titel: aktivieren, um Spalte aufsteigend zu sortieren" style="width: 242px;">Nachname</td>
                                    <td class="sorting" aria-controls="data-table" rowspan="1" colspan="1" aria-label="Titel: aktivieren, um Spalte aufsteigend zu sortieren" style="width: 242px;">Adresse</td>
                                    <td class="sorting" aria-controls="data-table" rowspan="1" colspan="1" aria-label="Titel: aktivieren, um Spalte aufsteigend zu sortieren" style="width: 242px;">PLZ</td>
                                    <td class="sorting" aria-controls="data-table" rowspan="1" colspan="1" aria-label="Titel: aktivieren, um Spalte aufsteigend zu sortieren" style="width: 242px;">Ort</td>
                                    <td class="sorting" aria-controls="data-table" rowspan="1" colspan="1" aria-label="Titel: aktivieren, um Spalte aufsteigend zu sortieren" style="width: 242px;">Telefonnr.</td>
                                    <td class="sorting" aria-controls="data-table" rowspan="1" colspan="1" aria-label="Titel: aktivieren, um Spalte aufsteigend zu sortieren" style="width: 242px;">E-Mail</td>
                                    <td class="sorting" aria-controls="data-table" rowspan="1" colspan="1" aria-label="Titel: aktivieren, um Spalte aufsteigend zu sortieren" style="width: 242px;">Geburtstagsdatum</td>
                                    <td class="sorting" aria-controls="data-table" rowspan="1" colspan="1" aria-label="Titel: aktivieren, um Spalte aufsteigend zu sortieren" style="width: 150px;">Newsletter</td>
                                    <td class="sorting" aria-controls="data-table" rowspan="1" colspan="1" aria-label="Titel: aktivieren, um Spalte aufsteigend zu sortieren" style="width: 150px;">Einstellung</td>
                                </tr>
                                </thead>

                                <tbody>

                                <?php
                                $users = $soap -> getAllUsers();
                                $count = count($users);
                                $i = 0;

                                foreach ($users as $user)
                                {
                                    if($i % 2 == 0)
                                    {
                                        ?><tr role="row" class="even"><?php
                                    }
                                    else
                                    {
                                        ?><tr role="row" class="odd"><?php
                                    }
                                    ?>

                                    <td class="sorting_1"><?php echo $user['firstname'] ?></td>
                                    <td class="sorting_1"><?php echo $user['lastname'] ?></td>
                                    <td class="sorting_1"><?php echo $user['street'] ?></td>
                                    <td class="sorting_1"><?php echo $user['postcode'] ?></td>
                                    <td class="sorting_1"><?php echo $user['city'] ?></td>
                                    <td class="sorting_1"><?php echo $user['telephone'] ?></td>
                                    <td class="sorting_1"><?php echo $user[''] ?></td> <!-- email -->
                                    <td class="sorting_1"><?php echo $user[''] ?></td> <!-- date of birth -->
                                    <td class="sorting_1"><?php echo $user[''] ?></td> <!-- newsletter -->
                                    <td><span class="glyphicon glyphicon-cog" aria-hidden="true"></span></td>
                                    </tr><?php
                                }


                                ?>

                                <!--
                                <tr role="row" class="odd">
                                    <td class="sorting_1">Max</td>
                                    <td>Mustermann</td>
                                    <td>Musterstrasse 14</td>
                                    <td>1010</td>
                                    <td>Musteren</td>
                                    <td>041 414 32 10</td>
                                    <td>max@muster.ch</td>
                                    <td>26.05.1986</td>
                                    <td><input type="checkbox" name="newsletter" checked></td>
                                    <td><span class="glyphicon glyphicon-cog" aria-hidden="true"></span>
                                        <span class="glyphicon glyphicon-remove" aria-hidden="true"></span></td>
                                </tr><tr role="row" class="even">
                                    <td class="sorting_1">Lea</td>
                                    <td>Mustermann</td>
                                    <td>Musterstrasse 16</td>
                                    <td>1010</td>
                                    <td>Musteren</td>
                                    <td>041 414 43 21</td>
                                    <td>lea@muster.ch</td>
                                    <td>06.09.1981</td>
                                    <td><input type="checkbox" name="newsletter" ></td>
                                    <td><span class="glyphicon glyphicon-cog" aria-hidden="true"></span>
                                        <span class="glyphicon glyphicon-remove" aria-hidden="true"></span></td>
                                </tr>
                                -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-5">
                            <div class="dataTables_info" id="data-table_info" role="status" aria-live="polite">1 bis 2 von 2 Einträgen</div>
                        </div><div class="col-sm-7">
                        <div class="dataTables_paginate paging_simple_numbers" id="data-table_paginate">
                            <ul class="pagination">
                                <li class="paginate_button previous disabled" id="data-table_previous">
                                    <a href="#" aria-controls="data-table" data-dt-idx="0" tabindex="0">Zurück</a>
                                </li>
                                <li class="paginate_button active">
                                    <a href="#" aria-controls="data-table" data-dt-idx="1" tabindex="0">1</a>
                                </li>
                                <li class="paginate_button next disabled" id="data-table_next">
                                    <a href="#" aria-controls="data-table" data-dt-idx="2" tabindex="0">Nächste</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    </div>
                </div>
            </div>

        <script type="text/javascript">
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