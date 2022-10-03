<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Option $option
 */
$this->assign('title', __('Popup Advertisement Price'));
$this->assign('description', '');
$this->assign('content_title', __('Popup Advertisement Price'));
?>

<?php
$traffic_source = [
    '1' => __('Desktop, Mobile and Tablet'),
    '2' => __('Desktop Only'),
    '3' => __('Mobile / Tablet Only'),
];
?>

<div class="box box-primary">
    <div class="box-body">

        <?php if ($this->request->getQuery('source') &&
            in_array($this->request->getQuery('source'), [1, 2, 3])
        ) : ?>

            <?php
            $source = $this->request->getQuery('source');
            ?>

            <legend><?= __("Set Prices For {0}", $traffic_source[$source]) ?></legend>

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
                                $this->Form->control('value[' . $key . '][' . $source . '][advertiser]', [
                                    'label' => false,
                                    'class' => 'form-control advertiser_price',
                                    'type' => 'text',
                                    'placeholder' => 'Advertiser Price',
                                    'value' => (isset($option->value[$key][$source]['advertiser'])) ? $option->value[$key][$source]['advertiser'] : '',
                                ]);

                                ?>
                            </div>
                            <div class="col-sm-4">
                                <?=
                                $this->Form->control('value[' . $key . '][' . $source . '][publisher]', [
                                    'label' => false,
                                    'class' => 'form-control publisher_price',
                                    'type' => 'text',
                                    'placeholder' => 'Publisher Price',
                                    'value' => (isset($option->value[$key][$source]['publisher'])) ? $option->value[$key][$source]['publisher'] : '',
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

        <?php else : ?>

            <?= $this->Form->create(null, ['type' => 'get']); ?>

            <?=
            $this->Form->control('source', [
                'label' => __('Set Price For'),
                'options' => $traffic_source,
                'empty' => __('Choose'),
                'class' => 'form-control',
            ]);
            ?>

            <?= $this->Form->button(__('Submit'), ['class' => 'btn btn-primary btn-lg']); ?>

            <?= $this->Form->end(); ?>

        <?php endif; ?>

    </div><!-- /.box-body -->
</div>

<?php $this->start('scriptBottom'); ?>
<script>
  $('#set-price').on('submit', function(e) {
    e.preventDefault();

    var price_type = $(this).find('select[name=price_type]').val();
    var desktop_price = $(this).find('input[name=desktop]').val();
    var mobile_price = $(this).find('input[name=mobile]').val();

    $('.advertiser_price').each(function() {
      if (!desktop_price) {
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

    $('.publisher_price').each(function() {
      if (!mobile_price) {
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
