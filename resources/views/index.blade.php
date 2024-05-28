<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Form</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <!-- <link rel="stylesheet" href="https://cdn.datatables.net/2.0.3/css/dataTables.dataTables.css"> -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/2.0.3/css/dataTables.dataTables.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.13.3/css/selectize.default.min.css">
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
    <style>
        #regModal .modal-content {
            background-color: #87b9e8;
        }

        #regModal .modal-dialog .modal-header {
            background-color: black;
            color: white;
        }

        .btn-close {
            background-color: white;

        }

        #spreedModal .modal-content {
            background-color: #000;
            color: #fff;
        }

        #validationMessages {
            color: red;

        }

        #spreedModal .modal-dialog {
            margin-left: 15%;
        }

        #spreedModal .modal-content {
            width: 150vh;

        }

        #spreedModal .modal-body {
            overflow-x: auto;
        }

        table {
            width: 100% border-collapse: collapse;
            margin-top: 20px;
            border: 1px solid #ddd;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 2px;
            text-align: left;
        }

        @media (max-width: 768px) {
            .modal-dialog {
                width: 50%;
                /* Adjust width for smaller screens */
                max-width: none;
            }
        }

        #loadingIndicator {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 9999;
        }

        .loader {
            border: 16px solid #f3f3f3;
            border-radius: 50%;
            border-top: 16px solid blue;
            width: 120px;
            height: 120px;
            -webkit-animation: spin 2s linear infinite;
            animation: spin 2s linear infinite;
        }

        @-webkit-keyframes spin {
            0% {
                -webkit-transform: rotate(0deg);
            }

            100% {
                -webkit-transform: rotate(360deg);
            }
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }
    </style>

</head>

