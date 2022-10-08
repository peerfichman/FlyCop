function getBtnId() {
    let aKeyValue = window.location.search.substring(1).split("&");
    let btnId = aKeyValue[0].split("=")[1];
    return btnId;
};


function clickBtn() {
    let btn = getBtnId();
    document.getElementById(btn).click();
};


function showMissionSC(missionSC) {

    let scDiv = document.getElementById("missSC");
    let idx = 1;
    for (const sc of missionSC.shortcuts) {
        let btn = document.createElement('button');
        btn.className = "btn btn-secondary btn-res";
        btn.id = "btn" + idx;
        btn.innerHTML = sc["missionName"];
        let mission = document.querySelectorAll('input[type=radio]')
        btn.onclick = () => {

            if (mission[0].value == sc["type"]) {
                mission[0].checked = 'cheked';
                ranges[2].disabled = false;

            }
            else {
                mission[1].checked = 'cheked';
                ranges[2].disabled = true;
            }
            ranges[0].value = outputs[0].value = sc["duration"];
            ranges[1].value = outputs[1].value = sc["maxAltitude"];
            ranges[2].value = outputs[2].value = sc["maxDistance"];

        }
        scDiv.appendChild(btn);
        idx++;
    }

    if (getBtnId()) {
        clickBtn();
    }
};


fetch("json/missonshortcuts.json")
    .then(response => response.json())
    .then(data => showMissionSC(data));


