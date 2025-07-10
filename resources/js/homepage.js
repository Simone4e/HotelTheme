document.addEventListener('DOMContentLoaded', () => {
    const input = document.getElementById('dateFromTo');
    window.flatpickr(input, {
        mode: 'range',
        altInput: true,
        altFormat: 'd/m/Y',
        dateFormat: 'Y-m-d',
        minDate: 'today',
        position: 'below',
        animate: true
    });
});
