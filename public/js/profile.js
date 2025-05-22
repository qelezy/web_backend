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

document.querySelectorAll("form[action^='/booking/cancel/']").forEach(form => {
    form.addEventListener("submit", (e) => {
        e.preventDefault();
        const actionUrl = form.getAttribute("action");
        const bookingId = actionUrl.split("/").pop();
        const formData = new FormData();
        formData.append("booking_id", bookingId);
        fetch("/booking/cancel", {
            method: "POST",
            body: formData
        })
        .then(response => response.json())
        .then(result => {
            if (result.success) {
                window.location.reload();
            }
            if (result.message) {
                alert(result.message);
            }
        });
    });
});