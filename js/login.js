$("#login").click(function () {
	var username = $("#username").val();
	var password = $("#password").val();
	$.ajax({
		type: "POST",
		url: "actions.php",
		data: {
			action: "login",
			user: username,
			password: password,
		},
		success: function (response) {
			switch (response) {
				case "Login Successful":
					$("#username, #password, #login").attr("disabled", "disabled");
					$("#alert").addClass("alert-success mt-3");
					$("#alert-text").addClass("fw-bold").text(response);
					$("#login").hide();
					$("#alert-div").fadeIn(500);
					window.setTimeout(function () {
						// window.location = "logs.php";
					}, 5000);
					break;
				case "Unregistered User":
					$("#username, #password, #login").attr("disabled", "disabled");
					$("#alert").addClass("alert-danger mt-3");
					$("#alert-text").addClass("fw-bold").text(response);
					$("#login").hide();
					$("#alert-div").fadeIn(500);
					window.setTimeout(function () {
						$("#alert-div").fadeOut(500);
						$("#username, #password, #login").removeAttr("disabled");
						$("#login").show();
					}, 3500);
					break;
				case "Incorrect Password!":
					$("#username, #password, #login").attr("disabled", "disabled");
					$("#alert").addClass("alert-danger mt-3");
					$("#alert-text").addClass("fw-bold").text(response);
					$("#login").hide();
					$("#alert-div").fadeIn(500);
					window.setTimeout(function () {
						$("#alert-div").fadeOut(500);
						$("#password").val("");
						$("#username, #password, #login").removeAttr("disabled");
						$("#login").show();
					}, 3500);
					break;
				case "empty form":
					$("#alert").addClass("alert-danger mt-3");
					$("#alert-text")
						.addClass("fw-bold")
						.text("Form fields cannot be empty!");
					$("#login").hide();
					$("#alert-div").fadeIn(500);
					window.setTimeout(function () {
						$("#alert-div").fadeOut(500);
						$("#login").show();
					}, 3500);
					break;
				default:
					$("#form-div").hide();
					$("#error-div").html("<pre>" + response + "</pre>");
					break;
			}
		},
	});
});