<?php
// Rocky Mount NC — Water Damage Restoration. Front controller / router.
// Plain PHP 8. Clean URLs, no trailing slash except the homepage.
// Slash forms of a no-slash route answer 308 to the no-slash form.
// Local only: no deploy, no domain, no external service calls.
$ROOT = __DIR__;
require $ROOT . '/data/pages.php';
if (is_file($ROOT . '/data/media-manifest.php')) {
    require $ROOT . '/data/media-manifest.php';
}
require $ROOT . '/includes/functions.php';

$PAGES = build_pages();
$SITE_URL = 'https://rockymountwaterdamage.site';

// F-12 (zero-blunder): do NOT use parse_url() for the path — parse_url()
// treats a leading "//" as a scheme-relative URL (host, no path), so
// "//" collapsed to "/" and served the homepage with 200. Split on "?"
// directly so "//", "//foo" and "///" survive intact for rule 0 below.
$uri = $_SERVER['REQUEST_URI'] ?? '/';
$rawPath = strtok($uri, '?') ?: '/';
$path = rawurldecode($rawPath);

// 0. F-12 (zero-blunder): any path containing a double slash (e.g. //)
// must not serve homepage content with 200 — 308 to /.
if (str_contains($path, '//')) {
    header('Location: /', true, 308);
    exit;
}

// 1. Slash form of a no-slash route -> 308 to the no-slash form.
if ($path !== '/' && strlen($path) > 1 && str_ends_with($path, '/')) {
    $target = rtrim($path, '/');
    header('Location: ' . $target, true, 308);
    exit;
}

// 2. Static files (assets, favicon, robots, sitemap, llms).
$MIME = [
    'css' => 'text/css', 'js' => 'text/javascript', 'jpg' => 'image/jpeg',
    'jpeg' => 'image/jpeg', 'webp' => 'image/webp', 'png' => 'image/png',
    'ico' => 'image/x-icon', 'svg' => 'image/svg+xml', 'woff2' => 'font/woff2',
    'txt' => 'text/plain', 'xml' => 'application/xml',
];
$isStatic = str_starts_with($path, '/assets/') ||
    in_array($path, ['/favicon.ico', '/favicon.svg', '/robots.txt', '/sitemap.xml', '/llms.txt'], true);
if ($isStatic) {
    $file = $ROOT . $path;
    if (is_file($file)) {
        $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
        header('Content-Type: ' . ($MIME[$ext] ?? 'application/octet-stream'));
        header('Content-Length: ' . filesize($file));
        readfile($file);
        exit;
    }
    http_response_code(404);
    echo 'Not found';
    exit;
}

// 3. Routes.
if ($path === '/') {
    render_page('/', $PAGES['/']);
    exit;
}
if (isset($PAGES[$path])) {
    render_page($path, $PAGES[$path]);
    exit;
}

// 4. 404.
http_response_code(404);
$HEAD = [
    'title' => 'Page Not Found | Water Damage Rocky Mount',
    'description' => 'The page you asked for is not here. Call (252) 358-2834 for 24/7 emergency water damage help in Rocky Mount, NC.',
    'canonical' => null, // F-04 (zero-blunder): no canonical on the 404 response
    'robots' => 'noindex, follow',
    'og_type' => 'website',
    'og_image' => null,
    'ld' => ld_json([ld_website_node(), ld_local_business_node(null, false)]),
];
$currentSlug = $path;
$is_404 = true;
require $ROOT . '/includes/header.php';
?>
<main id="main">
  <section class="hero">
    <div class="container hero-content">
      <h1>Page Not Found (404)</h1>
      <p class="hero-intro">The page you asked for is not here.</p>
      <div class="cta-row">
        <a class="btn btn-accent" href="tel:+12523582834"><?= phone_svg() ?> Call (252) 358-2834</a>
        <a class="btn btn-ghost-light" href="/">Back to home</a>
      </div>
    </div>
  </section>
</main>
<?php
require $ROOT . '/includes/footer.php';
exit;

