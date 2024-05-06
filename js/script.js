document.addEventListener("DOMContentLoaded", function () {
  var elementsWithDataBgImage = document.querySelectorAll("[data-bg-image]");

  elementsWithDataBgImage.forEach(function (element) {
    var imageUrl = element.getAttribute("data-bg-image");

    element.addEventListener("mouseover", function () {
      element.style.backgroundImage = "url('" + imageUrl + "')";
      element.style.color = "#";
    });
    element.addEventListener("mouseout", function () {
      element.style.backgroundImage = "url('')";
    });
  });
});
