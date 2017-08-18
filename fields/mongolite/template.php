<div 
  class="structure<?php e($field->readonly(), ' structure-readonly') ?>" 
  data-field="mongolite" 
>

  <?php echo $field->headline() ?>

  <table
    class="display"
    width="100%"
    data-mongolite-columns="<?php __(json_encode($field->columns())) ?>"
    data-mongolite-entries="<?php __(site()->url()) ?>/<?php __(kirby()->option('mongolite.route', 'kirby-mongolite')) ?>/<?php __($field->collection()) ?>"
    data-mongolite-add="<?php __($field->url('add')) ?>"
    data-mongolite-update="<?php __($field->url('update')) ?>"
    data-mongolite-delete="<?php __($field->url('delete')) ?>"
  ></table>

</div>