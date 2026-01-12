$(document).ready(function () {
    if (localStorage.getItem("session_token")) {
        window.location.href = "profile.html";
        return;
    }

    $("#loginBtn").click(function () {

        let email = $("#email").val();
        let password = $("#password").val();

        if (email === "" || password === "") {
            $("#msg").text("All fields are required");
            return;
        }

        $.ajax({
            url: "php/login.php",
            type: "POST",
            dataType: "json",
            data: {
                email: email,
                password: password
            },
            success: function (response) {
                if (response.status === "success") {
                    // store session token in localStorage
                    localStorage.setItem("session_token", response.token);

                    // redirect to profile
                    window.location.href = "profile.html";
                } else {
                    $("#msg").text(response.message);
                }
            },
            error: function () {
                $("#msg").text("Server error");
            }
        });

    });
});
