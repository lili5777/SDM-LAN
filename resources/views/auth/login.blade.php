<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — JABLAYMEN | LAN RI</title>
    <link rel="icon" href="{{ asset('gambar/lanri.png') }}" type="image/png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600;700&family=DM+Serif+Display:ital@0;1&display=swap" rel="stylesheet">
    <style>
        :root {
            --mint-50:   #f0fdf6;
            --mint-100:  #dcfce8;
            --mint-200:  #bbf7d2;
            --green-400: #34d474;
            --green-500: #16a34a;
            --green-600: #15803d;
            --green-700: #166534;
            --slate-50:  #f8fafc;
            --slate-100: #f1f5f9;
            --slate-200: #e2e8f0;
            --slate-300: #cbd5e1;
            --slate-400: #94a3b8;
            --slate-500: #64748b;
            --slate-600: #475569;
            --slate-700: #334155;
            --slate-900: #0f172a;
            --white:     #ffffff;
            --danger:    #ef4444;
            --danger-bg: #fef2f2;
            --r-sm: 10px;
            --r-md: 14px;
            --r-lg: 20px;
            --r-xl: 28px;
        }

        *, *::before, *::after { margin:0; padding:0; box-sizing:border-box; }

        body {
            font-family: 'DM Sans', sans-serif;
            min-height: 100vh;
            background: #9dd6c9;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
            position: relative;
            overflow-x: hidden;
        }

        .bg-blob {
            position: fixed;
            border-radius: 50%;
            pointer-events: none;
            z-index: 0;
        }
        .bg-blob-1 { width:520px; height:520px; background:#f2fdf6; top:-160px; right:-100px; animation:blobFloat 14s ease-in-out infinite; }
        .bg-blob-2 { width:380px; height:380px; background:#f2fdf6;; bottom:-120px; left:-80px; animation:blobFloat 18s ease-in-out infinite reverse; }
        .bg-blob-3 { width:200px; height:200px; background:#f2fdf6; top:55%; left:55%; opacity:0.5; animation:blobFloat 22s ease-in-out infinite 4s; }
        @keyframes blobFloat {
            0%,100% { transform:translate(0,0) scale(1); }
            40%      { transform:translate(20px,-18px) scale(1.04); }
            70%      { transform:translate(-12px,14px) scale(0.97); }
        }

        .login-card {
            position: relative; z-index: 10;
            width: 100%; max-width: 1000px;
            display: flex;
            border-radius: var(--r-xl);
            overflow: hidden;
            background: var(--white);
            box-shadow: 0 0 0 1px rgba(22,163,74,0.08), 0 24px 64px rgba(15,23,42,0.10), 0 4px 16px rgba(15,23,42,0.06);
            animation: cardIn 0.65s cubic-bezier(0.22,1,0.36,1) both;
        }
        @keyframes cardIn {
            from { opacity:0; transform:translateY(24px) scale(0.98); }
            to   { opacity:1; transform:translateY(0) scale(1); }
        }

        /* ===== LEFT PANEL ===== */
        .panel-left {
            flex: 0 0 44%;
            background: linear-gradient(145deg, #e8fdf1 0%, #d1fae5 40%, #a7f3d0 100%);
            display: flex; flex-direction: column;
            align-items: center; justify-content: center;
            padding: 52px 40px;
            position: relative; overflow: hidden;
        }

        .deco-circle { position:absolute; border-radius:50%; pointer-events:none; }
        .dc-1 { width:320px; height:320px; border:1.5px solid rgba(22,163,74,0.12); top:50%; left:50%; transform:translate(-50%,-50%); }
        .dc-2 { width:220px; height:220px; border:1px solid rgba(22,163,74,0.08); top:50%; left:50%; transform:translate(-50%,-50%); }
        .dc-3 { width:460px; height:460px; border:1px dashed rgba(22,163,74,0.07); top:50%; left:50%; transform:translate(-50%,-50%); animation:slowSpin 40s linear infinite; }
        @keyframes slowSpin { to { transform:translate(-50%,-50%) rotate(360deg); } }

        .mini-card {
            position: absolute;
            background: rgba(255,255,255,0.78);
            border: 1px solid rgba(22,163,74,0.15);
            border-radius: var(--r-md);
            padding: 9px 13px;
            display: flex; align-items: center; gap: 8px;
            font-size: 12px; font-weight: 600;
            color: var(--green-700);
            white-space: nowrap;
            box-shadow: 0 4px 16px rgba(22,163,74,0.10);
        }
        .mini-card i { color:var(--green-500); font-size:12px; }
        .mc-1 { top:13%; right:7%; animation:floatCard 6s ease-in-out infinite; }
        .mc-2 { bottom:17%; left:6%; animation:floatCard 8s ease-in-out infinite 1s; }
        .mc-3 { top:60%; right:5%; animation:floatCard 7s ease-in-out infinite 2.5s; }
        @keyframes floatCard {
            0%,100% { transform:translateY(0); }
            50%      { transform:translateY(-8px); }
        }

        .logo-area { position:relative; z-index:2; text-align:center; }

        .logo-ring {
            width:96px; height:96px;
            margin: 0 auto 20px;
            border-radius: 50%;
            background: var(--white);
            box-shadow: 0 8px 32px rgba(22,163,74,0.18), 0 2px 8px rgba(22,163,74,0.10);
            display: flex; align-items: center; justify-content: center;
            position: relative;
        }
        .logo-ring::after {
            content: '';
            position: absolute; inset:-7px;
            border-radius: 50%;
            border: 2px dashed rgba(22,163,74,0.22);
            animation: slowSpin 16s linear infinite;
        }
        .logo-ring img { width:64px; height:64px; object-fit:contain; }

        .brand-name {
            font-family: 'DM Serif Display', serif;
            font-size: 2.4rem;
            color: var(--green-700);
            line-height: 1; letter-spacing:-0.5px;
            margin-bottom: 8px;
        }
        .brand-pill {
            display: inline-block;
            font-size: 11px; font-weight: 700;
            letter-spacing: 2px; text-transform: uppercase;
            color: var(--green-600);
            background: rgba(22,163,74,0.10);
            border: 1px solid rgba(22,163,74,0.2);
            border-radius: 999px;
            padding: 4px 14px;
            margin-bottom: 18px;
        }
        .brand-desc {
            font-size: 13.5px;
            color: var(--green-700); opacity:0.68;
            line-height: 1.6; max-width:260px;
            margin: 0 auto;
        }

        /* ===== RIGHT PANEL ===== */
        .panel-right {
            flex: 1;
            background: var(--white);
            display: flex; flex-direction: column; justify-content: center;
            padding: 52px 52px;
            position: relative;
        }
        .panel-right::after {
            content: '';
            position: absolute; bottom:0; right:0;
            width:200px; height:200px;
            background: radial-gradient(ellipse at bottom right, var(--mint-100) 0%, transparent 65%);
            border-radius: 0 0 var(--r-xl) 0;
            pointer-events: none;
        }

        .form-badge {
            display: inline-flex; align-items: center; gap:7px;
            background: var(--mint-100);
            border: 1px solid var(--mint-200);
            border-radius: 999px;
            padding: 5px 14px 5px 10px;
            margin-bottom: 16px;
        }
        .badge-dot {
            width:8px; height:8px;
            background:var(--green-500); border-radius:50%;
            animation: livePulse 2.5s ease-in-out infinite;
        }
        @keyframes livePulse {
            0%,100% { box-shadow:0 0 0 0 rgba(22,163,74,0.5); }
            50%      { box-shadow:0 0 0 6px rgba(22,163,74,0); }
        }
        .badge-text { font-size:12px; font-weight:600; color:var(--green-600); letter-spacing:0.4px; }

        .form-heading {
            font-family: 'DM Serif Display', serif;
            font-size: 2rem; color:var(--slate-900);
            line-height: 1.15; margin-bottom:6px;
        }
        .form-sub {
            font-size: 13.5px; color:var(--slate-500);
            margin-bottom: 28px; line-height:1.55;
        }

        /* Alerts */
        .alert-box { border-radius:var(--r-sm); padding:11px 14px; font-size:13px; margin-bottom:16px; display:none; align-items:flex-start; gap:9px; animation:alertSlide 0.3s ease; }
        @keyframes alertSlide { from{opacity:0;transform:translateY(-8px);}to{opacity:1;transform:translateY(0);} }
        .alert-box.show { display:flex; }
        .alert-box.success { background:#f0fdf4; border:1px solid #bbf7d0; color:#166534; }
        .alert-box.danger  { background:var(--danger-bg); border:1px solid #fecaca; color:#991b1b; }
        .alert-backend { border-radius:var(--r-sm); padding:11px 14px; font-size:13px; margin-bottom:16px; display:flex; align-items:flex-start; gap:9px; background:var(--danger-bg); border:1px solid #fecaca; color:#991b1b; }

        /* Fields */
        .field { margin-bottom:16px; }
        .field-label { display:block; font-size:13px; font-weight:600; color:var(--slate-700); margin-bottom:7px; }
        .field-wrap { position:relative; }
        .field-icon { position:absolute; left:15px; top:50%; transform:translateY(-50%); font-size:15px; color:var(--slate-400); pointer-events:none; z-index:1; transition:color 0.2s; }
        .field-input {
            width:100%; height:50px;
            padding:0 46px;
            border:1.5px solid var(--slate-200);
            border-radius:var(--r-md);
            background:var(--slate-50);
            font-family:'DM Sans',sans-serif;
            font-size:14px; color:var(--slate-900);
            transition:all 0.22s ease; outline:none;
        }
        .field-input::placeholder { color:var(--slate-400); }
        .field-input:hover { border-color:var(--mint-200); background:var(--white); }
        .field-input:focus { border-color:var(--green-400); background:var(--white); box-shadow:0 0 0 4px rgba(52,212,116,0.14); }
        .field-wrap:focus-within .field-icon { color:var(--green-500); }
        .field-input.is-invalid { border-color:var(--danger); background:var(--danger-bg); }
        .field-input.is-invalid:focus { box-shadow:0 0 0 4px rgba(239,68,68,0.12); }

        .eye-btn { position:absolute; right:14px; top:50%; transform:translateY(-50%); background:none; border:none; cursor:pointer; font-size:14px; color:var(--slate-400); padding:4px; transition:color 0.2s; }
        .eye-btn:hover { color:var(--green-600); }

        .options-row { display:flex; align-items:center; justify-content:space-between; margin-bottom:20px; }
        .check-label { display:flex; align-items:center; gap:8px; font-size:13px; color:var(--slate-500); cursor:pointer; user-select:none; }
        .check-label input { display:none; }
        .check-box { width:18px; height:18px; border:1.5px solid var(--slate-300); border-radius:5px; background:var(--white); display:flex; align-items:center; justify-content:center; transition:all 0.2s; flex-shrink:0; }
        .check-label input:checked + .check-box { background:var(--green-500); border-color:var(--green-500); }
        .check-label input:checked + .check-box::after { content:''; display:block; width:5px; height:8px; border:2px solid white; border-top:none; border-left:none; transform:rotate(45deg) translateY(-1px); }
        .forgot-link { font-size:13px; font-weight:600; color:var(--green-600); text-decoration:none; transition:color 0.2s; }
        .forgot-link:hover { color:var(--green-500); }

        .btn-submit {
            width:100%; height:52px;
            border:none; border-radius:var(--r-md);
            background:var(--green-500);
            color:var(--white);
            font-family:'DM Sans',sans-serif;
            font-size:15px; font-weight:700;
            cursor:pointer;
            display:flex; align-items:center; justify-content:center; gap:10px;
            position:relative; overflow:hidden;
            transition:all 0.25s ease;
        }
        .btn-submit::before { content:''; position:absolute; inset:0; background:linear-gradient(to right,rgba(255,255,255,0.08),transparent); }
        .btn-submit:hover:not(:disabled) { background:var(--green-600); transform:translateY(-2px); box-shadow:0 10px 30px rgba(22,163,74,0.28); }
        .btn-submit:active:not(:disabled) { transform:translateY(0); }
        .btn-submit:disabled { opacity:0.65; cursor:not-allowed; }
        .btn-submit.loading::after { content:''; position:absolute; inset:0; background:linear-gradient(90deg,transparent,rgba(255,255,255,0.18),transparent); animation:shimmer 1s infinite; }
        @keyframes shimmer { from{transform:translateX(-100%);}to{transform:translateX(100%);} }
        .spin { width:18px; height:18px; border:2.5px solid rgba(255,255,255,0.35); border-top-color:white; border-radius:50%; animation:spinning 0.8s linear infinite; display:none; }
        @keyframes spinning { to{transform:rotate(360deg);} }

        .divider { display:flex; align-items:center; gap:12px; margin:20px 0; }
        .div-line { flex:1; height:1px; background:var(--slate-200); }
        .div-text { font-size:12px; color:var(--slate-400); font-weight:500; }

        .btn-sso {
            width:100%; height:48px;
            border:1.5px solid var(--slate-200); border-radius:var(--r-md);
            background:var(--white); color:var(--slate-700);
            font-family:'DM Sans',sans-serif; font-size:13.5px; font-weight:600;
            cursor:pointer; display:flex; align-items:center; justify-content:center; gap:10px;
            text-decoration:none; transition:all 0.22s;
        }
        .btn-sso:hover { border-color:var(--green-400); color:var(--green-700); background:var(--mint-50); transform:translateY(-1px); box-shadow:0 4px 12px rgba(22,163,74,0.1); }
        .sso-badge { width:22px; height:22px; background:var(--green-500); border-radius:6px; display:flex; align-items:center; justify-content:center; font-size:11px; color:white; }

        .form-footer { margin-top:22px; text-align:center; }
        .footer-help { font-size:12.5px; color:var(--slate-500); text-decoration:none; transition:color 0.2s; }
        .footer-help:hover { color:var(--green-600); }
        .footer-copy { font-size:11.5px; color:var(--slate-400); margin-top:5px; }

        .shake { animation:shake 0.4s ease; }
        @keyframes shake { 0%,100%{transform:translateX(0);}20%,60%{transform:translateX(-5px);}40%,80%{transform:translateX(5px);} }

        /* ===== RESPONSIVE ===== */
        @media (max-width:900px) {
            .panel-left { flex:0 0 40%; padding:40px 28px; }
            .panel-right { padding:44px 36px; }
            .brand-name { font-size:2rem; }
            .mc-3 { display:none; }
        }
        @media (max-width:700px) {
            body { padding:16px; align-items:flex-start; }
            .login-card { flex-direction:column; border-radius:var(--r-lg); }
            .panel-left { flex:none; padding:32px 28px 26px; min-height:230px; }
            .dc-3,.mc-1,.mc-2,.mc-3 { display:none; }
            .logo-ring { width:74px; height:74px; margin-bottom:14px; }
            .logo-ring img { width:52px; height:52px; }
            .brand-name { font-size:1.75rem; }
            .brand-desc { font-size:12.5px; }
            .panel-right { padding:32px 26px 28px; }
            .form-heading { font-size:1.6rem; }
        }
        @media (max-width:420px) {
            body { padding:12px; }
            .panel-left { padding:24px 20px 20px; min-height:195px; }
            .logo-ring { width:64px; height:64px; }
            .logo-ring img { width:44px; height:44px; }
            .brand-name { font-size:1.5rem; }
            .brand-desc { display:none; }
            .brand-pill { font-size:10px; }
            .panel-right { padding:26px 20px 22px; }
            .form-heading { font-size:1.4rem; }
            .field-input { height:46px; font-size:13.5px; }
            .btn-submit { height:48px; font-size:14px; }
        }
    </style>
</head>
<body>
    <div class="bg-blob bg-blob-1"></div>
    <div class="bg-blob bg-blob-2"></div>
    <div class="bg-blob bg-blob-3"></div>

    <div class="login-card">

        <!-- LEFT -->
        <div class="panel-left">
            <div class="deco-circle dc-1"></div>
            <div class="deco-circle dc-2"></div>
            <div class="deco-circle dc-3"></div>


            <div class="logo-area">
                <div class="logo-ring">
                    <img src="{{ asset('gambar/lanri.png') }}" alt="Logo LAN RI">
                </div>
                <div class="brand-name">JABLAYMEN</div>
                <div class="brand-pill">Layanan Dokumen SDM Online</div>
                <p class="brand-desc">Jalur Bersama Layanan Dokumen SDM Online — sistem terpadu administrasi kepegawaian LAN RI.</p>
            </div>
        </div>

        <!-- RIGHT -->
        <div class="panel-right">
            <div class="form-badge">
                <div class="badge-dot"></div>
                <span class="badge-text">Portal Resmi Pegawai Pusjar SKMP</span>
            </div>

            <h1 class="form-heading">Selamat Datang<br>Kembali 👋</h1>
            <p class="form-sub"></p>

            <div class="alert-box success" id="alertSuccess">
                <i class="fas fa-check-circle" style="margin-top:1px;flex-shrink:0;"></i>
                <span>Login berhasil! Mengarahkan ke dashboard…</span>
            </div>
            <div class="alert-box danger" id="alertError">
                <i class="fas fa-exclamation-circle" style="margin-top:1px;flex-shrink:0;"></i>
                <span id="errMsg"></span>
            </div>

            @if(session('error'))
            <div class="alert-backend">
                <i class="fas fa-exclamation-circle" style="margin-top:1px;flex-shrink:0;"></i>
                <span>{{ session('error') }}</span>
            </div>
            @endif

            <form id="loginForm" action="{{ route('login.submit') }}" method="POST" novalidate>
                @csrf

                <div class="field">
                    <label class="field-label" for="email">Email</label>
                    <div class="field-wrap">
                        <i class="fas fa-envelope field-icon"></i>
                        <input type="email" id="email" name="email" class="field-input"
                            placeholder="nama@lan.go.id"
                            value="{{ old('email') }}"
                            autocomplete="email" required>
                    </div>
                </div>

                <div class="field">
                    <label class="field-label" for="password">Kata Sandi</label>
                    <div class="field-wrap">
                        <i class="fas fa-lock field-icon"></i>
                        <input type="password" id="password" name="password" class="field-input"
                            placeholder="Masukkan kata sandi Anda"
                            autocomplete="current-password" required>
                        <button type="button" class="eye-btn" id="eyeBtn" aria-label="Tampilkan kata sandi">
                            <i class="fas fa-eye-slash" id="eyeIcon"></i>
                        </button>
                    </div>
                </div>

              

                <button type="submit" class="btn-submit" id="btnSubmit">
                    <div class="spin" id="spinner"></div>
                    <span id="btnLabel">Masuk</span>
                    <i class="fas fa-arrow-right" id="btnArrow" style="font-size:12px;"></i>
                </button>
            </form>


            <div class="form-footer">
                <a href="#" class="footer-help">Butuh bantuan? Hubungi Administrator Sistem</a>
                <p class="footer-copy">© 2025 Lembaga Administrasi Negara RI — Hak Cipta Dilindungi</p>
            </div>
        </div>
    </div>

    <script>
    (function(){
        var form=document.getElementById('loginForm'),
            btn=document.getElementById('btnSubmit'),
            btnLabel=document.getElementById('btnLabel'),
            btnArrow=document.getElementById('btnArrow'),
            spinner=document.getElementById('spinner'),
            aSuccess=document.getElementById('alertSuccess'),
            aError=document.getElementById('alertError'),
            errMsg=document.getElementById('errMsg'),
            emailEl=document.getElementById('email'),
            passEl=document.getElementById('password'),
            eyeBtn=document.getElementById('eyeBtn'),
            eyeIcon=document.getElementById('eyeIcon');

        eyeBtn.addEventListener('click',function(){
            var show=passEl.type==='password';
            passEl.type=show?'text':'password';
            eyeIcon.className=show?'fas fa-eye':'fas fa-eye-slash';
        });

        function showErr(msg,el){
            aSuccess.classList.remove('show');
            errMsg.textContent=msg;
            aError.classList.add('show');
            if(el){
                el.classList.add('is-invalid','shake');
                el.addEventListener('animationend',function(){el.classList.remove('shake');},{once:true});
                el.focus();
            }
            setTimeout(function(){aError.classList.remove('show');},6000);
        }

        emailEl.addEventListener('blur',function(){
            var re=/^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            this.classList.toggle('is-invalid',!!this.value&&!re.test(this.value));
        });
        emailEl.addEventListener('input',function(){
            if(this.classList.contains('is-invalid')&&/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(this.value)){
                this.classList.remove('is-invalid');
                aError.classList.remove('show');
            }
        });
        passEl.addEventListener('input',function(){
            this.classList.toggle('is-invalid',this.value.length>0&&this.value.length<3);
            if(this.value.length>=3) aError.classList.remove('show');
        });

        form.addEventListener('submit',function(e){
            aSuccess.classList.remove('show');
            aError.classList.remove('show');
            var email=emailEl.value.trim(),pass=passEl.value,re=/^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if(!email){e.preventDefault();showErr('Email tidak boleh kosong.',emailEl);return;}
            if(!re.test(email)){e.preventDefault();showErr('Format email tidak valid. Gunakan email instansi.',emailEl);return;}
            if(pass.length<3){e.preventDefault();showErr('Kata sandi minimal 3 karakter.',passEl);return;}
            btn.disabled=true;
            btn.classList.add('loading');
            spinner.style.display='block';
            btnLabel.textContent='Memproses…';
            btnArrow.style.display='none';
        });
    })();
    </script>
</body>
</html>