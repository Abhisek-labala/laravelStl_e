$(document).ready(function () {
    fetchData();
});

let dataTable;
//fetch data from databse
function fetchData() {
    $.ajax({
        type: "GET",

        url: "getData",
        success: function (response) {
            // Destroy the existing DataTable instance if it exists
            if ($.fn.DataTable.isDataTable('#myTable')) {
                $('#myTable').DataTable().destroy();
            }

            // Initialize DataTable
            dataTable = $('#myTable').DataTable({
                data: response, // Assuming response is an array of objects
                columns: [
                    { data: null, render: function (data, type, row, meta) { return meta.row + 1; } },
                    { data: 'id', "bVisible": false },
                    { data: 'name' },
                    { data: 'username' },
                    { data: 'password', "bVisible": false },
                    { data: 'email' },
                    { data: 'phone', "bVisible": false },
                    { data: 'dob' },
                    { data: 'address', "bVisible": false },
                    { data: 'gender' },
                    { data: 'country' },
                    { data: 'state' },
                    { data: 'hobbies', "bVisible": false },
                    { data: 'image_url' },
                    {
                        data: null, render: function (data, type, row) {
                            return '<button type="button" class="btn btn-primary m-1 editBtn" data-id="' + row.id + '"><i class="fas fa-edit"></i> Edit</button>' +
                                '<button type="button" class="btn btn-danger deleteBtn" data-id="' + row.id + '"><i class="fas fa-trash-alt"></i> Delete</button>' +
                                '<button type="button" class="btn btn-dark m-1 previewBtn" data-id="' + row.id + '"><i class="fas fa-eye"></i> Preview</button>' +
                                '<button type="button" class="btn btn-info dbtn" data-id="' + row.id + '"><i class="fa-solid fa-download"></i></button>';

                        }
                    }
                ]
            });
        }
    });
}

//updating data
$(document).on('click', '.editBtn', function () {
    $('#registrationForm').attr('data-action', 'edit');
    var rowData = dataTable.row($(this).parents('tr')).data();
    var vid = $(this).data("id");
    console.log(vid);
    $('#hiddenId').val(vid);
    $('#username').val(rowData.username);
    $('#name').val(rowData.name);
    $('#email').val(rowData.email);
    $('#phone').val(rowData.phone);
    $('#dob').val(rowData.dob);
    $('#address').val(rowData.address);
    $countryname = rowData.country;
    console.log(rowData);

    // Fetch and set the country first
    fetchcountrybyid($countryname, function () {
        // Fetch states for the selected country and then set the selected state
        fetchStates(rowData.country, rowData.state);
    });

    // Set the selected value for gender
    $('input[name="gender"][value="' + rowData.gender + '"]').prop('checked', true);

    // Uncheck all checkboxes first
    $('input[type="checkbox"]').prop('checked', false);
    if (rowData.hobbies) {
        var hobbyList = rowData.hobbies.split(", ");
        hobbyList.forEach(function (hobby) {
            $('#hobby-' + hobby.trim()).prop('checked', true);
        });
    }

    var imageUrl = $('<div>').html(rowData.image_url).find('img').attr('src');
    console.log(imageUrl);

    $('#imageprev').attr('src', imageUrl);

    $('#regModal').modal('show');
});

function fetchcountrybyid(cname, callback) {
    var csrfToken = $('meta[name="csrf-token"]').attr('content');
    if (cname) {
        $.ajax({
            url: 'getCountries',
            type: 'post',
            data: {
                country_name: cname,
                _token: csrfToken
            },
            dataType: 'json',
            success: function (response) {
                $.each(response, function (index, country) {
                    $('#country')[0].selectize.addOption({ value: country.id, text: country.country_name });
                    if (country.country_name === cname) {
                        $('#country')[0].selectize.setValue(country.id);
                    }
                });
                // Call the callback function after appending country options
                if (typeof callback === 'function') {
                    callback();
                }
            },
            error: function (xhr, status, error) {
                console.error("Error retrieving country name:", error);
            }
        });
    }
}


