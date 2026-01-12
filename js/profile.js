$(document).ready(function () {

  let token = localStorage.getItem("session_token");

  // If no token, force login
  if (!token) {
    window.location.href = "login.html";
    return;
  }

  // Load profile data
  $.ajax({
    url: "php/profile.php",
    type: "POST",
    dataType: "json",
    data: {
      action: "fetch",
      token: token
    },
    success: function (response) {
      if (response.status === "success") {
        $("#age").val(response.data.age);
        $("#dob").val(response.data.dob);
        $("#contact").val(response.data.contact);
      } else {
        alert("Session expired. Login again.");
        localStorage.removeItem("session_token");
        window.location.href = "login.html";
      }
    }
  });

  // Update profile
  $("#saveBtn").click(function () {

    let age = $("#age").val();
    let dob = $("#dob").val();
    let contact = $("#contact").val();

    $.ajax({
      url: "php/profile.php",
      type: "POST",
      dataType: "json",
      data: {
        action: "update",
        token: token,
        age: age,
        dob: dob,
        contact: contact
      },
      success: function (response) {
        $("#msg").text(response.message);
      },
      error: function () {
        $("#msg").text("Server error");
      }
    });

  });

  $("#logoutBtn").click(function () {
    $.ajax({
      url: "php/profile.php",
      type: "POST",
      dataType: "json",
      data: {
        action: "logout",
        token: localStorage.getItem("session_token")
      },
      success: function () {
        localStorage.removeItem("session_token");
        window.location.href = "login.html";
      }
    });
  });

});
