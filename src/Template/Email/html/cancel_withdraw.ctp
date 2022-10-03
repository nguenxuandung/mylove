<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Withdraw $withdraw
 * @var \App\Model\Entity\User $user
 */
?>

<p><?= __('Hello') ?> <?= $user->username ?>,</p>

<p><?= __('Your withdrawal request #{0} has been canceled.', $withdraw->id) ?></p>

<?php if ($withdraw->user_note) : ?>
    <h5><?= __('Reason') ?></h5>
    <p><?= h($withdraw->user_note) ?></p>
<?php endif; ?>

<p>
    <?= __('Thanks,') ?><br>
    <?= h(get_option('site_name')) ?>
</p>
