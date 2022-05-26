
window.onload = function () {
    timedHide(document.getElementById('alert'), 10);
}

function timedHide(element, seconds) {
    if (element) {
        setTimeout(function () {
            element.style.display = 'none';
        }, seconds * 1000);
    }
}
