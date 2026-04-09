# Workshop - Műhely Foglalási Platform

Webalkalmazás workshopok böngészésére, időpontfoglalásra és kezelésére. Laravel 13, Vue 3 és Inertia.js alapokon, szerepkör-alapú hozzáférés-kezeléssel, törzsvásárlói pontrendszerrel és teljes adminisztrációs felülettel.

<p align="center">
  <a href="https://go-skill-icons.vercel.app/">
    <img src="https://go-skill-icons.vercel.app/api/icons?i=laravel,vuejs,typescript,inertia,tailwindcss,shadcn,postgresql,php,vite,bun,git&titles=true&theme=light" />
  </a>
</p>

---

## Live Demo

🔗 [https://workshop-main-2sgave.free.laravel.cloud](https://workshop-main-2sgave.free.laravel.cloud)

### Test Accounts

| Szerepkör | Email | Jelszó |
|---|---|---|
| Admin | `admin@w.hu` | `a` |
| Óraadó | `ora@w.hu` | `a` |
| Felhasználó | `a@w.hu` | `a` |

---

## Felhasznált Technológiák

`Laravel 13` · `Vue 3` · `TypeScript` · `Inertia.js` · `Tailwind CSS` · `shadcn-vue` · `PostgreSQL` · `Spatie Laravel Permission` · `Ziggy` · `Vite` · `Bun`

---

## Tartalomjegyzék

- [Áttekintés](#áttekintés)
- [Tech Stack](#tech-stack)
- [Architektúra](#architektúra)
- [Adatbázis struktúra](#adatbázis-struktúra)
- [Szerepkörök és jogosultságok](#szerepkörök-és-jogosultságok)
- [Funkciók](#funkciók)
- [Telepítés](#telepítés)
- [Deployment](#deployment)

---

## Áttekintés

A Workshop platform lehetővé teszi, hogy a felhasználók workshopokat (főzés stb.) böngésszenek, időpontot foglaljanak, foglalásaikat kezeljék és törzsvásárlói pontokat gyűjtsenek. Az óraadók saját workshopjaikat és időpontjaikat kezelhetik. Az adminisztrátorok teljes rálátással rendelkeznek minden felhasználóra, óraadóra és tartalomra.

---

## Tech Stack

### Backend

| Technológia | Verzió | Cél |
|---|-------|---|
| **PHP** | 8.3+  | Szerveroldali nyelv |
| **Laravel** | 13    | Alkalmazás keretrendszer |
| **PostgreSQL** | 15+   | Éles adatbázis (Laravel Cloud) |
| **SQLite** |       | Lokális fejlesztési adatbázis |

#### Laravel Csomagok

- **`inertiajs/inertia-laravel`** - Összeköti a Laravel backendet a Vue frontenddel külön API nélkül. A controllerek Inertia válaszokat adnak vissza JSON vagy Blade nézetek helyett.
- **`tightenco/ziggy`** - Elérhetővé teszi a Laravel nevesített route-jait a JavaScript frontendnek, így Vue komponensekben is használható a `route('bookings.create')` szintaxis.
- **`spatie/laravel-permission`** - Szerepkör- és jogosultságkezelés. A `user`, `instructor` és `admin` szerepkörökhöz használjuk. `HasRoles` trait-et biztosít a `User` modellen és `role:instructor` middleware-t a route-ok védelméhez.
- **`laravel/sanctum`** - API token autentikáció, session kezeléshez.

### Frontend

| Technológia |  Cél |
|---|---|
| **Vue 3** | UI keretrendszer |
| **TypeScript** | Típusbiztos JavaScript |
| **Inertia.js** | SPA-szerű navigáció külön API nélkül |
| **Tailwind CSS** | Utility-first CSS keretrendszer |
| **Vite** | Frontend build eszköz és dev szerver |
| **Bun** | JavaScript runtime és csomagkezelő |

#### Frontend Csomagok

- **`@inertiajs/vue3`** - Vue 3 adapter az Inertia.js-hez. `<Link>`, `useForm`, `usePage`, `router` és más Inertia primitíveket biztosít.
- **`shadcn-vue`** - Stílusmentes, akadálymentes UI komponens könyvtár Radix Vue alapokon. Minden UI komponenshez használjuk: `Button`, `Card`, `Table`, `Input`, `Label`, `Badge`, `Tabs`, `Select`, `Separator`, `AlertDialog` stb.
- **`radix-vue`** - Headless primitív komponensek, amelyek a shadcn-vue-t hajtják.
- **`motion-v`** - Vue animációs könyvtár. A nyilvános workshop listázó oldal hero szekciójának belépő animációihoz használjuk.
- **`lucide-vue-next`** - Ikon könyvtár. Az egész interfészen action ikonokhoz.
- **`tailwind-merge`** - Tailwind osztályokat ütközés nélkül egyesít. A shadcn komponensek belsőleg használják.
- **`class-variance-authority`** - Komponens variánsokat kezel (pl. `Button` `variant="destructive"`). A shadcn komponensek belsőleg használják.
- **`clsx`** - Feltételes osztálynév utility.

---

## Architektúra

Az alkalmazás az **Inertia.js monolit mintát** követi:

- A Laravel kezeli a routing-ot, autentikációt, autorizációt és adatlekérést
- A Vue kezeli a renderelést és interaktivitást
- Az Inertia összeköti a kettőt - a controllerek közvetlenül Vue oldal komponenseknek adják át az adatokat propokként, külön REST API nélkül
- A frontend viselkedésében single-page application (nincs teljes oldal újratöltés), de nincs különálló API felület

### Könyvtárstruktúra

```
app/
  Http/
    Controllers/
      BookingController.php       # Felhasználói foglalás létrehozás, szerkesztés, lemondás
      DashboardController.php     # Felhasználói dashboard és foglalások oldal
      InstructorController.php    # Óraadói workshop és session kezelés
      AdminController.php         # Admin felügyelet minden felhasználó és óraadó felett
      WorkshopController.php      # Nyilvános workshop lista és részletek
      ProfileController.php       # Felhasználói profil kezelés
    Middleware/
      HandleInertiaRequests.php   # Auth felhasználó és szerepkörök megosztása minden Vue oldallal
      CheckBanned.php             # Felfüggesztett felhasználók kizárása (Uninplemented még)
  Models/
    User.php
    Workshop.php
    WorkshopSession.php
    Booking.php
    Coupon.php

resources/js/
  Pages/
    Workshops/         # Nyilvános workshop lista és részletek
    Dashboard/         # Felhasználói dashboard (áttekintés, foglalások)
    Bookings/          # Foglalás létrehozás és szerkesztés
    Instructor/        # Óraadói panel (workshopok, sessionök)
    Admin/             # Admin panel (felhasználók)
    Profile/           # Profil beállítások
    Auth/              # Bejelentkezés, regisztráció, jelszó visszaállítás, email megerősítés
  Components/
    ui/                # shadcn-vue komponensek
    WorkshopCard.vue   # Újrahasználható workshop kártya
    LoyaltySection.vue # Hűségprogram marketing szekció
  Layouts/
    AppLayout.vue      # Fő layout szerepkör-tudatos navigációval
    GuestLayout.vue    # Layout nem autentikált oldalakhoz
  lib/
    utils.ts           # Segédfüggvények (cn, php style diffForHumans függvény stb.)
    animations.ts      # motion-v animáció variánsok
```

---

## Adatbázis Struktúra

### `users`
| Oszlop | Típus | Megjegyzés |
|---|---|---|
| `id` | integer | Elsődleges kulcs |
| `name` | string | Teljes név |
| `email` | string | Egyedi, bejelentkezéshez |
| `password` | string | Hashelt |
| `nickname` | string | Opcionális |
| `birth_date` | date | Opcionális |
| `gender` | string | Opcionális |
| `points` | integer | Törzsvásárlói pontegyenleg, alapértelmezett 0 |
| `banned_until` | datetime | Null ha nincs felfüggesztve |
| `email_verified_at` | datetime | Null ha nem megerősített |

### `workshops`
| Oszlop | Típus | Megjegyzés |
|---|---|---|
| `id` | integer | Elsődleges kulcs |
| `user_id` | integer | Idegen kulcs -> `users.id` (az óraadó) |
| `name` | string | Workshop neve |
| `description` | text | Teljes leírás |
| `duration_minutes` | integer | Workshop hossza |
| `price_per_person` | integer | Ár HUF-ban |
| `archived` | boolean | Archivált workshopok rejtve a nyilvános listából |

### `workshop_sessions`
| Oszlop | Típus | Megjegyzés |
|---|---|---|
| `id` | integer | Elsődleges kulcs |
| `workshop_id` | integer | Idegen kulcs -> `workshops.id` |
| `starts_at` | datetime | Session kezdési időpontja |
| `max_capacity` | integer | Maximum résztvevő szám (headcount) |
| `status` | string | `active` vagy `cancelled` |

### `bookings`
| Oszlop | Típus | Megjegyzés |
|---|---|---|
| `id` | integer | Elsődleges kulcs |
| `user_id` | integer | Idegen kulcs -> `users.id` |
| `session_id` | integer | Idegen kulcs -> `workshop_sessions.id` |
| `headcount` | integer | Foglalt személyek száma |
| `amount_paid` | integer | Fizetett összeg HUF-ban |
| `payment_ref` | string | Nullable, fizetési átjáró referenciának fenntartva |
| `status` | string | `confirmed`, `cancelled_by_user`, `cancelled_by_instructor` |

Egyedi megszorítás: `(user_id, session_id)` - egy felhasználónak csak egy foglalása lehet egy sessionre.

### `coupons`
| Oszlop | Típus | Megjegyzés |
|---|---|---|
| `id` | integer | Elsődleges kulcs |
| `user_id` | integer | Nullable - null esetén nem személyre szabott |
| `code` | string | Egyedi beváltási kód |
| `discount_flat` | integer | Fix HUF kedvezmény |
| `discount_pct` | float | Százalékos kedvezmény |
| `used` | boolean | Beváltva volt-e már |

### Spatie Jogosultság Táblák
- `roles` - `user`, `instructor`, `admin`
- `model_has_roles` - felhasználók és szerepkörök közötti pivot

### Fő Kapcsolatok

```
User -> hasMany -> Bookings
User -> hasMany -> Workshops (óraadóként)
User -> hasMany -> Coupons

Workshop -> belongsTo -> User (óraadó)
Workshop -> hasMany -> WorkshopSessions

WorkshopSession -> belongsTo -> Workshop
WorkshopSession -> hasMany -> Bookings

Booking -> belongsTo -> User
Booking -> belongsTo -> WorkshopSession
```

### Kapacitás Számítás

A szabad helyek száma futásidőben kerül kiszámításra a `WorkshopSession` modell `spotsRemaining()` metódusával:

```php
public function spotsRemaining(): int
{
    $sum = $this->bookings()->sum('headcount');
    return $this->max_capacity - $sum;
}
```

Ez összegzi az összes foglalás `headcount` értékét (nem csak a sorok számát), így egy 3 főre szóló foglalás helyesen 3 helyet foglal el. Minden foglalás létrehozási és módosítási művelet adatbázis tranzakción belül ellenőrzi újra a rendelkezésre állást a versenyfeltételek elkerülése érdekében.

---

## Szerepkörök és Jogosultságok

A szerepköröket a `spatie/laravel-permission` csomag kezeli. Minden autentikált felhasználónak pontosan egy szerepköre van.

### `user` (alapértelmezett)
- Nyilvános workshop lista böngészése
- Workshop részletek és elérhető sessionök megtekintése
- Foglalások létrehozása, módosítása és lemondása
- Foglalási előzmények megtekintése a dashboardon (Unimplemented még)
- Törzsvásárlói pontok gyűjtése és megtekintése (Unimplemented még)
- Saját profil kezelése (név, email, jelszó, fiók törlés)

### `instructor`
Minden `user` jogosultság, plusz:
- Workshopok létrehozása (név, leírás, időtartam, ár)
- Saját workshopok szerkesztése és archiválása
- Sessionök létrehozása saját workshopjaikhoz (dátum, idő, max kapacitás)
- Session dátum és kapacitás szerkesztése (kapacitás nem csökkenthető a jelenlegi foglalások alá)
- Sessionök lemondása - az összes kapcsolódó foglalás automatikusan `cancelled_by_instructor` státuszra kerül
- Foglalási számok megtekintése sessionönként

Az óraadók **nem** láthatják más felhasználók személyes adatait.

### `admin`
Minden `instructor` jogosultság, plusz:
- Összes felhasználó megtekintése (adminok kivételével) óraadókra és felhasználókra bontva
- Bármely óraadó workshop és session kezelési paneljének elérése
- Workshopok létrehozása, szerkesztése, archiválása és sessionök lemondása bármely óraadó nevében
- Felhasználók felfüggesztése (1 nap, 7 nap, 30 nap, végleges) (Unimplemented még)
- Felfüggesztések feloldása (Unimplemented még)

Route védelem egymásba ágyazott middleware-rel:

```php
Route::middleware('auth')->group(function () {
    // Minden autentikált felhasználónak elérhető

    Route::middleware('role:instructor')->group(function () {
        // Csak óraadóknak
    });

    Route::middleware('role:admin')->group(function () {
        // Csak adminoknak
    });
});
```

---

## Funkciók

### Foglalási Folyamat
1. A felhasználó böngészi a workshopokat a nyilvános listán
2. Session és létszám kiválasztása a workshop részlet oldalon
3. Átirányítás a foglalás megerősítő oldalra (`/bookings/create?session=X&headcount=Y`)
4. Foglalás beküldése - a controller tranzakción belül újra ellenőrzi a szabad helyeket
5. Foglalás létrehozva `status: confirmed` státusszal

### Foglalás Módosítás
- A felhasználók módosíthatják a létszámot egy meglévő foglaláson
- A szabad helyek újraszámítása a felhasználó saját headcountját visszaadva történik
- A felhasználók lemondhatják a foglalásokat (`status: cancelled_by_user`)
- A módosítási és lemondási határidők megjelennek a felhasználónak (48 / 72 óra) (Unimplemented még)

### Session Lemondás
Amikor egy óraadó vagy admin lemond egy sessiont:
1. Az összes foglalás `status: cancelled_by_instructor` státuszra frissül
2. Maga a session `status: cancelled` státuszra frissül
   Mindkét művelet egyetlen adatbázis tranzakcióban fut.

### Szerepkör-Tudatos Navigáció
Az `AppLayout` navigáció a megosztott Inertia propokból (a `HandleInertiaRequests`-ben beállítva) olvassa a szerepköröket és feltételesen mutatja a linkeket a bejelentkezett felhasználó szerepköre alapján.

### Törzsvásárlói Pontok
A felhasználók pontokat gyűjtenek a `users.points` egyenlegükön. A pontok kuponokra válthatók be. A `coupons` tábla támogatja mind a fix (HUF), mind a százalékos kedvezményeket, és a kuponok lehetnek személyre szabottak (`user_id`-val) vagy általánosak. (Unimplemented még)

### Felhasználó Felfüggesztés
Az adminok felfüggeszthetik a felhasználókat előre meghatározott időtartamokra. A `CheckBanned` middleware minden kérésnél ellenőrzi a felfüggesztési státuszt - ha a felhasználó felfüggesztve van, automatikusan kijelentkezteti és specifikus üzenettel a bejelentkezési oldalra irányítja. (Unimplemented még)

---

## Telepítés

### Követelmények
- PHP 8.3+
- Composer
- Node.js 20+ / Bun
- SQLite (lokális) vagy PostgreSQL (éles)

### Beállítás

```bash
# Repository klónozása
git clone https://github.com/zoljs/workshop.git
cd workshop

# PHP függőségek telepítése
composer install

# JS függőségek telepítése
bun install

# Környezeti fájl másolása
cp .env.example .env

# Alkalmazás kulcs generálása
php artisan key:generate

# Migrációk futtatása
php artisan migrate

# Adatbázis feltöltése teszt adatokkal
php artisan db:seed

# Frontend eszközök buildelése
bun run build

# Fejlesztői szerver indítása, vagy Laravel Herd használata esetén adjuk hozzá a weboldalakhoz
php artisan serve
```

Frontend hot reload fejlesztés közben:
```bash
bun run dev
```

### Seeder által létrehozott fiókok

| Szerepkör | Email | Jelszó |
|---|---|---|
| Admin | `admin@w.hu` | `a` |
| Óraadó | `ora@w.hu` | `a` |
| Felhasználó | `a@w.hu` | `a` |

Regisztráció esetén szigorú megkötések vannak a jelszavakat illetően. Csak a teszt bejelentkezés sebességének növelése miatt egy betű a teszt felhasználók jelszava.

---

## Deployment

Az alkalmazás **Laravel Cloud** platformon fut, PostgreSQL adatbázissal.

### Környezeti Változók

```
APP_KEY=
APP_ENV=production
APP_DEBUG=false
APP_URL=

DB_CONNECTION=pgsql
DB_HOST=
DB_PORT=5432
DB_DATABASE=
DB_USERNAME=
DB_PASSWORD=
```

### Deploy Parancsok

Minden deployment után futtatandó a Laravel Cloud konzolon:

```bash
php artisan migrate --force
php artisan db:seed --force
```

### Build

A frontend eszközök a repository-ba vannak commitolva (`public/build/`), mivel a Laravel Cloud nem támogatja a Node.js-t a build pipeline-ban ennél a projektnél.

---

## Licenc

Ez a projekt az PPKE ITK Szoftvertechnológia tantárgy keretében készült féléves projektfeladatként.
