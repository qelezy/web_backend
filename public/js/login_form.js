const phoneInput = document.getElementById("phone-input"),
      passwordInput = document.getElementById("password-input"),
      form = document.getElementById("form");

const maskOptions = {
    mask: '+{7}(000)000-00-00'
};
const mask = IMask(phoneInput, maskOptions);

form.addEventListener("submit", (e) => {
    e.preventDefault();
    const phone = phoneInput.value,
          password = passwordInput.value;
    if (!phone || !password) {
        alert("Заполните обязательные поля ввода");
        return;
    }
    const phoneFormat = /^\+7\(\d{3}\)\d{3}-\d{2}-\d{2}$/;
    if (!phoneFormat.test(phone)) {
        alert("Введены некорректные данные");
        return;
    }
    const formData = new FormData(form);
    fetch("/auth/check", {
        method: "POST",
        body: formData
    })
    .then(response => response.json())
    .then(result => {
        form.reset();
        if (result.success) {
            window.location.href = result.redirect;
        } else {
            alert(result.message || "Ошибка входа");
        }
    });
});