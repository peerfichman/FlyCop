window.onload = () => {
    resetFormBtn();
};


let outputs = document.getElementsByTagName('output');
let ranges = document.querySelectorAll('input[type=range]');

function updateValues(i, val) {
    outputs[i].value = val;
};



function resetFormBtn() {

    document.getElementById("resetBtn").onclick = () => {
        for (let i = 0; i < outputs.length; i++) {
            outputs[i].value = ranges[i].min;
            ranges[i].value = ranges[i].min;
        }
    }
    return;
};


$(document).ready(function () {
    $("#standStill").click(function () {
        $("#maxDistance").attr("disabled", "disabled");
    });

    $("#patrol").click(function () {
        $("#maxDistance").removeAttr("disabled");
    });
});






