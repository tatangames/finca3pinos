<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Coffee Market — Demo</title>
    <style>
        :root{
            --bg:#0f0f0f;            /* wood/black backdrop */
            --bg-2:#1a1a1a;          /* dark panels */
            --ink:#111;              /* text dark */
            --paper:#fff;            /* text light */
            --muted:#b9b6b1;         /* subtle text */
            --brand:#c4a477;         /* golden-beige accent */
            --brand-2:#aa8a5d;       /* hover accent */
            --max:1200px;
        }
        *{box-sizing:border-box}
        html,body{height:100%}
        body{
            margin:0; font-family: ui-sans-serif, system-ui, -apple-system, Segoe UI, Roboto, "Helvetica Neue", Arial, "Apple Color Emoji","Segoe UI Emoji";
            color:var(--paper); background:var(--bg);
        }
        a{color:inherit; text-decoration:none}
        .container{width:100%; max-width:var(--max); margin-inline:auto; padding-inline:24px}

        /* ───────────────── NAVBAR ───────────────── */
        .nav{
            position:fixed; inset-block-start:0; inset-inline:0; z-index:50;
            background:transparent; transition:background .25s ease, box-shadow .25s ease;
        }
        .nav.is-scrolled{background:rgba(15,15,15,.86); backdrop-filter:saturate(140%) blur(8px); box-shadow:0 2px 0 rgba(255,255,255,.04)}
        .nav-row{display:flex; align-items:center; gap:20px; padding:18px 0}
        .logo{display:flex; align-items:center; gap:10px; font-weight:800; letter-spacing:.2px}
        .logo svg{inline-size:32px; block-size:32px}

        .nav-main{margin-left:auto; display:flex; align-items:center; gap:20px}
        .menu{display:flex; align-items:center; gap:22px}
        .menu > li{position:relative; list-style:none}
        .menu > li > a{font-weight:700; letter-spacing:.4px}
        .menu > li > a:hover{opacity:.9}

        /* desktop dropdowns */
        .dropdown{position:absolute; inset-block-start:120%; inset-inline-start:0; min-inline-size:240px; background:#121212; border:1px solid rgba(255,255,255,.06); border-radius:10px; padding:12px; opacity:0; transform:translateY(-4px); pointer-events:none; transition:all .18s ease}
        .menu > li:hover .dropdown{opacity:1; transform:translateY(0); pointer-events:auto}
        .dropdown a{display:block; padding:10px 12px; border-radius:8px; color:#e9e6e2}
        .dropdown a:hover{background:rgba(255,255,255,.06)}

        /* icons (cart/search) */
        .actions{display:flex; gap:14px; margin-left:8px}
        .btn-icon{display:grid; place-items:center; inline-size:36px; block-size:36px; border-radius:999px; background:rgba(255,255,255,.06); border:1px solid rgba(255,255,255,.08)}
        .btn-icon:hover{background:rgba(255,255,255,.12)}

        /* hamburger (mobile) */
        .hamburger{display:none}
        .hamburger-btn{display:grid; place-items:center; inline-size:44px; block-size:44px; border-radius:12px; border:1px solid rgba(255,255,255,.1); background:rgba(255,255,255,.06)}
        .hamburger-btn:hover{background:rgba(255,255,255,.12)}

        /* ───────────────── HERO ───────────────── */
        .hero{
            position:relative; display:grid; place-items:center; min-block-size:92vh; padding-top:96px; isolation:isolate;
            background:radial-gradient(80% 80% at 50% 40%, #232323 0%, #0f0f0f 65%);
        }
        /* Optional: put your hero background image here */
        /* .hero{ background: url('https://images.unsplash.com/photo-1500989145603-8e7ef71d639e?q=80&w=2400&auto=format&fit=crop') center/cover fixed; } */

        .title{font-size: clamp(42px, 7vw, 96px); font-weight:900; line-height:.9; text-align:center; letter-spacing:1px; word-spacing:6px; filter:drop-shadow(0 8px 24px rgba(0,0,0,.55))}
        .title .script{font-family: "Pacifico", cursive, system-ui; font-weight:400}

        .cta{margin-top:28px; display:inline-flex; align-items:center; gap:10px; padding:14px 22px; background:var(--brand); color:#222; border:0; border-radius:12px; font-weight:800; text-transform:none; cursor:pointer}
        .cta:hover{background:var(--brand-2)}

        /* ───────────────── MOBILE MENU PANEL ───────────────── */
        .panel{position:fixed; inset:0; z-index:60; background:rgba(0,0,0,.35); backdrop-filter:blur(2px); opacity:0; pointer-events:none; transition:opacity .2s ease}
        .panel[aria-hidden="false"]{opacity:1; pointer-events:auto}
        .sheet{position:absolute; inset-block:0; inset-inline-end:0; inline-size:min(86vw, 420px); background:linear-gradient(#bfa177,#bfa177); color:#111; transform:translateX(100%); transition:transform .28s cubic-bezier(.2,.8,.2,1); overflow:auto}
        .panel[aria-hidden="false"] .sheet{transform:none}
        .sheet-header{display:flex; align-items:center; justify-content:space-between; padding:18px 16px; border-block-end:1px solid rgba(0,0,0,.08)}
        .sheet-title{font-weight:800; letter-spacing:.6px}
        .sheet-close{inline-size:44px; block-size:44px; border-radius:999px; border:0; background:#efe7db; display:grid; place-items:center; font-size:26px}
        .sheet section{padding:16px}
        .sheet details{background:#fff; border-radius:14px; margin-block:10px; overflow:hidden}
        .sheet summary{cursor:pointer; list-style:none; padding:18px 18px; font-weight:900; font-size:20px; display:flex; align-items:center; justify-content:space-between}
        .sheet summary::-webkit-details-marker{display:none}
        .sheet ul{margin:0; padding:0 18px 14px 18px}
        .sheet li{list-style:none; padding:12px 0; border-bottom:1px solid #eee}
        .sheet li:last-child{border-bottom:0}

        /* ───────────────── RESPONSIVE ───────────────── */
        @media (max-width: 1024px){
            .menu{display:none}
            .actions{display:none}
            .hamburger{display:block; margin-left:auto}
            .nav-row{padding-block:12px}
        }
    </style>
    <!-- Optional display font for the script-like title (loads fast). Remove if you don't want it. -->
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Pacifico&display=swap" rel="stylesheet">
</head>
<body>
<!-- ─────────────── NAVBAR ─────────────── -->
<nav class="nav" id="nav" aria-label="Primary">
    <div class="container nav-row">
        <a href="#" class="logo" aria-label="CoffeeKing home">
            <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                <path d="M3 13c0 3.866 3.582 7 8 7s8-3.134 8-7V7H3v6Z" stroke="#c7a97a" stroke-width="1.5"/>
                <path d="M18 9c1.657 0 3 1.343 3 3s-1.343 3-3 3" stroke="#c7a97a" stroke-width="1.5"/>
            </svg>
            <span>Coffee<span style="color:#c7a97a">King</span></span>
        </a>

        <ul class="menu" role="menubar" aria-label="Main menu">
            <li role="none"><a role="menuitem" href="#home">Home</a></li>
            <li role="none">
                <a role="menuitem" href="#about" aria-haspopup="true" aria-expanded="false">About us ▾</a>
                <div class="dropdown" role="menu" aria-label="About us submenu">
                    <a href="#about">About us</a>
                    <a href="#delivery">Delivery</a>
                    <a href="#events">Events</a>
                    <a href="#offers">Special offers</a>
                    <a href="#testimonials">Testimonials</a>
                    <a href="#team">Team</a>
                    <a href="#faq">FAQ</a>
                </div>
            </li>
            <li role="none">
                <a role="menuitem" href="#products" aria-haspopup="true" aria-expanded="false">Products ▾</a>
                <div class="dropdown" role="menu" aria-label="Products submenu">
                    <a href="#shop">Shop</a>
                    <a href="#cart">Cart</a>
                    <a href="#checkout">Checkout</a>
                    <a href="#account">My account</a>
                </div>
            </li>
            <li role="none"><a role="menuitem" href="#blog">Blog</a></li>
            <li role="none"><a role="menuitem" href="#gallery">Gallery</a></li>
            <li role="none"><a role="menuitem" href="#contacts">Contacts</a></li>
            <li role="none"><a role="menuitem" href="#pages">Pages</a></li>
        </ul>

        <div class="actions" aria-hidden="true">
            <a class="btn-icon" href="#search" title="Search" aria-label="Search">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none"><circle cx="11" cy="11" r="7" stroke="#fff" stroke-width="1.6"/><path d="M20 20l-3.5-3.5" stroke="#fff" stroke-width="1.6"/></svg>
            </a>
            <a class="btn-icon" href="#cart" title="Cart" aria-label="Cart">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none"><path d="M3 5h2l2 12h10l2-9H7" stroke="#fff" stroke-width="1.6"/><circle cx="9" cy="20" r="1.5" fill="#fff"/><circle cx="17" cy="20" r="1.5" fill="#fff"/></svg>
            </a>
        </div>

        <div class="hamburger">
            <button id="openPanel" class="hamburger-btn" aria-expanded="false" aria-controls="mobilePanel" aria-label="Open menu">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M4 7h16M4 12h16M4 17h16" stroke="#fff" stroke-width="1.8" stroke-linecap="round"/></svg>
            </button>
        </div>
    </div>
</nav>

<!-- ─────────────── HERO ─────────────── -->
<header class="hero" id="home">
    <div class="container" style="text-align:center">
        <h1 class="title"><span class="script">Coffee</span> <span class="script">Market</span></h1>
        <a href="#products" class="cta" role="button">View products
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" style="margin-left:6px"><path d="M5 12h14m0 0-6-6m6 6-6 6" stroke="#222" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
        </a>
    </div>
</header>

<!-- ─────────────── MOBILE PANEL ─────────────── -->
<div class="panel" id="mobilePanel" aria-hidden="true">
    <aside class="sheet" role="dialog" aria-modal="true" aria-labelledby="menuTitle">
        <div class="sheet-header">
            <div class="sheet-title" id="menuTitle">Menu</div>
            <button class="sheet-close" id="closePanel" aria-label="Close menu">×</button>
        </div>

        <section>
            <details open>
                <summary>HOME <span>›</span></summary>
                <ul>
                    <li><a href="#home" onclick="closePanel()">Hero</a></li>
                </ul>
            </details>
            <details>
                <summary>ABOUT US <span>›</span></summary>
                <ul>
                    <li><a href="#about" onclick="closePanel()">About us</a></li>
                    <li><a href="#delivery" onclick="closePanel()">Delivery</a></li>
                    <li><a href="#events" onclick="closePanel()">Events</a></li>
                    <li><a href="#offers" onclick="closePanel()">Special offers</a></li>
                    <li><a href="#testimonials" onclick="closePanel()">Testimonials</a></li>
                    <li><a href="#team" onclick="closePanel()">Team</a></li>
                    <li><a href="#faq" onclick="closePanel()">FAQ</a></li>
                </ul>
            </details>
            <details>
                <summary>PRODUCTS <span>›</span></summary>
                <ul>
                    <li><a href="#shop" onclick="closePanel()">Shop</a></li>
                    <li><a href="#cart" onclick="closePanel()">Cart</a></li>
                    <li><a href="#checkout" onclick="closePanel()">Checkout</a></li>
                    <li><a href="#account" onclick="closePanel()">My account</a></li>
                </ul>
            </details>
            <details>
                <summary>BLOG <span>›</span></summary>
                <ul>
                    <li><a href="#blog" onclick="closePanel()">Latest posts</a></li>
                </ul>
            </details>
            <details>
                <summary>GALLERY <span>›</span></summary>
                <ul>
                    <li><a href="#gallery" onclick="closePanel()">Photos</a></li>
                </ul>
            </details>
            <details>
                <summary>CONTACTS <span>›</span></summary>
                <ul>
                    <li><a href="#contacts" onclick="closePanel()">Contact us</a></li>
                </ul>
            </details>
            <details>
                <summary>PAGES <span>›</span></summary>
                <ul>
                    <li><a href="#pages" onclick="closePanel()">All pages</a></li>
                </ul>
            </details>
        </section>
    </aside>
</div>

<!-- ─────────────── FILLER SECTIONS (placeholders) ─────────────── -->
<main id="products" class="container" style="padding:64px 24px 120px 24px">
    <h2 style="font-size:32px; margin:0 0 18px 0">Featured products</h2>
    <p style="color:#d8d6d3; max-width:70ch">This is a clean, mobile-first recreation with a sticky transparent navbar, desktop hover dropdowns and a mobile slide-out menu. Replace this placeholder with your real product grid or Blade components.</p>
</main>

<script>
    /* Sticky nav appearance */
    const nav = document.getElementById('nav');
    const onScroll = () => nav.classList.toggle('is-scrolled', window.scrollY > 10);
    window.addEventListener('scroll', onScroll); onScroll();

    /* Mobile panel */
    const panel = document.getElementById('mobilePanel');
    const openBtn = document.getElementById('openPanel');
    const closeBtn = document.getElementById('closePanel');

    function openPanel(){ panel.setAttribute('aria-hidden','false'); openBtn.setAttribute('aria-expanded','true'); document.body.style.overflow='hidden'; }
    function closePanel(){ panel.setAttribute('aria-hidden','true'); openBtn.setAttribute('aria-expanded','false'); document.body.style.overflow=''; }

    openBtn.addEventListener('click', openPanel);
    closeBtn.addEventListener('click', closePanel);
    panel.addEventListener('click', (e)=>{ if(e.target === panel) closePanel(); });
    window.addEventListener('keydown', (e)=>{ if(e.key==='Escape') closePanel(); });
</script>
</body>
</html>
