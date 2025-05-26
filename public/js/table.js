const addBtn = document.getElementById("add-btn"),
      reportBtn = document.getElementById("report-btn");

addBtn.addEventListener("click", () => {
    if (window.location.pathname.endsWith("/users")) {
        window.location.href = "admins/add";
    } else {
        window.location.href = window.location.pathname + "/add";
    }
});

reportBtn.addEventListener("click", () => {
    const tableName = window.location.pathname.split("/").pop();
    const formData = new FormData();
    formData.append("name", tableName);
    fetch("/report", {
        method: "POST",
        body: formData
    })
    .then(response => response.blob())
    .then(blob => {
        const link = document.createElement("a");
        link.href = URL.createObjectURL(blob);
        link.download = `${tableName}.pdf`;
        document.body.appendChild(link);
        link.click();
        link.remove();
        URL.revokeObjectURL(link.href);
    })
});