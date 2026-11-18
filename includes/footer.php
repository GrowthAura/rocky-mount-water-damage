<?php global $SERVICES, $GUIDES, $TOWNS, $SITE; ?>
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
      <?php foreach ($SERVICES as $s): ?>
      <a href="<?= esc($s['href']) ?>"><?= esc($s['label']) ?></a>
      <?php endforeach; ?>
    </nav>
    <nav class="footer-col" aria-label="Company">
      <h3>Company</h3>
      <a href="/">Home</a>
      <a href="/about">About</a>
      <a href="/contact">Contact</a>
      <?php foreach ($GUIDES as $g): ?>
      <a href="<?= esc($g['href']) ?>"><?= esc($g['label']) ?></a>
      <?php endforeach; ?>
    </nav>
    <div class="footer-col" aria-label="Areas we serve">
      <h3>Areas We Serve</h3>
      <p class="footer-areas"><?php foreach ($TOWNS as $i => $t): ?><?= $i ? ' &middot; ' : '' ?><?= esc($t) ?><?php endforeach; ?> &middot; ZIPs 27801, 27803, 27804</p>
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
