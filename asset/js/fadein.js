function fadeIn(el, display) {
    el.style.opacity = 0;
    el.style.display = display || "block";

    (function fade() {
        var val = parseFloat(el.style.opacity);
        if (!((val += .2) > 1)) {
            el.style.opacity = val;
            requestAnimationFrame(fade);
        }
    })();
}