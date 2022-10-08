window.onload = () => {
    checkBeforeDelete();
};

function checkBeforeDelete() {
    document.getElementById("check").onclick = () => {
        let agree = confirm("Are you sure you want to delete?");
        if(agree)
            return true;
        else
            return false;
    }
};