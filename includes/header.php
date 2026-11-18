<!DOCTYPE html>
<?php global $NAV_SERVICES, $GUIDES, $SITE; ?>
<html lang="en-US">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="theme-color" content="#0A5451">
<meta property="og:locale" content="en_US">
<title><?= esc($HEAD['title']) ?></title>
<meta name="description" content="<?= esc($HEAD['description']) ?>">
<?php if (!empty($HEAD['canonical'])): ?>
<link rel="canonical" href="<?= esc($HEAD['canonical']) ?>">
<?php endif; ?>
<meta name="robots" content="<?= esc($HEAD['robots'] ?? 'index, follow') ?>">
<meta property="og:type" content="<?= esc($HEAD['og_type'] ?? 'website') ?>">
<meta property="og:title" content="<?= esc($HEAD['title']) ?>">
<meta property="og:description" content="<?= esc($HEAD['description']) ?>">
<?php if (!empty($HEAD['canonical'])): ?>
<meta property="og:url" content="<?= esc($HEAD['canonical']) ?>">
<?php endif; ?>
<meta property="og:site_name" content="Water Damage Rocky Mount">
<?php if (!empty($HEAD['og_image'])): ?>
<meta property="og:image" content="<?= esc($HEAD['og_image']) ?>">
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:image" content="<?= esc($HEAD['og_image']) ?>">
<?php else: ?>
<meta name="twitter:card" content="summary">
<?php endif; ?>
<meta name="twitter:title" content="<?= esc($HEAD['title']) ?>">
<meta name="twitter:description" content="<?= esc($HEAD['description']) ?>">
<link rel="icon" href="/favicon.svg" type="image/svg+xml">
<link rel="stylesheet" href="/assets/css/styles.css">
<script type="application/ld+json"><?= $HEAD['ld'] ?></script>
</head>
<body>
<a class="skip-link" href="#main">Skip to content</a>
<header class="site-header" id="site-header">
  <div class="container header-inner">
    <a class="brand" href="/"<?= ($currentSlug === '/') ? ' aria-current="page"' : '' ?> aria-label="Water Damage Rocky Mount — home">
      <span class="brand-name">Water Damage <span class="brand-city">Rocky Mount</span></span>
      <span class="brand-tag">24/7</span>
    </a>
    <nav class="site-nav" aria-label="Main navigation">
      <details class="nav-services">
        <summary>Services</summary>
        <div class="nav-dropdown">
          <?php foreach ($NAV_SERVICES as $s): ?>
          <a href="<?= esc($s['href']) ?>"<?= ($currentSlug === $s['href']) ? ' aria-current="page"' : '' ?>><?= esc($s['label']) ?></a>
          <?php endforeach; ?>
          <span class="nav-group">Guides</span>
          <?php foreach ($GUIDES as $g): ?>
          <a href="<?= esc($g['href']) ?>"<?= ($currentSlug === $g['href']) ? ' aria-current="page"' : '' ?>><?= esc($g['label']) ?></a>
          <?php endforeach; ?>
        </div>
      </details>
      <a href="/about"<?= ($currentSlug === '/about') ? ' aria-current="page"' : '' ?>>About</a>
      <a href="/contact"<?= ($currentSlug === '/contact') ? ' aria-current="page"' : '' ?>>Contact</a>
    </nav>
    <a class="btn btn-accent header-call" href="tel:+12523582834"><span class="call-full">Call (252) 358-2834</span><span class="call-icon" aria-hidden="true">&#9742;</span></a>
    <details class="mobile-menu">
      <summary aria-label="Menu"><span aria-hidden="true">&#9776;</span><span class="menu-label">Menu</span></summary>
      <div class="mobile-menu-panel">
        <a href="/"<?= ($currentSlug === '/') ? ' aria-current="page"' : '' ?>>Home</a>
        <span class="nav-group">Services</span>
        <?php foreach ($NAV_SERVICES as $s): ?>
        <a href="<?= esc($s['href']) ?>"<?= ($currentSlug === $s['href']) ? ' aria-current="page"' : '' ?>><?= esc($s['label']) ?></a>
        <?php endforeach; ?>
        <span class="nav-group">Guides</span>
        <?php foreach ($GUIDES as $g): ?>
        <a href="<?= esc($g['href']) ?>"<?= ($currentSlug === $g['href']) ? ' aria-current="page"' : '' ?>><?= esc($g['label']) ?></a>
        <?php endforeach; ?>
        <a href="/about"<?= ($currentSlug === '/about') ? ' aria-current="page"' : '' ?>>About</a>
        <a href="/contact"<?= ($currentSlug === '/contact') ? ' aria-current="page"' : '' ?>>Contact</a>
        <a class="btn btn-accent" href="tel:+12523582834">Call (252) 358-2834</a>
      </div>
    </details>
  </div>
</header>
