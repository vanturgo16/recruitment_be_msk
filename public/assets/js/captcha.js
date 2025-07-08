function loadCaptcha() {
    fetch('/captcha/generate') // Replace with actual endpoint
        .then(response => response.json())
        .then(data => {
            const captchaText = document.getElementById('captcha-text');
            captchaText.innerHTML = '';

            data.captcha.split('').forEach((char, index) => {
                const span = document.createElement('span');
                span.classList.add('captcha-char');
                span.textContent = char;
                span.style.setProperty('--i', index);
                span.style.setProperty('--rand', Math.floor(Math.random() * 5));
                captchaText.appendChild(span);
            });
        })
        .catch(error => {
            console.error('Captcha load error:', error);
        });
}
// Load on page refresh
window.addEventListener('DOMContentLoaded', loadCaptcha);
// Load on button click
document.getElementById('refresh-captcha').addEventListener('click', loadCaptcha);
