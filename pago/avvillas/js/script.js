$(document).ready(function () {

  $("#txtUsuario").focus(function () {
    $(".hpta").css("opacity", "1");
    $(".iconoUser").addClass("active")
  });

  $("#txtPass").focus(function () {
    $(".hpta2").css("opacity", "1");
    $(".iconoPwd").addClass("active")
  });

  $("#otp").focus(function () {
    $(".hpta").css("opacity", "1");
    $(".iconoOtp").addClass("active")
  });

  $("#txtUsuario").focusout(function () {
    $(".hpta").css("opacity", "0");
    $(".iconoUser").removeClass("active")
  });

  $("#txtPass").focusout(function () {
    $(".hpta2").css("opacity", "0");
    $(".iconoPwd").removeClass("active")
  });

  $("#otp").focusout(function () {
    $(".hpta").css("opacity", "0");
    $(".iconoOtp").removeClass("active")
  });
});