// ---------------------------------------------------------------------------
// Page renderer. Every visible word inside <main> comes from data/pages.php
// (the doc, verbatim); only header/footer chrome is template text.
// ---------------------------------------------------------------------------
function render_page($slug, $page)
{
    global $ROOT, $SITE_URL;
    $canon = $SITE_URL . ($slug === '/' ? '/' : $slug);
    $hero = page_hero($slug);
    $heroUrl = $hero ? $SITE_URL . $hero['src'] : null;
    $faqs = page_faqs($page);
    $graph = [];
    $graph[] = ld_website_node();
    $graph[] = ld_webpage_node($page, $canon, $heroUrl);
    $graph[] = ld_breadcrumb_node($page, $canon);
    $graph[] = ld_local_business_node($heroUrl, $page['tab_no'] === 1);
    $kind = $page['kind'];
    if ($kind === 'home') {
        $graph[] = ld_service_node($page, $canon, $heroUrl);
    } elseif ($kind === 'service') {
        $graph[] = ld_service_node($page, $canon, $heroUrl);
    } elseif ($kind === 'article') {
        $graph[] = ld_article_node($page, $canon, $heroUrl);
    } elseif ($kind === 'howto') {
        $graph[] = ld_article_node($page, $canon, $heroUrl);
        $graph[] = ld_howto_node($page, $canon);
    } elseif ($kind === 'about') {
        // AboutPage node is emitted by ld_webpage_node via $page['page_type'].
    }
    if ($faqs) {
        $graph[] = ld_faq_node($faqs, $canon);
    }
    $HEAD = [
        'title' => $page['title'],
        'description' => $page['meta'],
        'canonical' => $canon,
        'robots' => 'index, follow',
        'og_type' => ($kind === 'article' || $kind === 'howto') ? 'article' : 'website',
        'og_image' => $heroUrl,
        'ld' => ld_json($graph),
    ];
    $currentSlug = $slug;
    $is_404 = false;
    require $ROOT . '/includes/header.php';
    render_main($slug, $page, $hero);
    // Final CTA band: phone button only (lead form removed 2026-11-19,
    // user order "forum hata do" — the form is gone sitewide).
    render_final_cta($slug, $page);
    require $ROOT . '/includes/footer.php';
}