function fetchStates(countryName, stateName) {
    var csrfToken = $('meta[name="csrf-token"]').attr('content');

    if (countryName) {
        $.ajax({
            url: 'getStates',
            type: 'POST',
            data: {
                country_name: countryName,
                _token: csrfToken
            },
            dataType: 'json',
            success: function (response) {
                $('#state')[0].selectize.clearOptions();
                $('#state')[0].selectize.addOption({ value: '', text: 'Select state' });

                $.each(response, function (index, state) {
                    $('#state')[0].selectize.addOption({ value: state.sid, text: state.state_name });
                    if (state.state_name === stateName) {
                        $('#state')[0].selectize.setValue(state.sid);

                    }
                });
            },
            error: function (xhr, status, error) {
                console.error(xhr.responseText);
            }
        });
    } else {
        $('#state')[0].selectize.clearOptions();
        $('#state')[0].selectize.setValue('');
    }
}

function updateData(id, formData) {

    $.ajax({
        url: "updateData",
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function (response) {
            // Handle success
            console.log("Data updated successfully:", response);
            $('#regModal').modal('hide');
            dataTable = fetchData();
        },
        error: function (xhr, status, error) {
            // Handle error
            console.error("Error updating data:", error);
            console.log(status);
            console.log(xhr.responseText);
        }
    });
}


$(document).on('click', '.addBtn', function () {
    $('#registrationForm').attr('data-action', 'add');
    $('#registrationForm')[0].reset();
    $('#imageprev').attr('src', '');

    $('#regModal').modal('show');

    const forms = document.querySelectorAll('.needs-validation')

    // Loop over them and prevent submission
    Array.from(forms).forEach(form => {
        form.addEventListener('submit', event => {
            if (!form.checkValidity()) {
                event.preventDefault()
                event.stopPropagation()
            }
            var selectValue = $('#country').val();
            if (!selectValue) {
                $('#country').addClass('is-invalid');
                event.preventDefault();
            } else {
                $('#country').removeClass('is-invalid');
            }

            const checkboxes = form.querySelectorAll('input[type="checkbox"]');
            console.log(checkboxes);
            const errorDiv = form.querySelector('#feedback');
            console.log(errorDiv);

            let checked = false;
            console.log(checked);
            checkboxes.forEach(function (checkbox) {
                if (checkbox.checked) {
                    checked = true;
                    console.log(checked);
                }
            });
            console.log(checked);
            if (checked) {
                errorDiv.style.display = 'none';
            } else {
                errorDiv.style.display = 'block';
            }

            form.classList.add('was-validated')
        }, false)
    })
    $("#registrationForm").removeClass("was-validated");
    var selectize = $('#country')[0].selectize;
    selectize.clear();
})

function insertData(formData) {
    $.ajax({
        url: "insertData",
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function (response) {
            // Handle success
            console.log(response);
            $('#regModal').modal('hide');

            dataTable = fetchData();
        },
        error: function (xhr, status, error) {
            // Handle error
            console.error(error);
        }
    });
}
$('#registrationForm').on('submit', function (event) {

    var form = $(this);

    if (!form[0].checkValidity()) {
        event.preventDefault();
        event.stopPropagation();
        form.addClass('was-validated');
        return;
    }

    // prevent form from submitting normally
    event.preventDefault();

    var hobbiesChecked = form.find('input[type="checkbox"]:checked').length;

    if (hobbiesChecked === 0) {
        form.find('.invalid-feedback .hobbies-feedback').css('display', 'block');
        // event.preventDefault();
        event.stopPropagation();
        console.log("no hobbies selected.");

    } else {
        form.find('.invalid-feedback .hobbies-feedback').hide();
        console.log("at least one hobby selected.");
    }

    console.log("form validated. proceeding to send data.");

    var formData = new FormData(this);
    var hobbies = [];
    form.find('input[type="checkbox"]:checked').each(function () {
        hobbies.push($(this).val());
    });
    console.log("hobbies: " + hobbies.join(', '));
    formData.append('hobbies', hobbies.join(', '));

    var action = form.attr('data-action');
    if (action === 'add') {
        insertData(formData);
    } else if (action === 'edit') {
        var id = $('#hiddenId').val('id');
        updateData(id, formData);
    }

});

