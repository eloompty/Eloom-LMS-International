# Security Policy

## Reporting a vulnerability

**Please do not report security vulnerabilities through public GitHub issues.**

Report privately through
[GitHub's private vulnerability reporting](../../security/advisories/new), which lets us
discuss and fix the issue before any details become public.

If you cannot use that, email **admin@eloom.com.au**.

Please include:

- the type of issue (privilege escalation, injection, authentication bypass, and so on)
- the affected file, route, or module
- the steps or proof-of-concept needed to reproduce it
- what an attacker could achieve

We will acknowledge your report within **5 working days** and aim to give you an assessment
and a remediation timeline within **10 working days**. We are happy to credit you in the
release notes for the fix unless you would rather stay anonymous.

## Supported versions

This project has not yet cut a tagged release. Until it does, only the `main` branch receives
security fixes.

## Notes for operators

Eloom LMS handles student personal data, identity documents, and payment records. A few
things are worth checking on any deployment:

- **Set `APP_DEBUG=false` in production.** Laravel's debug pages disclose environment
  variables, including database credentials.
- **Never commit `.env`.** It is git-ignored along with every `.env.*` variant except
  `.env.example`.
- **Complete first-run setup immediately after deploying.** While the database has no users,
  `/admin` serves an unauthenticated setup form that creates the first super admin. That
  endpoint closes permanently once an account exists, but an instance left exposed with an
  empty database can be claimed by whoever reaches it first.
- **Rotate `APP_KEY` and every credential** if you restored this application from a backup or
  a fork whose history may have contained them.
- **Serve over HTTPS.** Session cookies and Passport tokens for five separate portals are
  otherwise sent in the clear.
- **Put access control in front of uploaded files.** Uploads are written under `public/` and
  served directly by the web server with no authorisation check — including
  `public/images/students/documents`, `public/images/students/payments`,
  `public/images/student/unit_fee` (payment receipts), and assignment submissions under
  `public/images/assignments/`. Filenames are derived from `time()`, so they are also
  guessable by enumeration. Anyone who knows or brute-forces a URL can retrieve identity
  documents and receipts without logging in.

  Until this is reworked to use the private disk with a signed-URL controller, restrict those
  directories at the web server or place the instance behind a network boundary. This is a
  known architectural issue rather than a recently introduced regression.
