# Water Damage Restoration Rocky Mount NC

PHP lead-gen site for Water Damage Rocky Mount — (252) 358-2834.
Domain: rockymountwaterdamage.site (Cloudflare Pages).

- `main` = PHP source (front controller + includes/ + data/).
- `cloudflare-pages` = static export deployed to Cloudflare Pages via
  GitHub Actions (`.github/workflows/deploy-cloudflare.yml`).
  Every push to that branch auto-deploys to production.
- Static export is built from a frozen snapshot with
  `../qa/export_cloudflare.py` — never hand-edit the export branch.
