document.addEventListener("DOMContentLoaded", function() {
    document.addEventListener('wpcf7invalid', (event) => {
        document.querySelector(".wpcf7-response-output").classList.add("alert", "alert-danger");
    });
    document.addEventListener('wpcf7spam', (event) => {
        document.querySelector(".wpcf7-response-output").classList.add("alert", "alert-warning");
    });
    document.addEventListener('wpcf7mailfailed', (event) => {
        const el = document.querySelector(".wpcf7-response-output");
        el.classList.add("alert", "alert-warning");

        if(el.classList.contains("wpcf7-display-none") && el.classList.contains("wpcf7-acceptance-missing")) {
            const acceptanceMissing = document.createElement("div");
            acceptanceMissing.innerHTML = "wpcf7-acceptance-missing";
            el.appendChild(acceptanceMissing);
        }
    });
    document.addEventListener('wpcf7mailsent', (event) => {
        document.querySelector(".wpcf7-response-output").classList.add("alert", "alert-success");
    });
    const checkboxesWithClassAgree = document.getElementsByClassName("agree");
    for (const checkbox of checkboxesWithClassAgree) {
        checkbox.checked = false;
    }
});