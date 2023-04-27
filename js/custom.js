var page = window.location.pathname,
	currentPage = page.split("/").pop();

var int_page_arr = [
	"activity.php",
	"integration_logs.php",
	"integration_webhooks.php",
];

if (int_page_arr.includes(currentPage)) {
	document.addEventListener("DOMContentLoaded", function () {
		var queryString = window.location.search;
		if (queryString != "") {
			const searchParams = new URLSearchParams(queryString);
			var pageNum = searchParams.get("page");
			null == pageNum
				? $(".page-link#1").addClass("active disabled")
				: $(".page-link#" + pageNum).addClass("active disabled");
		} else {
			$(".page-link#1").addClass("active disabled");
		}
	});
}

function loader() {
	$("body").addClass("preloading");
	setTimeout(function () {
		$(".preloader").addClass("hide");
		$("body").removeClass("preloading");
	}, 1500);
}

function showLoader() {
	$("body").addClass("preloading");
	$(".preloader").removeClass("hide");
}

function hideLoader() {
	$(".preloader").addClass("hide");
	$("body").removeClass("preloading");
}

function showFilters() {
	var form = $("#filter-form");
	form.is(":visible") ? form.hide() : form.show();
}

function resetFilters() {
	window.location = window.location.href.split("?")[0];
}

function removeInvalidClass(form) {
	$(form).find(".is-invalid").removeClass("is-invalid");
}

function isAlphaNumericKey(e) {
	var regex = new RegExp("^[a-zA-Z0-9 ',.-]+$");
	var str = String.fromCharCode(!e.charCode ? e.which : e.charCode);
	if (regex.test(str)) {
		return true;
	}
	e.preventDefault();
	return false;
}

function isNumberKey(evt) {
	var charCode = evt.which ? evt.which : event.keyCode;
	if (charCode > 31 && (charCode < 48 || charCode > 57)) {
		return false;
	}
	return true;
}

function isAlphaKey(e) {
	var regex = new RegExp("^[a-zA-Z ',.-]+$");
	var str = String.fromCharCode(!e.charCode ? e.which : e.charCode);
	if (regex.test(str)) {
		return true;
	}
	e.preventDefault();
	return false;
}

function formatFileSize(bytes) {
	if (isNaN(bytes) || bytes < 0) return "Invalid input";
	const UNITS = ["B", "KB", "MB", "GB"],
		FACTORS = [1, 1024, 1048576, 1024 ** 3];
	let index = 0;
	for (; bytes >= FACTORS[index + 1] && index < UNITS.length - 1; ) index++;
	const size = (bytes / FACTORS[index]).toFixed(2);
	return `${size} ${UNITS[index]}`;
}

function linkify(str) {
	var pattern = /(https?:\/\/[^\s]+)/i;
	var replacement = '<a class="text-info" target="_blank" href="$1">$1</a>';
	return str.replace(pattern, replacement);
}

function notify(text) {
	$(".notification-body").text(text);
	$(".notification").toggleClass("show hide");
	setTimeout(function () {
		$(".notification").toggleClass("show hide");
	}, 4000);
}

function uploadAlert(text, type = "") {
	var alert = $("#upload-alert");
	alert.removeClass("alert-success, alert-danger");

	"error" == type
		? (alert.html(text).addClass("alert-danger"),
		  $("#upload_file").removeClass("is-valid"))
		: alert.html(text).addClass("alert-success");

	$("#upload-form").fadeOut(function () {
		alert.fadeIn();
	});

	window.setTimeout(function () {
		alert.fadeOut(function () {
			$("#upload_file, #submit").prop("disabled", false);
			$("#submit").html("Upload");
			$("#upload-form").fadeIn();
		});
	}, 2500);
}
