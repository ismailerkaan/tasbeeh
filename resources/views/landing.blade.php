<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tasbeeh App</title>
    <style>
        :root {
            --bg-1: #06111e;
            --bg-2: #0b1f38;
            --accent: #10b981;
            --accent-2: #22d3ee;
            --text: #f8fafc;
            --muted: #94a3b8;
            --card: rgba(15, 23, 42, 0.72);
            --border: rgba(148, 163, 184, 0.22);
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            min-height: 100vh;
            font-family: "Montserrat", "Segoe UI", sans-serif;
            color: var(--text);
            background:
                radial-gradient(circle at 10% 10%, rgba(16, 185, 129, 0.2), transparent 36%),
                radial-gradient(circle at 90% 20%, rgba(34, 211, 238, 0.18), transparent 30%),
                linear-gradient(135deg, var(--bg-1), var(--bg-2));
        }

        .wrapper {
            width: min(1120px, 92%);
            margin: 0 auto;
            padding: 32px 0 56px;
        }

        .top {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 16px;
            margin-bottom: 40px;
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
            color: var(--text);
        }

        .logo img {
            width: 46px;
            height: 46px;
            object-fit: contain;
            filter: drop-shadow(0 6px 18px rgba(16, 185, 129, 0.35));
        }

        .logo span {
            font-size: 1.05rem;
            font-weight: 700;
            letter-spacing: 0.35px;
        }

        .btn {
            border: 1px solid transparent;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 11px 18px;
            border-radius: 12px;
            font-weight: 600;
            transition: 0.2s ease;
        }

        .btn-primary {
            color: #03231a;
            background: linear-gradient(90deg, var(--accent), var(--accent-2));
            box-shadow: 0 14px 28px rgba(34, 211, 238, 0.22);
        }

        .btn-primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 18px 36px rgba(34, 211, 238, 0.28);
        }

        .hero {
            display: grid;
            grid-template-columns: 1.3fr 1fr;
            gap: 22px;
            align-items: stretch;
        }

        .hero-main,
        .hero-side {
            border: 1px solid var(--border);
            background: var(--card);
            border-radius: 22px;
            backdrop-filter: blur(6px);
        }

        .hero-main {
            padding: 34px;
        }

        .hero-main h1 {
            margin: 0;
            font-size: clamp(1.8rem, 4vw, 2.8rem);
            line-height: 1.1;
            letter-spacing: -0.6px;
        }

        .hero-main p {
            margin: 14px 0 0;
            color: var(--muted);
            line-height: 1.7;
            max-width: 62ch;
        }

        .hero-actions {
            margin-top: 24px;
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }

        .btn-outline {
            color: var(--text);
            border-color: var(--border);
            background: rgba(15, 23, 42, 0.55);
        }

        .hero-side {
            padding: 22px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            gap: 12px;
        }

        .kpi {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 12px 14px;
            background: rgba(15, 23, 42, 0.52);
        }

        .kpi small {
            color: var(--muted);
            display: block;
            margin-bottom: 4px;
        }

        .kpi strong {
            font-size: 1.12rem;
        }

        .grid {
            margin-top: 20px;
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 12px;
        }

        .feature {
            border: 1px solid var(--border);
            background: rgba(15, 23, 42, 0.55);
            border-radius: 14px;
            padding: 16px;
        }

        .feature h3 {
            margin: 0 0 8px;
            font-size: 1rem;
            line-height: 1.3;
        }

        .feature p {
            margin: 0;
            color: var(--muted);
            font-size: 0.92rem;
            line-height: 1.6;
        }

        .footer {
            margin-top: 28px;
            color: var(--muted);
            font-size: 0.86rem;
            text-align: center;
        }

        @media (max-width: 980px) {
            .hero {
                grid-template-columns: 1fr;
            }

            .grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }

        @media (max-width: 640px) {
            .wrapper {
                padding-top: 20px;
            }

            .top {
                margin-bottom: 22px;
            }

            .hero-main {
                padding: 22px;
            }

            .grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
<main class="wrapper">
    <header class="top">
        <a href="/" class="logo">
            <img src="{{ asset('assets/images/logos.png') }}" alt="Tasbeeh App Logo">
            <span>Tasbeeh App</span>
        </a>
        <a href="{{ route('tasbeeh.download') }}" class="btn btn-primary">Uygulamayı İndir</a>
    </header>

    <section class="hero">
        <article class="hero-main">
            <h1>Zikirmatik, Ezan Vakitleri ve Dua Deneyimi Tek Uygulamada</h1>
            <p>
                Tasbeeh App, kullanıcıların günlük zikir rutinlerini takip etmesini, dua içeriklerine erişmesini ve ezan vakitlerini
                düzenli şekilde kullanmasını hedefleyen mobil uygulama altyapısıdır.
            </p>
            <div class="hero-actions">
                <a href="{{ route('tasbeeh.download') }}" class="btn btn-primary">Hemen İndir</a>
            </div>
        </article>

        <aside class="hero-side">
            <div class="kpi">
                <div>
                    <small>Yönetim</small>
                    <strong>Versiyon Kontrollü İçerik</strong>
                </div>
            </div>
            <div class="kpi">
                <div>
                    <small>Bildirim</small>
                    <strong>Kullanıcı / Tüm Kullanıcı</strong>
                </div>
            </div>
            <div class="kpi">
                <div>
                    <small>Takip</small>
                    <strong>Zikir &amp; Dua Etkileşim Analitiği</strong>
                </div>
            </div>
        </aside>
    </section>

    <section class="grid">
        <article class="feature">
            <h3>Zikir Yönetimi</h3>
            <p>Kategori bazlı zikir CRUD, hedef, anlam ve fazilet alanları ile içerik yönetimi.</p>
        </article>
        <article class="feature">
            <h3>Dua Yönetimi</h3>
            <p>Dua kategorileri ve dua içeriklerinin kaynak ve meal destekli düzenlenmesi.</p>
        </article>
        <article class="feature">
            <h3>Günlük Zikir</h3>
            <p>Tarih bazlı günlük zikir seçimi ve mobil uygulamada kontrollü gösterim.</p>
        </article>
        <article class="feature">
            <h3>Kullanıcı Senkronu</h3>
            <p>Cihaz, lokasyon, okunan içerikler ve toplam zikir sayısının tek endpoint ile senkronu.</p>
        </article>
        <article class="feature">
            <h3>Push Altyapısı</h3>
            <p>FCM tabanlı bildirim kuyruğu, iptal/düzenleme ve durum takibi.</p>
        </article>
        <article class="feature">
            <h3>Raporlama</h3>
            <p>Dashboard metrikleri, top kullanıcılar ve en çok okunan zikir grafikleri.</p>
        </article>
    </section>

    <footer class="footer">
        Tasbeeh App Admin
    </footer>
</main>
</body>
</html>
