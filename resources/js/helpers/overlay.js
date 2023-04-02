function overlay() {
    let overlay = document.getElementById('overlay');
    // check if overlay is already displayed
    if (overlay.style.display === 'flex') {
        // if yes, hide it
        overlay.style.display = 'none';
    } else {
        // if not, display it
        overlay.style.display = 'flex';
    }
}

export default overlay;
