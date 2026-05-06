// ===== NIGHT MODE =====
function toggleNight() {
    document.body.classList.toggle('night');
    const btn = document.getElementById('nightBtn');
    if (document.body.classList.contains('night')) {
        btn.textContent = '☀️ الوضع النهاري';
        localStorage.setItem('nightMode', 'on');
    } else {
        btn.textContent = '🌙 الوضع الليلي';
        localStorage.setItem('nightMode', 'off');
    }
}

// Remember night mode preference
window.onload = function() {
    if (localStorage.getItem('nightMode') === 'on') {
        document.body.classList.add('night');
        const btn = document.getElementById('nightBtn');
        if (btn) btn.textContent = '☀️ الوضع النهاري';
    }
}

// ===== FILTER REGIONS =====
function filterRegions(category) {
    const cards = document.querySelectorAll('.region-card');
    const btns = document.querySelectorAll('.filter-btn');

    btns.forEach(b => b.classList.remove('active'));
    event.target.classList.add('active');

    cards.forEach(card => {
        if (category === 'all' || card.dataset.category === category) {
            card.style.display = 'block';
        } else {
            card.style.display = 'none';
        }
    });

    // Update count
    const visible = document.querySelectorAll('.region-card[style="display: block;"], .region-card:not([style])');
    const countEl = document.getElementById('count');
    if (countEl) countEl.textContent = visible.length;
}

// ===== FORM VALIDATION (Admin) =====
function validateLoginForm() {
    const user = document.getElementById('username').value.trim();
    const pass = document.getElementById('password').value.trim();
    
    if (!user) { showError('الرجاء إدخال اسم المستخدم'); return false; }
    if (!pass) { showError('الرجاء إدخال كلمة المرور'); return false; }
    return true;
}

function validateAddForm() {
    const name = document.getElementById('name').value.trim();
    const desc = document.getElementById('description').value.trim();
    const cat = document.getElementById('category').value;
    
    if (!name) { showError('الرجاء إدخال اسم المنطقة'); return false; }
    if (!desc) { showError('الرجاء إدخال الوصف'); return false; }
    if (!cat) { showError('الرجاء اختيار التصنيف'); return false; }
    return true;
}

function showError(msg) {
    let el = document.getElementById('formError');
    if (!el) {
        el = document.createElement('div');
        el.id = 'formError';
        el.className = 'alert-error';
        document.querySelector('.form-container').prepend(el);
    }
    el.textContent = msg;
}