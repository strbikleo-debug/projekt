# DoIt – To-Do aplikácia v PHP

Jednoduchá to-do aplikácia s prihlásením, registráciou a správou úloh.

---

## Súbory

| Súbor | Popis |
|---|---|
| `setup.php` | Vytvorí databázu a tabuľky – spustiť raz |
| `db.php` | Pripojenie k databáze |
| `index.php` | Prihlásenie |
| `register.php` | Registrácia nového účtu |
| `tasks.php` | Hlavná stránka – pridávanie a správa úloh |
| `logout.php` | Odhlásenie |

---

## Inštalácia

1. Skopíruj všetky súbory do `htdocs/todo_app/` (XAMPP) alebo `htdocs/todo_app/` (MAMP)
2. Spusti Apache a MySQL v XAMPP/MAMP
3. Otvor `localhost/todo_app/setup.php` – vytvorí databázu
4. Choď na `localhost/todo_app/index.php` a prihlás sa

---

## Databáza

Názov databázy: `todo_app`

**Tabuľka users**
| Stĺpec | Typ | Popis |
|---|---|---|
| id | INT | primárny kľúč |
| username | VARCHAR(100) | meno používateľa (unikátne) |
| password | VARCHAR(255) | heslo |

**Tabuľka tasks**
| Stĺpec | Typ | Popis |
|---|---|---|
| id | INT | primárny kľúč |
| user_id | INT | väzba na users |
| title | VARCHAR(255) | názov úlohy |
| description | TEXT | popis úlohy |
| status | ENUM | pending / done |
| created_at | TIMESTAMP | dátum vytvorenia |

---

## Funkcie

- Prihlásenie a odhlásenie cez session
- Registrácia nového účtu
- Pridanie úlohy (názov + popis)
- Označenie úlohy ako hotovej
- Vrátenie úlohy späť na nedokončenú
- Vymazanie úlohy
- Každý používateľ vidí len svoje úlohy
