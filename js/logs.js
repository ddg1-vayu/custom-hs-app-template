$(document).ready(function () {
	var logsTable = $("#api_logs").DataTable({
		ajax: "get_logs.php",
		buttons: [
			{
				extend: "colvis",
				text: "Columns",
				titleAttr: "Toggle Columns",
			},
			{
				text: '<i class="fa-solid fa-arrows-rotate fs-6"></i>',
				action: function (e, dt, node, config) {
					dt.ajax.reload();
				},
				className: "refresh-btn",
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
				name: "api_origin",
				data: "api_origin",
				className: "curl_origin",
			},
			{
				name: "hub_portal_id",
				data: "hub_portal_id",
				className: "curl_portal",
			},
			{
				name: "file_name",
				data: "file_name",
				className: "curl_file",
			},
			{
				name: "curl_url",
				data: "id",
				className: "curl_url",
				searchable: false,
				orderable: false,
				targets: 1,
				render: function (data, row, type) {
					var rowId = type.id;
					return `<button type="button" class="btn btn-primary view-btn" data-bs-toggle="modal" title="View" data-bs-target="#data-modal" onclick="showEndpoint(${rowId})"><i class="fa-solid fa-eye fa-fw" aria-hidden="true"></i></button>`;
				},
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
					return `<button type="button" class="btn btn-primary view-btn" data-bs-toggle="modal" title="View" data-bs-target="#data-modal" onclick="showPayload(${rowId})"><i class="fa-solid fa-eye fa-fw" aria-hidden="true"></i></button>`;
				},
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
					return `<button type="button" class="btn btn-primary view-btn" data-bs-toggle="modal" title="View" data-bs-target="#data-modal" onclick="showPayload(${rowId}, 'json')"><i class="fa-solid fa-eye fa-fw" aria-hidden="true"></i></button>`;
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
					return `<button type="button" class="btn btn-primary view-btn" data-bs-toggle="modal" title="View" data-bs-target="#data-modal" onclick="showResponse(${rowId})"><i class="fa-solid fa-eye fa-fw" aria-hidden="true"></i></button>`;
				},
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
					return `<button type="button" class="btn btn-primary view-btn" data-bs-toggle="modal" title="View" data-bs-target="#data-modal" onclick="showResponse(${rowId}, 'json')"><i class="fa-solid fa-eye fa-fw" aria-hidden="true"></i></button>`;
				},
			},
			{
				name: "timestamp",
				data: "timestamp",
				className: "curl_timestamp",
			},
		],
		columnDefs: [
			{
				targets: 8,
				render: function (data, type, row) {
					var status;
					data >= 100 && data < 200
						? (status = "status-100-200")
						: data >= 200 && data < 300
						? (status = "status-200-300")
						: data >= 300 && data < 400
						? (status = "status-300-400")
						: data >= 400 && (status = "status-400-500");

					return `<span class="${status}" title="${data}"> ${data} </span>`;
				},
			},
		],
		deferRender: true,
		dom: '<"#data-table.row"<"#buttons.col-lg-3 col-md-6 col-sm-4 order-lg-0"B><"#length.col-lg-2 col-md-6 col-sm-6 order-lg-1"l><"#filter.col-lg-3 col-md-12 col-sm-12 order-lg-2 order-sm-0"f><"#table.col-lg-12 col-md-12 col-sm-12 order-lg-3"t><"#count.col-lg-4 col-md-12 col-sm-12 order-lg-4"i><"#pages.col-lg-8 col-md-12 col-sm-12 order-lg-5"p>>r',
		initComplete: function () {
			var dataInfo = logsTable.page.info();
			var totalRecords = dataInfo.recordsTotal;
			var pageLength = dataInfo.length;

			if (totalRecords > 0) {
				if (totalRecords < pageLength) {
					$("#length, #pages").hide();
					$("#count").addClass("pb-1");
				} else if (totalRecords == pageLength) {
					$("#pages").hide();
					$("#count").addClass("pb-1");
				}
			} else {
				$("#api_logs_wrapper").html(
					'<div class="alert alert-warning fw-bold text-center m-0" role="alert"> No Records Found! </div>'
				);
			}
		},
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
		order: [[11, "desc"]],
		pageLength: 15,
		pagingType: "simple_numbers",
		processing: true,
		responsive: true,
		searchDelay: 500,
		serverSide: true,
	});

	$.fn.DataTable.ext.pager.numbers_length = 7;

	$("#api_logs_length").find("select").removeClass("form-select-sm");
	$("#api_logs_filter").find("input").removeClass("form-control-sm");

	window.setTimeout(function () {
		if ($("#api_logs > tbody > tr > td").hasClass("dataTables_empty")) {
			$("#length, #filter, #count, #pages").hide();
			$("#table").css("margin-bottom", "0");
		}
	}, 100);
});
