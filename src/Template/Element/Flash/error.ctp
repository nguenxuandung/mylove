<?php
/**
 * @var \App\View\AppView $this
 * @var array $params
 * @var string $message
 */
if (!isset($params['escape']) || $params['escape'] !== false) {
    $message = h($message);
}
?>
<?php if ($message) : ?>
    <div class="alert alert-danger" role="alert"><i class="fa fa-exclamation-triangle"></i> <?= $message ?></div>
<?php endif; ?>