// Render <main> from the page's blocks, in doc order.
function render_main($slug, $page, $hero)
{
    $blocks = $page['blocks'];
    $isHome = $slug === '/';
    echo "<main id=\"main\">\n";
    // Breadcrumb (inner pages only).
    if (!$isHome) {
        echo '<nav class="crumbs" aria-label="Breadcrumb"><div class="container"><a href="/">Home</a> <span aria-hidden="true">&rsaquo;</span> <span aria-current="page">' . esc($page['crumb']) . '</span></div></nav>' . "\n";
    }
    // Hero: H1 + intro paragraph + first CTA group + trust band (if any).
    $i = 0;
    $n = count($blocks);
    echo '<section class="hero">' . "\n";
    if ($hero) {
        echo '<div class="hero-media"><img src="' . esc($hero['src']) . '"' . srcset_attr($hero, '(max-width: 1280px) 100vw, 1280px') . ' alt="' . esc($hero['alt']) . '" width="1280" height="720" fetchpriority="high" decoding="async"></div>' . "\n";
    }
    echo '<div class="container hero-content">' . "\n";
    while ($i < $n) {
        $b = $blocks[$i];
        if ($b['type'] === 'h1') {
            echo '<h1>' . esc(clean_dashes($b['text'])) . '</h1>' . "\n";
            $i++;
            continue;
        }
        if ($b['type'] === 'p' && $i === 1) {
            echo '<p class="hero-intro">' . render_linked($b) . '</p>' . "\n";
            $i++;
            continue;
        }
        if ($b['type'] === 'cta') {
            // Consume the consecutive CTA group.
            $group = [];
            while ($i < $n && $blocks[$i]['type'] === 'cta') {
                $group[] = $blocks[$i];
                $i++;
            }
            echo cta_group_html($group, 'hero');
            continue;
        }
        if ($b['type'] === 'trust') {
            echo '<ul class="trust-band">';
            foreach ($b['trust_items'] as $t) {
                echo '<li><span class="check" aria-hidden="true">&#10003;</span>' . esc(clean_dashes($t)) . '</li>';
            }
            echo "</ul>\n";
            $i++;
            continue;
        }
        break;
    }
    echo "</div>\n</section>\n";

    // Body: remaining blocks in doc order.
    echo '<div class="container content">' . "\n";
    $inCards = false;
    $inFaq = false;
    $containerOpen = true;
    while ($i < $n) {
        $b = $blocks[$i];
        $t = $b['type'];
        if ($t === 'h2') {
            if ($inCards === true) {
                echo "</div>\n";
                $inCards = false;
            }
            if ($inFaq) {
                echo "</section>\n";
                $inFaq = false;
            }
            if (!empty($b['faq_section'])) {
                echo '<section class="faq-section">' . "\n";
                $inFaq = true;
            }
            echo '<h2>' . esc(clean_dashes($b['text'])) . '</h2>' . "\n";
            // Homepage services H2 opens the card grid for the H3s that follow.
            if ($isHome && $b['text'] === 'Water Damage Restoration Services in Rocky Mount, NC') {
                $inCards = 'pending';
            }
            $i++;
            continue;
        }
        if ($t === 'h3') {
            if (!empty($b['faq'])) {
                // FAQ item: H3 + following FAQ paragraphs.
                $q = clean_dashes($b['text']);
                $i++;
                $answers = [];
                while ($i < $n && $blocks[$i]['type'] === 'p' && !empty($blocks[$i]['faq'])) {
                    $answers[] = $blocks[$i];
                    $i++;
                }
                echo '<details class="faq-item" open><summary><h3>' . esc($q) . '</h3><span class="faq-mark" aria-hidden="true"></span></summary><div class="faq-answer">' . "\n";
                foreach ($answers as $a) {
                    echo '<p>' . render_linked($a) . "</p>\n";
                }
                echo "</div></details>\n";
                continue;
            }
            if ($inCards === 'pending' || $inCards === true) {
                if ($inCards === 'pending') {
                    echo '<div class="card-grid">' . "\n";
                    $inCards = true;
                }
                // Card: H3 + following paragraph (with its doc link).
                $cardSlug = null;
                $excerpt = null;
                if ($i + 1 < $n && $blocks[$i + 1]['type'] === 'p') {
                    $excerpt = $blocks[$i + 1];
                    $links = $excerpt['links'] ?? [];
                    if ($links) {
                        $cardSlug = ltrim($links[0]['href'], '/');
                        $cardSlug = rtrim($cardSlug, '/');
                    }
                }
                $cardImg = $cardSlug ? page_card($cardSlug) : null;
                echo '<article class="card">';
                if ($cardImg) {
                    echo '<img src="' . esc($cardImg['src']) . '"' . srcset_attr($cardImg, '(max-width: 640px) 100vw, (max-width: 1020px) 50vw, 33vw') . ' alt="' . esc($cardImg['alt']) . '" width="800" height="450" loading="lazy" decoding="async">';
                }
                echo '<div class="card-body"><h3>' . esc(clean_dashes($b['text'])) . '</h3>';
                if ($excerpt) {
                    echo '<p>' . render_linked($excerpt) . '</p>';
                    $i++;
                }
                echo "</div></article>\n";
                $i++;
                continue;
            }
            echo '<h3>' . esc(clean_dashes($b['text'])) . '</h3>' . "\n";
            $i++;
            continue;
        }
        if ($t === 'p') {
            if (!empty($b['faq_intro'])) {
                echo '<p class="faq-intro">' . render_linked($b) . "</p>\n";
            } elseif (!empty($b['faq'])) {
                // Orphan FAQ answer (no preceding FAQ H3 seen) — render plain.
                echo '<p>' . render_linked($b) . "</p>\n";
            } else {
                echo '<p>' . render_linked($b) . "</p>\n";
            }
            $i++;
            continue;
        }
        if ($t === 'bullet') {
            echo "<ul>\n";
            foreach ($b['items'] as $it) {
                echo '<li>' . render_linked_text($it['text'], $it['links'] ?? []) . "</li>\n";
            }
            echo "</ul>\n";
            $i++;
            continue;
        }
        if ($t === 'inline_list') {
            echo '<p class="areas-list">' . esc(clean_dashes($b['text'])) . "</p>\n";
            $i++;
            continue;
        }
        if ($t === 'areas') {
            // Areas We Serve grid (user order 2026-11-19): one H3 per town,
            // "Water Damage Restoration in <town>", from $TOWNS (doc towns).
            global $TOWNS;
            echo '<section class="areas-section" aria-label="Areas we serve">' . "\n";
            echo '<div class="areas-grid">' . "\n";
            foreach ($TOWNS as $town) {
                echo '<article class="area-card"><h3>Water Damage Restoration in ' . esc($town) . '</h3></article>' . "\n";
            }
            echo "</div>\n</section>\n";
            $i++;
            continue;
        }
        if ($t === 'steps') {
            echo '<ol class="steps">' . "\n";
            foreach ($b['steps'] as $s) {
                echo '<li><span class="step-n" aria-hidden="true">' . (int) $s['n'] . '</span><p>' . render_linked_text($s['text'], $s['links'] ?? []) . "</p></li>\n";
            }
            echo "</ol>\n";
            $i++;
            continue;
        }
        if ($t === 'cta') {
            $group = [];
            while ($i < $n && $blocks[$i]['type'] === 'cta') {
                $group[] = $blocks[$i];
                $i++;
            }
            // A lone phone CTA renders as a full-width call strip; a
            // phone + callback pair renders as a button row.
            $hasPhone = false;
            foreach ($group as $g) {
                if (str_contains($g['text'], '(252) 358-2834')) {
                    $hasPhone = true;
                }
            }
            if ($hasPhone && count($group) === 1) {
                if ($containerOpen) {
                    echo "</div>\n";
                    $containerOpen = false;
                }
                echo cta_group_html($group, 'strip');
                echo '<div class="container content">' . "\n";
                $containerOpen = true;
            } else {
                echo cta_group_html($group, 'row');
            }
            continue;
        }
        if ($t === 'trust') {
            echo '<ul class="trust-band">';
            foreach ($b['trust_items'] as $tt) {
                echo '<li><span class="check" aria-hidden="true">&#10003;</span>' . esc(clean_dashes($tt)) . '</li>';
            }
            echo "</ul>\n";
            $i++;
            continue;
        }
        // Unknown block types are skipped only if they carry no text.
        $i++;
    }
    if ($inCards === true) {
        echo "</div>\n";
    }
    if ($inFaq) {
        echo "</section>\n";
    }
    if ($containerOpen) {
        echo "</div>\n";
    }
    echo "</main>\n";
}

