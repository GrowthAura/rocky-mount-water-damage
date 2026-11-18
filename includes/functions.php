<?php
// Rocky Mount NC — shared helpers: escaping, dash-stripping, link
// rendering, media-manifest wiring, JSON-LD builders, nav data.
// Fresh code written for Rocky Mount; no text copied from other sites.

// Inline phone glyph for CTA buttons (aria-hidden; no emoji in buttons).
function phone_svg()
{
    return '<svg aria-hidden="true" width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M6.6 10.8c1.4 2.8 3.8 5.1 6.6 6.6l2.2-2.2c.3-.3.7-.4 1-.2 1.1.4 2.3.6 3.6.6.6 0 1 .4 1 1V20c0 .6-.4 1-1 1C10.6 21 3 13.4 3 4c0-.6.4-1 1-1h3.5c.6 0 1 .4 1 1 0 1.2.2 2.4.6 3.6.1.3 0 .7-.2 1l-2.3 2.2z"/></svg>';
}
$SITE = [
    'brand' => 'Water Damage Rocky Mount',
    'phone' => '(252) 358-2834',
    'phone_href' => 'tel:+12523582834',
    'phone_intl' => '+1-252-358-2834',
    'url' => 'https://rockymountwaterdamage.site',
    'area' => 'Rocky Mount, NC',
];

// Navigation: services = tabs 02-20 (19). Guides = tabs 21-22.
$SERVICES = [
    ['label' => 'Emergency Water Removal', 'href' => '/emergency-water-removal-rocky-mount-nc'],
    ['label' => 'Crawl Space Water Damage', 'href' => '/crawl-space-water-damage-rocky-mount-nc'],
    ['label' => 'Flood Damage Restoration', 'href' => '/flood-damage-restoration-rocky-mount-nc'],
    ['label' => 'Hurricane & Storm Water Damage', 'href' => '/hurricane-storm-water-damage-rocky-mount-nc'],
    ['label' => 'Burst Pipe & Plumbing Leak', 'href' => '/burst-pipe-water-damage-rocky-mount-nc'],
    ['label' => 'Sewage & Septic Backup', 'href' => '/sewage-backup-cleanup-rocky-mount-nc'],
    ['label' => 'Mold Remediation', 'href' => '/mold-remediation-rocky-mount-nc'],
    ['label' => 'AC & HVAC Water Damage', 'href' => '/ac-hvac-water-damage-rocky-mount-nc'],
    ['label' => 'Crawl Space Moisture Control', 'href' => '/crawl-space-moisture-control-rocky-mount-nc'],
    ['label' => 'Roof Leak & Ceiling Damage', 'href' => '/ceiling-roof-leak-water-damage-rocky-mount-nc'],
    ['label' => 'Water Heater & Appliance Leaks', 'href' => '/water-heater-leak-cleanup-rocky-mount-nc'],
    ['label' => 'Subfloor & Joist Drying', 'href' => '/subfloor-water-damage-rocky-mount-nc'],
    ['label' => 'Hardwood Floor Water Damage', 'href' => '/hardwood-floor-water-damage-rocky-mount-nc'],
    ['label' => 'Mobile & Manufactured Homes', 'href' => '/mobile-home-water-damage-rocky-mount-nc'],
    ['label' => 'Commercial Water Damage', 'href' => '/commercial-water-damage-rocky-mount-nc'],
    ['label' => 'Rental Property Water Damage', 'href' => '/rental-property-water-damage-rocky-mount-nc'],
    ['label' => 'Structural Drying', 'href' => '/structural-drying-rocky-mount-nc'],
    ['label' => 'Water Damage Repair & Rebuild', 'href' => '/water-damage-repair-rocky-mount-nc'],
    ['label' => 'Contents Pack-Out', 'href' => '/contents-pack-out-rocky-mount-nc'],
];
$GUIDES = [
    ['label' => 'Insurance Claim Help', 'href' => '/water-damage-insurance-claim-help-rocky-mount-nc'],
    ['label' => 'What To Do After Water Damage', 'href' => '/what-to-do-after-water-damage-rocky-mount-nc'],
];
// NAV COUNTS (his 2026-11-18 order — header 6, footer 8, nothing more).
// Header + mobile menu carry ONLY these 6 money services, in this order;
// the footer carries these 6 + Hurricane & Storm + AC & HVAC (8).
// $SERVICES (19) stays intact above: it still drives the lead-form
// service dropdown (lead-handler.php), and all 19 service pages stay live
// and stay linked from body content + homepage cards — ONLY the nav lists
// are trimmed. Guides (2) stay exactly as-is in both places.
$NAV_SERVICES = [
    ['label' => 'Emergency Water Removal', 'href' => '/emergency-water-removal-rocky-mount-nc'],
    ['label' => 'Crawl Space Water Damage', 'href' => '/crawl-space-water-damage-rocky-mount-nc'],
    ['label' => 'Flood Damage Restoration', 'href' => '/flood-damage-restoration-rocky-mount-nc'],
    ['label' => 'Burst Pipe & Plumbing Leak', 'href' => '/burst-pipe-water-damage-rocky-mount-nc'],
    ['label' => 'Mold Remediation', 'href' => '/mold-remediation-rocky-mount-nc'],
    ['label' => 'Sewage & Septic Backup', 'href' => '/sewage-backup-cleanup-rocky-mount-nc'],
];
$FOOTER_SERVICES = array_merge($NAV_SERVICES, [
    ['label' => 'Hurricane & Storm Water Damage', 'href' => '/hurricane-storm-water-damage-rocky-mount-nc'],
    ['label' => 'AC & HVAC Water Damage', 'href' => '/ac-hvac-water-damage-rocky-mount-nc'],
]);
// Towns named in the doc (footer Areas column, plain text — no pages exist).
$TOWNS = ['Rocky Mount', 'West Mount', 'Battleboro', 'Tarboro', 'Nashville', 'Sharpsburg', 'Pinetops', 'Princeville', 'Spring Hope', 'Red Oak', 'Dortches', 'Whitakers', 'Elm City', 'Wilson'];

