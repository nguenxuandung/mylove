<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\User $user
 * @var \App\Model\Entity\Invoice $invoice
 */
?>
<?= __('Hello') ?>,

<?= __('A new invoice has been created with the following details:') ?>

<?= __('Username') ?>: <?= $user->username ?>

<?= __('Invoice Id') ?>: <?= $invoice->id ?>

<?= __('Description') ?>: <?= $invoice->description ?>

<?= __('Amount') ?>: <?= display_price_currency($invoice->amount); ?>


<?= $this->Url->build('/', true); ?>admin/invoices/view/<?= $invoice->id; ?>


<?= __('Thanks,') ?>

<?= h(get_option('site_name')) ?>
