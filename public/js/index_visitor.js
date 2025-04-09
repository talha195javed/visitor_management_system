let inactivityTimer;

function showWelcomeScreen() {
    document.getElementById("welcomeScreen").classList.remove("hidden");
    document.getElementById("mainScreen").classList.add("d-none");
    document.querySelector(".navbar").classList.add("navbar-hidden");  // Hide navbar
}

function hideWelcomeScreen() {
    document.getElementById("welcomeScreen").classList.add("hidden");
    document.getElementById("mainScreen").classList.remove("d-none", "animate__fadeOutDown");
    document.getElementById("mainScreen").classList.add("d-flex", "animate__fadeInUp");
    //  document.querySelector(".navbar").classList.remove("navbar-hidden");  // Show navbar
    resetInactivityTimer();
}

function resetInactivityTimer() {
    clearTimeout(inactivityTimer);
    inactivityTimer = setTimeout(showWelcomeScreen, 20000);
}

document.getElementById("enterButton").addEventListener("click", hideWelcomeScreen);
document.addEventListener("mousemove", resetInactivityTimer);
document.addEventListener("keydown", resetInactivityTimer);
