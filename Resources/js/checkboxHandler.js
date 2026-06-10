	
	document.addEventListener("click", function (event) {
    // Check if the clicked element is a checkbox
    if (event.target.classList.contains("subCheckbox") || event.target.id === "mainCheckbox") {
        let mainCheckbox = document.getElementById("mainCheckbox");
        let subCheckboxes = document.querySelectorAll(".subCheckbox");

        if (!mainCheckbox) {
            console.error("Main checkbox not found! Check if the ID is correct.");
            return;
        }

        // Handle main checkbox change
        if (event.target.id === "mainCheckbox") {
            subCheckboxes.forEach(cb => cb.checked = event.target.checked);
        } 
        // Handle sub-checkbox change
        else {
            let allChecked = document.querySelectorAll(".subCheckbox:checked").length === subCheckboxes.length;
            mainCheckbox.checked = allChecked;
        }
    }
});
