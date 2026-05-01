import './bootstrap';
import 'bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

const evaluatePasswordStrength = (value) => ({
    length: value.length >= 8,
    uppercase: /[A-Z]/.test(value),
    lowercase: /[a-z]/.test(value),
    number: /[0-9]/.test(value),
    symbol: /[^A-Za-z0-9]/.test(value),
});

const syncPasswordToggle = (button, input) => {
    const isVisible = input.type === 'text';
    const nextLabel = isVisible ? 'Hide password' : 'Show password';

    button.setAttribute('aria-label', nextLabel);
    button.setAttribute('aria-pressed', isVisible ? 'true' : 'false');

    const label = button.querySelector('[data-password-toggle-text]');
    if (label) {
        label.textContent = nextLabel;
    }

    button.querySelectorAll('[data-password-icon]').forEach((icon) => {
        const shouldShow = icon.dataset.passwordIcon === (isVisible ? 'hide' : 'show');
        icon.classList.toggle('hidden', ! shouldShow);
    });
};

const initPasswordToggles = () => {
    document.querySelectorAll('[data-password-toggle]').forEach((button) => {
        const input = document.getElementById(button.dataset.passwordToggle);

        if (! input) {
            return;
        }

        syncPasswordToggle(button, input);

        button.addEventListener('click', () => {
            input.type = input.type === 'password' ? 'text' : 'password';
            syncPasswordToggle(button, input);
            input.focus({ preventScroll: true });
        });
    });
};

const initPasswordStrengthHints = () => {
    document.querySelectorAll('[data-password-strength]').forEach((input) => {
        const panel = input.closest('div')?.nextElementSibling;

        if (! panel || ! panel.hasAttribute('data-password-strength-panel')) {
            return;
        }

        const sync = () => {
            const checks = evaluatePasswordStrength(input.value);

            panel.querySelectorAll('[data-password-rule]').forEach((item) => {
                const rule = item.dataset.passwordRule;
                item.classList.toggle('is-met', Boolean(checks[rule]));
            });
        };

        sync();
        input.addEventListener('input', sync);
    });
};

document.addEventListener('DOMContentLoaded', () => {
    initPasswordToggles();
    initPasswordStrengthHints();
});
