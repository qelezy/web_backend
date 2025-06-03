const trainerIdInput = document.getElementById("trainer-input"),
      datetimeInput = document.getElementById("datetime-input"),
      typeInput = document.getElementById("type-input"),
      form = document.getElementById("form");

form.addEventListener("submit", (e) => {
    e.preventDefault();
    const trainerId = trainerIdInput.value,
          datetime = datetimeInput.value,
          type = typeInput.value;
    if (!trainerId || !datetime || !type) {
        alert("Заполните обязательные поля ввода");
        return;
    }
    if (isNaN(new Date(datetime).getDate())) {
        alert("Введены некорректные данные");
        return;
    }
    const formData = new FormData(form);
    fetch("/schedules/add", {
        method: "POST",
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        form.reset();
        if (data.message) {
            alert(data.message);
        }
    });
});