$(document).ready(function () {
	let scroll_btn = document.getElementById("btn-back-to-top");
	window.onscroll = function () {
		display_btn();
	};

	function display_btn() {
		if (
			document.body.scrollTop > 20 ||
			document.documentElement.scrollTop > 20
		) {
			scroll_btn.style.display = "block";
		} else {
			scroll_btn.style.display = "none";
		}
	}
	scroll_btn.addEventListener("click", backToTop);

	function backToTop() {
		document.body.scrollTop = 0;
		document.documentElement.scrollTop = 0;
	}
});
