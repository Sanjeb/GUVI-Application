$(document).ready(function () {
  $("#registerBtn").click(function () {

    let name = $("#name").val();
    let email = $("#email").val();
    let password = $("#password").val();
    let confirmPassword = $("#confirm_password").val();

    if (name === "" || email === "" || password === "" || confirmPassword === "") {
      $("#msg").text("All fields are required");
      return;
    }
    if (password !== confirmPassword) {
      $("#msg").text("Passwords do not match");
      return;
    }

    $.ajax({
      url: "php/register.php",
      type: "POST",
      data: {
        name: name,
        email: email,
        password: password
      },
      success: function (response) {
        $("#msg").text(response);

        // Clear form only if registration was successful
        if (response.trim() === "Registered successfully") {
          $("#name").val("");
          $("#email").val("");
          $("#password").val("");
          $("#confirm_password").val("");
        }
      },
      error: function (xhr) {
        if (xhr.responseText) {
          $("#msg").text(xhr.responseText);
        } else {
          $("#msg").text("Server error");
        }
      }
    });

  });
});
