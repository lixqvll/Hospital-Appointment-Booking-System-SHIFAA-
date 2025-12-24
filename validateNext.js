document.addEventListener("DOMContentLoaded", function () {
    const nextBtn = document.querySelector(".next-link");
    const validationMessage = document.getElementById("validationMessage");

    if (!nextBtn) return;

    nextBtn.addEventListener("click", function (event) {
        const radios = document.querySelectorAll('input[type="radio"]');

     
        if (radios.length === 0) return;

        const selected = document.querySelector('input[type="radio"]:checked');

        if (!selected) {
            event.preventDefault();
            validationMessage.textContent = "عليك الاختيار أولاً";
            validationMessage.style.display = "block";
        } else {
            validationMessage.style.display = "none";
        }
    });
});
