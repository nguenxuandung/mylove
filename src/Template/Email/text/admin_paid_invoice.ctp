<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Invoice $invoice
 * @var \App\Model\Entity\User $user
 */
?>
<?= __('Hello') ?>,

<?= __('Invoice {0} has been paid with the following details:', $invoice->id) ?>

<?= __('Username') ?>: <?= $user->username ?>

<?= __('Invoice Id') ?>: <?= $invoice->id ?>

<?= __('Description') ?>: <?= $invoice->description ?>

<?= __('Amount') ?>: <?= display_price_currency($invoice->amount); ?>


<?= $this->Url->build('/', true); ?>admin/invoices/view/<?= $invoice->id; ?>


<?= __('Thanks,') ?>

<?= h(get_option('site_name')) ?>
