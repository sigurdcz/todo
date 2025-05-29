# 🗒️ TODO Aplikace v čistém PHP & JS

Tato aplikace je jednoduchý TODO systém napsaný v čistém PHP a JavaScriptu bez použití frameworků, composeru nebo buildovacích nástrojů. Architektura je postavena dle MVVM principu a respektuje zásady SOLID, DRY a YAGNI.

---

## 📐 Architektura a technologie

* **PHP:** Čistý PHP backend
* **JS:** Čistý JavaScript pro frontend
* **Bez frameworků:** Žádný Composer, Webpack nebo jiný framework – vše je ručně napsané
* **Architektura:** MVVM (Model-View-ViewModel)
* **Principy:** SOLID, DRY, YAGNI
* **Dependency Injection:** Manuální přes bootstrap
* **Konfigurace:** `.env` soubor pro správu globálních proměnných
* **Migrace:** Chronologický systém migrací s historií v databázi
* **Databáze:** PDO + vlastní lehký wrapper (připravený pro budoucí Repository vrstvu)
* **Logování:** Jednoduchý logger pro `info` a `error` logy

---

## 📦 Instalace

1. Zkopíruj projekt na server s podporou PHP 8+
2. Nakonfiguruj `.env` soubor podle vzoru `.env.example`
3. Spusť migrace:

   ```
   GET /migrate
   ```

---

## 🔐 Autentizace

* Přihlašování: `/auth/login`
* Registrace: `/auth/register`
* Odhlášení: `/auth/logout`

---

## 🧩 API Přehled

Všechny `/todo` a root (`/`) cesty vyžadují autorizaci přes `AuthMiddleware`.

### 🧑‍💼 Auth

| Metoda | Cesta            | Popis                         |
| ------ | ---------------- | ----------------------------- |
| GET    | `/auth/login`    | Zobrazí přihlašovací formulář |
| POST   | `/auth/login`    | Zpracuje přihlášení           |
| GET    | `/auth/register` | Zobrazí registrační formulář  |
| POST   | `/auth/register` | Zpracuje registraci           |
| GET    | `/auth/logout`   | Odhlásí uživatele             |

---

### ✅ TODO

| Metoda | Cesta                               | Popis                               |
| ------ | ----------------------------------- | ----------------------------------- |
| GET    | `/todo`                             | Vrací seznam všech TODO listů       |
| GET    | `/todo/{listId}`                    | Vrací detail konkrétního TODO listu |
| GET    | `/todo/list/{listId}`               | Vrací seznam úkolů v TODO listu     |
| POST   | `/todo/list/{listId}/task`          | Přidá nový úkol do listu            |
| PUT    | `/todo/list/{listId}/task/{taskId}` | Aktualizuje úkol                    |
| DELETE | `/todo/list/{listId}/task/{taskId}` | Smaže úkol z listu                  |

---

### 🧱 Migrace

| Metoda | Cesta      | Popis                   |
| ------ | ---------- | ----------------------- |
| GET    | `/migrate` | Spustí migrace databáze |

---

## 🛡️ Bezpečnost

* Middleware autentizace chrání klíčové cesty
* PDO s připravenými dotazy chrání před SQL injection
* Migrace nejsou za autorizací
* API je přístupné bez beareru 
* Nejsou ochrany typu FireWalker, DDOS, LoginAtemp atp...
* Migrace řes šifrovaný cron
* je toho hodně..
* Přechod na clou

---

## 📋 Poznámky

* Repository vrstva bude brzy doplněna – již je připravený databázový wrapper
* Logger může být dále rozšířen o různé kanály (soubor, mail, apod.)

---

## 📞 Vývoj a TODO

- Vyvinuto s pomocí ChatGPT

### TODO
- Prihlasovani pres PWD (login bude hash v url) 
- vyřešení bezpečnosti
- SEO
- komprimace & stare JS stable JS/CSS (jako webpack nebo babel)
- Použití STAN, Unity testů, Integračních testů
- Použití spíše VUE/React ?
- ... 

