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
			"null" == response || null == response
				? $("#data-modal-content").html(
						'<div class="alert alert-info"> No endpoint sent with this request! </div>'
				  )
				: $("#data-modal-content").html(
						"<span style='font-weight:500'>" + response + "</span>"
				  );
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
				"null" == response || null == response
					? $("#data-modal-content").html(
							'<div class="alert alert-info"> No payload sent with this request! </div>'
					  )
					: $("#data-modal-content").html("<pre>" + response + "</pre>");
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
				"null" == response || null == response
					? $("#data-modal-content").html(
							'<div class="alert alert-info"> No response received. </div>'
					  )
					: $("#data-modal-content").html("<pre>" + response + "</pre>");
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
				"null" == response || null == response
					? $("#data-modal-content").html(
							'<div class="alert alert-info"> No response received. </div>'
					  )
					: $("#data-modal-content").html("<pre>" + response + "</pre>");
			}, 500);
		},
		error: function (response) {
			console.log(response);
		},
	});
}

function upload() {
	var fileInput = document.getElementById("upload_file"),
		filePath = fileInput.value,
		allowedExtensions =
			/(\.bmp|\.gif|\.jpeg|\.jpg|\.png|\.tiff|\.webp|\.pdf|\.doc|\.docx|\.xls|\.xlsx|\.csv|\.ppt|\.pptx|\.txt|\.mp4|\.quicktime|\.webm|\.avi|\.mov|\.mkv|\.3gp|\.mp4|\.mp3|\.mpeg|\.ogg)$/i;

	if ("" != document.getElementById("upload_file").value) {
		var fileSize = fileInput.files[0].size / (1024 * 1024);

		if (allowedExtensions.exec(filePath) && fileSize.toFixed(1) < 16.0) {
			var form = $("#upload-form")[0],
				formData = new FormData(form);

			$.ajax({
				type: "POST",
				enctype: "multipart/form-data",
				processData: false,
				contentType: false,
				url: "actions.php",
				data: formData,
				beforeSend: function () {
					$("#progress-bar").show();
					$("#upload_file").addClass("is-valid").prop("disabled", true);
					$("#submit")
						.html(
							`<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> <span class="visually-hidden">Loading...</span>`
						)
						.prop("disabled", true);
				},
				xhr: function () {
					var xhr = new XMLHttpRequest();
					xhr.upload.addEventListener(
						"progress",
						function (event) {
							if (event.lengthComputable) {
								var percentComplete = (event.loaded / event.total) * 100;
								$(".progress-bar")
									.text(parseInt(percentComplete) + "%")
									.attr("aria-valuenow", percentComplete)
									.css("width", percentComplete + "%");
							}
						},
						false
					);
					return xhr;
				},
				success: function (response) {
					if ("" != response || "null" != response) {
						var message = JSON.parse(response).message;

						uploadAlert(message);
						window.setTimeout(function () {
							$("#upload-modal").modal("hide");
							window.setTimeout(function () {
								$("#upload_file").removeClass("is-valid");
								$("#progress-bar").hide();
								$(".progress-bar")
									.text("")
									.attr("aria-valuenow", 0)
									.css("width", 0 + "%");
								form.reset();
							}, 2000);
							$(".refresh-btn").click();
						}, 2500);
					} else {
						uploadAlert("Undefined error! Try again later!", "error");
					}
				},
				error: function (jqXHR) {
					var message = JSON.parse(jqXHR.responseText).message,
						statusCode = jqXHR.status;

					$("#progress-bar").hide();
					$(".progress-bar")
						.text("")
						.attr("aria-valuenow", 0)
						.css("width", 0 + "%");

					uploadAlert(`${message} (${statusCode})`, "error");
				},
			});
		} else {
			allowedExtensions.exec(filePath)
				? fileSize.toFixed(1) > 16.0 &&
				  uploadAlert(
						"error",
						"Selected file cannot be uploaded - File size more than 16 MB"
				  )
				: uploadAlert(
						"error",
						"Selected file cannot be uploaded - Unsupported File Type"
				  );
		}
	} else {
		uploadAlert("File must be selected to Upload!", "error");
	}
}

function showFile(id) {
	$.ajax({
		type: "POST",
		url: "get_data.php",
		data: { action: "get_file", recordId: id },
		beforeSend: function () {
			$("#data-modal-content").html('<div class="loading"></div>');
		},
		success: function (response) {
			$("#data-modal-label").html("FILE");
			"null" != response ||
			null != response ||
			response != NULL ||
			"" != response
				? $("#data-modal-content").html(response)
				: $("#data-modal-content").html(
						'<div class="alert alert-info"> No File Found! </div>'
				  );
		},
		error: function (response) {
			console.log(response.responseText);
		},
	});
}

function copyLink(element, id) {
	$.ajax({
		type: "POST",
		url: "get_data.php",
		data: {
			action: "get_link",
			recordId: id,
		},
		beforeSend: function () {
			element.innerHTML = `<i class="fa-solid fa-rotate fa-fw fa-spin" aria-hidden="true"></i>`;
			element.setAttribute("disabled", "");
		},
		success: function (response) {
			if (
				"null" != response ||
				null != response ||
				"" != response ||
				response != NULL
			) {
				navigator.clipboard.writeText(response);
				setTimeout(function () {
					notify("Link Copied!");
					element.innerHTML = `<i class="fa-solid fa-link fa-fw" aria-hidden="true"></i>`;
					element.removeAttribute("disabled");
					element.classList.replace("btn-success", "btn-primary");
				}, 2000);
			}
		},
		error: function (response) {
			console.log(response.responseText);
		},
	});
}
