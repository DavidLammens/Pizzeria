"use strict";


//melding wegklikken

document.getElementById("meldingBtn").addEventListener("click", function (elem) {
    elem.preventDefault();
    document.getElementById("melding").hidden = true;
  });