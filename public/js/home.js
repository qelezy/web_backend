const bookBtn = document.getElementById("book-btn"),
      profileBtn = document.getElementById("profile-btn");

bookBtn.addEventListener("click", () => {
    window.location.href = "/book-redirect";
});

profileBtn.addEventListener("click", () => {
    window.location.href = "/profile-redirect";
});