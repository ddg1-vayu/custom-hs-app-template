$(document).ready(function () {
	var webhooksTable = $("#webhooks_tbl").DataTable({
		ajax: "get_webhooks.php",
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
				titleAttr: "Refresh",
			},
		],
		columns: [
			{
				name: "portal",
				data: "portal",
				className: "webhook_portal",
			},
			{
				name: "type",
				data: "type",
				className: "webhook_type",
			},
			{
				name: "source",
				data: "source",
				className: "webhook_source",
			},
			{
				name: "file_name",
				data: "file_name",
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
					return `<button type="button" class="btn btn-primary view-btn" title="View" data-bs-toggle="modal" data-bs-target="#data-modal" onclick="showWebhook('${rowId}')"><i class="fa-solid fa-eye fa-fw" aria-hidden="true"></i></button>`;
				},
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
					return `<button type="button" class="btn btn-primary view-btn" title="View" data-bs-toggle="modal" data-bs-target="#data-modal" onclick="showWebhook(${rowId}, 'json')"><i class="fa-solid fa-eye fa-fw" aria-hidden="true"></i></button>`;
				},
			},
			{
				name: "status",
				data: "status",
				className: "webhook_status",
				searchable: false,
				orderable: false,
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
				targets: 6,
				render: function (data, type, row) {
					var status;
					"processed" == data || 1 == data
						? (status =
								'<i class="fa-regular fa-circle-check fa-fw success-code" aria-hidden="true" title="Processed"></i>')
						: ("not processed" != data && 0 != data) ||
						  (status =
								'<i class="fa-regular fa-circle-xmark fa-fw error-code" aria-hidden="true" title="Not Processed"></i>');
					return status;
				},
			},
		],
		deferRender: true,
		dom: '<"#data-table.row"<"#buttons.col-lg-3 col-md-6 col-sm-4 order-lg-0"B><"#length.col-lg-2 col-md-6 col-sm-6 order-lg-1"l><"#filter.col-lg-3 col-md-12 col-sm-12 order-lg-2 order-sm-0"f><"#table.col-lg-12 col-md-12 col-sm-12 order-lg-3"t><"#count.col-lg-4 col-md-12 col-sm-12 order-lg-4"i><"#pages.col-lg-8 col-md-12 col-sm-12 order-lg-5"p>>r',
		initComplete: function () {
			var dataInfo = webhooksTable.page.info();
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
				$("#webhooks_tbl_wrapper").html(
					'<div class="alert alert-warning fw-bold text-center m-0" role="alert"> No Records Found! </div>'
				);
			}
		},
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
				first: "First",
				last: "Last",
				next: "Next",
				previous: "Prev",
			},
		},
		lengthMenu: [
			[15, 30, 60, 120, 240, 480],
			[15, 30, 60, 120, 240, 480],
		],
		order: [[7, "desc"]],
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
