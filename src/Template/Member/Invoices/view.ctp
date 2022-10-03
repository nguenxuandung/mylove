<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Invoice $invoice
 */
$this->assign('title', __('Invoice #{0}', $invoice->id));
$this->assign('description', '');
$this->assign('content_title', __('Invoice #{0}', $invoice->id));
?>

<?php
$statuses = [
    1 => __('Paid'),
    2 => __('Unpaid'),
    3 => __('Canceled'),
    4 => __('Invalid Payment'),
    5 => __('Refunded'),
]
?>

<div class="box box-primary checkout-form">
    <div class="box-header with-border">
        <i class="fa fa-credit-card"></i>
        <h3 class="box-title"><?= __('Invoice #{0}', $invoice->id) ?></h3>
    </div>
    <div class="box-body">

        <legend><?= __('Invoice Details') ?></legend>

        <table class="table table-hover table-striped">
            <tr>
                <td><?= __('Status') ?></td>
                <td><?= $statuses[$invoice->status] ?></td>
            </tr>
            <tr>
                <td><?= __('Description') ?></td>
                <td><?= h($invoice->description) ?></td>
            </tr>
            <tr>
                <td><?= __('Amount') ?></td>
                <td><?= display_price_currency($invoice->amount) ?></td>
            </tr>
            <?php if ($invoice->payment_method) : ?>
                <tr>
                    <td><?= __('Payment Method') ?></td>
                    <td><?= (isset(get_payment_methods()[$invoice->payment_method])) ?
                            get_payment_methods()[$invoice->payment_method] : $invoice->payment_method ?></td>
                </tr>
            <?php endif; ?>
            <tr>
                <td><?= __('Paid Date') ?></td>
                <td><?= display_date_timezone($invoice->paid_date) ?></td>
            </tr>
            <tr>
                <td><?= __('Created') ?></td>
                <td><?= display_date_timezone($invoice->created) ?></td>
            </tr>
        </table>

        <?php if ($invoice->status == 2) : ?>

            <?=
            $this->Form->create(null, [
                'url' => ['controller' => 'Invoices', 'action' => 'checkout'],
                'id' => 'checkout-form',
            ]);

            $this->Form->setTemplates([
                'radioWrapper' => '<div class="radio">{{label}}</div>',
            ]);

            ?>

            <?= $this->Form->hidden('id', ['value' => $invoice->id]); ?>

            <legend><?= __("Payment Method") ?></legend>

            <?=
            $this->Form->control('payment_method', [
                'type' => 'select',
                'empty' => __('Please Select'),
                'options' => get_payment_methods(),
                'required' => 'required',
                'label' => false,
            ]);
            ?>

            <?php if ((bool)get_option('stripe_enable', false)) : ?>
                <div class="payment-method-details" data-paymentmethod="stripe" style="display: none;">
                    <div class="row">
                        <div class="col-sm-4">
                            <?=
                            $this->Form->control('stripe_cc', [
                                'label' => __('Credit Card'),
                                'class' => 'form-control',
                                'type' => 'number',
                            ])
                            ?>

                            <div class="row">
                                <div class="col-sm-6">
                                    <?=
                                    $this->Form->control('stripe_exp_month', [
                                        'label' => __('Expiration Month'),
                                        'class' => 'form-control',
                                        'type' => 'number',
                                        'max' => 12,
                                    ])
                                    ?>
                                </div>
                                <div class="col-sm-6">
                                    <?=
                                    $this->Form->control('stripe_exp_year', [
                                        'label' => __('Expiration Year'),
                                        'class' => 'form-control',
                                        'type' => 'number',
                                    ])
                                    ?>
                                </div>
                            </div>

                            <?=
                            $this->Form->control('stripe_cvc', [
                                'label' => __('CVC'),
                                'class' => 'form-control',
                                'type' => 'number',
                            ])
                            ?>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <?php if (get_option('banktransfer_enable', 'no') == 'yes') : ?>
                <div class="payment-method-details" data-paymentmethod="banktransfer" style="display: none;">
                    <?php
                    $searchReplaceArray = [
                        '[campaign_id]' => $invoice->id,
                        '[invoice_id]' => $invoice->id,
                        '[campaign_price]' => display_price_currency($invoice->amount),
                        '[invoice_amount]' => display_price_currency($invoice->amount),
                        '[invoice_description]' => $invoice->description,
                    ];
                    $banktransfer_instructions = str_replace(
                        array_keys($searchReplaceArray),
                        array_values($searchReplaceArray),
                        get_option('banktransfer_instructions')
                    );
                    ?>
                    <?= $banktransfer_instructions ?>
                </div>
            <?php endif; ?>

            <p class="text-center">
                <?= $this->Form->button(__('Pay Invoice'), ['class' => 'btn btn-success btn-lg']); ?>
            </p>

            <?= $this->Form->end(); ?>

        <?php endif; ?>

    </div><!-- /.box-body -->
</div>

<?php $this->start('scriptBottom'); ?>
<script>
  var checkout_form = $('#checkout-form');

  checkout_form.on('submit', function(e) {
    e.preventDefault();

    var checkoutForm = $(this);
    var submitButton = checkoutForm.find('button');

    $.ajax({
      dataType: 'json', // The type of data that you're expecting back from the server.
      type: 'POST', // he HTTP method to use for the request
      url: checkoutForm.attr('action'),
      data: checkoutForm.serialize(), // Data to be sent to the server.
      beforeSend: function(xhr) {
        submitButton.attr('disabled', 'disabled');
        $('<div class="overlay"><i class="fa fa-refresh fa-spin"></i></div>').
            insertAfter($('.checkout-form .box-body'));
      },
      success: function(result, status, xhr) {
        //console.log( result );
        if (result.status === 'success') {

          if (result.type === 'form') {
            //console.log( result.message );
            $(result.form).insertAfter(checkoutForm);
            $('#checkout-redirect-form').submit();
          }

          if (result.type === 'url') {
            //console.log( result.message );
            window.location.href = result.url;
          }

          if (result.type === 'offline') {
            //console.log( result.message );
            window.location.href = result.url;
          }

        } else {
          alert(result.message);
          submitButton.removeAttr('disabled');
          $('.checkout-form').find('.overlay').remove();
          checkoutForm[0].reset();
        }
      },
      error: function(xhr, status, error) {
        alert('An error occured: ' + xhr.status + ' ' + xhr.statusText);
      },
      complete: function(xhr, status) {
      },
    });
  });

  checkout_form.on('change', function(e) {
    var payment_method = $(this).find('select[name=payment_method]').val();
    var payment_method_details = $(this).find('.payment-method-details');

    payment_method_details.css('display', 'none');
    $(this).find('.payment-method-details[data-paymentmethod=\'' + payment_method + '\']').css('display', 'block');
  });
</script>
<?php $this->end(); ?>
