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
