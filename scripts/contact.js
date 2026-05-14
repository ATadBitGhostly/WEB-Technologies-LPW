document.addEventListener("DOMContentLoaded", function () {
  const form = document.getElementById("contactForm");

  form.addEventListener("submit", function (e) {
    e.preventDefault();

    clearAlerts();

    const name = document.getElementById("name").value.trim();
    const email = document.getElementById("email").value.trim();
    const message = document.getElementById("message").value.trim();

    const errors = [];

    if (name === "") errors.push("Name is required.");
    if (email === "") errors.push("Email is required.");
    else if (!isValidEmail(email)) errors.push("Please enter a valid email address.");
    if (message === "") errors.push("Message is required.");
    else if (message.length < 20) errors.push("Message must be at least 20 characters long.");

    if (errors.length > 0) {
      showAlert(errors, "danger");
      return;
    }

    form.submit();
  });

  function isValidEmail(email) {
    return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
  }

  function showAlert(messages, type) {
    const alertDiv = document.createElement("div");
    alertDiv.className = `alert alert-${type}`;
    alertDiv.id = "formAlert";

    if (messages.length === 1) {
      alertDiv.textContent = messages[0];
    } else {
      const ul = document.createElement("ul");
      messages.forEach(msg => {
        const li = document.createElement("li");
        li.textContent = msg;
        ul.appendChild(li);
      });
      alertDiv.appendChild(ul);
    }

    form.parentNode.insertBefore(alertDiv, form);
  }

  function clearAlerts() {
    const existing = document.getElementById("formAlert");
    if (existing) existing.remove();
  }
});