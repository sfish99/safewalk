function loginAsWalker() {
  window.location.href = "login.html?role=walker";
}

function loginAsVolunteer() {
  window.location.href = "login.html?role=volunteer";
}

<<<<<<< HEAD
function goToRegister(type) {
  if (type === "walker"){
    window.location.href = "includes/register_walker.html";
  }
  else {
    window.location.href = "includes/register_volunteer.html";

  }
=======
function goToRegister() {
  window.location.href = "includes/register_volunteer.html";
>>>>>>> 71acbb7a74c890faa78ca4c88e3e1760f53b43bb
}
