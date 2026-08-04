# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## What this is

PANDA System — a Laravel + Livewire app that runs a multi-stage approval workflow for PANs (Personnel Action Notices: regularization, salary alignment, promotion, transfer, allowances, etc.) through five stages: Requestor → Division Head → HR Preparer → HR Approver → Final Approver. Admin staff manage user access and departments separately.

`docs/SYSTEM_OVERVIEW.md` has the full plain-language walkthrough of every module, form, and the data model — read that before making workflow changes, it's more current and accurate than `README.md`.

## Commands

```bash
composer install
npm install

# Dev (serves + queue listener + logs + vite, concurrently)
composer run dev

# Frontend only
npm run dev      # vite dev server
npm run build    # production build — required after any Blade/Tailwind class changes
                  # before those changes will actually render (no CSS purge on demand)

# DB
php artisan migrate
php artisan db:seed --class=<SeederName>   # seeders other than DatabaseSeeder must be named explicitly

# Tests — test suite is currently just framework stubs (tests/Feature/ExampleTest.php,
# tests/Unit/ExampleTest.php), no real coverage exists yet
php artisan test
php artisan test --filter=TestName

# Lint/format
vendor/bin/pint
vendor/bin/pint --dirty   # only staged/changed files
```

No `.env.example` `APP_URL` is set by default; local dev in this repo runs via Laragon at `http://pandasystem.test`.

## Architecture

**Livewire components ARE the controllers.** There are almost no traditional Controllers — each module's route renders a Blade page shell that mounts one or more `app/Http/Livewire/*.php` components, and all reads/writes happen through Livewire component methods (`wire:click`/Alpine `$wire.method()` calls), not HTTP endpoints. When asked "where's the logic for X," look in `app/Http/Livewire/`, not `app/Http/Controllers/`.

**HR Preparer, HR Approver, and Final Approver share one component and one Blade view** (`PreparerPan.php` / `preparer-pan.blade.php`), branching entirely on a `$module` string prop (`'hr_preparer'`, `'hr_approver'`, `'final_approver'`, also `'division_head'`) and on the PAN's `request_status`. Don't assume a change to one of these three "modules" is isolated — check all the `$module ==` branches.

**Three independent access layers on `User`, easy to conflate:**
1. `access` — a JSON map of 5 booleans (`RQ_Module`, `DH_Module`, `HRP_Module`, `HRA_Module`, `FA_Module`), checked by the `module.access:XX` route middleware (`CheckModuleAccess`). This is what gates whether a user can reach a stage's routes at all.
2. `role` — a free string (`hrhead` / `admin` / null), checked ad hoc directly in Blade/component code (`Auth::user()->role == 'hrhead'`), not by any middleware. Governs the HR Preparer vs. HR Head Preparer split and which admin-page sections render.
3. `is_confidentiality_approver` — a boolean, gates visibility of `confidentiality == 'manila'` PANs at the Division Head stage specifically (cross-department, independent of which department a user heads).

These three are orthogonal — a user can have any combination. Don't assume `role == 'admin'` implies any particular `access` flags, or vice versa.

**Department/division-head assignment is DB-driven**, not hardcoded. `departments` + `department_user` (pivot with two independent booleans, `is_requestor` and `is_head`) replaced a hardcoded `PanAccessMap.php` lookup file that used to exist — that file is gone, don't recreate that pattern. A department can have multiple heads (co-heads); a user can head a department without being a requestor for it, or vice versa. Managed entirely through the admin "User Access" page's Departments modal — there is no separate admin page for departments.

**Field-level AES encryption on select columns, applied in PHP, not via Eloquent casts.** `RequestorModel` and `PreparerModel` override `setAttribute()`/`getAttribute()` to transparently `Crypt::encryptString()`/`decryptString()` specific columns (on `RequestorModel`: `justification`, `requested_by`, `requestor_id`, `divisionhead_id`, `hr_id` — notably `approver_id` is NOT encrypted, that's an existing inconsistency, not a bug to silently "fix"; on `PreparerModel`: most of its columns). **This means those columns can never be filtered or joined on at the DB level** — Laravel's encryption is non-deterministic (random IV per call), so `WHERE divisionhead_id = ?` can never match. This is *why* the app filters PAN visibility by the plain-text `department` string column instead of by user ID — that's a deliberate workaround for the encryption, not an oversight. Keep using department-name matching for any new visibility rule in this area rather than trying to query the encrypted ID columns.

**PAN status is a single `request_status` enum column** on `requestor`, not a separate state table. The Division Head, HR Preparer, HR Approver, and Final Approver queues are all separate Livewire components querying the same `requestor` table, each with its own hardcoded status whitelist for what's "in their queue" — when adding a new status or changing a transition, you generally need to update the enum migration *and* every queue component's whitelist *and* the blade conditionals that show/hide action buttons per status, not just one place.

**Employee-level attachments** (`employee_attachments` table, `App\Models\EmployeeAttachment`, `EmployeeAttachments` Livewire component embedded in `panda.employeerecord-view`) let HR upload legacy/pre-system PAN scans tied to an `Employee` record rather than to a specific `requestor` row. These are always `confidentiality = 'manila'`, stored on the `local` (private) disk — not `public`, unlike `RequestorModel`'s `supporting_file` — and served through an authenticated `/employee-attachment/{id}/download` route. Upload/delete is restricted to `role == 'hrhead'` or `HRA_Module` access; viewing (including download) is also open to `FA_Module` access. The three `employeerecord-view` routes are gated per their URL prefix (`HRP`/`HRA`/`FA` respectively) so each stage can reach its own copy of the page.

**External systems**: the user/employee roster used by the admin "User Access" page is fetched live from an external HR API (`bfcgroup.ph`) with an ID field that's separately encrypted; the local `users` table only holds accounts that have been granted at least one module (created lazily on first grant, not pre-populated from the external roster). Backups run via `spatie/laravel-backup` to local + Google Drive, on a daily schedule (`app/Console/Kernel.php`).

## Known gaps (don't assume these are handled)

- A regular HR Preparer can still open a `confidentiality == 'manila'` PAN's edit page directly via URL/link — the list view disables the button, but there's no server-side guard blocking direct access. Flagged, not yet fixed.
- A Division Head "reject" action exists in the component and has a ready confirmation-modal config, but no button in the current Blade template calls it — only Approve and Return to Requestor are wired up.
- `spatie/laravel-backup`'s success/failure/health notification recipient is still the package's placeholder address, not a real inbox — only the Laravel scheduler's `emailOutputOnFailure` (a different, narrower mechanism) actually reaches a real address.
- `README.md` describes an aspirational architecture (a `PersonnelAction` model, `ApprovalController`, `CheckRole` middleware, a `RolesSeeder`) that doesn't match the actual implementation — don't rely on it for architecture facts, use `docs/SYSTEM_OVERVIEW.md` or read the code.
