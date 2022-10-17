$(document).ready(function () {
	$("#api_logs").DataTable({
		ajax: "get_logs.php",
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
				name: "curl_type",
				data: "curl_type",
				className: "curl_type",
			},
			{
				name: "file_name",
				data: "file_name",
				className: "curl_file",
			},
			{
				name: "hub_portal_id",
				data: "hub_portal_id",
				className: "curl_portal",
			},
			{
				name: "api_origin",
				data: "api_origin",
				className: "curl_origin",
			},
			{
				name: "curl_url",
				data: "curl_url",
				className: "curl_url",
			},
			{
				name: "curl_method",
				data: "curl_method",
				className: "curl_method",
			},
			{
				name: "curl_payload",
				data: "id",
				className: "curl_payload",
				searchable: false,
				orderable: false,
				targets: 1,
				render: function (data, row, type) {
					var rowId = type.id;
					return (
						'<button type="button" class="btn btn-primary view-btn" data-bs-toggle="modal" title="View" data-bs-target="#data-modal" onclick="displayPayload(' +
						rowId +
						')"><i class="fa fa-eye" aria-hidden="true"></i></button>'
					);
				},
			},
			{
				name: "curl_http_code",
				data: "curl_http_code",
				className: "curl_http_code",
			},
			{
				name: "curl_response",
				data: "id",
				className: "curl_response",
				searchable: false,
				orderable: false,
				targets: 1,
				render: function (data, row, type) {
					var rowId = type.id;
					return (
						'<button type="button" class="btn btn-primary view-btn" data-bs-toggle="modal" title="View" data-bs-target="#data-modal" onclick="displayResult(' +
						rowId +
						')"><i class="fa fa-eye" aria-hidden="true"></i></button>'
					);
				},
			},
			{
				name: "timestamp",
				data: "timestamp",
				className: "curl_timestamp",
			},
		],
		deferRender: true,
		dom: '<"#data-table.row"<"#buttons.col-lg-3 col-md-6 col-sm-4 order-lg-0"B><"#length.col-lg-2 col-md-6 col-sm-6 order-lg-1"l><"#filter.col-lg-3 col-md-12 col-sm-12 order-lg-2 order-sm-0"f><"#table.col-lg-12 col-md-12 col-sm-12 order-lg-3"t><"#count.col-lg-4 col-md-12 col-sm-12 order-lg-4"i><"#pages.col-lg-8 col-md-12 col-sm-12 order-lg-5"p>>r',
		language: {
			lengthMenu: "Viewing _MENU_ logs",
			info: "Showing _START_ to _END_ of _TOTAL_ logs",
			emptyTable: "No records found!",
			loadingRecords: "Fetching...",
			processing: "<div class='loader'></div>",
			search: "_INPUT_",
			searchPlaceholder: "Search logs...",
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
		order: [[9, "desc"]],
		pageLength: 15,
		pagingType: "simple_numbers",
		processing: true,
		responsive: true,
		searchDelay: 500,
		serverSide: true,
		columnDefs: [
			{
				targets: 7,
				render: function (data, type, row) {
					var color;
					if (data >= 100 && data < 200) {
						color = "#808080";
					} else if (data >= 200 && data < 300) {
						color = "#07C007";
					} else if (data >= 300 && data < 400) {
						color = "#10107A";
					} else if (data >= 400 && data < 500) {
						color = "#EDED23";
					} else {
						color = "#FF0000";
					}
					return '<span style="color:' + color + '">' + data + "</span>";
				},
			},
		],
	});

	$("#api_logs_length").find("select").removeClass("form-select-sm");
	$("#api_logs_filter").find("input").removeClass("form-control-sm");
});

function displayPayload(recordId) {
	$.ajax({
		type: "POST",
		url: "get_data.php",
		data: {
			action: "get_payload",
			recordId: recordId,
		},
		success: function (response) {
			$("#data-modal-label").html("REQUEST PAYLOAD");
			if (response == "null" || response == null) {
				$("#data-modal-content").html(
					'<div class="alert alert-info fw-bold m-0"> No payload sent with this request. </div>'
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

function displayResult(recordId) {
	$.ajax({
		type: "POST",
		url: "get_data.php",
		data: {
			action: "get_result",
			recordId: recordId,
		},
		success: function (response) {
			$("#data-modal-label").html("REQUEST RESULT");
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
