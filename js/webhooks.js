$(document).ready(function () {
	$("#webhooks_tbl").DataTable({
		ajax: "get_webhooks.php",
		buttons: [
			{
				extend: "colvis",
				text: "Columns",
				titleAttr: "Toggle Columns",
			},
			{
				text: '<i class = "fa fa-refresh fs-6">',
				action: function (e, dt, node, config) {
					dt.ajax.reload();
				},
				className: "refreshBtn",
				titleAttr: "Refresh",
			},
		],
		columns: [
			{
				name: "type",
				data: "type",
				className: "webhook_type",
			},
			{
				name: "portal",
				data: "portal",
				className: "webhook_portal",
			},
			{
				name: "source",
				data: "source",
				className: "webhook_source",
			},
			{
				name: "file",
				data: "file",
				className: "webhook_file",
			},
			{
				name: "webhook_payload",
				data: "id",
				className: "webhook_payload",
				searchable: false,
				orderable: false,
				targets: 1,
				render: function (data, row, type) {
					var rowId = type.id;
					return (
						'<button type="button" class="btn btn-primary view-btn" title="View" data-bs-toggle="modal" data-bs-target="#data-modal" onclick="displayWebhook(' +
						rowId +
						')"><i class="fa fa-eye" aria-hidden="true"></i></button>'
					);
				},
			},
			{
				name: "status",
				data: "status",
				className: "webhook_status",
			},
			{
				name: "timestamp",
				data: "timestamp",
				className: "webhook_timestamp",
			},
			{
				name: "modified",
				data: "modified",
				className: "webhook_modified",
			},
		],
		columnDefs: [
			{
				targets: 5,
				render: function (data, type, row) {
					var bg_color, text;
					if (data == "processed" || data == 1) {
						bg_color = "#07c007";
						text = "Processed";
					} else if (data == "not processed" || data == 0) {
						bg_color = "#FF0000";
						text = "Not Processed";
					}
					return (
						'<span class="status-span" style="background-color:' +
						bg_color +
						'">' +
						text +
						"</span>"
					);
				},
			},
		],
		deferRender: true,
		dom: '<"#webhooks.row"<"#buttons.col-lg-3 col-md-6 col-sm-4 order-lg-0"B><"#length.col-lg-2 col-md-6 col-sm-6 order-lg-1"l><"#filter.col-lg-3 col-md-12 col-sm-12 order-lg-2 order-sm-0"f><"#table.col-lg-12 col-md-12 col-sm-12 order-lg-3"t><"#count.col-lg-4 col-md-12 col-sm-12 order-lg-4"i><"#pages.col-lg-8 col-md-12 col-sm-12 order-lg-5"p>>r',
		language: {
			lengthMenu: "Viewing _MENU_ webhooks",
			info: "Showing _START_ to _END_ of _TOTAL_ webhooks",
			emptyTable: "No records found!",
			loadingRecords: "Fetching...",
			processing: "<div class='loader'></div>",
			search: "_INPUT_",
			searchPlaceholder: "Search webhooks...",
			zeroRecords: "No records available!",
			paginate: {
				next: "Next",
				previous: "Prev",
			},
		},
		lengthMenu: [
			[15, 30, 60, 120, 240, 480],
			[15, 30, 60, 120, 240, 480],
		],
		order: [[6, "desc"]],
		pageLength: 15,
		pagingType: "simple_numbers",
		processing: true,
		searchDelay: 500,
		serverSide: true,
		responsive: true,
	});

	$("#webhooks_tbl_length").find("select").removeClass("form-select-sm");
	$("#webhooks_tbl_filter").find("input").removeClass("form-control-sm");
});

function displayWebhook(recordId) {
	$.ajax({
		type: "POST",
		url: "get_data.php",
		data: {
			action: "get_webhook",
			recordId: recordId,
		},
		success: function (response) {
			$("#data-modal-label").html("WEBHOOK");
			if (response == "null" || response == null) {
				$("#data-modal-content").html(
					'<div class="alert alert-info fw-bold m-0"> No response received. </div>'
				);
			} else {
				$("#data-modal-content").html("<pre>" + response + "</pre>");
			}
		},
		error: function (response) {
			console.log(response);
		},
	});
}
