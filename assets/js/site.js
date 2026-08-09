(() => {
  'use strict';

  const header = document.querySelector('[data-header]');
  const menu = document.querySelector('[data-menu]');
  const menuToggle = document.querySelector('[data-menu-toggle]');

  const updateHeader = () => header?.classList.toggle('is-sticky', window.scrollY > 24 || !document.body.classList.contains('home'));
  updateHeader();
  window.addEventListener('scroll', updateHeader, { passive: true });

  const closeMenu = () => {
    menuToggle?.setAttribute('aria-expanded', 'false');
    menu?.classList.remove('is-open');
    document.body.classList.remove('menu-open');
  };

  menuToggle?.addEventListener('click', () => {
    const open = menuToggle.getAttribute('aria-expanded') !== 'true';
    menuToggle.setAttribute('aria-expanded', String(open));
    menu?.classList.toggle('is-open', open);
    document.body.classList.toggle('menu-open', open);
  });
  menu?.querySelectorAll('a').forEach((link) => link.addEventListener('click', () => {
    closeMenu();
  }));
  document.addEventListener('keydown', (event) => {
    if (event.key === 'Escape' && menu?.classList.contains('is-open')) {
      closeMenu();
      menuToggle?.focus();
    }
  });

  const cookiePanel = document.querySelector('[data-cookie-panel]');
  const cookieDetails = document.querySelector('[data-cookie-details]');
  const cookieSave = document.querySelector('[data-cookie-save]');
  const consentKey = 'webstas_cookie_consent_v1';
  const readConsent = () => {
    try { return JSON.parse(localStorage.getItem(consentKey) || 'null'); } catch (_) { return null; }
  };
  const writeConsent = (analytics, marketing) => {
    try { localStorage.setItem(consentKey, JSON.stringify({ necessary: true, analytics, marketing, updated: new Date().toISOString() })); } catch (_) { /* Choice remains active for this page view. */ }
    cookiePanel.hidden = true;
    document.dispatchEvent(new CustomEvent('webstas:consent', { detail: { analytics, marketing } }));
  };
  if (cookiePanel && !readConsent()) cookiePanel.hidden = false;
  document.querySelector('[data-cookie-accept]')?.addEventListener('click', () => writeConsent(true, true));
  document.querySelector('[data-cookie-reject]')?.addEventListener('click', () => writeConsent(false, false));
  document.querySelector('[data-cookie-customize]')?.addEventListener('click', () => {
    cookieDetails.hidden = false;
    cookieSave.hidden = false;
  });
  cookieSave?.addEventListener('click', () => {
    writeConsent(Boolean(document.querySelector('[data-cookie-category="analytics"]')?.checked), Boolean(document.querySelector('[data-cookie-category="marketing"]')?.checked));
  });
  document.querySelectorAll('[data-cookie-settings]').forEach((button) => button.addEventListener('click', () => {
    const saved = readConsent();
    const analytics = document.querySelector('[data-cookie-category="analytics"]');
    const marketing = document.querySelector('[data-cookie-category="marketing"]');
    if (analytics) analytics.checked = Boolean(saved?.analytics);
    if (marketing) marketing.checked = Boolean(saved?.marketing);
    cookiePanel.hidden = false;
    cookieDetails.hidden = false;
    cookieSave.hidden = false;
  }));

  const sha256 = async (value) => {
    const bytes = new TextEncoder().encode(value);
    const digest = await crypto.subtle.digest('SHA-256', bytes);
    return Array.from(new Uint8Array(digest), (byte) => byte.toString(16).padStart(2, '0')).join('');
  };

  const solveAltcha = async (box) => {
    const button = box.querySelector('[data-altcha-button]');
    const label = box.querySelector('[data-altcha-label]');
    const payloadField = box.querySelector('[data-altcha-payload]');
    button.disabled = true;
    box.classList.remove('is-verified');
    box.classList.add('is-solving');
    label.textContent = 'Выполняется проверка…';
    try {
      const response = await fetch('/api/challenge.php', { credentials: 'same-origin', headers: { Accept: 'application/json' } });
      const challenge = await response.json();
      if (!response.ok || !challenge.challenge) throw new Error(challenge.error || 'Challenge error');
      let solution = -1;
      for (let number = 0; number <= challenge.maxnumber; number += 1) {
        if (await sha256(challenge.salt + number) === challenge.challenge) { solution = number; break; }
      }
      if (solution < 0) throw new Error('No solution');
      const payload = { algorithm: challenge.algorithm, challenge: challenge.challenge, number: solution, salt: challenge.salt, signature: challenge.signature };
      payloadField.value = btoa(JSON.stringify(payload));
      box.classList.add('is-verified');
      label.textContent = 'Проверка ALTCHA пройдена';
    } catch (_) {
      payloadField.value = '';
      label.textContent = 'Проверка не удалась. Повторить';
    } finally {
      box.classList.remove('is-solving');
      button.disabled = false;
    }
  };

  document.querySelectorAll('[data-altcha]').forEach((box) => {
    box.querySelector('[data-altcha-button]')?.addEventListener('click', () => solveAltcha(box));
  });

  document.querySelectorAll('[data-request-form]').forEach(async (form) => {
    const status = form.querySelector('[data-form-status]');
    const started = form.querySelector('[data-form-started]');
    started.value = String(Math.floor(Date.now() / 1000));
    try {
      const response = await fetch('/api/session.php', { credentials: 'same-origin', headers: { Accept: 'application/json' } });
      const data = await response.json();
      if (data.csrf) form.querySelector('[data-csrf]').value = data.csrf;
    } catch (_) {
      status.textContent = 'Форма временно недоступна. Используйте телефон или email.';
      status.className = 'form-status error';
    }
    form.addEventListener('submit', async (event) => {
      event.preventDefault();
      status.textContent = '';
      status.className = 'form-status';
      if (!form.checkValidity()) { form.reportValidity(); return; }
      if (!form.querySelector('[data-altcha-payload]').value) {
        status.textContent = 'Сначала подтвердите проверку ALTCHA.';
        status.classList.add('error');
        return;
      }
      const submit = form.querySelector('[type="submit"]');
      submit.disabled = true;
      status.textContent = 'Отправляем заявку…';
      try {
        const response = await fetch(form.action, { method: 'POST', body: new FormData(form), credentials: 'same-origin', headers: { Accept: 'application/json' } });
        const data = await response.json();
        if (!response.ok || !data.ok) throw new Error(data.message || 'Ошибка отправки');
        status.textContent = data.message;
        status.classList.add('success');
        form.reset();
        started.value = String(Math.floor(Date.now() / 1000));
        const box = form.querySelector('[data-altcha]');
        box.classList.remove('is-verified');
        box.querySelector('[data-altcha-label]').textContent = 'Подтвердить, что вы не робот';
      } catch (error) {
        status.textContent = error.message || 'Не удалось отправить заявку.';
        status.classList.add('error');
      } finally {
        submit.disabled = false;
      }
    });
  });
})();
