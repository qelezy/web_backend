const tabs = document.querySelectorAll(".tab-btn");
const contents = document.querySelectorAll(".tab-content");

tabs.forEach(btn => {
    btn.addEventListener("click", () => {
        tabs.forEach(b => b.classList.remove("active"));
        contents.forEach(c => c.style.display = "none");
        btn.classList.add("active");
        document.getElementById(btn.dataset.tab).style.display = "block";
    });
});

document.querySelectorAll("form[action^='/booking/existing/']").forEach(form => {
    form.addEventListener("submit", (e) => {
        e.preventDefault();
        const actionUrl = form.getAttribute("action");
        const scheduleId = actionUrl.split("/").pop();
        const formData = new FormData();
        formData.append("schedule_id", scheduleId);
        fetch("/booking/existing", {
            method: "POST",
            body: formData
        })
        .then(response => response.json())
        .then(result => {
            if (result.message) {
                alert(result.message);
            }
        });
    });
});

const form = document.querySelector("form[action='/booking/individual']");

form.addEventListener("submit", (e) => {
    e.preventDefault();
    const datetimeInput = form.querySelector("input[name='datetime']");
    const trainerSelect = form.querySelector("select[name='trainer_id']");

    const datetime = datetimeInput.value,
          trainerId = trainerSelect.value;
    
    if (!datetime || !trainerId) {
        alert("Заполните обязательные поля ввода");
        return;
    }
    if (isNaN(new Date(datetime).getDate())) {
        alert("Введены некорректные данные");
        return;
    }
    const formData = new FormData(form);
    fetch("/booking/individual", {
        method: "POST",
        body: formData
    })
    .then(response => response.json())
    .then(result => {
        form.reset();
        if (result.message) {
            alert(result.message);
        }
    });
});
