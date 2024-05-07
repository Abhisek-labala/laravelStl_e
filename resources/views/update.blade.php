<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Update password</title>
  <!-- Bootstrap CSS -->
  <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
  <style>
  /* Custom styles */
  body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;
  }
  .modal-content {
    background-color: #fff;
    border-radius: 10px;
    animation: slideIn 0.5s forwards;
    transform: translateY(-100%);
  }
  @keyframes slideIn {
    from {
      transform: translateY(-100%);
    }
    to {
      transform: translateY(0);
    }
  }
  .modal-header {
    border-bottom: none;
  }
  .modal-title {
    color: #333;
  }
  .modal-body {
    padding: 20px;
  }
  .modal-footer {
    border-top: none;
  }
  .btn-primary {
    background-color: #007bff;
    border-color: #007bff;
  }
  .btn-primary:hover {
    background-color: #0056b3;
    border-color: #0056b3;
  }
  @keyframes slideInRight {
    from {
      transform: translateX(100%);
    }
    to {
      transform: translateX(0);
    }
  }
  @keyframes slideOutRight {
    from {
      transform: translateX(0);
    }
    to {
      transform: translateX(100%);
    }
  }
  .toast {
    position: fixed;
    top: 1rem;
    right: 1rem;
    animation: slideInRight 0.5s ease-in-out;
  }
  .toast-header  {
    position: relative;
    z-index: 1;
    background-color: #dc3545;
    color: #fff;
    font-weight: bold;
  }
  .toast-body {
    color: #000;
  }
  .bg-gradient-primary {
    background-color: #4e73df;
    background-image: linear-gradient(180deg, #4e73df 10%, #224abe 100%);
    background-size: cover;
  }
</style>
</head>
<body class="bg-gradient-primary">
@if(isset($_GET['username']))
<div role="alert" id="toast" aria-live="assertive" aria-atomic="true" class="toast" data-bs-autohide="false">
    <div class="toast-header">
      <strong class="me-auto">Password Mismatch</strong>
    </div>
    <div class="toast-body">
      The passwords you entered do not match. Please try again.
    </div>
</div>
@endif
<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Update Password</h1>
      </div>
      <div class="modal-body">
        <form id="updateform"action="{{ route('update', ['username' => request()->get('username')]) }}" method="POST">
            @csrf
            <div class="mb-3">
              <label for="password" class="form-label">New Password</label>
              <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <div class="mb-3">
              <label for="cpassword" class="form-label">Confirm Password</label>
              <input type="password" class="form-control" id="cpassword" name="cpassword" required>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" href="{{ url('/') }}">Close</button>
              <button type="submit" class="btn btn-primary">Save changes</button>
            </div>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Bootstrap JS and custom script -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script src="{{asset('js/script.js')}}"></script>
</body>
</html>
