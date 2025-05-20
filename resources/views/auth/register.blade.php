<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Food Journal Sign Up</title>

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" rel="stylesheet" />

  <style>
    body {
      font-family: "Montserrat", sans-serif;
    }
    .form-control:focus {
      box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25); /* Red shadow */
    }
  </style>
</head>
<body class="bg-white text-dark">

<div class="container border border-secondary mt-4 p-0">
  <!-- Header -->
  <header class="d-flex justify-content-center align-items-center py-3 border-bottom border-secondary">
    <img src="https://placehold.co/24x24/png?text=🍴" alt="Fork and spoon icon" class="me-2" width="24" height="24" />
    <h1 class="fs-6 fw-normal mb-0">FOOD JOURNAL</h1>
  </header>

  <!-- Main Content -->
  <div class="row g-0">
    <!-- Image Section -->
    <div class="col-md-6">
      <img
        src="https://placehold.co/600x400?text=Malaysian+food+wrapped+in+leaves+on+a+plate+placed+on+a+wooden+table+with+a+red+patterned+cloth+underneath"
        alt="Malaysian food wrapped in leaves"
        class="img-fluid h-100 object-fit-cover"
      />
    </div>

    <!-- Form Section -->
    <div class="col-md-6 p-5 d-flex flex-column justify-content-center">
      <h2 class="h3 fw-semibold mb-3">Welcome to FoodJournal</h2>
      <p class="mb-4 small">Your new favorite Malaysian cuisine!</p>

      <form method="POST" action="{{ route('register') }}" class="w-100" novalidate>
        @csrf

        <div class="mb-3">
          <label for="email" class="form-label small">Email</label>
          <input
            type="email"
            class="form-control form-control-sm"
            id="email"
            name="email"
            value="{{ old('email') }}"
            required
          />
        </div>

        <div class="mb-3">
          <label for="username" class="form-label small">Username</label>
          <input
            type="text"
            class="form-control form-control-sm"
            id="username"
            name="username"
            value="{{ old('username') }}"
            required
          />
        </div>

        <div class="mb-3">
          <label for="password" class="form-label small">Password</label>
          <input
            type="password"
            class="form-control form-control-sm"
            id="password"
            name="password"
            required
          />
        </div>

        <div class="mb-3">
  <label for="password_confirmation" class="form-label small">Confirm Password</label>
  <input
    type="password"
    class="form-control form-control-sm"
    id="password_confirmation"
    name="password_confirmation"
    required
  />
</div>


        <button type="submit" class="btn btn-danger btn-sm text-uppercase fw-bold px-4 py-2">
          Sign Up
        </button>
      </form>

      <p class="text-center text-muted mt-4 small">
        Already have an account?
        <a href="{{ route('login') }}" class="text-danger text-decoration-none">Log in</a>
      </p>

      <nav class="d-flex justify-content-center gap-4 mt-4 small text-muted">
        <a href="#" class="text-decoration-none">Terms of Service</a>
        <a href="#" class="text-decoration-none">Privacy Policy</a>
        <a href="#" class="text-decoration-none">Contact Us</a>
      </nav>
    </div>
  </div>
</div>

<!-- Bootstrap Bundle JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
