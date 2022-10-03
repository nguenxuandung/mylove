<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Option $option
 */
$this->assign('title', __('Interstitial Payout Rates'));
$this->assign('description', '');
$this->assign('content_title', __('Interstitial Payout Rates'));
?>

<div class="box box-primary">
    <div class="box-body">

        <form id="set-price" class="form-inline" style="font-size: 150%;padding-bottom:20px">
            <?= __('Set price in') ?>
            <select class="form-control" name="price_type">
                <option value="fixed"><?= __('Fixed') ?></option>
                <option value="%">%</option>
            </select>

            <?= __('Desktop') ?> <input type="number" min="0" step="any" name="desktop" class="form-control">

            <?= __('Mobile / Tablet') ?> <input type="number" min="0" step="any" name="mobile" class="form-control">

            <button type="submit" class="btn btn-primary"><?= __('Set') ?></button>

        </form>

        <?= $this->Form->create($option); ?>

        <?= $this->Form->hidden('id'); ?>

        <?php $i = 1; ?>

        <div class="row">
            <?php foreach (get_countries(true) as $key => $value) : ?>
                <div class="col-sm-6">
                    <div class="row">
                        <div class="col-sm-4"><?= $value ?></div>
                        <div class="col-sm-4">
                            <?=
                            $this->Form->control('value[' . $key . '][2]', [
                                'label' => false,
                                'class' => 'form-control desktop-country',
                                'type' => 'text',
                                'placeholder' => __('Desktop'),
                                'value' => isset($option->value[$key][2]) ? $option->value[$key][2] : '',
                            ]);

                            ?>
                        </div>
                        <div class="col-sm-4">
                            <?=
                            $this->Form->control('value[' . $key . '][3]', [
                                'label' => false,
                                'class' => 'form-control mobile-country',
                                'type' => 'text',
                                'placeholder' => __('Mobile / Tablet'),
                                'value' => isset($option->value[$key][3]) ? $option->value[$key][3] : '',
                            ]);

                            ?>
                        </div>
                    </div>
                </div>
                <?= (0 == $i % 2) ? '</div><div class="row">' : ''; ?>
                <?php $i++; ?>
            <?php endforeach; ?>
        </div>

        <?= $this->Form->button(__('Save'), ['class' => 'btn btn-primary']); ?>

        <?= $this->Form->end(); ?>

    </div><!-- /.box-body -->
</div>

<?php $this->start('scriptBottom'); ?>
<script>
  $('#set-price').on('submit', function(e) {
    e.preventDefault();

    var price_type = $(this).find('select[name=price_type]').val();
    var desktop_price = $(this).find('input[name=desktop]').val();
    var mobile_price = $(this).find('input[name=mobile]').val();

    $('.desktop-country').each(function() {
      if(!desktop_price) {
        return true;
      }
      if (price_type === 'fixed') {
        $(this).val(desktop_price);
      }
      if (price_type === '%') {
        var new_price = $(this).val() * (1 + (desktop_price / 100));
        $(this).val(new_price);
      }
    });

    $('.mobile-country').each(function() {
      if(!mobile_price) {
        return true;
      }
      if (price_type === 'fixed') {
        $(this).val(mobile_price);
      }
      if (price_type === '%') {
        var new_price = $(this).val() * (1 + (mobile_price / 100));
        $(this).val(new_price);
      }
    });

    $(this).find('input[name=desktop]').val('');
    $(this).find('input[name=mobile]').val('');
  });
</script>
<?php $this->end(); ?>
