const resetButton = document.querySelector('input[type="reset"]');
resetButton.addEventListener("click", focusAutofocus);

function focusAutofocus() {
    document.querySelector("[autofocus]").focus();
}