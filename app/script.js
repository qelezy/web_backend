const datetimeInput = document.getElementById("datetime-input"),
      durationInput = document.getElementById("duration-input"),
      form = document.getElementById("form");

durationInput.addEventListener("input", (e) => {
    const value = e.target.value;
    const format = /^-?\d*(\.\d*)?$/;
    console.log(value);
    if (!format.test(value)) {
        e.target.value = value.slice(0, -1);
    }
});

form.addEventListener("submit", (e) => {
    e.preventDefault();
    const datetime = datetimeInput.value,
          duration = durationInput.value;
    if (!datetime || !duration) {
        alert("Заполните поля ввода");
        return;
    }
    const format = /^\d{4}-\d{2}-\d{2}T\d{2}:\d{2}$/;
    const durationNum = parseFloat(duration);
    if (!format.test(datetime) || isNaN(durationNum)) {
        alert("Введены некорректные данные");
        return;
    }
    if (durationNum <= 0) {
        alert("Длительность должна быть больше 0");
        return;
    }
    const formData = new FormData(form);
    fetch("form_handler.php", {
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