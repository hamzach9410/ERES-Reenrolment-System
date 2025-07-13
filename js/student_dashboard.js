// Existing Message Fade-Out Script
setTimeout(function () {
  var message = document.getElementById("message");
  if (message) {
    message.style.opacity = "0";
  }
}, 1000); // Disappear after 1 seconds

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
