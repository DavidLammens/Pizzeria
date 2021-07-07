"use strict";


//Toggled de opties 'ik heb een account' en 'ik heb geen account'.

let togglers = document.querySelectorAll(".toggler");

togglers.forEach((element) => {
  element.addEventListener("click", function (elem) {
    elem.target.nextElementSibling.classList.toggle('hide');
  });
})


//toont extra form voor account-registratie via checkbox

document.getElementById("ookAccount").addEventListener('change', function(e) {
    document.getElementById('registratieForm').classList.toggle('hide');
});
