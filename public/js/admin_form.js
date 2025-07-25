import { setupInputFilter } from "./input_filter.js";

const lastNameInput = document.getElementById("last-name-input"),
      firstNameInput = document.getElementById("first-name-input"),
      surnameInput = document.getElementById("surname-input"),
      phoneInput = document.getElementById("phone-input"),
      passwordInput = document.getElementById("password-input"),
      form = document.getElementById("form");

const lastNameFormat = /^[a-zA-Zа-яА-ЯёЁ\s]+(?:-[a-zA-Zа-яА-ЯёЁ\s]*)?$/;
const firstNameFormat = /^[a-zA-Zа-яА-ЯёЁ\s]+$/;
const surnameFormat = /^[a-zA-Zа-яА-ЯёЁ\s]*$/;
setupInputFilter(lastNameInput, lastNameFormat);
setupInputFilter(firstNameInput, firstNameFormat);
setupInputFilter(surnameInput, surnameFormat);

const maskOptions = {
    mask: '+{7}(000)000-00-00'
};
const mask = IMask(phoneInput, maskOptions);

form.addEventListener("submit", (e) => {
    e.preventDefault();
    const lastName = lastNameInput.value,
          firstName = firstNameInput.value,
          surname = surnameInput.value,
          phone = phoneInput.value,
          password = passwordInput.value;
    if (!lastName || !firstName || !phone || !password) {
        alert("Заполните обязательные поля ввода");
        return;
    }
    const phoneFormat = /^\+7\(\d{3}\)\d{3}-\d{2}-\d{2}$/;
    if (!lastNameFormat.test(lastName) || !firstNameFormat.test(firstName) || !surnameFormat.test(surname) || !phoneFormat.test(phone)) {
        alert("Введены некорректные данные");
        return;
    }
    const formData = new FormData(form);
    fetch("/admins/add", {
        method: "POST",
        body: formData
    })
    .then(response => console.log(response.text()))
    .then(result => {
        form.reset();
        if (result.message) {
            alert(result.message);
        }
    });
});