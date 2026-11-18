// Rocky Mount Water Damage Restoration — callback form endpoint.
// Cloudflare Pages Function: POST /api/callback (functions/api/callback.js).
//
// Why this exists: the PHP site's lead form posts to the same URL and the
// PHP handler renders an honest notice on the page. A static Cloudflare
// Pages export has no PHP, and POSTing to a static HTML file answers 405,
// so the export rewrites the form action to this endpoint. It mirrors the
// PHP handler's behaviour exactly: same validation (name required, phone
// required + pattern, message/what required, honeypot must stay empty),
// same honest outcome — NO mail is sent and nothing is stored, because no
// email destination has been given. It returns a small branded HTML page
// with the same notice the PHP site shows, pointing at the phone line.
const PHONE_DISPLAY = '(252) 358-2834';
const PHONE_TEL = 'tel:+12523582834';
const SITE_NAME = 'Water Damage Rocky Mount';

function esc(s) {
  return String(s ?? '')
    .replace(/&/g, '&amp;')
    .replace(/</g, '&lt;')
    .replace(/>/g, '&gt;')
    .replace(/"/g, '&quot;');
}

function page(title, body) {
  return `<!DOCTYPE html><html lang="en"><head><meta charset="utf-8">` +
    `<meta name="viewport" content="width=device-width, initial-scale=1">` +
    `<meta name="robots" content="noindex">` +
    `<title>${esc(title)} | ${esc(SITE_NAME)}</title>` +
    `<style>body{font-family:system-ui,-apple-system,Segoe UI,Roboto,sans-serif;` +
    `margin:0;background:#FBF8F2;color:#0F1D2E}main{max-width:640px;margin:0 auto;` +
    `padding:48px 20px}a{color:#0A5451}.btn{display:inline-block;background:#0A5451;` +
    `color:#fff;padding:14px 22px;border-radius:999px;text-decoration:none;` +
    `font-weight:700;margin-top:16px}</style></head><body><main>${body}</main></body></html>`;
}

export async function onRequestPost(context) {
  const { request } = context;
  let data = {};
  try {
    const ct = request.headers.get('content-type') || '';
    if (ct.includes('application/x-www-form-urlencoded')) {
      const fd = await request.formData();
      fd.forEach((v, k) => { data[k] = String(v); });
    } else if (ct.includes('application/json')) {
      data = await request.json();
    } else {
      const fd = await request.formData().catch(() => null);
      if (fd) fd.forEach((v, k) => { data[k] = String(v); });
    }
  } catch (e) {
    return new Response(page('Something went wrong',
      `<h1>Something went wrong</h1><p>Your request could not be read. ` +
      `For immediate help, call <a href="${PHONE_TEL}">${PHONE_DISPLAY}</a>.</p>`),
      { status: 400, headers: { 'content-type': 'text/html; charset=utf-8' } });
  }

  // Honeypot must stay empty — a filled honeypot is a bot, not a
  // validation miss: reject it outright with 400.
  if ((data.website || '').trim() !== '') {
    return new Response(page('Request rejected',
      `<h1>Request rejected</h1><p>Please leave the extra field empty. ` +
      `For immediate help, call <a href="${PHONE_TEL}">${PHONE_DISPLAY}</a>.</p>`),
      { status: 400, headers: { 'content-type': 'text/html; charset=utf-8' } });
  }
  const errors = [];
  const name = (data.name || '').trim();
  const phone = (data.phone || '').trim();
  const isContact = (data.page || '') === '/contact';
  if (!name) errors.push('Please enter your name.');
  if (!phone) {
    errors.push('Please enter your phone number.');
  } else if (!/^[0-9+\-() ]{7,40}$/.test(phone)) {
    errors.push('Please enter a valid phone number.');
  }
  if (!isContact && !(data.message || '').trim()) errors.push('Please tell us what happened.');
  if (isContact && !(data.what || '').trim()) errors.push('Please say what happened.');

  if (errors.length) {
    const items = errors.map((e) => `<li>${esc(e)}</li>`).join('');
    return new Response(page('Please fix the following',
      `<h1>Please fix the following</h1><ul>${items}</ul>` +
      `<p><a href="javascript:history.back()">Go back to the form</a> ` +
      `or call <a href="${PHONE_TEL}">${PHONE_DISPLAY}</a> for immediate help.</p>`),
      { status: 422, headers: { 'content-type': 'text/html; charset=utf-8' } });
  }

  return new Response(page('Thank you',
    `<h1>Thank you. Your request was received on this page only.</h1>` +
    `<p>No email address has been set up for requests yet, so nothing was sent anywhere. ` +
    `For immediate help, call <a href="${PHONE_TEL}">${PHONE_DISPLAY}</a>.</p>` +
    `<a class="btn" href="${PHONE_TEL}">Call ${PHONE_DISPLAY}</a>`),
    { status: 200, headers: { 'content-type': 'text/html; charset=utf-8' } });
}

export async function onRequest(context) {
  return new Response('Method not allowed', { status: 405 });
}
