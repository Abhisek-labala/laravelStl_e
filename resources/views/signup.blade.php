<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Signup</title>

    <!-- Custom fonts for this template-->
    <!-- <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css"> -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
        integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="{{asset('css/sb-admin-2.min.css')}}" rel="stylesheet">
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.13.3/css/selectize.default.min.css">

</head>

<body class="bg-gradient-primary">

    <div class="container">

        <div class="card o-hidden border-0 shadow-lg my-5">
            <div class="card-body p-0">
                <!-- Nested Row within Card Body -->

                <div class="p-5">
                    <div class="text-center">
                        <h1 class="h4 text-gray-900 mb-4">Create an Account!</h1>
                    </div>
                    <form id="SignupForm" enctype="multipart/form-data" Action="" method="get">
                        @csrf
                        <div class="form-group row">
                            <div class="col-sm-6 mb-sm-0">
                                <label for="name" class="form-label">Name</label>
                                <input type="text" class="form-control" placeholder="Enter Your Name" id="name"
                                    name="name" required>
                                <div class="invalid-feedback">Please enter your name.</div>

                            </div>
                            <div class="col-sm-6">
                                <label for="email" class="form-label">Email address</label>
                                <input type="email" class="form-control form-control-user"
                                    placeholder="Enter Your Email id" id="email" name="email" required>
                                <div class="invalid-feedback">Please enter a valid email address.</div>
                            </div>
                            <div class="col-sm-6 mb-sm-0">
                                <label for="username" class="form-label">User Name</label>
                                <input type="text" class="form-control" placeholder="Enter Your Name" id="username"
                                    name="username" required>
                                <div class="invalid-feedback">Please enter User name.</div>

                            </div>
                            <div class="col-sm-6">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" class="form-control form-control-user"
                                    placeholder="Enter Your password" id="password" name="password" required>
                                <div class="invalid-feedback">Please enter a valid password.</div>
                            </div>
                            <div class="col-sm-6 mb-sm-0">
                                <label for="number" class="form-label">Phone</label>
                                <input type="text" class="form-control" placeholder="Enter Phone No" id="phone"
                                    maxlength="10" name="phone" required>
                                <div class="invalid-feedback">Please enter your phone number.</div>
                            </div>
                            <div class="col-sm-6">
                                <label for="dob" class="form-label">Date Of Birth</label>
                                <input type="date" class="form-control" id="dob" name="dob" required>
                                <div class="invalid-feedback">Please enter your date of birth.</div>
                            </div>
                            <div class="col-sm-6 mb-sm-0">
                                <label for="address" class="form-label">Address</label>
                                <textarea class="form-control" id="address" name="address" placeholder="Enter Address"
                                    maxlength="150" required></textarea>
                                <div class="invalid-feedback">Please enter your address.</div>
                            </div>

                            <div class="col-sm-6 mt-1">
                                <label for="gender">GENDER</label>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="gender" value="Male"
                                        id="flexRadioDefault1">
                                    <label class="form-check-label" for="flexRadioDefault1">MALE</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="gender" value="Female"
                                        id="flexRadioDefault2">
                                    <label class="form-check-label" for="flexRadioDefault2">FEMALE</label>
                                </div>
                            </div>
                            <div class="col-sm-6 mb-3 mt-2 mb-sm-0">
                                <label for="country" class="form-label">Country</label>
                                <select class="selectize" id="country" name="country" data-live-search="true" required>
                                    <option selected disabled value="">Select country</option>
                                </select>
                                <div class="invalid-feedback">Please select your country.</div>
                            </div>
                            <div class="col-sm-6 mb-2 mt-2">
                                <label for="state" class="form-label">State</label>
                                <select class="selectize" id="state" name="state" required>
                                    <option selected disabled value="">Select state</option>
                                </select>
                                <div class="invalid-feedback">Please select your state.</div>
                            </div>
                            <div class="row">
                            <div class="col-md-12 m-3">
                                <label for="file">Upload Image:</label><br>
                                <input type="file" id="fileInput" name="fileToUpload"><br><br>
                                <div id="imagepreview" class="col-sm-6 mt-2">
                                    <img id="imageprev" style="width:10%;height:10%;">
                                </div>
                            </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 mb-2">
                                    <label id="hobby-" name="hobbies">Hoobies :</label><br>
                                    <div class="form-check d-inline-block mr-3">
                                        <input class="form-check-input" type="checkbox" value="Cricket"
                                            id="hobby-Cricket" name="hobbies[]">
                                        <label class="form-check-label" for="checkbox1">Cricket</label>
                                    </div>
                                    <div class="form-check d-inline-block mr-3">
                                        <input class="form-check-input" type="checkbox" value="Football"
                                            id="hobby-Football" name="hobbies[]">
                                        <label class="form-check-label" for="checkbox2">Football</label>
                                    </div>
                                    <div class="form-check d-inline-block mr-3">
                                        <input class="form-check-input" type="checkbox" value="Singing"
                                            id="hobby-Singing" name="hobbies[]">
                                        <label class="form-check-label" for="checkbox3">Singing</label>
                                    </div>
                                    <div class="form-check d-inline-block mr-3">
                                        <input class="form-check-input" type="checkbox" value="Dancing"
                                            id="hobby-Dancing" name="hobbies[]">
                                        <label class="form-check-label" for="checkbox4">Dancing</label>
                                    </div>
                                    <div class="form-check d-inline-block mr-3">
                                        <input class="form-check-input" type="checkbox" value="Travelling"
                                            id="hobby-Travelling" name="hobbies[]">
                                        <label class="form-check-label" for="checkbox5">Travelling</label>
                                    </div>
                                    <div class="form-check d-inline-block">
                                        <input class="form-check-input" type="checkbox" value="IndoorGames"
                                            id="hobby-IndoorGames" name="hobbies[]">
                                        <label class="form-check-label" for="checkbox6">Indoor Games</label>
                                    </div>
                                </div>
                            </div>

                            <button class="btn btn-primary btn-user btn-block mb-2">
                                Register Account
                            </button>
                    </form>
                    <hr>
                    <div class="text-center">
                    <a class="large" href="{{ url('/') }}">Already have an account? Login!</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>

    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="{{asset('js/script.js')}}"></script>
    <!-- <script src="{{asset('js/sb-admin-2.min.js')}}"></script> -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.13.3/js/standalone/selectize.min.js"></script>
    <script>
        $(document).ready(function () {
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
        });

    </script>
</body>

</html>