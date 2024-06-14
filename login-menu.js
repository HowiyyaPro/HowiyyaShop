document.addEventListener("DOMContentLoaded", function () {
    var loginBtn = document.getElementById("loginBtn");
    var loginMenu = document.getElementById("loginMenu");

    var loginModal = document.getElementById("loginModal");
    var registerModal = document.getElementById("registerModal");

    var openLoginModal = document.getElementById("openLoginModal");
    var openRegisterModal = document.getElementById("openRegisterModal");

    var closeLoginModal = document.getElementById("closeLoginModal");
    var closeRegisterModal = document.getElementById("closeRegisterModal");

    var switchToRegister = document.getElementById("switchToRegister");
    var switchToLogin = document.getElementById("switchToLogin");

    loginBtn.addEventListener("click", function (event) {
        event.stopPropagation();
        loginMenu.style.display = loginMenu.style.display === "block" ? "none" : "block";
    });

    openLoginModal.addEventListener("click", function (event) {
        event.stopPropagation();
        loginModal.style.display = "block";
    });

    openRegisterModal.addEventListener("click", function (event) {
        event.stopPropagation();
        registerModal.style.display = "block";
    });

    closeLoginModal.addEventListener("click", function () {
        loginModal.style.display = "none";
    });

    closeRegisterModal.addEventListener("click", function () {
        registerModal.style.display = "none";
    });

    switchToRegister.addEventListener("click", function (event) {
        event.preventDefault();
        loginModal.style.display = "none";
        registerModal.style.display = "block";
    });

    switchToLogin.addEventListener("click", function (event) {
        event.preventDefault();
        registerModal.style.display = "none";
        loginModal.style.display = "block";
    });

    // Close the modal if the user clicks outside of it
    window.onclick = function (event) {
        if (event.target === loginModal) {
            loginModal.style.display = "none";
        }
        if (event.target === registerModal) {
            registerModal.style.display = "none";
        }
    };
});