//deleting data from database
$('#myTable').on('click', '.deleteBtn', function () {
    let id = $(this).data("id");
    console.log(id);
    deleteData(id);
});
//function to deletedatabse
function deleteData(id) {
    var csrfToken = $('meta[name="csrf-token"]').attr('content');

    $.ajax({
        url: 'deleteData/' + id,
        method: 'POST',
        data: {
            _token: csrfToken, id: id
        },
        success: function (response) {
            alert('Record deleted successfully.');
            // alert(response);
            fetchData();
        },
        error: function (error) {
            alert('Failed to delete record.');
            console.error(error);
        }
    });
}

$('#fileInput').on('change', function (event) {
    var input = event.target;
    var reader = new FileReader();
    reader.onload = function () {
        var preview = $('#imageprev');
        preview.attr('src', reader.result);
    }
    reader.readAsDataURL(input.files[0]);

    // Displaying file path
    var filePath = input.value;
    $('#imagePath').text("File Path: " + filePath);
});


$(document).ready(function () {

    // Function to extract CSRF token from meta tag
    function getCSRFToken() {
        return $('meta[name="csrf-token"]').attr('content');
    }

    $('#country').change(function () {
        var csrfToken = $('meta[name="csrf-token"]').attr('content');

        var countryId = $(this).val();
        if (countryId !== "") {
            $('#state')[0].selectize.clear();
            $.ajax({
                url: 'getStates',
                type: 'POST',
                data: {
                    _token: csrfToken,
                    country_id: countryId
                },
                dataType: 'json',
                success: function (response) {
                    $('#state')[0].selectize.clearOptions();
                    $.each(response, function (index, state) {
                        $('#state')[0].selectize.addOption({ value: state.sid, text: state.state_name });
                    });
                    $('#state')[0].selectize.refreshOptions(false);
                },
                error: function (xhr, status, error) {
                    console.error(xhr.responseText);
                }
            });
        } else {
            // If no country is selected, clear the state dropdown
            $('#state')[0].selectize.clearOptions();
            $('#state')[0].selectize.setValue('');
        }
    });

    // Initialize Selectize on country and state dropdowns
    $('#state').selectize({
        create: false,
        sortField: 'text'
    });
});


$(document).ready(function () {
    // Function to fetch country data via AJAX
    function fetchCountries() {
        $.ajax({
            url: 'getCountries',
            type: 'GET',
            dataType: 'json',
            success: function (response) {
                // Clear existing options
                $('#country').empty();

                // Add default option
                $('#country').append('<option selected disabled value="">Select country</option>');

                // Loop through the response and add options to select element
                $.each(response, function (index, country) {
                    // Append option with country name and set value as country id
                    $('#country').append('<option value="' + country.id + '">' + country.country_name + '</option>');
                });

                // Initialize selectize plugin after appending options
                $('#country').selectize({
                    sortField: 'text'
                });
            },
            error: function (xhr, status, error) {
                console.error('Error fetching countries:', error);
            }
        });
    }

    // Call fetchCountries function on document ready
    fetchCountries();
});
$(document).ready(function () {
    $('#SignupForm').submit(function (e) {
        e.preventDefault();

        var formData = new FormData(this);
        var csrfToken = $('meta[name="csrf-token"]').attr('content');

        // Append CSRF token to the formData object
        formData.append('_token', csrfToken);

        $.ajax({
            type: "POST",
            url: "/signup",
            data: formData,
            processData: false,
            contentType: false,
            dataType: "json",
            success: function (response) {
                console.log(response);
            },
            error: function (error) {
                console.log(error);
            }
        });
    });
});

