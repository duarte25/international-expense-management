<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - Gestao de Despesas</title>
    <style>
        :root { --bg:#f2efe8; --card:#fffdf8; --text:#1f2937; --muted:#6b7280; --primary:#0f766e; --primary-2:#115e59; --border:#e5ddd1; --bad:#b91c1c; }
        * { box-sizing: border-box; }
        body {
            margin:0;
            font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
            background:
                radial-gradient(circle at 15% 20%, rgba(243, 168, 59, 0.23), transparent 35%),
                radial-gradient(circle at 82% 16%, rgba(10, 127, 114, 0.20), transparent 32%),
                linear-gradient(180deg, #f8f4ed 0%, var(--bg) 100%);
            color: var(--text);
        }
        .wrap { min-height: 100vh; display:grid; place-items:center; padding:20px; }
        .card { width:100%; max-width:460px; background:var(--card); border:1px solid var(--border); border-radius:16px; padding:20px; }
        h1 { margin:0 0 6px; font-size:1.6rem; }
        p { margin:0 0 16px; color:var(--muted); }
        label { display:block; margin:10px 0 4px; font-size:.9rem; }
        input, button { width:100%; padding:10px 11px; border-radius:10px; border:1px solid #d7d0c4; font-size:.95rem; }
        input.is-invalid { border-color: #ef4444; background:#fff7f7; }
        button { border:none; background:var(--primary); color:#fff; font-weight:600; cursor:pointer; margin-top:12px; }
        button:hover { background:var(--primary-2); }
        a { color:#0f766e; text-decoration:none; }
        .field-error { min-height: 16px; margin-top:4px; font-size:.78rem; color:var(--bad); line-height:1.2; }
        .feedback {
            margin-top: 10px;
            border-radius: 10px;
            padding: 10px 12px;
            font-size: .9rem;
            border: 1px solid #d8dee7;
            background: #f8fafc;
            color: #334155;
            display: none;
        }
        .feedback.bad {
            background: #fef2f2;
            border-color: #fecaca;
            color: #991b1b;
        }
    </style>
</head>
<body>
<div class="wrap">
    <div class="card">
        <h1>Login</h1>
        <p>Entre para acessar seu painel de despesas.</p>

        <label>E-mail</label>
        <input id="email" placeholder="email@dominio.com">
        <div id="error_email" class="field-error"></div>

        <label>Senha</label>
        <input id="password" type="password" placeholder="sua senha">
        <div id="error_password" class="field-error"></div>

        <button onclick="login()">Entrar</button>

        <p style="margin-top:12px;">Nao tem conta? <a href="/register">Criar cadastro</a></p>
        <div id="feedback" class="feedback"></div>
        <!-- <div id="res" class="res">{}</div> -->
    </div>
</div>

<script>
const tokenKey = 'iem_api_token';
const feedbackBox = document.getElementById('feedback');
// const resBox = document.getElementById('res');

if (localStorage.getItem(tokenKey)) {
    window.location.href = '/dashboard';
}

// function show(data) {
//     resBox.textContent = JSON.stringify(data, null, 2);
// }

function clearFieldErrors() {
    ['email', 'password'].forEach((field) => {
        const input = document.getElementById(field);
        const error = document.getElementById(`error_${field}`);
        if (input) input.classList.remove('is-invalid');
        if (error) error.textContent = '';
    });
}

function applyFieldErrors(errors = {}) {
    Object.entries(errors).forEach(([field, messages]) => {
        const input = document.getElementById(field);
        const error = document.getElementById(`error_${field}`);
        const message = Array.isArray(messages) ? (messages[0] || '') : String(messages || '');
        if (input) input.classList.add('is-invalid');
        if (error) error.textContent = message;
    });
}

function setFeedback(text) {
    feedbackBox.style.display = text ? 'block' : 'none';
    feedbackBox.textContent = text || '';
    feedbackBox.classList.remove('bad');
    if (text) feedbackBox.classList.add('bad');
}

async function login() {
    clearFieldErrors();
    setFeedback('');

    try {
        const payload = {
            email: document.getElementById('email').value,
            password: document.getElementById('password').value
        };

        const res = await fetch('/api/login', {
            method: 'POST',
            headers: { 'Accept': 'application/json', 'Content-Type': 'application/json' },
            body: JSON.stringify(payload)
        });

        let data = {};
        try { data = await res.json(); } catch {}
        // show({ status: res.status, data });

        if (res.ok && data.token) {
            localStorage.setItem(tokenKey, data.token);
            window.location.href = '/dashboard';
            return;
        }

        if (data?.errors) {
            applyFieldErrors(data.errors);
            return;
        }

        setFeedback(data?.message || 'Nao foi possivel fazer login. Tente novamente.');
    } catch (_) {
        setFeedback('Falha de conexao. Verifique se o servidor esta rodando.');
    }
}
</script>
</body>
</html>