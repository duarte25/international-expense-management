<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - Gestao de Despesas</title>
    <style>
        :root { --bg:#f7f4ee; --card:#fffdf8; --text:#1f2937; --muted:#6b7280; --primary:#0f766e; --primary-2:#115e59; --border:#e5ddd1; }
        * { box-sizing: border-box; }
        body { margin:0; font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif; background: var(--bg); color: var(--text); }
        .wrap { min-height: 100vh; display:grid; place-items:center; padding:20px; }
        .card { width:100%; max-width:460px; background:var(--card); border:1px solid var(--border); border-radius:16px; padding:20px; }
        h1 { margin:0 0 6px; font-size:1.6rem; }
        p { margin:0 0 16px; color:var(--muted); }
        label { display:block; margin:10px 0 4px; font-size:.9rem; }
        input, button { width:100%; padding:10px 11px; border-radius:10px; border:1px solid #d7d0c4; font-size:.95rem; }
        button { border:none; background:var(--primary); color:#fff; font-weight:600; cursor:pointer; margin-top:12px; }
        button:hover { background:var(--primary-2); }
        a { color:#0f766e; text-decoration:none; }
        .res { margin-top:12px; font-family: monospace; font-size:.82rem; background:#111827; color:#e5e7eb; border-radius:10px; padding:10px; min-height:64px; white-space: pre-wrap; }
    </style>
</head>
<body>
<div class="wrap">
    <div class="card">
        <h1>Login</h1>
        <p>Entre para acessar seu painel de despesas.</p>

        <label>E-mail</label>
        <input id="email" placeholder="email@dominio.com">

        <label>Senha</label>
        <input id="password" type="password" placeholder="sua senha">

        <button onclick="login()">Entrar</button>

        <p style="margin-top:12px;">Nao tem conta? <a href="/register">Criar cadastro</a></p>
        <div id="res" class="res">{}</div>
    </div>
</div>

<script>
const tokenKey = 'iem_api_token';
const resBox = document.getElementById('res');

if (localStorage.getItem(tokenKey)) {
    window.location.href = '/dashboard';
}

function show(data) {
    resBox.textContent = JSON.stringify(data, null, 2);
}

async function login() {
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
    show({ status: res.status, data });

    if (res.ok && data.token) {
        localStorage.setItem(tokenKey, data.token);
        window.location.href = '/dashboard';
    }
}
</script>
</body>
</html>
