function showFilters() {
	if (!$("#filter-form").is(":visible")) {
		$("#filter-form").show();
	} else {
		$("#filter-form").hide();
	}
}

function resetFilters() {
	window.location = window.location.href.split("?")[0];
}

function showEndpoint(recordId) {
	$.ajax({
		type: "POST",
		url: "get_data.php",
		data: {
			action: "get_endpoint",
			recordId: recordId,
		},
		success: function (response) {
			$("#data-modal-label").html("ENDPOINT");
			if (response == "null" || response == null) {
				$("#data-modal-content").html(
					'<div class="alert alert-info fw-bold m-0"> No endpoint sent with this request. </div>'
				);
			} else {
				$("#data-modal-content").html(
					"<span style='font-weight:500'>" + response + "</span>"
				);
			}
		},
		error: function (response) {
			console.log(response);
		},
	});
}

function showPayload(recordId) {
	$.ajax({
		type: "POST",
		url: "get_data.php",
		data: {
			action: "get_payload",
			recordId: recordId,
		},
		success: function (response) {
			$("#data-modal-label").html("PAYLOAD");
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

function showResult(recordId) {
	$.ajax({
		type: "POST",
		url: "get_data.php",
		data: {
			action: "get_result",
			recordId: recordId,
		},
		success: function (response) {
			$("#data-modal-label").html("RESULT");
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

document.addEventListener("DOMContentLoaded", function () {
	// var page = window.location.pathname;
	// var pageArr = page.split("/");
	// var currentPage = pageArr[pageArr.length - 1];

	// switch (currentPage) {
	// 	case "integration_logs.php":
	// 		console.log(1);
	// 		break;
	// 	case "logs.php":
	// 		console.log(2);
	// 		break;
	// 	case "webhooks.php":
	// 		console.log(3);
	// 		break;
	// }

	var queryString = window.location.search;
	if (queryString != "") {
		const searchParams = new URLSearchParams(queryString);
		var pageNum = searchParams.get("page");
		if (pageNum == null) {
			$(".page-link#1").addClass("active disabled");
		} else {
			$(".page-link#" + pageNum).addClass("active disabled");
		}
	} else {
		$(".page-link#1").addClass("active disabled");
	}
});