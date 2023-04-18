function showEndpoint(recordId) {
	var formData = { action: "get_endpoint", recordId: recordId };

	$.ajax({
		type: "POST",
		url: "get_data.php",
		data: formData,
		beforeSend: function () {
			$("#data-modal-content").html('<div class="loading"></div>');
		},
		success: function (response) {
			$("#data-modal-label").html("ENDPOINT");
			if (response == "null" || response == null) {
				$("#data-modal-content").html(
					'<div class="alert alert-info"> No endpoint sent with this request! </div>'
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

function showPayload(recordId, type = "array") {
	var action = type == "json" ? "get_payload_json" : "get_payload";

	var formData = { action: action, recordId: recordId };

	$.ajax({
		type: "POST",
		url: "get_data.php",
		data: formData,
		beforeSend: function () {
			$("#data-modal-content").html('<div class="loading"></div>');
		},
		success: function (response) {
			$("#data-modal-label").html("PAYLOAD");
			setTimeout(function () {
				if (response == "null" || response == null) {
					$("#data-modal-content").html(
						'<div class="alert alert-info"> No payload sent with this request! </div>'
					);
				} else {
					$("#data-modal-content").html("<pre>" + response + "</pre>");
				}
			}, 500);
		},
		error: function (response) {
			console.log(response);
		},
	});
}

function showResponse(recordId, type = "array") {
	var action = type == "json" ? "get_response_json" : "get_response";

	var formData = { action: action, recordId: recordId };

	$.ajax({
		type: "POST",
		url: "get_data.php",
		data: formData,
		beforeSend: function () {
			$("#data-modal-content").html('<div class="loading"></div>');
		},
		success: function (response) {
			$("#data-modal-label").html("RESPONSE");
			setTimeout(function () {
				if (response == "null" || response == null) {
					$("#data-modal-content").html(
						'<div class="alert alert-info"> No response received. </div>'
					);
				} else {
					$("#data-modal-content").html("<pre>" + response + "</pre>");
				}
			}, 500);
		},
		error: function (response) {
			console.log(response);
		},
	});
}

function showWebhook(recordId, type = "array") {
	var action = type == "json" ? "get_webhook_json" : "get_webhook";

	var formData = { action: action, recordId: recordId };

	$.ajax({
		type: "POST",
		url: "get_data.php",
		data: formData,
		beforeSend: function () {
			$("#data-modal-content").html('<div class="loading"></div>');
		},
		success: function (response) {
			$("#data-modal-label").html("WEBHOOK");
			setTimeout(function () {
				if (response == "null" || response == null) {
					$("#data-modal-content").html(
						'<div class="alert alert-info"> No response received. </div>'
					);
				} else {
					$("#data-modal-content").html("<pre>" + response + "</pre>");
				}
			}, 500);
		},
		error: function (response) {
			console.log(response);
		},
	});
}
