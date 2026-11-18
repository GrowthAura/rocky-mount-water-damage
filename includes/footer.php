<?php global $FOOTER_SERVICES, $GUIDES, $TOWNS, $SITE; ?>
<footer class="site-footer">
  <div class="container footer-grid">
    <div class="footer-brand">
      <p class="footer-brand-name">Water Damage <span>Rocky Mount</span></p>
      <p>Rocky Mount, NC &middot; Nash &amp; Edgecombe Counties</p>
      <a class="btn btn-gold" href="tel:+12523582834"><?= phone_svg() ?> Call (252) 358-2834</a>
      <p><a href="tel:+12523582834">(252) 358-2834</a></p>
    </div>
    <nav class="footer-col" aria-label="Services">
      <h3>Services</h3>
      <?php foreach ($FOOTER_SERVICES as $s): ?>
      <a href="<?= esc($s['href']) ?>"<?= ($currentSlug === $s['href']) ? ' aria-current="page"' : '' ?>><?= esc($s['label']) ?></a>
      <?php endforeach; ?>
    </nav>
    <nav class="footer-col" aria-label="Company">
      <h3>Company</h3>
      <a href="/"<?= ($currentSlug === '/') ? ' aria-current="page"' : '' ?>>Home</a>
      <a href="/about"<?= ($currentSlug === '/about') ? ' aria-current="page"' : '' ?>>About</a>
      <a href="/contact"<?= ($currentSlug === '/contact') ? ' aria-current="page"' : '' ?>>Contact</a>
      <?php foreach ($GUIDES as $g): ?>
      <a href="<?= esc($g['href']) ?>"<?= ($currentSlug === $g['href']) ? ' aria-current="page"' : '' ?>><?= esc($g['label']) ?></a>
      <?php endforeach; ?>
    </nav>
    <div class="footer-col" aria-label="Areas we serve">
      <h3>Areas We Serve</h3>
      <ul class="footer-areas-list">
        <?php foreach ($TOWNS as $t): ?><li><?= esc($t) ?></li><?php endforeach; ?>
      </ul>
      <p class="footer-areas-zips">ZIPs 27801, 27803, 27804</p>
      <p>Tar River &amp; Stoney Creek</p>
    </div>
  </div>
  <div class="footer-base">
    <div class="container footer-base-inner">
      <span>&copy; <?= date('Y') ?> Water Damage Rocky Mount</span>
      <span>24/7 Emergency Response</span>
      <a href="tel:+12523582834">(252) 358-2834</a>
    </div>
  </div>
</footer>
<a class="call-bar" href="tel:+12523582834" aria-label="Call (252) 358-2834 now"><span aria-hidden="true">&#9742;</span> Call (252) 358-2834</a>
<script src="/assets/js/main.js" defer></script>
</body>
</html>
