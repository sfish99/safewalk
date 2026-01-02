// Redirects the user to the walker login page
function loginAsWalker() {
  window.location.href = "includes/login_walker.php";
}

// Redirects the user to the volunteer login page
function loginAsVolunteer() {
  window.location.href = "includes/login_volunteer.php";
}

// Redirects the user to the appropriate registration page based on type
function goToRegister(type) {
  if (type === "walker"){
    window.location.href = "includes/register_walker.html";
  }
  if (type === "volunteer") {
    window.location.href = "includes/register_volunteer.html";

  }
}
