document.addEventListener("DOMContentLoaded", function() {
    const form = document.getElementById('loginForm');
    if (form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();

            const formData = new FormData(this);

            fetch('../Controllers/login.php', {
                method: 'POST',
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                if (data.status === 'success') {
                    if (data.role === 'Admin') {
                        window.location.href = "../Controllers/adminDashboard.php";
                    } else if (data.role === 'Employee') {
                        window.location.href = "../Controllers/employeeDashboard.php";
                    }
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        confirmButtonColor: '#3786D5', // Blue
                        text: data.message
                    });
                }
            })
            .catch(err => {
                Swal.fire({
                    icon: 'error',
                    title: 'Server Error',
                    confirmButtonColor: '#3786D5', // Blue
                    text: 'Something went wrong, please try again later.'
                });
            });
        });
    }
});
