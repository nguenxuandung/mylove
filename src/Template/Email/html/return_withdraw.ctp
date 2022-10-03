<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Withdraw $withdraw
 * @var \App\Model\Entity\User $user
 */
?>

<p><?= __('Hello') ?> <?= $user->username ?>,</p>

<p><?= __('Your withdrawal request #{0} has been returned back to your account.', $withdraw->id) ?></p>

<p>
    <?= __('Thanks,') ?><br>
    <?= h(get_option('site_name')) ?>
</p>
