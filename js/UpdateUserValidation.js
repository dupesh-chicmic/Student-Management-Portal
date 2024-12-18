$(document).ready(function () {
    $("#updateForm").validate({
        rules: {
            image: {
                extension: "png|jpe?g"
            },
            first_name: {
                required: true,
                pattern: /^[A-Za-z]+$/
            },
            last_name: {
                required: true,
                pattern: /^[A-Za-z]+$/
            },
            email: {
                required: true,
                email: true
            },
            phone: {
                required: true,
                digits: true,
                minlength: 10,
                maxlength: 10
            },
            dob: {
                required: true,
                date: true
            }
        },
        messages: {
            image: {
                extension: "Only PNG, JPG, and JPEG files are allowed."
            },
            first_name: {
                required: "First Name is required.",
                pattern: "Only alphabets are allowed in First Name."
            },
            last_name: {
                required: "Last Name is required.",
                pattern: "Only alphabets are allowed in Last Name."
            },
            email: {
                required: "Email is required.",
                email: "Enter a valid email address."
            },
            phone: {
                required: "Phone number is required.",
                digits: "Only digits are allowed in Phone number.",
                minlength: "Phone number must be exactly 10 digits.",
                maxlength: "Phone number must be exactly 10 digits."
            },
            dob: {
                required: "Date of Birth is required.",
                date: "Enter a valid date."
            }
        },
        submitHandler: function (form) {
            const formData = new FormData(form);

            $.ajax({
                url: "../controller/UpdateStudent.php",
                type: "POST",
                data: formData,
                processData: false,
                contentType: false,
                success: function (response) {
                    const data = JSON.parse(response);
                    if (data.status === 'success') {
                        alert(data.message);
                        window.location.href = "../view/view.php"; // Redirect to the view page
                    } else {
                        alert("Error: " + data.message);
                    }
                },
                error: function () {
                    alert("Failed to submit form. Please try again.");
                }
            });
        }
    });
});
