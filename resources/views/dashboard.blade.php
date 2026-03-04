<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Painel de Despesas - Gestao de Despesas</title>
    <style>
        :root { --bg:#f2efe8; --card:#fffdf8; --text:#1f2937; --muted:#6b7280; --primary:#0f766e; --primary-2:#115e59; --border:#e5ddd1; --danger:#b91c1c; }
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
        .container { max-width:1000px; margin:24px auto; padding:16px; }
        .topbar { display:flex; justify-content:space-between; align-items:center; margin-bottom:14px; }
        .topbar h1 { margin:0; font-size:1.5rem; }
        .total-chip {
            margin-top: 8px;
            display: inline-block;
            font-weight: 700;
            background: #ecfdf5;
            color: #065f46;
            border: 1px solid #a7f3d0;
            padding: 7px 10px;
            border-radius: 999px;
        }
        .card { background:var(--card); border:1px solid var(--border); border-radius:16px; padding:16px; }
        .grid { display:grid; grid-template-columns:1fr 1fr; gap:12px; }
        label { display:block; margin:10px 0 4px; font-size:.9rem; }
        input, select, button { width:100%; padding:10px 11px; border-radius:10px; border:1px solid #d7d0c4; font-size:.95rem; }
        button { border:none; background:var(--primary); color:#fff; font-weight:600; cursor:pointer; margin-top:10px; }
        button:hover { background:var(--primary-2); }
        .secondary { background:#374151; }
        .danger { background:var(--danger); width:auto; padding:9px 14px; margin:0; }
        .res { margin-top:12px; font-family: monospace; font-size:.82rem; background:#111827; color:#e5e7eb; border-radius:10px; padding:10px; min-height:64px; white-space: pre-wrap; }
        .item { margin-top:8px; border:1px solid #d1d5db; border-radius:10px; padding:9px; background:#f9fafb; }
        .item-actions { margin-top:8px; display:flex; gap:8px; }
        .item-actions button {
            width: auto;
            margin: 0;
            padding: 7px 10px;
            font-size: .82rem;
            border-radius: 8px;
        }
        .item-actions .edit { background:#1d4ed8; }
        .item-actions .remove { background:#b91c1c; }
        .muted { color:var(--muted); font-size:.85rem; }
        .modal-backdrop {
            position: fixed;
            inset: 0;
            background: rgba(15, 23, 42, .45);
            display: none;
            align-items: center;
            justify-content: center;
            padding: 16px;
            z-index: 60;
        }
        .modal-backdrop.show { display: flex; }
        .modal {
            width: 100%;
            max-width: 420px;
            background: #fff;
            border: 1px solid #e5ddd1;
            border-radius: 14px;
            padding: 14px;
        }
        .modal h3 { margin: 0 0 8px; }
        .modal-actions {
            margin-top: 10px;
            display: flex;
            gap: 8px;
            justify-content: flex-end;
        }
        .modal-actions button {
            width: auto;
            margin: 0;
            padding: 8px 12px;
        }
        .modal-danger {
            background: #b91c1c;
        }
        @media (max-width: 760px) { .grid { grid-template-columns:1fr; } }
    </style>
</head>
<body>
<div class="container">
    <div class="topbar">
        <h1>Painel de Despesas</h1>
        <button class="danger" onclick="logout()">Sair</button>
    </div>

    <div class="grid">
        <section class="card">
            <h2>Nova despesa</h2>
            <label>Valor</label>
            <input id="amount" placeholder="120.50">
            <label>Moeda</label>
            <select id="currency">
                <option value="USD" selected>USD - Dolar Americano</option>
                <option value="EUR">EUR - Euro</option>
                <option value="GBP">GBP - Libra Esterlina</option>
                <option value="JPY">JPY - Iene Japones</option>
                <option value="CAD">CAD - Dolar Canadense</option>
                <option value="AUD">AUD - Dolar Australiano</option>
                <option value="CHF">CHF - Franco Suico</option>
                <option value="CNY">CNY - Yuan Chines</option>
                <option value="ARS">ARS - Peso Argentino</option>
                <option value="BRL">BRL - Real Brasileiro</option>
            </select>
            <button onclick="createExpense()">Salvar despesa</button>
        </section>

        <section class="card">
            <h2>Minhas despesas</h2>
            <div id="total_brl" class="total-chip">Total em BRL: R$ 0,00</div>
            <div id="list" class="muted">Carregando...</div>
        </section>
    </div>

    <!--
    <section class="card" style="margin-top:12px;">
        <h2>Resposta da API</h2>
        <div id="res" class="res">{}</div>
    </section>
    -->
</div>

<div id="edit_modal_backdrop" class="modal-backdrop" onclick="closeEditModal(event)">
    <div class="modal" onclick="event.stopPropagation()">
        <h3>Editar despesa</h3>
        <label>Novo valor</label>
        <input id="edit_amount" placeholder="120.50">
        <label>Nova moeda</label>
        <select id="edit_currency">
            <option value="USD">USD - Dolar Americano</option>
            <option value="EUR">EUR - Euro</option>
            <option value="GBP">GBP - Libra Esterlina</option>
            <option value="JPY">JPY - Iene Japones</option>
            <option value="CAD">CAD - Dolar Canadense</option>
            <option value="AUD">AUD - Dolar Australiano</option>
            <option value="CHF">CHF - Franco Suico</option>
            <option value="CNY">CNY - Yuan Chines</option>
            <option value="ARS">ARS - Peso Argentino</option>
            <option value="BRL">BRL - Real Brasileiro</option>
        </select>
        <div class="modal-actions">
            <button class="secondary" onclick="closeEditModal()">Cancelar</button>
            <button onclick="saveEditExpense()">Salvar</button>
        </div>
    </div>
</div>

<div id="delete_modal_backdrop" class="modal-backdrop" onclick="closeDeleteModal(event)">
    <div class="modal" onclick="event.stopPropagation()">
        <h3>Apagar despesa</h3>
        <p style="margin:0; color:#475569;">Tem certeza que deseja remover esta despesa? Esta ação não pode ser desfeita.</p>
        <div class="modal-actions">
            <button class="secondary" onclick="closeDeleteModal()">Cancelar</button>
            <button class="modal-danger" onclick="confirmDeleteExpense()">Apagar</button>
        </div>
    </div>
</div>

<script>
const tokenKey = 'iem_api_token';
// const resBox = document.getElementById('res');
const listBox = document.getElementById('list');
const totalBox = document.getElementById('total_brl');
const editBackdrop = document.getElementById('edit_modal_backdrop');
const deleteBackdrop = document.getElementById('delete_modal_backdrop');
const editAmount = document.getElementById('edit_amount');
const editCurrency = document.getElementById('edit_currency');
const token = localStorage.getItem(tokenKey);
let editingExpenseId = null;
let deletingExpenseId = null;

if (!token) {
    window.location.href = '/login';
}

// function show(data) {
//     resBox.textContent = JSON.stringify(data, null, 2);
// }

function formatBrl(value) {
    return new Intl.NumberFormat('pt-BR', { style: 'currency', currency: 'BRL' }).format(value || 0);
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
    // show({ status: res.status, data });

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
        const total = rows.reduce((sum, item) => sum + (parseFloat(item.amount_brl) || 0), 0);
        totalBox.textContent = `Total em BRL: ${formatBrl(total)}`;
        if (!rows.length) {
            listBox.innerHTML = '<span class="muted">Sem despesas cadastradas.</span>';
            return;
        }
        listBox.innerHTML = rows.map(item => `
            <div class="item">
                <strong>${item.currency_code} ${item.amount_original}</strong> -> BRL ${item.amount_brl ?? '-'}<br>
                <span class="muted">status: ${item.status} | cotacao: ${item.exchange_rate ?? '-'} | id: ${item.id}</span>
                <div class="item-actions">
                    <button class="edit" onclick="openEditModal(${item.id}, '${item.amount_original}', '${item.currency_code}')">Editar</button>
                    <button class="remove" onclick="openDeleteModal(${item.id})">Apagar</button>
                </div>
            </div>
        `).join('');
    } catch (_) {}
}

function openEditModal(id, currentAmount, currentCurrency) {
    editingExpenseId = id;
    editAmount.value = currentAmount;
    editCurrency.value = (currentCurrency || 'USD').toUpperCase();
    editBackdrop.classList.add('show');
}

function closeEditModal(event = null) {
    if (event && event.target !== editBackdrop) return;
    editingExpenseId = null;
    editBackdrop.classList.remove('show');
}

async function saveEditExpense() {
    if (!editingExpenseId) return;
    try {
        await api(`expenses/${editingExpenseId}`, 'PUT', {
            amount: editAmount.value,
            currency: (editCurrency.value || '').toUpperCase()
        });
        closeEditModal();
        loadExpenses();
    } catch (_) {}
}

function openDeleteModal(id) {
    deletingExpenseId = id;
    deleteBackdrop.classList.add('show');
}

function closeDeleteModal(event = null) {
    if (event && event.target !== deleteBackdrop) return;
    deletingExpenseId = null;
    deleteBackdrop.classList.remove('show');
}

async function confirmDeleteExpense() {
    if (!deletingExpenseId) return;
    try {
        await api(`expenses/${deletingExpenseId}`, 'DELETE');
        closeDeleteModal();
        loadExpenses();
    } catch (_) {}
}

async function createExpense() {
    try {
        await api('expenses', 'POST', {
            amount: document.getElementById('amount').value,
            currency: (document.getElementById('currency').value || '').toUpperCase()
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
