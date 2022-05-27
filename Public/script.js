
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

var toggler = document.getElementsByClassName("caret");
var i;

for (i = 0; i < toggler.length; i++) {
    toggler[i].addEventListener("click", function () {
        this.parentElement.querySelector(".nested").classList.toggle("active");
        this.classList.toggle("caret-down");
    });
}
