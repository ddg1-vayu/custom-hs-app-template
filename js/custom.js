document.addEventListener("DOMContentLoaded", function () {
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