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
					'<div class="alert alert-info"> No payload sent with this request! </div>'
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

function showPayloadJSON(recordId) {
	$.ajax({
		type: "POST",
		url: "get_data.php",
		data: {
			action: "get_payload_json",
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
			action: "get_response",
			recordId: recordId,
		},
		success: function (response) {
			$("#data-modal-label").html("RESPONSE");
			if (response == "null" || response == null) {
				$("#data-modal-content").html(
					'<div class="alert alert-info"> No response received. </div>'
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

function showWebhook(recordId) {
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
					'<div class="alert alert-info"> No response received. </div>'
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

function showWebhookJSON(recordId) {
	$.ajax({
		type: "POST",
		url: "get_data.php",
		data: {
			action: "get_webhook_json",
			recordId: recordId,
		},
		success: function (response) {
			$("#data-modal-label").html("WEBHOOK");
			if (response == "null" || response == null) {
				$("#data-modal-content").html(
					'<div class="alert alert-info"> No response received. </div>'
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
