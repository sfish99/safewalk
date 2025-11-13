// החלפת תפקיד
document.querySelectorAll('.role-btn').forEach(btn => {
  btn.addEventListener('click', () => {
    document.querySelectorAll('.role-btn').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');
    console.log('role ->', btn.dataset.role);
  });
});

// ניתוב בסיסי - דמו
['open-call','history','support','profile'].forEach(id => {
  const el = document.getElementById(id);
  if (!el) return;
  el.addEventListener('click', () => {
    window.location.href = '/login?next=' + encodeURIComponent('/' + id);
  });
});

// SOS - זרימת דמו
const sos = document.getElementById('sosBtn');
let sosCooldown = false;

sos.addEventListener('click', () => {
  if (sosCooldown) return alert('ביצעת קריאת חירום זה עתה.');
  const ok = confirm('לשלוח קריאת חירום עכשיו?');
  if (!ok) return;

  sos.textContent = 'שולח...';
  setTimeout(() => {
    sos.textContent = 'SOS';
    sosCooldown = true;
    alert('קריאת חירום נשלחה.');
    setTimeout(() => sosCooldown = false, 20000);
  }, 1200);
});