$(document).ready(function () {
    $('#loginForm').submit(function (e) {
        e.preventDefault();

        var formData = new FormData(this);
        var csrfToken = $('meta[name="csrf-token"]').attr('content');

        // Append CSRF token to the formData object
        formData.append('_token', csrfToken);

        $.ajax({
            type: "POST",
            url: "/login",
            data: formData,
            processData: false,
            contentType: false,
            dataType: "json",
            success: function (response) {
                console.log(response);
                // Do any further actions you need here after success
            },
            error: function (error) {
                console.log(error);
                // Handle errors here
            }
        });
    });
});

$(document).ready(function () {
    $('#exampleModal').modal('show');
    $('#updateform').on('submit', function (e) {
        e.preventDefault(); // Prevent form submission
        var formData = new FormData(this);
        var csrfToken = $('meta[name="csrf-token"]').attr('content');

        // Append CSRF token to the formData object
        formData.append('_token', csrfToken);

        $.ajax({
            type: 'POST',
            url: '/update',
            data: formData,
            processData: false,
            contentType: false,
            dataType: "json",
            success: function (response) {
                window.location.href = response.redirect_url;
            },
            error: function (xhr, status, error) {
                // Handle error response
                var errorMessage = xhr.responseJSON.error || 'An error occurred.';
                // Show error message
                $('#errorMessage').text(errorMessage).show();
            }
        });
    });
});




// Create toast instance
var toast = new bootstrap.Toast(document.getElementById('toast'));

// Add event listener to the form submission
document.querySelector("form").addEventListener("submit", function (event) {
    var password = document.getElementById("password").value;
    var cpassword = document.getElementById("cpassword").value;
    if (password !== cpassword) {
        event.preventDefault();
        toast.show();
        setTimeout(function () {
            toast.hide();
        }, 3000);
    }
});
$(document).on('click', '.dbtn', function () {
    var rowData = dataTable.row($(this).parents('tr')).data();
    var requestData = {
        Name: rowData.name,
        Rollno: rowData.id,

    };
    $(document).ajaxStart(function () {
        $('#loadingIndicator').show();
    });

    $(document).ajaxStop(function () {
        $('#loadingIndicator').hide();
    });
    var csrfToken = $('meta[name="csrf-token"]').attr('content');
    $.ajax({
        type: "POST",
        url: "generate-certificate",
        data: {
            _token: csrfToken,
            requestData: requestData
        },
        dataType: "json",
        beforeSend: function () {

        },
        success: function (response) {
            console.log(response);
            var pdfContent = atob(response.pdf_content);

            var decodedPdfContent = Uint8Array.from(atob(response.pdf_content), c => c.charCodeAt(0));

            var blob = new Blob([decodedPdfContent], { type: 'application/pdf' });

            var link = document.createElement('a');
            link.href = window.URL.createObjectURL(blob);
            link.download = 'certificate.pdf';
            link.click();
        },
        error: function (xhr, status, error) {
            console.error(xhr.responseText);
        },
        complete: function () {
        }
    });
});

$(document).on('click', '.previewBtn', function () {
    var rowData = dataTable.row($(this).parents('tr')).data();
    var requestData = {
        Name: rowData.name,
        Rollno: rowData.id,
    };
    $(document).ajaxStart(function () {
        $('#loadingIndicator').show();
    });

    $(document).ajaxStop(function () {
        $('#loadingIndicator').hide();
    });

    var csrfToken = $('meta[name="csrf-token"]').attr('content');

    $.ajax({
        type: "POST",
        url: "preview",
        data: {
            _token: csrfToken,
            requestData: requestData
        },
        dataType: "json",
        beforeSend: function () {

        },
        success: function (response) {
            // Set the iframe source with the preview HTML
            $('#previewIframe').attr('srcdoc', response.html);
            // Show the modal containing the iframe
            $('#previewModal').modal('show');
            
        },
        error: function (xhr, status, error) {
            console.error(xhr.responseText);
        },
        complete: function () {
        }
    });
});


