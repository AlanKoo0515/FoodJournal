<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Food Journal</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet">
    
    <style>
        body {
            font-family: "Poppins", sans-serif;
        }
    </style>
</head>
<body class="bg-white text-black">

<div class="container border border-secondary mt-4">
    <!-- Header -->
    <header class="d-flex justify-content-center align-items-center py-3 border-bottom border-secondary">
        <img src="https://storage.googleapis.com/a1aa/image/dd23b33b-7421-4ca4-1755-c85abab0ba65.jpg" 
             alt="Food Journal logo" class="me-2" width="24" height="24">
        <span class="fs-6 fst-italic">FOOD JOURNAL.</span>
    </header>

    <!-- Main Content -->
    <main class="row g-0">
        <!-- Image Section -->
        <div class="col-md-6">
            <img src="https://storage.googleapis.com/a1aa/image/52f5b17f-5265-4ea9-d3da-40bb08ace926.jpg" 
                 class="img-fluid h-100 object-fit-cover" 
                 alt="Malaysian food on banana leaves">
        </div>

        <!-- Form Section -->
        <div class="col-md-6 p-5 d-flex flex-column justify-content-center">
            <h1 class="h3 fw-semibold mb-3">Welcome to FoodJournal</h1>
            <p class="mb-4 small">Your new favorite Malaysian cuisine!</p>

            <form class="w-100" method="POST" action="#">
                @csrf
                <div class="mb-3">
                    <label for="email" class="form-label small">Email</label>
                    <input type="email" class="form-control form-control-sm" id="email" name="email">
                </div>

                <div class="mb-3">
                    <label for="new-password" class="form-label small">New Password</label>
                    <input type="password" class="form-control form-control-sm" id="new-password" name="new_password">
                </div>

                <div class="mb-3">
                    <label for="confirm-password" class="form-label small">Confirm Password</label>
                    <input type="password" class="form-control form-control-sm" id="confirm-password" name="confirm_password">
                </div>

                <button type="submit" class="btn btn-danger btn-sm text-uppercase fw-bold mt-2 px-4">Save</button>
            </form>

            <!-- Footer Links -->
            <nav class="mt-5 d-flex justify-content-end gap-3 small">
                <a href="#" class="text-decoration-none">Terms of Service</a>
                <a href="#" class="text-decoration-none">Privacy Policy</a>
                <a href="#" class="text-decoration-none">Contact Us</a>
            </nav>
        </div>
    </main>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
