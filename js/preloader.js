$(document).ready(function () {
	$("body").addClass("preloading");
	setTimeout(function () {
		showPage();
	}, 1500);
});

function showPage() {
	$(".preloader").addClass("hide");
	$("body").removeClass("preloading");
}