function esc($s)
{
    return htmlspecialchars((string) $s, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

// Strip em dashes and en dashes at render time WITHOUT changing any
// words: " – " / " — " become ", ", a bare dash becomes a space.
function clean_dashes($s)
{
    $s = str_replace([' — ', ' – ', '— ', '– ', ' —', ' –'], ', ', $s);
    $s = str_replace(['—', '–'], ' ', $s);
    $s = preg_replace('/\s+/', ' ', $s);
    return trim($s);
}

// Plain-text version of a doc string (for schema / word counts).
function plain_text($s)
{
    return clean_dashes(html_entity_decode((string) $s, ENT_QUOTES, 'UTF-8'));
}

function site_url($path)
{
    global $SITE;
    if ($path === '/') {
        return $SITE['url'] . '/';
    }
    return $SITE['url'] . $path;
}

function phone_link()
{
    global $SITE;
    return '<a href="' . esc($SITE['phone_href']) . '">' . esc($SITE['phone']) . '</a>';
}

// Render a doc text with its links[] applied at the recorded offsets.
// Offsets refer to the ORIGINAL text (with dashes), so links are applied
// first (single left-to-right pass) and dashes stripped from the finished
// fragment afterwards.
function render_linked_text($text, $links)
{
    $orig = (string) $text;
    if (!$links) {
        return esc(clean_dashes($orig));
    }
    usort($links, fn($a, $b) => $a['start'] <=> $b['start']);
    $parts = [];
    $pos = 0;
    foreach ($links as $l) {
        $parts[] = esc(mb_substr($orig, $pos, $l['start'] - $pos));
        $href = $l['href'] === '/' ? '/' : rtrim($l['href'], '/');
        $parts[] = '<a href="' . esc($href) . '">' . esc(mb_substr($orig, $l['start'], $l['end'] - $l['start'])) . '</a>';
        $pos = $l['end'];
    }
    $parts[] = esc(mb_substr($orig, $pos));
    return clean_dashes_html(implode('', $parts));
}

// Strip em/en dashes from a rendered HTML fragment. Only U+2013/U+2014
// are touched, so tags, attributes and ASCII hyphens are never affected.
function clean_dashes_html($html)
{
    $html = str_replace([' — ', ' – '], ', ', $html);
    $html = str_replace(['—', '–'], ' ', $html);
    return $html;
}

function render_linked($block)
{
    return render_linked_text($block['text'], $block['links'] ?? []);
}

// --- Media manifest (written by the image agent; may not exist yet) ----
// Expected shape: $MEDIA = ['hero' => [slug => ['src','alt','width','height']],
//                           'card' => [slug => [...]]]. Slugs are no-slash
// forms ('home' for the homepage hero). Missing slots render NO <img>.
// Responsive attributes: the manifest stores srcset as a ready-made
// "url widthw, ..." string; emit it verbatim. sizes must match the real
// rendered slot in styles.css — hero is full-width at every viewport,
// cards are 1 col <=640px, 2 col <=1020px, 3 col above that.
function srcset_attr($media, $sizes)
{
    if (empty($media['srcset'])) {
        return '';
    }
    return ' srcset="' . esc($media['srcset']) . '" sizes="' . esc($sizes) . '"';
}
function media_for($kind, $slug)
{
    global $MEDIA;
    if (empty($MEDIA[$kind][$slug])) {
        return null;
    }
    $m = $MEDIA[$kind][$slug];
    if (empty($m['src']) || empty($m['alt'])) {
        return null;
    }
    return $m;
}

function page_hero($slug)
{
    return media_for('hero', $slug === '/' ? 'home' : ltrim($slug, '/'));
}

function page_card($slug)
{
    return media_for('card', $slug);
}

// --- FAQ extraction ------------------------------------------------------
function page_faqs($page)
{
    $faqs = [];
    $blocks = $page['blocks'];
    $n = count($blocks);
    for ($i = 0; $i < $n; $i++) {
        $b = $blocks[$i];
        if ($b['type'] === 'h3' && !empty($b['faq'])) {
            $answers = [];
            $j = $i + 1;
            while ($j < $n && $blocks[$j]['type'] === 'p' && !empty($blocks[$j]['faq'])) {
                $answers[] = plain_text($blocks[$j]['text']);
                $j++;
            }
            $faqs[] = ['q' => plain_text($b['text']), 'a' => implode(' ', $answers)];
        }
    }
    return $faqs;
}

// --- JSON-LD -------------------------------------------------------------
function area_served()
{
    global $TOWNS;
    $areas = [
        ['@type' => 'City', 'name' => 'Rocky Mount, NC'],
        ['@type' => 'AdministrativeArea', 'name' => 'Nash County, NC'],
        ['@type' => 'AdministrativeArea', 'name' => 'Edgecombe County, NC'],
    ];
    foreach ($TOWNS as $t) {
        if ($t !== 'Rocky Mount') {
            $areas[] = ['@type' => 'City', 'name' => $t . ', NC'];
        }
    }
    return $areas;
}

function ld_local_business_node($heroUrl, $isHome)
{
    global $SITE;
    $node = [
        '@type' => $isHome ? ['EmergencyService', 'LocalBusiness'] : 'LocalBusiness',
        '@id' => $SITE['url'] . '/#business',
        'name' => $SITE['brand'],
        'telephone' => $SITE['phone_intl'],
        'url' => $SITE['url'] . '/',
        'areaServed' => area_served(),
        // F-09 (zero-blunder): doc states hours twice — tab 24 "Hours: 24 hours
        // a day, 7 days a week, including holidays and storm events" and tab 02
        // FAQ "Our phone line and crews run 24 hours a day, seven days a week".
        'openingHours' => 'Mo-Su 00:00-24:00',
        'description' => 'Water damage restoration in Rocky Mount, NC: 24/7 water removal, crawl space drying and repair across Nash and Edgecombe counties.',
    ];
    if ($heroUrl) {
        $node['image'] = $heroUrl;
    }
    return $node;
}

function ld_website_node()
{
    global $SITE;
    return [
        '@type' => 'WebSite',
        '@id' => $SITE['url'] . '/#website',
        'name' => $SITE['brand'],
        'url' => $SITE['url'] . '/',
    ];
}

function ld_webpage_node($page, $canon, $heroUrl)
{
    global $SITE;
    $node = [
        '@type' => $page['page_type'],
        '@id' => $canon . '#webpage',
        'name' => $page['title'],
        'url' => $canon,
        'description' => $page['meta'],
        'isPartOf' => ['@id' => $SITE['url'] . '/#website'],
        'inLanguage' => 'en-US',
    ];
    if ($heroUrl) {
        $node['primaryImageOfPage'] = $heroUrl;
    }
    return $node;
}

function ld_breadcrumb_node($page, $canon)
{
    global $SITE;
    $items = [
        ['@type' => 'ListItem', 'position' => 1, 'name' => 'Home', 'item' => $SITE['url'] . '/'],
    ];
    if ($page['slug'] !== '/') {
        $items[] = ['@type' => 'ListItem', 'position' => 2, 'name' => $page['crumb'], 'item' => $canon];
    }
    return ['@type' => 'BreadcrumbList', '@id' => $canon . '#breadcrumb', 'itemListElement' => $items];
}

function ld_service_node($page, $canon, $heroUrl)
{
    global $SITE;
    $node = [
        '@type' => 'Service',
        '@id' => $canon . '#service',
        'name' => plain_text(page_h1($page)),
        'serviceType' => $page['crumb'],
        'description' => $page['meta'],
        'url' => $canon,
        'provider' => ['@id' => $SITE['url'] . '/#business'],
        'areaServed' => area_served(),
        'telephone' => $SITE['phone_intl'],
    ];
    if ($heroUrl) {
        $node['image'] = $heroUrl;
    }
    return $node;
}

function ld_article_node($page, $canon, $heroUrl)
{
    global $SITE;
    $node = [
        '@type' => 'Article',
        '@id' => $canon . '#article',
        'headline' => plain_text(page_h1($page)),
        'description' => $page['meta'],
        'url' => $canon,
        'author' => ['@id' => $SITE['url'] . '/#business'],
        'publisher' => ['@id' => $SITE['url'] . '/#business'],
        'inLanguage' => 'en-US',
    ];
    if ($heroUrl) {
        $node['image'] = $heroUrl;
    }
    return $node;
}

function ld_howto_node($page, $canon)
{
    // The six First Hour steps (tab 22's steps block).
    $steps = [];
    $name = 'What to Do in the First Hour After Water Damage';
    $blocks = $page['blocks'];
    $n = count($blocks);
    for ($i = 0; $i < $n; $i++) {
        if ($blocks[$i]['type'] === 'h2' && str_contains($blocks[$i]['text'], 'First Hour')) {
            $name = plain_text($blocks[$i]['text']);
        }
        if ($blocks[$i]['type'] === 'steps') {
            foreach ($blocks[$i]['steps'] as $s) {
                $steps[] = [
                    '@type' => 'HowToStep',
                    'position' => (int) $s['n'],
                    'text' => plain_text($s['text']),
                ];
            }
            break;
        }
    }
    return [
        '@type' => 'HowTo',
        '@id' => $canon . '#howto',
        'name' => $name,
        'step' => $steps,
    ];
}

function ld_faq_node($faqs, $canon)
{
    $entities = [];
    foreach ($faqs as $f) {
        $entities[] = [
            '@type' => 'Question',
            'name' => $f['q'],
            'acceptedAnswer' => ['@type' => 'Answer', 'text' => $f['a']],
        ];
    }
    return ['@type' => 'FAQPage', '@id' => $canon . '#faq', 'mainEntity' => $entities];
}

function ld_json($graph)
{
    return json_encode(
        ['@context' => 'https://schema.org', '@graph' => $graph],
        JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT
    );
}

function page_h1($page)
{
    foreach ($page['blocks'] as $b) {
        if ($b['type'] === 'h1') {
            return $b['text'];
        }
    }
    return $page['title'];
}
