const tokenKey = 'lina_auth_token';
const userKey = 'lina_user';

function showAuthAlert(id, message, type = 'danger') {
    const alert = document.getElementById(id);
    alert.className = `alert alert-${type}`;
    alert.textContent = message;
}

async function postJson(url, payload) {
    const response = await fetch(url, {
        method: 'POST',
        headers: {
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(payload)
    });

    const data = await response.json().catch(() => ({}));

    if (!response.ok) {
        const errors = data.errors ? Object.values(data.errors).flat().join(' ') : null;
        throw new Error(errors || data.message || 'Request failed');
    }

    return data;
}

document.getElementById('loginForm')?.addEventListener('submit', async (event) => {
    event.preventDefault();

    const form = event.currentTarget;
    const button = form.querySelector('button[type="submit"]');
    button.disabled = true;

    try {
        const data = await postJson('/api/auth/login', Object.fromEntries(new FormData(form)));

        if (!data.is_admin) {
            localStorage.removeItem(tokenKey);
            localStorage.removeItem(userKey);
            showAuthAlert('loginAlert', 'This interface is currently available for admin users only.');
            return;
        }

        localStorage.setItem(tokenKey, data.token);
        localStorage.setItem(userKey, JSON.stringify({
            ...data.user,
            role: data.role,
            is_admin: data.is_admin
        }));

        window.location.href = '/admin';
    } catch (error) {
        showAuthAlert('loginAlert', error.message);
    } finally {
        button.disabled = false;
    }
});

document.getElementById('registerForm')?.addEventListener('submit', async (event) => {
    event.preventDefault();

    const form = event.currentTarget;
    const payload = Object.fromEntries(new FormData(form));
    const button = form.querySelector('button[type="submit"]');
    button.disabled = true;

    try {
        await postJson('/api/auth/register', payload);
        form.reset();
        showAuthAlert('registerAlert', 'Account created. You can sign in now.', 'success');
    } catch (error) {
        showAuthAlert('registerAlert', error.message);
    } finally {
        button.disabled = false;
    }
});
