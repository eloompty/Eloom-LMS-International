# Eloom LMS

An open source learning management system for training providers, built on Laravel.

Eloom LMS covers the full student lifecycle for a vocational or higher-education provider —
from agent-sourced application and offer letter, through enrolment, intake scheduling,
attendance and assessment, to fees, certificates and alumni. It ships five separate web
portals over one codebase and a token-authenticated REST API for mobile clients.

[![License: MIT](https://img.shields.io/badge/License-MIT-blue.svg)](LICENSE)
[![PHP 8.3+](https://img.shields.io/badge/PHP-8.3%2B-777bb4.svg)](https://php.net)
[![Laravel 13](https://img.shields.io/badge/Laravel-13-ff2d20.svg)](https://laravel.com)

> **Status:** this project was extracted from a production system and opened up. It is
> usable, but the public release is young — expect rough edges around first-run setup and
> documentation. Issues and pull requests are welcome.

---

## Portals

Each audience gets its own login, guard, and layout:

| Portal | Path | Guard | For |
|---|---|---|---|
| Administration | `/admin` | `user` | Staff — the full management back office |
| Student | `/student` | `student` | Enrolled students |
| Trainer | `/trainer` | `trainer` | Trainers and assessors |
| Agent | `/agent` | `agent` | Recruitment agencies |
| Agent branch user | `/branch-user` | `agent_branch_user` | Staff within an agency branch |

The REST API is versioned under `/api/v1/` with Passport tokens (`student_api`,
`trainer_api`) and self-documents at `/api/documentation`.

## Features

Built as 42 [nwidart](https://nwidart.com/laravel-modules) modules, all enabled by default
via `modules_statuses.json`.

**Admissions and enrolment** — applications, offer letters with a configurable written
agreement, offer conditions, credit and RPL, offer status tracking, document checklists,
scholarships, agents and agency branches with commission handling.

**Academic delivery** — courses and qualifications, universities, intakes and intake
scheduling, classrooms and delivery sites, timetabling, online classes, resources, and a
trainer-facing assignment and marking workflow.

**Assessment and progress** — assignments, marking types and rubrics, gradebook, attendance,
certificates, and reporting.

**Finance** — fee types and fee schedules, payment plans and instalments, invoices and
receipts, discounts, Stripe payments, and agent commissions.

**Engagement** — announcements, events, surveys, chat, tickets, CRM, email templates, and
browser/mobile push notifications.

**Platform** — role-based permissions, multi-guard authentication, activity logging,
document management, address and country reference data, and a settings system covering
email transports, offer letters, notifications, and integrations.

## Requirements

- PHP **8.3+** with the usual Laravel extensions, plus `gd` or `imagick` for image handling
- Composer 2
- MySQL 8 (or MariaDB 10.6+)
- Node.js 18+ and npm
- Redis — optional, for cache, queues, and broadcasting

## Installation

```bash
git clone https://github.com/<your-org>/eloom-lms.git
cd eloom-lms

composer install
npm install

cp .env.example .env
php artisan key:generate
```

Set your database credentials in `.env`, then:

```bash
php artisan migrate --seed
php artisan storage:link
php artisan passport:install     # required for the REST API
npm run production
php artisan serve
```

The seeders load reference data only — roles and permissions, countries, document and fee
types, marking types, email templates, and default settings. No user accounts are created;
you make the first one through the browser.

### First-run setup

Visit **`/admin`**. While the database has no users, that route serves a one-time setup form
instead of the login page. It creates, in a single step:

- the first **super admin** account
- your **organisation** record — name, CEO, contact details, logo, and address
- a first **delivery site** with its own address

Once that account exists, `/admin` serves the normal login page and the setup endpoint is
closed — `POST /admin/register` returns 404 for every later request. Permissions attach to the
`user_type` string rather than to individual accounts, so the new `super_admin` immediately
picks up the full permission set loaded by `RoleSeeder`.

The organisation details captured here appear on offer letters, invoices, receipts, and
statements, and can be edited later under **Company**.

## Configuration

All optional. The application runs without any of these; the related features simply stay
off.

### Browser push notifications (Firebase)

Copy the web app config from Firebase Console → Project Settings → General → Your apps, and
the VAPID key pair from Cloud Messaging → Web configuration:

```dotenv
FIREBASE_API_KEY=
FIREBASE_AUTH_DOMAIN=
FIREBASE_PROJECT_ID=
FIREBASE_STORAGE_BUCKET=
FIREBASE_MESSAGING_SENDER_ID=
FIREBASE_APP_ID=
FIREBASE_MEASUREMENT_ID=
FIREBASE_VAPID_KEY=
```

Leave `FIREBASE_PROJECT_ID` empty to disable push entirely — the messaging scripts are then
omitted from every layout. These values are public by design: they identify a Firebase
project rather than authenticating against it, and access is controlled by the project's
authorised domains. See [`config/firebase.php`](config/firebase.php).

### Video conferencing (Zoom)

```dotenv
ZOOM_API_URL=
ZOOM_API_KEY=
ZOOM_API_SECRET=
ZOOM_API_JWT=
```

### Payments, mail, and offer letters

Stripe keys, mail transports (SMTP, Mailgun, Mailchimp), and Google/Microsoft OAuth are
configured in the admin UI under **Settings**, not in `.env`.

Offer letters are template-driven and ship without any provider-specific content. Before
issuing one, fill in **Settings → Offer**: organisation contact details, bank account
details, CRICOS provider code, signatory, logo, and your own enrolment terms and conditions.
The terms field is intentionally empty on a fresh install — the code of conduct, fees and
refunds policy, complaints and appeals process, and any regulatory disclosures are specific
to each provider and jurisdiction.

### Real-time

Broadcasting is off by default (`BROADCAST_DRIVER=null`). The repository includes both a
[Laravel Reverb](https://reverb.laravel.com) dependency and a standalone Socket.IO server
(`socketServer.js`, port 3000) used for chat and notifications.

## Development

```bash
php artisan serve          # http://localhost:8000
npm run watch              # rebuild assets on change
php artisan queue:work     # if QUEUE_CONNECTION is not sync

./vendor/bin/pint          # code style
php artisan test           # test suite
```

Module scaffolding uses `nwidart/laravel-modules`:

```bash
php artisan module:make Example
php artisan module:make-controller ExampleController Example
```

## Contributing

Issues and pull requests are welcome. Please keep changes focused, match the surrounding
code style, and run Pint before submitting.

## License

Released under the [MIT License](LICENSE).

This repository vendors third-party front-end components, including AdminLTE and its plugin
bundle, each under its own license. See [NOTICE](NOTICE) for attribution.
