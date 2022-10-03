<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\User $user
 * @var \App\Model\Entity\Invoice $invoice
 */
?>
<p><?= __('Hello') ?>,</p>

<p><?= __('A new invoice has been created with the following details:') ?></p>

<p><?= __('Username') ?>: <?= $user->username ?></p>
<p><?= __('Invoice Id') ?>: <?= $invoice->id ?></p>
<p><?= __('Description') ?>: <?= $invoice->description ?></p>
<p><?= __('Amount') ?>: <?= display_price_currency($invoice->amount); ?></p>

<p>
    <a href="<?php echo $this->Url->build('/',
        true); ?>admin/invoices/view/<?= $invoice->id; ?>"><?= $this->Url->build('/', true); ?>admin/invoices/view/<?= $invoice->id; ?></a>
</p>

<p>
    <?= __('Thanks,') ?><br>
    <?= h(get_option('site_name')) ?>
</p>
