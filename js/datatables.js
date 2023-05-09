var dt_page_arr = ["uploads.php", "logs.php", "webhooks.php"];

if (currentPage == dt_page_arr[0]) {
	$(document).ready(function () {
		var uploadsTable = $("#uploads_tbl").DataTable({
			ajax: "datatable.php?table=3",
			buttons: [
				{
					extend: "colvis",
					text: "Columns",
					titleAttr: "Toggle Columns",
				},
				{
					text: '<i class="fa-solid fa-arrows-rotate fa-fw fs-6"></i>',
					action: function (e, dt, node, config) {
						dt.ajax.reload();
					},
					className: "refresh-btn",
					titleAttr: "Refresh",
				},
			],
			columns: [
				{
					name: "file_name",
					data: "file_name",
					className: "file_name",
				},
				{
					name: "file_type",
					data: "file_type",
					className: "file_type",
					searchable: false,
					orderable: false,
					render: function (data, row, type) {
						var fileType = data.split("/").pop();
						var icon;

						switch (fileType) {
							case "bmp":
							case "gif":
							case "jpeg":
							case "jpg":
							case "png":
							case "tiff":
							case "webp":
								icon = "fa-file-image file-image";
								break;
							case "3gp":
							case "avi":
							case "mkv":
							case "mov":
							case "mp4":
							case "quicktime":
							case "webm":
								icon = "fa-file-video file-video";
								break;
							case "mp3":
							case "mpeg":
							case "ogg":
								icon = "fa-file-audio file-audio";
								break;
							case "doc":
							case "docx":
							case "ms-doc":
							case "msword":
							case "vnd.openxmlformats-officedocument.wordprocessingml.document":
								icon = "fa-file-word file-doc";
								break;
							case "csv":
								icon = "fa-file-csv file-sheet";
								break;
							case "excel":
							case "vnd.ms-excel":
							case "vnd.openxmlformats-officedocument.spreadsheetml.sheet":
							case "x-excel":
							case "x-msexcel":
							case "xls":
							case "xlsx":
								icon = "fa-file-excel file-sheet";
								break;
							case "mspowerpoint":
							case "powerpoint":
							case "ppt":
							case "pptx":
							case "vnd.ms-powerpoint":
							case "vnd.openxmlformats-officedocument.presentationml.presentation":
								icon = "fa-file-powerpoint file-ppt";
								break;
							case "txt":
							case "plain":
								icon = "fa-file-lines file-other";
								break;
							case "pdf":
								icon = "fa-file-pdf file-pdf";
								break;
						}

						return `<i class='fa-solid ${icon} fs-1' aria-hidden='true'></i>`;
					},
				},
				{
					name: "file_size",
					data: "file_size",
					className: "file_size",
					render: function (data, row, type) {
						return formatFileSize(data);
					},
				},
				{
					name: "uploaded",
					data: "uploaded",
					className: "file_timestamp",
				},
				{
					name: "modified",
					data: "modified",
					className: "file_timestamp",
				},
				{
					name: "actions",
					data: "id",
					className: "file_actions",
					searchable: false,
					orderable: false,
					render: function (data, row, type) {
						var rowId = type.id;
						return `<div class="actions_box"> <button type="button" class="btn btn-primary view-btn copy-btn" onclick="copyLink(this, ${rowId})" title="Copy Link"><i class="fa-solid fa-link fa-fw" aria-hidden="true"></i></button> <button type="button" class="btn btn-primary view-btn" data-bs-toggle="modal" data-bs-target="#data-modal" onclick="showFile(${rowId})" title="View"><i class="fa-solid fa-eye fa-fw" aria-hidden="true"></i></button> <button type="button" class="btn btn-danger view-btn delete-btn" onclick="deleteFile(this, ${rowId})" title="Delete"><i class="fa-solid fa-trash fa-fw" aria-hidden="true"></i></button> </div>`;
					},
				},
			],
			deferRender: true,
			dom: '<"#data-table.row"<"#buttons.col-lg-3 col-md-6 col-sm-4 order-lg-0"B><"#length.col-lg-2 col-md-6 col-sm-6 order-lg-1"l><"#filter.col-lg-3 col-md-12 col-sm-12 order-lg-2 order-sm-0"f><"#table.col-lg-12 col-md-12 col-sm-12 order-lg-3"t><"#count.col-lg-4 col-md-12 col-sm-12 order-lg-4"i><"#pages.col-lg-8 col-md-12 col-sm-12 order-lg-5"p>>r',
			initComplete: function () {
				var dataInfo = uploadsTable.page.info();
				var totalRecords = dataInfo.recordsTotal;
				var pageLength = dataInfo.length;

				totalRecords > 0 &&
					(totalRecords < pageLength
						? ($("#length, #pages").hide(), $("#count").addClass("pb-1"))
						: totalRecords == pageLength &&
						  ($("#pages").hide(), $("#count").addClass("pb-1")));
			},
			language: {
				lengthMenu: "Viewing _MENU_ files",
				info: "Showing _START_ to _END_ of _TOTAL_ files",
				emptyTable: "No records found!",
				loadingRecords: "Fetching...",
				processing: "<div class='loader'></div>",
				search: "_INPUT_",
				searchPlaceholder: "Search files...",
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
			order: [[4, "desc"]],
			pageLength: 15,
			pagingType: "simple_numbers",
			processing: true,
			searchDelay: 500,
			serverSide: true,
			responsive: true,
		});

		$("#uploads_tbl_length").find("select").removeClass("form-select-sm");
		$("#uploads_tbl_filter").find("input").removeClass("form-control-sm");
	});
} else if (currentPage == dt_page_arr[1]) {
	$(document).ready(function () {
		var logsTable = $("#api_logs").DataTable({
			ajax: "datatable.php?table=1",
			buttons: [
				{
					extend: "colvis",
					text: "Columns",
					titleAttr: "Toggle Columns",
				},
				{
					text: '<i class="fa-solid fa-arrows-rotate fa-fw fs-6"></i>',
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
					render: function (data, type, row) {
						var status;
						data >= 100 && data < 200
							? (status = "status-100-200")
							: data >= 200 && data < 300
							? (status = "status-200-300")
							: data >= 300 && data < 400
							? (status = "status-300-400")
							: data >= 400 && (status = "status-400-500");
						return `<span class="${status}"> ${data} </span>`;
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
			deferRender: true,
			dom: '<"#data-table.row"<"#buttons.col-lg-3 col-md-6 col-sm-4 order-lg-0"B><"#length.col-lg-2 col-md-6 col-sm-6 order-lg-1"l><"#filter.col-lg-3 col-md-12 col-sm-12 order-lg-2 order-sm-0"f><"#table.col-lg-12 col-md-12 col-sm-12 order-lg-3"t><"#count.col-lg-4 col-md-12 col-sm-12 order-lg-4"i><"#pages.col-lg-8 col-md-12 col-sm-12 order-lg-5"p>>r',
			initComplete: function () {
				var dataInfo = logsTable.page.info();
				var totalRecords = dataInfo.recordsTotal;
				var pageLength = dataInfo.length;

				totalRecords > 0
					? totalRecords < pageLength
						? ($("#length, #pages").hide(), $("#count").addClass("pb-1"))
						: totalRecords == pageLength &&
						  ($("#pages").hide(), $("#count").addClass("pb-1"))
					: $("#api_logs_wrapper").html(
							'<div class="alert alert-warning fw-bold text-center m-0" role="alert"> No Records Found! </div>'
					  );
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

		$("#api_logs_length").find("select").removeClass("form-select-sm");
		$("#api_logs_filter").find("input").removeClass("form-control-sm");
	});
} else if (currentPage == dt_page_arr[2]) {
	$(document).ready(function () {
		var webhooksTable = $("#webhooks_tbl").DataTable({
			ajax: "datatable.php?table=2",
			buttons: [
				{
					extend: "colvis",
					text: "Columns",
					titleAttr: "Toggle Columns",
				},
				{
					text: '<i class="fa-solid fa-arrows-rotate fa-fw fs-6"></i>',
					action: function (e, dt, node, config) {
						dt.ajax.reload();
					},
					className: "refresh-btn",
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
					render: function (data, type, row) {
						var status;
						"processed" == data || 1 == data
							? (status =
									'<i class="fa-regular fa-circle-check success-code" aria-hidden="true" title="Processed"></i>')
							: ("not processed" != data && 0 != data) ||
							  (status =
									'<i class="fa-regular fa-circle-xmark error-code" aria-hidden="true" title="Not Processed"></i>');
						return status;
					},
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
			deferRender: true,
			dom: '<"#data-table.row"<"#buttons.col-lg-3 col-md-6 col-sm-4 order-lg-0"B><"#length.col-lg-2 col-md-6 col-sm-6 order-lg-1"l><"#filter.col-lg-3 col-md-12 col-sm-12 order-lg-2 order-sm-0"f><"#table.col-lg-12 col-md-12 col-sm-12 order-lg-3"t><"#count.col-lg-4 col-md-12 col-sm-12 order-lg-4"i><"#pages.col-lg-8 col-md-12 col-sm-12 order-lg-5"p>>r',
			initComplete: function () {
				var dataInfo = webhooksTable.page.info();
				var totalRecords = dataInfo.recordsTotal;
				var pageLength = dataInfo.length;

				totalRecords > 0
					? totalRecords < pageLength
						? ($("#length, #pages").hide(), $("#count").addClass("pb-1"))
						: totalRecords == pageLength &&
						  ($("#pages").hide(), $("#count").addClass("pb-1"))
					: $("#webhooks_tbl_wrapper").html(
							'<div class="alert alert-warning fw-bold text-center m-0" role="alert"> No Records Found! </div>'
					  );
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
			order: [[8, "desc"]],
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
}
