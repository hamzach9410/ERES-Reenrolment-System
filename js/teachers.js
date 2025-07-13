document.querySelector(".menu-toggle").addEventListener("click", function () {
  document.querySelector(".sidebar").classList.toggle("active");
});
document.querySelectorAll(".sidebar a").forEach((link) => {
  link.addEventListener("click", function () {
    document.querySelector(".sidebar").classList.remove("active");
  });
});
document.querySelectorAll(".edit-btn").forEach((button) => {
  button.addEventListener("click", function () {
    const id = this.getAttribute("data-id");
    const form = document.getElementById("edit-form-" + id);
    if (form) {
      form.classList.toggle("active");
    } else {
      console.error("Edit form not found for ID: " + id);
    }
  });
});
