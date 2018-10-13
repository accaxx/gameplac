$(() => {
    const
        box = document.getElementsByClassName('box');
    box.on('click', e => {
        alert('clicked');
    })
});