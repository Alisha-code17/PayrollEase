$(document).ready(function() {
    // Event delegation for allowance change
    $(document).on('change', '.allowance-select', function() {
        var selectedOption = $(this).find("option:selected");
        var amount = selectedOption.data("amount");
        var allowanceAmountInput = $(this).closest(".row").find(".allowance-amount");
        allowanceAmountInput.val(amount);
    });
    // Event delegation for deduction change
    $(document).on('change', '.deduction-select', function() {
        var selectedOption = $(this).find("option:selected");
        var amount = selectedOption.data("amount");
        var deductionAmountInput = $(this).closest(".row").find("input[type='text']");
        deductionAmountInput.val(amount);
    });
});

$(document).ready(function() {
    //ALLOWANCE LOGIC
    $(document).on('change', '.allowance-select', function() {
        let selectedAllowances = [];
        // Collect all selected allowance values
        $('.allowance-select').each(function() {
            const val = $(this).val();
            if (val !== "Select") {
                selectedAllowances.push(val);
            }
        });
        // Enable all options first
        $('.allowance-select option').prop('disabled', false);
        // Disable selected values in other dropdowns
        $('.allowance-select').each(function() {
            const currentSelect = $(this);
            selectedAllowances.forEach(function(val) {
                if (currentSelect.val() !== val) {
                    currentSelect.find('option[value="' + val + '"]').prop('disabled', true);
                }
            });
        });
    });
    // DEDUCTION LOGIC
    $(document).on('change', '.deduction-select', function() {
        let selectedDeductions = [];
        // Collect all selected deduction values
        $('.deduction-select').each(function() {
            const val = $(this).val();
            if (val !== "Select") {
                selectedDeductions.push(val);
            }
        });
        // Enable all options first
        $('.deduction-select option').prop('disabled', false);
        // Disable selected values in other dropdowns
        $('.deduction-select').each(function() {
            const currentSelect = $(this);
            selectedDeductions.forEach(function(val) {
                if (currentSelect.val() !== val) {
                    currentSelect.find('option[value="' + val + '"]').prop('disabled', true);
                }
            });
        });
    });
});


document.addEventListener("submit", function(e) {
    if (e.target && e.target.id === "payroll-profile-form") {
        e.preventDefault();

        const form = e.target;
        const formData = new FormData(form);

        fetch("payroll_profile.php", {
            method: "POST",
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === "success") {
                Swal.fire({
                    icon: "success",
                    title: "Success!",
                    text: "Payroll Profile Created. ID: " + data.payrollProfileId,
                    showConfirmButton: false,
                    timer: 3000
                });
                form.reset();
            } else {
                Swal.fire({
                    icon: "error",
                    title: "Error!",
                    text: data.message || "Something went wrong."
                });
            }
        })
        .catch(error => {
            console.error("Error:", error);
            Swal.fire({
                icon: "error",
                title: "Error!",
                text: "Failed to save. Try again later."
            });
        });
    }
});
