// Bonus Amount Validation (Numbers only, max 5 digits)
    function validateBonusAmount(inputField) {
        const errorElement = document.getElementById("bonusamount-error");
        const submitBtn = document.querySelector('button[type="submit"]');
        
        // Clear previous errors
        if (errorElement) errorElement.style.display = "none";
        if (submitBtn) submitBtn.disabled = false;

        const value = inputField.value.trim();

        // Check if contains non-digit characters
        if (!/^\d*$/.test(value)) {
            if (errorElement) {
                errorElement.innerText = "Only numbers are allowed!";
                errorElement.style.display = "block";
            }
            if (submitBtn) submitBtn.disabled = true;
            inputField.value = value.replace(/[^0-9]/g, '');
            return;
        }

        // Check if exceeds 5 digits
        if (value.length > 5) {
            if (errorElement) {
                errorElement.innerText = "Bonus amount should not exceed 5 digits!";
                errorElement.style.display = "block";
            }
            if (submitBtn) submitBtn.disabled = true;
            inputField.value = value.slice(0, 5);
            return;
        }
    }

    // Initialize bonus amount validation
    document.addEventListener("DOMContentLoaded", function() {
        const bonusInput = document.getElementById("bonusamount");
        if (bonusInput) {
            bonusInput.addEventListener("input", function() {
                validateBonusAmount(this);
            });
        }
    });