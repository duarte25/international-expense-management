<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard - Gestao de Despesas</title>
    <style>
        :root { --bg:#f7f4ee; --card:#fffdf8; --text:#1f2937; --muted:#6b7280; --primary:#0f766e; --primary-2:#115e59; --border:#e5ddd1; --danger:#b91c1c; }
        * { box-sizing: border-box; }
        body { margin:0; font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif; background: var(--bg); color: var(--text); }
        .container { max-width:1000px; margin:24px auto; padding:16px; }
        .topbar { display:flex; justify-content:space-between; align-items:center; margin-bottom:14px; }
        .topbar h1 { margin:0; font-size:1.5rem; }
        .card { background:var(--card); border:1px solid var(--border); border-radius:16px; padding:16px; }
        .grid { display:grid; grid-template-columns:1fr 1fr; gap:12px; }
        label { display:block; margin:10px 0 4px; font-size:.9rem; }
        input, button { width:100%; padding:10px 11px; border-radius:10px; border:1px solid #d7d0c4; font-size:.95rem; }
        button { border:none; background:var(--primary); color:#fff; font-weight:600; cursor:pointer; margin-top:10px; }
        button:hover { background:var(--primary-2); }
        .secondary { background:#374151; }
        .danger { background:var(--danger); width:auto; padding:9px 14px; margin:0; }
        .res { margin-top:12px; font-family: monospace; font-size:.82rem; background:#111827; color:#e5e7eb; border-radius:10px; padding:10px; min-height:64px; white-space: pre-wrap; }
        .item { margin-top:8px; border:1px solid #d1d5db; border-radius:10px; padding:9px; background:#f9fafb; }
        .muted { color:var(--muted); font-size:.85rem; }
        @media (max-width: 760px) { .grid { grid-template-columns:1fr; } }
    </style>
</head>
<body>
<div class="container">
    <div class="topbar">
        <h1>Dashboard</h1>
        <button class="danger" onclick="logout()">Sair</button>
    </div>

    <div class="grid">
        <section class="card">
            <h2>Nova despesa</h2>
            <label>Valor</label>
            <input id="amount" placeholder="120.50">
            <label>Moeda</label>
            <input id="currency" placeholder="USD">
            <label>
                <input id="pending" type="checkbox" style="width:auto;"> Salvar como pendente se API falhar
            </label>
            <button onclick="createExpense()">Salvar despesa</button>
            <button class="secondary" onclick="loadExpenses()">Atualizar lista</button>
        </section>

        <section class="card">
            <h2>Minhas despesas</h2>
            <div id="list" class="muted">Carregando...</div>
        </section>
    </div>

    <section class="card" style="margin-top:12px;">
        <h2>Resposta da API</h2>
        <div id="res" class="res">{}</div>
    </section>
</div>

<script>
const tokenKey = 'iem_api_token';
const resBox = document.getElementById('res');
const listBox = document.getElementById('list');
const token = localStorage.getItem(tokenKey);

if (!token) {
    window.location.href = '/login';
}

function show(data) {
    resBox.textContent = JSON.stringify(data, null, 2);
}

async function api(path, method='GET', body=null) {
    const res = await fetch('/api/' + path, {
        method,
        headers: {
            'Accept': 'application/json',
            'Content-Type': 'application/json',
            'Authorization': `Bearer ${localStorage.getItem(tokenKey) || ''}`
        },
        body: body ? JSON.stringify(body) : null
    });

    let data = {};
    try { data = await res.json(); } catch {}
    show({ status: res.status, data });

    if (res.status === 401) {
        localStorage.removeItem(tokenKey);
        window.location.href = '/login';
        throw new Error('unauthorized');
    }

    if (!res.ok) throw data;
    return data;
}

async function loadExpenses() {
    try {
        const data = await api('expenses');
        const rows = data.data || [];
        if (!rows.length) {
            listBox.innerHTML = '<span class="muted">Sem despesas cadastradas.</span>';
            return;
        }
        listBox.innerHTML = rows.map(item => `
            <div class="item">
                <strong>${item.currency_code} ${item.amount_original}</strong> -> BRL ${item.amount_brl ?? '-'}<br>
                <span class="muted">status: ${item.status} | cotacao: ${item.exchange_rate ?? '-'} | id: ${item.id}</span>
            </div>
        `).join('');
    } catch (_) {}
}

async function createExpense() {
    try {
        await api('expenses', 'POST', {
            amount: document.getElementById('amount').value,
            currency: (document.getElementById('currency').value || '').toUpperCase(),
            save_as_pending_on_failure: document.getElementById('pending').checked
        });
        loadExpenses();
    } catch (_) {}
}

async function logout() {
    try { await api('logout', 'POST'); } catch (_) {}
    localStorage.removeItem(tokenKey);
    window.location.href = '/login';
}

loadExpenses();
</script>
</body>
</html>
