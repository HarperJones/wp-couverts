<?php $info = couverts_basic_info(); ?>
<?php $language = couverts_language() ?>
<?php $dates    = couverts_get_open_dates(90); ?>
<form class="couverts-form <?php echo implode(' ',apply_filters('couverts_form_classes',['form-horizontal'])); ?>" method="post">
  <input type="hidden" name="action" value="couverts_handle_reservation" />
  <h1 class="restaurant__name"><?php echo esc_html($info->RestaurantName) ?></h1>
  <h2 class="restaurant__city"><?php echo esc_html($info->RestaurantCity) ?></h2>

  <div class="<?php echo implode(' ',apply_filters('couverts_fieldgroup_classes',['form-group','reservation__timeselection'])); ?>">
    <p><?php echo esc_html($info->IntroText->$language) ?></p>

    <label for="reservation_date" class="<?php echo implode(' ',apply_filters('couverts_label_classes',['control-label','col-md-2'])); ?>"><?php _e('Date','couverts') ?></label>
    <div class="<?php echo implode(' ',apply_filters('couverts_field_container',['col-md-3'])) ?>">
      <select name="reservation_date" class="form-control js-trigger-reload" data-trigger-times="true" data-trigger-size="true">
        <?php
        foreach( $dates as $curdate ):
        ?>
        <option value="<?php echo $curdate->format('Y-m-d') ?>"><?php echo date_i18n('l j F',$curdate->getTimestamp()); ?></option>
        <?php endforeach; ?>
      </select>
    </div>

    <label for="reservation_party" class="<?php echo implode(' ',apply_filters('couverts_label_classes',['control-label','col-md-2'])); ?>"><?php _e('Persons','couverts') ?></label>
    <div class="<?php echo implode(' ',apply_filters('couverts_field_container',['col-md-3'])) ?>">
      <select name="reservation_party" class="form-control js-trigger-reload" data-trigger-times="true">
        <option value="1">1 <?php echo _e('Person','couverts') ?></option>
        <option value="2" selected>2 <?php echo _e('Persons','couverts') ?></option>
        <option value="3">3 <?php echo _e('Persons','couverts') ?></option>
        <option value="4">4 <?php echo _e('Persons','couverts') ?></option>
        <option value="5">5 <?php echo _e('Persons','couverts') ?></option>
        <option value="6">6 <?php echo _e('Persons','couverts') ?></option>
        <option value="7">7 <?php echo _e('Persons','couverts') ?></option>
        <option value="8">8 <?php echo _e('Persons','couverts') ?></option>
        <option value="9">9 <?php echo _e('Persons','couverts') ?></option>
        <option value="10">10 <?php echo _e('Persons','couverts') ?></option>
      </select>
    </div>

    <label for="reservation_time" class="<?php echo implode(' ',apply_filters('couverts_label_classes',['control-label','col-md-2'])); ?>"><?php _e('Time','couverts') ?></label>
    <div class="<?php echo implode(' ',apply_filters('couverts_field_container',['col-md-3'])) ?>">
      <select name="reservation_time" class="form-control">
        <!-- Filled by AJAX -->
      </select>
    </div>
    <button class="btn btn-primary js-page1-submit"><?php _e('Next', 'couverts') ?></button>
  </div>
  <div
    class="<?php echo implode(' ', apply_filters('couverts_fieldgroup_classes', ['form-group', 'reservation__contactinfo', 'hidden-xs-up'])); ?>">
    <!-- @todo: add fields from fields.php in one form or another -->
    <div class="js-contact-fields">

    </div>
    <button class="btn btn-primary js-page2-back"><?php _e('Back', 'couverts') ?></button>
    <button class="btn btn-primary js-page2-submit"><?php _e('Confirm', 'couverts') ?></button>
  </div>
  <div
    class="<?php echo implode(' ', apply_filters('couverts_fieldgroup_classes', ['form-group', 'reservation__confirmation', 'hidden-xs-up'])); ?>">
    <p></p>
  </div>
</form>