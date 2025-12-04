function loginAsWalker() {
  window.location.href = "includes/login_walker.html";
}

function loginAsVolunteer() {
  window.location.href = "includes/login_volunteer.php";
}

function goToRegister(type) {
  if (type === "walker"){
    window.location.href = "includes/register_walker.html";
  }
  if (type === "volunteer") {
    window.location.href = "includes/register_volunteer.html";

  }
}
