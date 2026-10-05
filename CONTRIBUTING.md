# Contributing to Eloom LMS

Thanks for your interest in improving Eloom LMS. Bug reports, documentation fixes, and pull
requests are all welcome.

## Reporting bugs and requesting features

Use the [issue tracker](../../issues). The templates ask for the details that usually decide
how quickly something can be reproduced — please fill them in rather than deleting them.

**Do not open a public issue for a security vulnerability.** See [SECURITY.md](SECURITY.md).

## Getting set up

Follow the installation steps in the [README](README.md). In short:

```bash
composer install
npm install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
php artisan serve
```

Then visit `/admin` to complete first-run setup.

The test suite runs against an in-memory SQLite database and needs no MySQL server:

```bash
php artisan test
```

## Pull requests

- **Branch from `main`** and keep each pull request to a single concern. A focused 40-line
  diff gets reviewed; a 2,000-line one that mixes a bugfix with a refactor does not.
- **Add a test** for any behaviour change. Feature tests that touch the database should use
  the `RefreshDatabase` trait.
- **Run the suite before pushing** — `php artisan test` should be green.
- **Explain the why.** The diff shows what changed; the description should say what problem
  it solves and how you verified the fix.

### Code style

The project uses [Laravel Pint](https://laravel.com/docs/pint) with the default preset. Format
the files you touched before opening a pull request:

```bash
./vendor/bin/pint --dirty
```

Most of this codebase predates Pint and is not yet formatted to the preset, so CI checks style
**only on the files your pull request changes**. Please don't reformat unrelated files — a
whitespace-only diff across hundreds of files destroys `git blame` and makes review
impossible. If you think a directory is worth reformatting wholesale, open an issue first so
it can be done as its own dedicated commit.

### Architecture notes

- The application is organised as [nwidart](https://nwidart.com/laravel-modules) modules under
  `Modules/`. New functionality usually belongs in an existing module; use
  `php artisan module:make` if it genuinely warrants a new one.
- Each audience has its own auth guard (`user`, `student`, `trainer`, `agent`,
  `agent_branch_user`). Route groups must use the right one.
- Permissions attach to the `user_type` string, not to individual accounts. New capabilities
  need rows in `user_roles`, seeded via `RoleSeeder`.
- Deployment-specific values belong in `config/` and `.env`, never hardcoded in templates.
  Organisation-specific content — anything on an offer letter, invoice, or receipt — belongs
  in the settings tables so each provider supplies their own.

## Licence

By contributing, you agree that your contributions are licensed under the
[MIT License](LICENSE).
