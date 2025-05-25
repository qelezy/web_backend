const logoutBtn = document.getElementById("logout-btn");

logoutBtn.addEventListener("click", () => {
    fetch("/auth/logout", {
        method: "POST",
    })
    .then(response => response.json())
    .then(result => {
        if (result.success) {
            window.location.href = "/";
        } else {
            alert("Ошибка при выходе");
        }
    })
});