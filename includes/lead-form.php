<?php
// Lead / callback form. Rendered inside the final-CTA band on every page.
// Contact page uses the callback fields from the doc's developer spec
// (Name, Phone, Address or ZIP, What happened, Is water still coming in,
// Best time to call). Other pages use Name, Phone, Service, Message + a
// hidden Page field. No mail is sent (see lead-handler.php).
global $SITE;
$isContact = ($slug === '/contact');
$form = $form ?? [];
$errors = $form['errors'] ?? [];
$errFields = $form['error_fields'] ?? []; // WOW round: fields that failed
$values = $form['values'] ?? [];
$notice = $form['notice'] ?? false;
?>
<div class="form-card" id="request-service">
  <p class="form-title">Request a Callback</p>
  <?php if ($notice): ?>
  <div class="form-note" role="status">
    <p><strong>Thank you. Your request was received on this page only.</strong> No email address has been set up for requests yet, so nothing was sent anywhere. For immediate help, call <?= phone_link() ?>.</p>
  </div>
  <?php else: ?>
  <?php if ($errors): ?>
  <div class="form-note" role="alert" id="form-errors">
    <p><strong>Please fix the following:</strong> <?= esc(implode(' ', $errors)) ?></p>
  </div>
  <?php endif; ?>
  <form method="post" action="" novalidate>
    <input type="hidden" name="page" value="<?= esc($slug) ?>">
    <label for="f-name">Your name</label>
    <input id="f-name" name="name" type="text" autocomplete="name" required maxlength="120" value="<?= esc($values['name'] ?? '') ?>"<?= in_array('name', $errFields) ? ' aria-invalid="true" aria-describedby="form-errors"' : '' ?>>
    <label for="f-phone">Phone number</label>
    <input id="f-phone" name="phone" type="tel" autocomplete="tel" required maxlength="40" value="<?= esc($values['phone'] ?? '') ?>"<?= in_array('phone', $errFields) ? ' aria-invalid="true" aria-describedby="form-errors"' : '' ?>>
    <?php if ($isContact): ?>
    <label for="f-address_zip">Address or ZIP</label>
    <input id="f-address_zip" name="address_zip" type="text" autocomplete="postal-code" maxlength="160" value="<?= esc($values['address_zip'] ?? '') ?>">
    <label for="f-what">What happened?</label>
    <select id="f-what" name="what"<?= in_array('what', $errFields) ? ' aria-invalid="true" aria-describedby="form-errors"' : '' ?>>
      <option value="">Select what happened</option>
      <?php foreach (lead_what_options() as $o): ?>
      <option value="<?= esc($o) ?>"<?= (($values['what'] ?? '') === $o) ? ' selected' : '' ?>><?= esc(ucfirst($o)) ?></option>
      <?php endforeach; ?>
    </select>
    <fieldset class="form-fieldset">
      <legend>Is water still coming in?</legend>
      <label class="radio"><input type="radio" name="still_coming" value="yes"<?= (($values['still_coming'] ?? '') === 'yes') ? ' checked' : '' ?>> Yes</label>
      <label class="radio"><input type="radio" name="still_coming" value="no"<?= (($values['still_coming'] ?? '') === 'no') ? ' checked' : '' ?>> No</label>
    </fieldset>
    <label for="f-best_time">Best time to call</label>
    <input id="f-best_time" name="best_time" type="text" maxlength="120" value="<?= esc($values['best_time'] ?? '') ?>">
    <?php else: ?>
    <label for="f-service">What is happening?</label>
    <select id="f-service" name="service">
      <option value="">Select a service</option>
      <?php foreach (lead_service_options() as $o): ?>
      <option value="<?= esc($o) ?>"<?= (($values['service'] ?? '') === $o) ? ' selected' : '' ?>><?= esc($o) ?></option>
      <?php endforeach; ?>
    </select>
    <label for="f-message">Tell us what happened</label>
    <textarea id="f-message" name="message" required maxlength="2000"<?= in_array('message', $errFields) ? ' aria-invalid="true" aria-describedby="form-errors"' : '' ?>><?= esc($values['message'] ?? '') ?></textarea>
    <?php endif; ?>
    <p class="hp-field" aria-hidden="true"><label>Leave this empty <input type="text" name="website" tabindex="-1" autocomplete="off" value=""></label></p>
    <button class="btn btn-accent" type="submit">Request a Callback</button>
    <p class="form-small">For immediate help, call <?= phone_link() ?>.</p>
  </form>
  <?php endif; ?>
</div>