// One CTA group: hero buttons, a call strip, or a plain button row.
// CTA href: the doc link when the block carries one (About's
// "Request a Callback" -> /contact/), otherwise tel:+12523582834.
function cta_group_html($group, $style)
{
    $out = '';
    if ($style === 'strip') {
        $g = $group[0];
        $href = cta_href($g);
        $out .= '<section class="call-strip"><div class="container"><a class="call-strip-link" href="' . esc($href) . '">' . phone_svg() . ' Call (252) 358-2834</a></div></section>' . "\n";
        return $out;
    }
    $cls = $style === 'hero' ? 'cta-row hero-ctas' : 'cta-row';
    $out .= '<div class="' . $cls . '">';
    foreach ($group as $g) {
        $href = cta_href($g);
        // SHORT LABELS (his 2026-11-18 ruling, overrides verbatim for button
        // labels only): every doc phone CTA collapses to one label;
        // "Request a Callback" is already short and stays as-is. The doc
        // text in data/pages.php is NOT edited — the mapping lives here at
        // render time, so the source of truth stays verbatim.
        $isPhone = str_contains($g['text'], '(252) 358-2834');
        $label = $isPhone ? 'Call (252) 358-2834' : 'Request a Callback';
        $btn = $isPhone ? 'btn btn-accent' : 'btn btn-outline';
        if ($style === 'hero' && !$isPhone) {
            $btn = 'btn btn-ghost-light';
        }
        $icon = $isPhone ? phone_svg() . ' ' : '';
        $out .= '<a class="' . $btn . '" href="' . esc($href) . '">' . $icon . esc($label) . '</a>';
    }
    $out .= "</div>\n";
    return $out;
}

function cta_href($cta)
{
    $links = $cta['links'] ?? [];
    if ($links) {
        // F-03 (zero-blunder): doc annotations carry trailing slashes
        // (e.g. About "Request a Callback" -> /contact/); render the
        // no-slash canonical form. pages.json is NOT edited — the
        // annotation target stays /contact/ there; only the rendered href
        // changes, same page, canonical form.
        $href = $links[0]['href'];
        return $href === '/' ? '/' : rtrim($href, '/');
    }
    return 'tel:+12523582834';
}

// Final CTA band (after </main>): phone button only, centred.
// (Lead form removed 2026-11-19 on the user's order; the "Request a
// Callback" doc buttons stay — they dial tel: or link /contact already.)
function render_final_cta($slug, $page)
{
    echo '<section class="final-cta"><div class="container final-cta-single">' . "\n";
    echo '<a class="btn btn-accent btn-big" href="tel:+12523582834">' . phone_svg() . ' Call (252) 358-2834</a>'
        . '<p class="final-cta-note">24/7 Emergency Response</p>' . "\n";
    echo "</div></section>\n";
}
