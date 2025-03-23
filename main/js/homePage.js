document.addEventListener("DOMContentLoaded", () => {
    // Sidebar toggle function
    window.toggleMenu = () => {
      const sidebar = document.getElementById("sidebar");
      if (sidebar.style.left === "0px") {
          sidebar.style.left = "-250px"; // Hide menu
      } else {
          sidebar.style.left = "0px"; // Show menu
      }
  };
});

var slideIndex = 1;
showDivs(slideIndex);

function plusDivs(n) {
  showDivs(slideIndex += n);
}

function showDivs(n) {
  var i;
  var x = document.getElementsByClassName("slidingImage");
  if (n > x.length) {slideIndex = 1}
  if (n < 1) {slideIndex = x.length} ;
  for (i = 0; i < x.length; i++) {
    x[i].style.display = "none";
  }
  x[slideIndex-1].style.display = "block";
}