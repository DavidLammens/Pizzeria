"use strict";


//om datalist als een select (geeft value door ipv innertext) te laten werken, met hidden input

let options = document.querySelectorAll('option');

document.getElementById('PlaatsInput').addEventListener('input', function(e) {
    let input = document.getElementById('PlaatsInput'),
        hiddenInput = document.getElementById('PlaatsInput-hidden');
        
        hiddenInput.value = "";

    for(let i = 0; i < options.length; i++) {
        let option = options[i];

        if(option.innerText === input.value) {
            hiddenInput.value = option.getAttribute('data-value');
            break;
        }
    }
});