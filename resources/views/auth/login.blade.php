<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Food Journal Login</title>

  <!-- Bootstrap 5 CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" rel="stylesheet" />

  <style>
    body {
      font-family: "Montserrat", sans-serif;
    }
    .form-control:focus {
      box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25); /* Bootstrap red highlight */
    }
  </style>
</head>
<body class="bg-white text-dark">

<div class="container border border-secondary mt-5 p-0">
  <!-- Header -->
  <header class="d-flex justify-content-center py-3 border-bottom border-secondary">
    <div class="d-flex align-items-center gap-2">
      <img src="https://storage.googleapis.com/a1aa/image/2fb9d4c2-eed7-441c-de2d-8c64b6cf73d2.jpg" alt="Food Journal logo" width="24" height="24" />
      <span class="fs-6 fw-light text-uppercase">FOOD JOURNAL</span>
    </div>
  </header>

  <!-- Main Content -->
  <div class="row g-0">
    <!-- Image Side -->
    <div class="col-md-6">
      <img src="https://storage.googleapis.com/a1aa/image/60e7d67d-2d67-4e52-9b68-5db0f7a62535.jpg"
           alt="Malaysian cuisine wrapped food on plate with cloth on wooden table"
           class="img-fluid h-100 object-fit-cover" />
    </div>

    <!-- Login Form Side -->
    <div class="col-md-6 p-5 d-flex flex-column justify-content-center">
      <h1 class="h3 fw-semibold mb-2">Welcome to FoodJournal</h1>
      <p class="mb-4 small">Your new favorite Malaysian cuisine!</p>

      <form method="POST" action="{{ route('login') }}" class="w-100" novalidate>
        @csrf

        <div class="mb-3">
          <label for="username" class="form-label small">Username</label>
          <input type="text"
                 class="form-control form-control-sm"
                 id="username"
                 name="username"
                 value="{{ old('username') }}"
                 required />
        </div>

        <div class="mb-3">
          <label for="password" class="form-label small">Password</label>
          <input type="password"
                 class="form-control form-control-sm"
                 id="password"
                 name="password"
                 required />
        </div>

        <button type="submit" class="btn btn-danger btn-sm text-uppercase fw-bold px-4 py-2">
          Log In
        </button>
      </form>

      <p class="mt-3 small">
        <a href="{{ route('password.request') }}" class="text-danger text-decoration-none">Forgot Password?</a>
      </p>

      <p class="text-center text-muted small mt-3">
        Don’t have an account?
        <a href="{{ route('register') }}" class="text-danger text-decoration-none">Sign Up</a>
      </p>

      <nav class="d-flex justify-content-center gap-4 mt-4 small text-muted">
        <a href="#" class="text-decoration-none">Terms of Service</a>
        <a href="#" class="text-decoration-none">Privacy Policy</a>
        <a href="#" class="text-decoration-none">Contact Us</a>
      </nav>
    </div>
  </div>
</div>

@if ($errors->any())
  <div>{{ $errors->first() }}</div>
@endif

<!-- Bootstrap Bundle JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
