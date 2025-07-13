// Mobile Menu Toggle
document.querySelector(".menu-toggle").addEventListener("click", function () {
  document.querySelector(".sidebar").classList.toggle("active");
});

// Close Sidebar on Link Click (Mobile)
document.querySelectorAll(".sidebar a").forEach((link) => {
  link.addEventListener("click", function () {
    document.querySelector(".sidebar").classList.remove("active");
  });
});
