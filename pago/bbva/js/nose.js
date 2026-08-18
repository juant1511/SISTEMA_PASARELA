$(document).ready(function () {

    $(".InputDocumento").keydown(function () {
      $(".LabelDocumento").addClass("hayContenido")
    });

    $(".InputDocumento").keyup(function () {
      $(".LabelDocumento").removeClass("hayContenido")
    });
  });
  