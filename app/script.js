const datetimeInput = document.getElementById("datetime-input"),
      durationInput = document.getElementById("duration-input"),
      form = document.getElementById("form");

form.addEventListener("submit", (e) => {
    e.preventDefault();
    const datetime = datetimeInput.value,
          duration = durationInput.value;
    let message = "";
    if (datetime && duration) {
        const format = /^\d{4}-\d{2}-\d{2}T\d{2}:\d{2}$/;
        const durationNum = parseFloat(duration);
        if (format.test(datetime) && !isNaN(durationNum)) {
            if (durationNum <= 0) {
                message = "Длительность должна быть больше 0";
            } else {
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
            }
        } else {
            message = "Введены некорректные данные";
        }
    } else {
        message = "Заполните поля ввода";
    }
    if (message){
        alert(message);
    }
});