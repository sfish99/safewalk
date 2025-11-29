function loginAsWalker() {
  window.location.href = "login.html?role=walker";
}

function loginAsVolunteer() {
  window.location.href = "login.html?role=volunteer";
}

function goToRegister(type) {
  if (type === "walker"){
    window.location.href = "includes/register_walker.html";
  }
  if (type === "volunteer") {
    window.location.href = "includes/register_volunteer.html";

  }
}
