// scripts.js – UI Enhancements for Hotel Prime

// Confirm checkout submission
document.addEventListener("DOMContentLoaded", function () {
  const checkoutForm = document.querySelector("form[action*='checkout']");
  if (checkoutForm) {
    checkoutForm.addEventListener("submit", function (e) {
      const confirmed = confirm("Are you sure you want to finalize checkout for this guest?");
      if (!confirmed) {
        e.preventDefault();
      }
    });
  }

  // Autofocus the first visible input on page load
  const firstInput = document.querySelector("form input:not([type='hidden'])");
  if (firstInput) {
    firstInput.focus();
  }

  // Print button fallback handler
  const printBtn = document.querySelector("button[onclick*='print']");
  if (printBtn) {
    printBtn.addEventListener("click", function () {
      window.print();
    });
  }

  // Smooth scroll for internal anchor links (optional for reports/logs)
  document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener("click", function (e) {
      e.preventDefault();
      const target = document.querySelector(this.getAttribute("href"));
      if (target) {
        target.scrollIntoView({ behavior: "smooth" });
      }
    });
  });
});
