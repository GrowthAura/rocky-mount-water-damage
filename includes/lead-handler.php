<?php
// Lead / callback form handler. Local only: no mail is sent and nothing
// leaves the server — no email destination has been given. A successful
// submit shows an honest notice and points to the phone line.
function handle_lead_post($post, $path)
{
    $isContact = ($path === '/contact');
    $values = [];
    $errors = [];
    $fields = $isContact
        ? ['name', 'phone', 'address_zip', 'what', 'still_coming', 'best_time']
        : ['name', 'phone', 'service', 'message'];
    foreach ($fields as $f) {
        $values[$f] = trim((string) ($post[$f] ?? ''));
    }
    $values['page'] = $path;
    // Honeypot must stay empty.
    if (!empty($post['website'])) {
        $errors[] = 'Please leave the extra field empty.';
    }
    if ($values['name'] === '') {
        $errors[] = 'Please enter your name.';
    }
    if ($values['phone'] === '') {
        $errors[] = 'Please enter your phone number.';
    } elseif (!preg_match('/^[0-9+\-() ]{7,40}$/', $values['phone'])) {
        $errors[] = 'Please enter a valid phone number.';
    }
    if (!$isContact && $values['message'] === '') {
        $errors[] = 'Please tell us what happened.';
    }
    if ($isContact && $values['what'] === '') {
        $errors[] = 'Please say what happened.';
    }
    if ($errors) {
        return ['errors' => $errors, 'values' => $values, 'notice' => false];
    }
    return ['errors' => [], 'values' => $values, 'notice' => true];
}

// Service options for the lead-form dropdown (tabs 02-20 + guides).
function lead_service_options()
{
    global $SERVICES, $GUIDES;
    $opts = [];
    foreach ($SERVICES as $s) {
        $opts[] = $s['label'];
    }
    foreach ($GUIDES as $g) {
        $opts[] = $g['label'];
    }
    $opts[] = 'Other';
    return $opts;
}

function lead_what_options()
{
    return [
        'water under house',
        'burst pipe',
        'flood',
        'storm or roof leak',
        'AC leak',
        'sewage or septic',
        'other',
    ];
}
