function logout() {
  // Clear session storage to log out the admin
  sessionStorage.clear();

  // Redirect to the home page
  window.location.href = "home.html";
}

// Attach the logout function to the button
document.getElementById("logoutBtn").addEventListener("click", logout);
