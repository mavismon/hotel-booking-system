<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Hotel Booking System</title>
    <link
      href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css"
      rel="stylesheet"
    />
    <link rel="stylesheet" href="style.css" />
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css"
      rel="stylesheet"
    />
    <style>
      body {
        background-color: rgb(251, 253, 243);
        height: 100vh;
        margin: 0;
        display: flex;
        justify-content: center;
        align-items: center;
        background: url("./images/login.png") no-repeat center center;
        background-size: cover;
      }

      .custom-container {
        max-width: 800px; /* Adjust the max-width as needed */
      }

      .img-rounded {
        border-radius: 15px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
      }

      h1,
      h2,
      h3 {
        font-family: "Arial", sans-serif; /* Change font-family as needed */
      }

      p.lead {
        font-size: 1.25rem; /* Adjust font size for lead paragraph */
      }

      .navbar-brand,
      .nav-link {
        font-family: "Arial", sans-serif; /* Change font-family as needed */
        color: #ffffff;
      }

      .navbar {
        background-color: rgb(207, 245, 205);
      }

      .icon-style {
        font-size: 22px;
        color: rgb(0, 0, 0);
        margin-right: 8px; /* Space between icon and text */
      }

      .brand-name {
        font-size: 20px; /* Adjust text size */
        font-weight: bold;
        color: rgb(0, 0, 0); /* Text color */
        text-transform: uppercase; /* Uppercase text for a more refined look */
        letter-spacing: 1px; /* Slight letter spacing for a modern feel */
        transition: color 0.3s ease; /* Smooth color transition */
      }

      .navbar-brand:hover .brand-name {
        color: #342f2f; /* Change text color on hover */
      }

      .fixed-size {
        width: 70%;
        height: 250px; /* Set a fixed height for all images */
        object-fit: cover; /* Ensure images cover the set height and width */
      }

      .form-container {
        width: 200%;
        max-width: 500px; /* Adjust the max-width as needed */
        padding: 20px;
        border: 1px solid #ccc;
        border-radius: 10px;
        background-color: rgba(
          251,
          253,
          243,
          0.9
        ); /* Slight transparency for background */
        box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
      }
      .custom-btn {
        transition: background-color 0.3s ease, color 0.3s ease; /* Smooth transition for background and text color */
      }

      .btn-book {
        transition: background-color 0.3s ease, color 0.3s ease;
        background-color: rgb(207, 245, 205);
        font-size: large;
        font-weight: 500;
      }

      .custom-btn:active {
        background-color: rgb(
          207,
          245,
          205
        ); /* Change background color when clicked */
        color: black !important; /* Change text color to black when clicked */
      }

      .btn-book:active {
        background-color: rgb(
          0,
          0,
          0
        ); /* Change background color when clicked */
        color: rgb(
          255,
          255,
          255
        ) !important; /* Change text color to black when clicked */
      }
    </style>
  </head>
  <body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light fixed-top">
      <a class="navbar-brand d-flex align-items-center" href="#">
        <i class="bi bi-house-door icon-style"></i>
        <span class="brand-name">Hotel Queen</span>
      </a>

      <button
        class="navbar-toggler"
        type="button"
        data-toggle="collapse"
        data-target="#navbarNav"
        aria-controls="navbarNav"
        aria-expanded="false"
        aria-label="Toggle navigation"
      >
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ml-auto">
          <li class="nav-item">
            <button id="logoutBtn" class="btn custom-btn">Logout</button>
          </li>

          <li class="nav-item">
           
            <a href="signup.html">
              <button id="signupBtn" class="btn custom-btn">Sign Up</button>
            </a>
          </li>
        </ul>
      </div>
    </nav>

    <form action="login.php" method="POST">
      <div class="form-container">
        <h2 class="text-center">Admin Log In</h2>
        <div class="form-group">
          <label for="email">Email Address</label>
          <input
            type="email"
            class="form-control"
            id="email"
            name="email"
            placeholder="Enter your email address"
            required
          />
        </div>
        <div class="form-group">
          <label for="password">Password</label>
          <input
            type="password"
            class="form-control"
            id="password"
            name="password"
            placeholder="Enter your password"
            required
          />
        </div>
        <div class="text-center">
          <button type="submit" class="btn btn-book text-black mr-2">
            Log In
          </button>
          <button type="button" class="btn btn-book text-black mr-2">
            Forgot Password
          </button>
        </div>
      </div>
    </form>

    <script src="logout.js"></script>
  </body>
</html>
