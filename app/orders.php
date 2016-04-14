<?php
/**
 * Created by IntelliJ IDEA
 * User: Tom
 * Date: 07.04.2016
 * Time: 15:38
 */

session_start();

if(!isset($_SESSION['username'])) {
    return header('Location: index.php'); 
}
?>

<div id="content" style="padding-left:50px; padding-right:50px;">
	<div class="row">
		<div id="content_pane" class="col-md-6">
			<div class="panel panel-default">
				<!-- Default panel contents -->
				<div class="panel-heading">Bestellnummer: 100000060</div>
				<div class="panel-body">

				<p><label style="width:70px; font-weight:normal;">Käufer:</label><label style="text-indent: 5em;">Paula Meier</label></p>
				<p><label style="width:70px; font-weight:normal;">Email:</label><label style="text-indent: 5em;">paula@meier.ch</label></p>
				<p><label style="width:70px; font-weight:normal;">Datum und Zeit:</label><label style="text-indent: 5em;">2015-06-03 12:56:50</label></p>
				</div>

				<!-- Table -->
				<table class="table">
					<thead class="tablebold">
					<tr>
						<td>Artikel Name</td>
						<td>Anzahl</td>
						<td>Einzelpreis</td>
						<td>Gesamtpreis</td>
					</tr>
					</thead>
					<tbody>
						<tr>
							<td>Bananen</td>
							<td>2</td>
							<td>0.50</td>
							<td>1.00</td>
						</tr>
						<tr style="border-top:2px solid #666666;">
							<td>Total</td>
							<td></td>
							<td></td>
							<td>1.00</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>

		<div id="content_table" class="col-md-6">
			<div class="table-responsive rwd-article">
				<div id="data-table-sales_wrapper" class="dataTables_wrapper form-inline dt-bootstrap no-footer" style="width: 800px;">
					<div class="row">
						<div class="col-sm-10">
							<div id="data-table-sales_filter" class="dataTables_filter">
								<label>Suchen<input type="search" class="form-control input-sm" placeholder="" aria-controls="data-table-sales"></label>
							</div>
						</div>
					</div>

					<div class="row">
						<div class="col-sm-10">
							<table class="table table-hover table-striped table-bordered dataTable no-footer" id="data-table-sales" style="width: 100%;" role="grid" aria-describedby="data-table-sales_info">

								<thead class="tablebold">
								<tr role="row">
									<td class="sorting_asc" tabindex="0" aria-controls="data-table-sales" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Datum und Zeit: aktivieren, um Spalte absteigend zu sortieren" style="width: 300px;">Datum und Zeit</td>
									<td class="sorting" tabindex="0" aria-controls="data-table-sales" rowspan="1" colspan="1" aria-label="Käufer: aktivieren, um Spalte aufsteigend zu sortieren" style="width: 229px;">Käufer</td>
									<td class="sorting_disabled" rowspan="1" colspan="1" aria-label="Gesamtbetrag" style="width: 282px;">Gesamtbetrag</td>
								</tr>
								</thead>
								<tbody>
								<tr onclick="loadItem('sales','content_pane','60');" role="row" class="odd">
									<td class="sorting_1">2015-06-03 12:56:50</td>
									<td>Paula Meier</td>
									<td>0.50</td>
								</tr>
								</tbody>
							</table>
						</div>
					</div>

					<div class="row">
						<div class="col-sm-5">
							<div class="dataTables_info" id="data-table-sales_info" role="status" aria-live="polite">1 bis 1 von 1 Einträgen</div>
						</div>
						<div class="col-sm-5">
							<div class="dataTables_paginate paging_simple_numbers" id="data-table-sales_paginate">
								<ul class="pagination">
									<li class="paginate_button previous disabled" id="data-table-sales_previous"><a href="#" aria-controls="data-table-sales" data-dt-idx="0" tabindex="0">Zurück</a></li>
									<li class="paginate_button active"><a href="#" aria-controls="data-table-sales" data-dt-idx="1" tabindex="0">1</a></li>
									<li class="paginate_button next disabled" id="data-table-sales_next"><a href="#" aria-controls="data-table-sales" data-dt-idx="2" tabindex="0">Nächste</a></li>
								</ul>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>


<script type="text/javascript">
loadItem('sales', 'content_pane', 60);

$(document).ready(function() {

    $('#data-table-sales').DataTable({
     	"language": {
                "sEmptyTable":      "Keine Daten in der Tabelle vorhanden",
			    "sInfo":            "_START_ bis _END_ von _TOTAL_ Eintr&auml;gen",
			    "sInfoEmpty":       "0 bis 0 von 0 Einträgen",
			    "sInfoFiltered":    "(gefiltert von _MAX_ Eintr&auml;gen)",
			    "sInfoPostFix":     "",
			    "sInfoThousands":   ".",
			    "sLengthMenu":      "_MENU_ Eintr&auml;ge anzeigen",
			    "sLoadingRecords":  "Wird geladen...",
			    "sProcessing":      "Bitte warten...",
			    "sSearch":          "Suchen",
			    "sZeroRecords":     "Keine Eintr&auml;ge vorhanden.",
			     "oLanguage": {
          "sProcessing": "loading data..."
   },
			    "oPaginate": {
			        "sFirst":       "Erste",
			        "sPrevious":    "Zur&uuml;ck",
			        "sNext":        "N&auml;chste",
			        "sLast":        "Letzte"
			    },
			    "oAria": {
			        "sSortAscending":  ": aktivieren, um Spalte aufsteigend zu sortieren",
			        "sSortDescending": ": aktivieren, um Spalte absteigend zu sortieren"
			    }
        },
        "bLengthChange": false,
        "pageLength": 15,
        "aoColumnDefs": [
        { "bSortable": false, "aTargets": [ 2 ] } ]
     });
} );

</script>