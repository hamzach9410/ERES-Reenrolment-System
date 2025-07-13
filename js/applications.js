document.querySelector(".menu-toggle").addEventListener("click", function () {
  document.querySelector(".sidebar").classList.toggle("active");
});
document.querySelectorAll(".sidebar a").forEach((link) => {
  link.addEventListener("click", function () {
    document.querySelector(".sidebar").classList.remove("active");
  });
});
