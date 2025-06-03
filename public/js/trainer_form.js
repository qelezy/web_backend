import { setupInputFilter } from "./input_filter.js";

const lastNameInput = document.getElementById("last-name-input"),
      firstNameInput = document.getElementById("first-name-input"),
      surnameInput = document.getElementById("surname-input"),
      phoneInput = document.getElementById("phone-input"),
      specializationInput = document.getElementById("specialization-input"),
      form = document.getElementById("form");

const lastNameFormat = /^[a-zA-Zа-яА-ЯёЁ\s]+(?:-[a-zA-Zа-яА-ЯёЁ\s]*)?$/;
const firstNameFormat = /^[a-zA-Zа-яА-ЯёЁ\s]+$/;
const surnameFormat = /^[a-zA-Zа-яА-ЯёЁ\s]*$/;
setupInputFilter(lastNameInput, lastNameFormat);
setupInputFilter(firstNameInput, firstNameFormat);
setupInputFilter(surnameInput, surnameFormat);

const specializationFormat = /^[a-zA-Zа-яА-ЯёЁ\s]+$/;
setupInputFilter(specializationInput, specializationFormat);

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
          specialization = specializationInput.value;
    if (!lastName || !firstName || !phone || !specialization) {
        alert("Заполните обязательные поля ввода");
        return;
    }
    const phoneFormat = /^\+7\(\d{3}\)\d{3}-\d{2}-\d{2}$/;
    const isDateInvalid = birthDate ? isNaN(new Date(birthDate).getDate()) : false;
    if (!lastNameFormat.test(lastName) || !firstNameFormat.test(firstName) || !surnameFormat.test(surname) || !phoneFormat.test(phone) || !specializationFormat.test(specialization) || isDateInvalid) {
        alert("Введены некорректные данные");
        return;
    }
    const formData = new FormData(form);
    fetch("/trainers/add", {
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