$(document).ready(function () {
  $("#registerBtn").click(function () {

    let name = $("#name").val();
    let email = $("#email").val();
    let password = $("#password").val();

    if (name === "" || email === "" || password === "") {
      $("#msg").text("All fields are required");
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
      },
      error: function () {
        $("#msg").text("Server error");
      }
    });

  });
});