$(document).ready(function () {
// Function to handle file input change event
$('#spreedsheetfile').change(function (event) {
    console.log("File input change event triggered.");

    // Get the selected file
    var file = $(this)[0].files[0];
    console.log("Selected file:", file);

    // Perform validation
    var validationResult = validateFile(file);
    console.log("Validation result:", validationResult);

    // Display validation result
    $('#validationMessages').text(validationResult);
    $('#excelTable').empty();
});

// Function to validate the selected file
function validateFile(file) {
    if (!file) {
        return "No file selected.";
    }

    // Check file type
    var allowedTypes = ['xls', 'xlsx', 'csv'];
    var fileType = file.name.split('.').pop().toLowerCase();
    if (allowedTypes.indexOf(fileType) === -1) {
        return "Invalid file type. Only Excel (XLS, XLSX) and CSV files are allowed.";
    }

    // Check file size (example: limit to 5MB)
    var maxSize = 1 * 1024 * 1024; // 1MB in bytes
    if (file.size > maxSize) {
        return "File size exceeds the limit. Maximum size allowed is 1MB.";
    }

    // If all validations pass
    return "File is valid.";
}

// Function to handle button click for form submission
$('#preview').click(function (event) {
    // Get the selected file
    var file = $('#spreedsheetfile')[0].files[0];
    console.log("Selected file:", file);

    // Perform validation
    var validationResult = validateFile(file);
    console.log("Validation result:", validationResult);

    // If file is valid, proceed with form submission via AJAX
    if (validationResult === "File is valid.") {
        var formData = new FormData($('#uploadForm')[0]);
        console.log(formData);

        $.ajax({
            url: 'excelValidation',
            type: 'post',
            data: formData,
            processData: false,
            contentType: false,
            success: function (response) {
                // Parse JSON response
                // var response = JSON.parse(response);
                console.log(response);

                // Construct human-readable format
                var message = response.message + "\n";
                if (response.errors) {
                    message += "Errors:\n";
                    response.errors.forEach(function (error) {
                        message += "- " + error + "\n";
                    });
                }
                if (response.emptyCells) {
                    message += "Empty Cells:\n";
                    response.emptyCells.forEach(function (emptyCell) {
                        message += "- " + emptyCell + "\n";
                    });
                }

                $('#validationMessages').text(message);

                if (!response.errors && !response.emptyCells) {
                    if (response.tableHTML) {
                        $('#excelTable').html(response.tableHTML);
                    }
                }
            },
            error: function (xhr, status, error) {
                alert("Error occurred while uploading file. Please try again later.");
                console.error(xhr.responseText);
            }
        });
    } else {
        // Display validation result if file is invalid
        $('#validationMessages').text(validationResult);
        console.error("Validation error:", validationResult); // Log validation error
    }

    // Prevent default form submission behavior
    return false;
});
$('#spreedsheetfile').focus(function () {
    $('#validationMessages').empty();
    $('#excelTable').empty();

});
$(document).on('click', '#uploadButton', function (e) {
    var file = $('#spreedsheetfile')[0].files[0];
    console.log("Selected file:", file);

    // Perform validation
    var validationResult = validateFile(file);
    console.log("Validation result:", validationResult);

    // If file is valid, proceed with form submission via AJAX
    if (validationResult === "File is valid.") {
        var formData = new FormData($('#uploadForm')[0]);
        console.log(formData);
        $.ajax({
            url: 'excelUpload',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function (response) {
                // Parse JSON response
                // var response = JSON.parse(response);
                console.log(response);
                $('#validationMessages').text(response);
                window.location.href = "index.php";
            },
            error: function (jqXHR, textStatus, error) {
                console.log("AJAX Error:", error);
                var Errors =JSON.parse(error);
                console.log(Errors);
                $('#validationMessages').text(Errors);
                
            }
        });
    }
});
});    

    