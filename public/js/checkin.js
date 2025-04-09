 document.addEventListener("DOMContentLoaded", function () {
    const checkEmailBtn = document.getElementById("checkEmailBtn");
    const checkEmailInput = document.getElementById("check_email");
    const formFields = document.getElementById("formFields");
    const checkFieldContainer = document.getElementById("checkField");
    const visitorFormContainer = document.getElementById("visitorFormContainer");
    const checkEmailText = document.getElementById("checkEmailText");
    const loadingSpinner = document.getElementById("loadingSpinner");

    const preRegisteredBtn = document.getElementById("preRegisteredBtn");
    const newVisitorBtn = document.getElementById("newVisitorBtn");
    const visitorTypeSelection = document.getElementById("visitorTypeSelection");

     if (preRegisteredBtn) {
         preRegisteredBtn.addEventListener("click", () => {
             visitorTypeSelection.style.display = "none";
             visitorFormContainer.style.display = "block";
             checkFieldContainer.style.display = "block";
             formFields.style.display = "none";
         });
     }

     if (newVisitorBtn) {
         newVisitorBtn.addEventListener("click", () => {
             visitorTypeSelection.style.display = "none";
             visitorFormContainer.style.display = "block";
             checkFieldContainer.style.display = "none";
             formFields.style.display = "block";
         });
     }

    checkEmailBtn.addEventListener("click", function () {
    let email = checkEmailInput.value.trim();
    if (email !== "") {
    checkEmailText.classList.add("d-none");
    loadingSpinner.classList.remove("d-none");

        fetch(checkPreRegisteredRoute, {
    method: "POST",
    headers: {
    "Content-Type": "application/json",
    "X-CSRF-TOKEN": document.querySelector('input[name="_token"]').value
},
    body: JSON.stringify({ email: email })
})
    .then(response => response.json())
    .then(data => {
    checkEmailText.classList.remove("d-none");
    loadingSpinner.classList.add("d-none");

    if (data.success) {
    let visitor = data.visitor;
    document.getElementById("visitor_id").value = visitor.id;

    // Update only visible fields with visitor data
    let fields = ["full_name", "company", "email", "phone", "id_type", "identification_number"];
    fields.forEach(field => {
    let input = document.getElementById(field);
    if (input && input.closest('.form-group') && input.closest('.form-group').style.display !== 'none') {
    input.value = visitor[field] ?? '';
}
});

    Swal.fire("Success", "Visitor details retrieved.", "success");
} else {
    Swal.fire("Not Found", "Visitor not found. Please enter details manually.", "warning");
}

    // Hide check field and show full form
    checkFieldContainer.style.display = "none";
    formFields.style.display = "block";
})
    .catch(error => {
    console.error("Error:", error);
    checkEmailText.classList.remove("d-none");
    loadingSpinner.classList.add("d-none");
    Swal.fire("Error", "An error occurred while checking. Please try again.", "error");
});
}
});

    // Disable submit button to prevent multiple submissions
    const form = document.getElementById('progressiveForm');
    form.addEventListener('submit', function (e) {
    const submitBtn = form.querySelector('button[type="submit"]');
    submitBtn.disabled = true;
    submitBtn.innerText = 'Submitting...';
});
});

    document.querySelector(".navbar").classList.add("navbar-hidden");

     document.getElementById('progressiveForm').addEventListener('submit', function(event) {
     const checkEmailField = document.getElementById('check_email');

     checkEmailField.setAttribute('novalidate', true);

     checkEmailField.removeAttribute('required');
 });
