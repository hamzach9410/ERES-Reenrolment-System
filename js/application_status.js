document.addEventListener("DOMContentLoaded", function () {
  // Get the PHP-injected status from the hidden input field
  var status = document.getElementById("statusValue").value;
  var statusElement = document.querySelector(".status");
  var printButton = document.getElementById("printBtn");
  var signatureSection = document.getElementById("signatureSection");

  if (status === "Approved") {
    statusElement.textContent = "Approved";
    statusElement.style.color = "green";
    statusElement.style.display = "block";
    if (printButton) printButton.style.display = "inline-block";
    if (signatureSection) signatureSection.style.display = "block";
  } else {
    statusElement.textContent = status;
    if (printButton) printButton.style.display = "none";
    if (signatureSection) signatureSection.style.display = "none";
  }
});

function printApplication() {
  document
    .querySelectorAll("body > *:not(.container)")
    .forEach((el) => (el.style.display = "none"));
  document.querySelector(".header").style.display = "flex";
  window.print();
  document
    .querySelectorAll("body > *:not(.container)")
    .forEach((el) => (el.style.display = ""));
}