<body>
    <div id="loadingIndicator">
        <div class="loader"></div>
    </div>

    <div class="toast" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="toast-header">
            <img src="..." class="rounded me-2" alt="...">
            <strong class="me-auto">Bootstrap</strong>
            <small class="text-body-secondary">11 mins ago</small>
            <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
        <div class="toast-body">
            Hello, world! This is a toast message.
        </div>
    </div>
    <h1 class="text-center">Welcome to Dashboard</h1>
    <div class="container-fluid">

        <!-- Modal -->
        <div class="modal fade" id="regModal" tabindex="-1" aria-labelledby="regModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="regModalLabel">Registration form</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="registrationForm" class="needs-validation" novalidate enctype="multipart/form-data"
                            action="" method="POST">
                            @csrf
                            <input type="hidden" name="hidden" id="hiddenId">
                            <div class="mb-3">
                                <label for="name" class="form-label">Name</label>
                                <input type="text" class="form-control" placeholder="Enter Your Name" id="name"
                                    name="name" required>
                                <div class="invalid-feedback">Please enter a valid name.</div>
                            </div>
                            <div class="mb-3">
                                <label for="username" class="form-label">User Name</label>
                                <input type="text" class="form-control" placeholder="Enter Your Name" id="username"
                                    name="username" required>
                                <div class="invalid-feedback">Please enter a valid Username.</div>
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Email address</label>
                                <input type="email" class="form-control" placeholder="Enter Your Email id" id="email"
                                    name="email" required>
                                <div class="invalid-feedback">Please enter a valid email address.</div>
                            </div>
                            <div class="mb-3">
                                <label for="number" class="form-label">Phone</label>
                                <input type="text" class="form-control" placeholder="Enter Phone No" id="phone"
                                    maxlength="10" name="phone" required>
                                <div class="invalid-feedback">Please enter a valid phone number.</div>
                            </div>
                            <div class="mb-3">
                                <label for="dob" class="form-label">Date Of Birth</label>
                                <input type="date" class="form-control" id="dob" name="dob" required>
                                <div class="invalid-feedback">Please enter a valid date.</div>
                            </div>
                            <div class="mb-3">
                                <label for="address" class="form-label">Address</label>
                                <textarea class="form-control" id="address" name="address" placeholder="Enter Address"
                                    maxlength="150" required></textarea>
                                <div class="invalid-feedback">Please enter a valid address.</div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label d-block">Gender</label>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="gender" value="Male"
                                        id="flexRadioDefault1" required>
                                    <label class="form-check-label" for="flexRadioDefault1">MALE</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="gender" value="Female"
                                        id="flexRadioDefault2" required>
                                    <label class="form-check-label" for="flexRadioDefault2">FEMALE</label>
                                </div>
                                <div class="valid-feedback">Looks Good.</div>
                                <div class="invalid-feedback">Please select a gender.</div>
                            </div>
                            <div class="mb-3">
                                <label for="country" class="form-label">Country</label>
                                <select class="selectize" id="country" name="country" data-live-search="true" required>
                                    <option selected disabled value="">Select country</option>
                                </select>
                                <div class="invalid-feedback">Please select your country.</div>
                            </div>
                            <div class="mb-3">
                                <label for="state" class="form-label">State</label>
                                <select class="selectize" id="state" name="state" required>
                                    <option selected disabled value="">Select state</option>
                                    <div class="invalid-feedback">Please select your state.</div>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label id="hobby-">Hoobies :</label><br>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="Cricket" id="hobby-Cricket"
                                        name="hobbies[]">
                                    <label class="form-check-label" for="checkbox1">Cricket</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="Football" id="hobby-Football"
                                        name="hobbies[]">
                                    <label class="form-check-label" for="checkbox2">Football</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="Singing" id="hobby-Singing"
                                        name="hobbies[]">
                                    <label class="form-check-label" for="checkbox3">Singing</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="Dancing" id="hobby-Dancing"
                                        name="hobbies[]">
                                    <label class="form-check-label" for="checkbox4">Dancing</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="Travelling"
                                        id="hobby-Travelling" name="hobbies[]">
                                    <label class="form-check-label" for="checkbox5">Travelling</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="IndoorGames"
                                        id="hobby-IndoorGames" name="hobbies[]">
                                    <label class="form-check-label" for="checkbox6">Indoor Games</label>
                                </div>
                                <div class="invalid-feedback " id="feedback">Please select at least one hobby.</div>
                            </div>
                            <div class="mb-3">
                                <label for="file">Upload Image:</label><br>
                                <input type="file" id="fileInput" name="fileToUpload" required><br><br>
                                <div class="invalid-feedback">Please upload an image.</div>
                            </div>
                            <div id="imagepreview">
                                <img id="imageprev" style="width:50%;height:50%;">
                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-dark" data-bs-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-success">Submit</button>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Spreedsheet modal -->
        <div class="modal fade" id="spreedModal" tabindex="-1" aria-labelledby="spreedModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="spreedModalLabel">Upload Excel</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <a href="generate-excel">Click here</a> to Download Template.
                        <form id="uploadForm" enctype="multipart/form-data">
                            @csrf
                            <div class="m-2">
                                <label for="spreedsheetfile">Upload File</label><br>
                                <input type="file" id="spreedsheetfile" name="spreedsheetfile"><br><br>
                            </div>

                            <div id="validationMessages"></div>
                            <div id="excelTable"></div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-primary" id="preview">Preview</button><br><br>
                                <!-- <button type="button" class="btn btn-success" id="uploadButton">Upload</button> -->
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        

        <!-- Preview Modal -->
        <div class="modal fade" id="previewModal" tabindex="-1" aria-labelledby="previewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="previewModalLabel">Certificate Preview</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                
            </div>
            <div class="modal-body">
                <iframe id="previewIframe" frameborder="0" style="width: 100%; height: 500px;"></iframe>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-info dbtn">Download PDF</button>
            </div>
        </div>
    </div>
</div>


            <button type="button" class="btn btn-success mb-3 mt-3 addBtn" data-bs-toggle="modal"
                data-bs-target="#regModal">ADD
                Details</button>
            <button type="button" class="btn btn-primary m-3" data-bs-toggle="modal"
                data-bs-target="#spreedModal">Upload File</button>


            <table id="myTable" class="table table-striped table-bordered" style="width:100%">
                <thead>
                    <tr>
                        <th>SL</th>
                        <th>Id</th>
                        <th>Name</th>
                        <th>Username</th>
                        <th>Password</th>
                        <th>Email</th>
                        <th>Phone No</th>
                        <th>Date Of Birth</th>
                        <th>Address</th>
                        <th>Gender</th>
                        <th>Country</th>
                        <th>State</th>
                        <th>Hoobies</th>
                        <th>Image</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody class="regdata">

                </tbody>
            </table>
        </div>



        <script src=" {{asset('js/bootstrap.bundle.min.js')}}"></script>

        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
        <script src="https://cdn.datatables.net/2.0.3/js/dataTables.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.13.3/js/standalone/selectize.min.js">
        </script>
        <script src=" {{asset('js/script.js')}}"></script>
</body>

</html>