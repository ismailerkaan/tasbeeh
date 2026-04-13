<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gizlilik Sozlesmesi | Tasbeeh App</title>
    <style>
        :root {
            --bg: #f5f7fb;
            --card: #ffffff;
            --text: #1f2937;
            --muted: #6b7280;
            --primary: #10b981;
            --border: #e5e7eb;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            background: var(--bg);
            color: var(--text);
            font-family: "Montserrat", "Segoe UI", sans-serif;
            line-height: 1.7;
        }

        .container {
            width: min(920px, 92%);
            margin: 0 auto;
            padding: 28px 0 44px;
        }

        .header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            margin-bottom: 20px;
        }

        .brand {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
            color: var(--text);
            font-weight: 700;
        }

        .brand img {
            width: 40px;
            height: 40px;
            object-fit: contain;
        }

        .back-link {
            text-decoration: none;
            color: var(--primary);
            font-weight: 600;
        }

        .card {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 16px;
            padding: 24px;
        }

        h1 {
            margin: 0 0 8px;
            font-size: clamp(1.4rem, 3vw, 2rem);
        }

        .updated {
            margin: 0 0 18px;
            color: var(--muted);
            font-size: 0.95rem;
        }

        h2 {
            margin: 20px 0 8px;
            font-size: 1.06rem;
        }

        p {
            margin: 0;
        }

        ul {
            margin: 8px 0 0;
            padding-left: 20px;
        }
    </style>
</head>
<body>
<main class="container">
    <header class="header">
        <a class="brand" href="/">
            <img src="{{ asset('assets/images/logos.png') }}" alt="Tasbeeh App Logo">
            <span>Tasbeeh App</span>
        </a>
        <a class="back-link" href="/">Ana Sayfa</a>
    </header>

    <section class="card">
        <h1>Gizlilik Sozlesmesi</h1>
        <p class="updated">Son guncelleme: {{ now()->format('d.m.Y') }}</p>

        <h2>1. Toplanan Veriler</h2>
        <p>
            Uygulama deneyimini iyilestirmek icin kullanici kimligi, cihaz bilgileri, konum secimi, bildirim tokeni ve
            uygulama icindeki okuma/zikir ilerleme verileri islenebilir.
        </p>

        <h2>2. Verilerin Kullanim Amaci</h2>
        <p>
            Toplanan veriler; icerik senkronizasyonu, kullaniciya ozel bildirim gonderimi, teknik sorun tespiti ve hizmet kalitesinin
            artirilmasi amaciyla kullanilir.
        </p>

        <h2>3. Veri Paylasimi</h2>
        <p>
            Kisisel veriler, yasal zorunluluklar disinda ucuncu kisilerle satilmaz veya ticari amacla paylasilmaz. Bildirim ve altyapi
            surecleri icin teknik hizmet saglayicilar kullanilabilir.
        </p>

        <h2>4. Veri Saklama ve Guvenlik</h2>
        <p>
            Veriler guvenli sistemlerde saklanir ve yetkisiz erisime karsi teknik/yonetsel onlemler uygulanir. Gereksiz hale gelen veriler
            makul surelerde silinir veya anonimlestirilir.
        </p>

        <h2>5. Kullanici Haklari</h2>
        <p>Kullanicilar asagidaki haklara sahiptir:</p>
        <ul>
            <li>Hangi verilerin islendigi konusunda bilgi talep etme,</li>
            <li>Yanlis veya eksik verilerin duzeltilmesini isteme,</li>
            <li>Uygun durumlarda verilerin silinmesini talep etme,</li>
            <li>Bildirim izinlerini cihaz ayarlarindan degistirme.</li>
        </ul>

        <h2>6. Iletisim</h2>
        <p>
            Gizlilikle ilgili tum sorulariniz icin uygulama icindeki "Geri Bildirim Gonder" alani uzerinden bize ulasabilirsiniz.
        </p>
    </section>
</main>
</body>
</html>
