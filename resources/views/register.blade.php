<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Criar conta - Gestao de Despesas</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=space-grotesk:400,500,600,700|dm-sans:400,500" rel="stylesheet" />
    <style>
        :root {
            --bg: #f2efe8;
            --ink: #172026;
            --muted: #5b646e;
            --card: #fffdfa;
            --line: #e2d9cb;
            --brand: #0a7f72;
            --brand-2: #08665b;
            --warm: #f3a83b;
            --ok: #0f766e;
            --bad: #b91c1c;
        }

        * { box-sizing: border-box; }

        body {
            margin: 0;
            color: var(--ink);
            font-family: "DM Sans", "Trebuchet MS", sans-serif;
            background:
                radial-gradient(circle at 15% 20%, rgba(243, 168, 59, 0.23), transparent 35%),
                radial-gradient(circle at 82% 16%, rgba(10, 127, 114, 0.20), transparent 32%),
                linear-gradient(180deg, #f8f4ed 0%, var(--bg) 100%);
            min-height: 100vh;
        }

        .page {
            max-width: 980px;
            margin: 26px auto;
            padding: 0 16px 24px;
        }

        .hero {
            border: 1px solid var(--line);
            border-radius: 22px;
            padding: 18px 20px;
            background: linear-gradient(120deg, #fff8ea 0%, #eefbf8 100%);
            margin-bottom: 14px;
        }

        .hero h1 {
            margin: 0;
            font-family: "Space Grotesk", sans-serif;
            font-size: 1.65rem;
            letter-spacing: -0.02em;
        }

        .hero p {
            margin: 6px 0 0;
            color: var(--muted);
        }

        .shell {
            border: 1px solid var(--line);
            border-radius: 22px;
            padding: 16px;
            background: var(--card);
            box-shadow: 0 10px 25px rgba(17, 24, 39, 0.06);
        }

        .section {
            border: 1px solid #e9dfd3;
            border-radius: 16px;
            padding: 14px;
            margin-bottom: 12px;
            background: #fffefb;
        }

        .section h2 {
            margin: 0 0 10px;
            font-family: "Space Grotesk", sans-serif;
            font-size: 1rem;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .dot {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background: var(--warm);
        }

        .grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 10px;
        }

        label {
            display: block;
            margin: 0 0 4px;
            font-size: .85rem;
            color: #3b4550;
            font-weight: 600;
        }

        input, button {
            width: 100%;
            border-radius: 10px;
            border: 1px solid #d7cfc3;
            padding: 10px 11px;
            font-size: .95rem;
            font-family: "DM Sans", sans-serif;
            background: #fff;
            color: var(--ink);
        }

        input[readonly] {
            background: #f6f7f9;
            color: #46515c;
        }

        input.is-invalid {
            border-color: #ef4444;
            background: #fff7f7;
        }

        input:focus, button:focus {
            outline: 2px solid rgba(10, 127, 114, .25);
            outline-offset: 1px;
            border-color: var(--brand);
        }

        .btn {
            border: none;
            cursor: pointer;
            font-weight: 700;
            letter-spacing: .01em;
            transition: transform .15s ease, background .15s ease;
        }

        .btn:active { transform: translateY(1px); }

        .btn-primary {
            background: var(--brand);
            color: #fff;
        }

        .btn-primary:hover { background: var(--brand-2); }

        .btn-soft {
            background: #374151;
            color: #fff;
        }

        .input-action {
            display: grid;
            grid-template-columns: 1fr auto;
            gap: 8px;
            align-items: center;
        }

        .toggle-btn {
            width: 42px;
            height: 42px;
            padding: 0;
            border: 1px solid #cbd5e1;
            background: #eef2f7;
            color: #1f2937;
            cursor: pointer;
            font-weight: 600;
            border-radius: 10px;
            display: grid;
            place-items: center;
        }

        .toggle-btn:hover {
            background: #e2e8f0;
        }

        .toggle-btn.active {
            background: #d1fae5;
            border-color: #6ee7b7;
            color: #065f46;
        }

        .toggle-btn svg {
            width: 20px;
            height: 20px;
            display: block;
        }

        .footer {
            display: flex;
            gap: 10px;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            margin-top: 6px;
        }

        .field-error {
            min-height: 16px;
            margin-top: 4px;
            font-size: .78rem;
            color: var(--bad);
            line-height: 1.2;
        }

        .feedback {
            margin-top: 10px;
            border-radius: 10px;
            padding: 10px 12px;
            font-size: .9rem;
            border: 1px solid #d8dee7;
            background: #f8fafc;
            color: #334155;
        }

        .feedback.ok {
            background: #ecfdf5;
            border-color: #a7f3d0;
            color: #065f46;
        }

        .feedback.bad {
            background: #fef2f2;
            border-color: #fecaca;
            color: #991b1b;
        }

        .link {
            color: var(--brand);
            text-decoration: none;
            font-weight: 600;
        }

        .debug {
            margin-top: 12px;
            border: 1px solid #e0d6ca;
            border-radius: 12px;
            overflow: hidden;
            background: #fbfaf8;
        }

        .debug summary {
            cursor: pointer;
            list-style: none;
            padding: 10px 12px;
            font-weight: 700;
            font-size: .9rem;
        }

        .debug pre {
            margin: 0;
            padding: 12px;
            border-top: 1px solid #e5ddd1;
            background: #101827;
            color: #e5e7eb;
            font-size: .8rem;
            white-space: pre-wrap;
            font-family: ui-monospace, Menlo, Consolas, monospace;
            min-height: 78px;
        }

        @media (max-width: 760px) {
            .grid { grid-template-columns: 1fr; }
            .page { margin-top: 14px; }
        }
    </style>
</head>
<body>
<div class="page">
    <section class="hero">
        <h1>Criar conta</h1>
        <p>Preencha seus dados.</p>
    </section>

    <section class="shell">
        <form onsubmit="event.preventDefault(); registerUser();">
            <section class="section">
                <h2><span class="dot"></span>Dados da conta</h2>
                <div class="grid">
                    <div>
                        <label>Nome</label>
                        <input id="name" placeholder="Seu nome completo" autocomplete="name">
                        <div id="error_name" class="field-error"></div>
                    </div>
                    <div>
                        <label>E-mail</label>
                        <input id="email" placeholder="email@dominio.com" autocomplete="email">
                        <div id="error_email" class="field-error"></div>
                    </div>
                    <div>
                        <label>CPF (11 digitos)</label>
                        <input id="cpf" placeholder="000.000.000-00" inputmode="numeric" oninput="onCpfInput()">
                        <div id="error_cpf" class="field-error"></div>
                    </div>
                    <div>
                        <label>Senha</label>
                        <div class="input-action">
                            <input id="password" type="password" placeholder="Minimo 8 caracteres" autocomplete="new-password">
                            <button type="button" class="toggle-btn" aria-label="Mostrar senha" title="Mostrar senha" onclick="togglePassword('password', this)">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <path d="M2 12s3.7-7 10-7 10 7 10 7-3.7 7-10 7-10-7-10-7z"/>
                                    <circle cx="12" cy="12" r="3"/>
                                </svg>
                            </button>
                        </div>
                        <div id="error_password" class="field-error"></div>
                    </div>
                    <div>
                        <label>Confirmacao de senha</label>
                        <div class="input-action">
                            <input id="password_confirmation" type="password" placeholder="Repita a senha" autocomplete="new-password">
                            <button type="button" class="toggle-btn" aria-label="Mostrar confirmacao de senha" title="Mostrar confirmacao de senha" onclick="togglePassword('password_confirmation', this)">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <path d="M2 12s3.7-7 10-7 10 7 10 7-3.7 7-10 7-10-7-10-7z"/>
                                    <circle cx="12" cy="12" r="3"/>
                                </svg>
                            </button>
                        </div>
                        <div id="error_password_confirmation" class="field-error"></div>
                    </div>
                </div>
            </section>

            <section class="section">
                <h2><span class="dot"></span>Endereco</h2>
                <div>
                    <label>CEP (8 digitos)</label>
                    <input id="cep" placeholder="00000-000" inputmode="numeric" oninput="onCepTyped()" onblur="onCepBlur()">
                    <div id="error_cep" class="field-error"></div>
                </div>

                <div class="grid" style="margin-top:10px;">
                    <div>
                        <label>Rua</label>
                        <input id="street" placeholder="Preenchido pelo CEP" readonly>
                        <div id="error_street" class="field-error"></div>
                    </div>
                    <div>
                        <label>Bairro</label>
                        <input id="neighborhood" placeholder="Preenchido pelo CEP" readonly>
                        <div id="error_neighborhood" class="field-error"></div>
                    </div>
                    <div>
                        <label>Numero</label>
                        <input id="house_number" placeholder="Ex: 123">
                        <div id="error_house_number" class="field-error"></div>
                    </div>
                    <div>
                        <label>Cidade</label>
                        <input id="city" placeholder="Preenchido pelo CEP" readonly>
                        <div id="error_city" class="field-error"></div>
                    </div>
                    <div>
                        <label>Estado</label>
                        <input id="state" placeholder="UF" readonly>
                        <div id="error_state" class="field-error"></div>
                    </div>
                    <div style="grid-column: 1 / -1;">
                        <label>Complemento</label>
                        <input id="complement" placeholder="Complemento (auto ou editavel)">
                        <div id="error_complement" class="field-error"></div>
                    </div>
                </div>
            </section>

            <div class="footer">
                <button type="submit" class="btn btn-primary" style="max-width:260px;">Criar conta</button>
                <div>Ja possui conta? <a class="link" href="/login">Ir para login</a></div>
            </div>

            <div id="feedback" class="feedback" style="display:none;"></div>
        </form>

        <details class="debug">
            <summary>Debug da API (opcional)</summary>
            <pre id="res">{}</pre>
        </details>
    </section>
</div>

<script>
const tokenKey = 'iem_api_token';
const resBox = document.getElementById('res');
const feedbackBox = document.getElementById('feedback');
let cepDebounce;
let lastCepLookup = '';
const formFields = ['name', 'email', 'cpf', 'cep', 'street', 'house_number', 'neighborhood', 'city', 'state', 'password', 'password_confirmation', 'complement'];

if (localStorage.getItem(tokenKey)) {
    window.location.href = '/dashboard';
}

function show(data) {
    resBox.textContent = JSON.stringify(data, null, 2);
}

function setFeedback(text, type = '') {
    feedbackBox.style.display = text ? 'block' : 'none';
    feedbackBox.textContent = text || '';
    feedbackBox.classList.remove('ok', 'bad');
    if (type) feedbackBox.classList.add(type);
}

function extractErrorMessage(data) {
    if (data?.errors && typeof data.errors === 'object') {
        const firstKey = Object.keys(data.errors)[0];
        if (firstKey && Array.isArray(data.errors[firstKey]) && data.errors[firstKey].length) {
            return data.errors[firstKey][0];
        }
    }
    if (typeof data?.message === 'string' && data.message.trim() !== '') {
        return data.message;
    }
    return 'Nao foi possivel concluir o cadastro.';
}

function clearFieldErrors() {
    formFields.forEach((field) => {
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

function clearFieldError(field) {
    const input = document.getElementById(field);
    const error = document.getElementById(`error_${field}`);
    if (input) input.classList.remove('is-invalid');
    if (error) error.textContent = '';
}

function setFieldError(field, message) {
    const input = document.getElementById(field);
    const error = document.getElementById(`error_${field}`);
    if (input) input.classList.add('is-invalid');
    if (error) error.textContent = message;
}

function togglePassword(inputId, button) {
    const input = document.getElementById(inputId);
    const show = input.type === 'password';
    input.type = show ? 'text' : 'password';
    button.classList.toggle('active', show);
    button.setAttribute('aria-label', show ? 'Ocultar senha' : 'Mostrar senha');
    button.setAttribute('title', show ? 'Ocultar senha' : 'Mostrar senha');
}

function maskCpf(value) {
    const digits = value.replace(/\D+/g, '').slice(0, 11);
    const p1 = digits.slice(0, 3);
    const p2 = digits.slice(3, 6);
    const p3 = digits.slice(6, 9);
    const p4 = digits.slice(9, 11);

    if (digits.length <= 3) return p1;
    if (digits.length <= 6) return `${p1}.${p2}`;
    if (digits.length <= 9) return `${p1}.${p2}.${p3}`;
    return `${p1}.${p2}.${p3}-${p4}`;
}

function maskCep(value) {
    const digits = value.replace(/\D+/g, '').slice(0, 8);
    if (digits.length <= 5) return digits;
    return `${digits.slice(0, 5)}-${digits.slice(5)}`;
}

function onCpfInput() {
    const input = document.getElementById('cpf');
    input.value = maskCpf(input.value);
}

function fillAddress(data = {}) {
    document.getElementById('street').value = data.logradouro || '';
    document.getElementById('neighborhood').value = data.bairro || '';
    document.getElementById('city').value = data.localidade || '';
    document.getElementById('state').value = data.uf || '';

    const complementInput = document.getElementById('complement');
    if (!complementInput.value || complementInput.dataset.auto === '1') {
        complementInput.value = data.complemento || '';
        complementInput.dataset.auto = '1';
    }
}

function clearAddress() {
    fillAddress({});
}

async function lookupCep() {
    clearFieldError('cep');
    const cep = (document.getElementById('cep').value || '').replace(/\D+/g, '');

    if (cep === lastCepLookup) {
        return;
    }
    lastCepLookup = cep;

    try {
        const res = await fetch(`https://viacep.com.br/ws/${cep}/json/`);
        const data = await res.json();

        if (!res.ok || data.erro) {
            clearAddress();
            setFieldError('cep', 'CEP invalido ou inexistente.');
            return;
        }

        fillAddress(data);
    } catch (_) {
        // Keep silent on temporary CEP API failures.
    }
}

function onCepInput() {
    clearFieldError('cep');
    clearTimeout(cepDebounce);
    const cep = (document.getElementById('cep').value || '').replace(/\D+/g, '');
    if (cep.length !== 8) {
        lastCepLookup = '';
        clearAddress();
        return;
    }
    cepDebounce = setTimeout(() => {
        lookupCep();
    }, 350);
}

function onCepBlur() {
    const cep = (document.getElementById('cep').value || '').replace(/\D+/g, '');
    if (cep.length === 0) {
        clearFieldError('cep');
        return;
    }
    if (cep.length !== 8) {
        clearAddress();
        lastCepLookup = '';
        setFieldError('cep', 'CEP invalido ou inexistente.');
        return;
    }
    lookupCep();
}

function onCepTyped() {
    const input = document.getElementById('cep');
    input.value = maskCep(input.value);
    onCepInput();
}

async function registerUser() {
    clearFieldErrors();
    setFeedback('');

    const payload = {
        name: document.getElementById('name').value,
        email: document.getElementById('email').value,
        cpf: document.getElementById('cpf').value,
        cep: document.getElementById('cep').value,
        street: document.getElementById('street').value,
        house_number: document.getElementById('house_number').value,
        neighborhood: document.getElementById('neighborhood').value,
        city: document.getElementById('city').value,
        state: document.getElementById('state').value,
        complement: document.getElementById('complement').value,
        password: document.getElementById('password').value,
        password_confirmation: document.getElementById('password_confirmation').value
    };

    try {
        const res = await fetch('/api/register', {
            method: 'POST',
            headers: { 'Accept': 'application/json', 'Content-Type': 'application/json' },
            body: JSON.stringify(payload)
        });

        let data = {};
        try { data = await res.json(); } catch {}
        show({ status: res.status, data });

        if (res.ok && data.token) {
            setFeedback('Cadastro realizado com sucesso. Redirecionando...', 'ok');
            localStorage.setItem(tokenKey, data.token);
            window.location.href = '/dashboard';
            return;
        }

        if (data?.errors) {
            applyFieldErrors(data.errors);
            setFeedback('');
            return;
        }
        setFeedback(extractErrorMessage(data), 'bad');
    } catch (_) {
        setFeedback('Falha de conexao. Verifique se o servidor esta rodando.', 'bad');
    }
}
</script>
</body>
</html>
