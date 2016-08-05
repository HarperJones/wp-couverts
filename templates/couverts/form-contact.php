<?php if ($inputFields->Gender->Show): ?>
  <label for=""
         class="<?php echo implode(' ', apply_filters('couverts_label_classes', ['control-label', 'col-md-2'])); ?>"><?php _e('Gender', 'couverts'); ?><?= $inputFields->Gender->Required ? " *" : "" ?></label>
  <div class="<?php echo implode(' ', apply_filters('couverts_field_container', ['col-md-3'])) ?>">
    <label class="radio-inline">
      <input type="radio" name="gender" id="gender-male"
             value="Male" <?= $inputFields->Gender->Required ? " required" : "" ?>> <?php _e('Male', 'couverts') ?>
    </label>
    <label class="radio-inline">
      <input type="radio" name="gender" id="gender-female" value="Female"> <?php _e('Female', 'couverts') ?>
    </label>
  </div>
<?php endif ?>
<?php if ($inputFields->FirstName->Show): ?>
  <div class="form-group">
    <label for="firstname"
           class="<?php echo implode(' ', apply_filters('couverts_label_classes', ['control-label', 'col-md-2'])); ?>"><?php _e('First Name', 'couverts'); ?><?= $inputFields->FirstName->Required ? " *" : "" ?></label>
    <div class="<?php echo implode(' ', apply_filters('couverts_field_container', ['col-md-3'])) ?>">
      <input type="text" class="form-control" id="firstname" name="firstname" placeholder="<?php _e('first name','couverts') ?>"
             value=""<?= $inputFields->FirstName->Required ? " required" : "" ?>>
    </div>
  </div>
<?php endif ?>
<?php if ($inputFields->LastName->Show): ?>
  <div class="form-group">
    <label for="lastname"
           class="<?php echo implode(' ', apply_filters('couverts_label_classes', ['control-label', 'col-md-2'])); ?>">
      <?php _e('Last Name', 'couverts') ?><?= $inputFields->LastName->Required ? " *" : "" ?></label>
    <div class="<?php echo implode(' ', apply_filters('couverts_field_container', ['col-md-3'])) ?>">
      <input type="text" class="form-control" id="lastname" name="lastname" placeholder="<?php _e('last name','couverts') ?>"
             value="" <?= $inputFields->LastName->Required ? " required" : "" ?>>
    </div>
  </div>
<?php endif ?>
<?php if ($inputFields->Email->Show): ?>
  <div class="form-group">
    <label for="email"
           class="<?php echo implode(' ', apply_filters('couverts_label_classes', ['control-label', 'col-md-2'])); ?>"><?php _e('Email', 'couverts') ?><?= $inputFields->Email->Required ? " *" : "" ?></label>
    <div class="<?php echo implode(' ', apply_filters('couverts_field_container', ['col-md-3'])) ?>">
      <input type="email" class="form-control" id="email" name="email" placeholder="<?php _e('email address','couverts'); ?>"
             value="" <?= $inputFields->Email->Required ? " required" : "" ?>>
    </div>
  </div>
<?php endif ?>
<?php if ($inputFields->PhoneNumber->Show): ?>
  <div class="form-group">
    <label for="phonenumber"
           class="<?php echo implode(' ', apply_filters('couverts_label_classes', ['control-label', 'col-md-2'])); ?>"><?php _e('Phone Number', 'couverts') ?>
      <?= $inputFields->PhoneNumber->Required ? " *" : "" ?></label>
    <div class="<?php echo implode(' ', apply_filters('couverts_field_container', ['col-md-3'])) ?>">
      <input type="text" class="form-control" id="phonenumber" name="phonenumber" placeholder="<?php _e('phone number','couverts') ?>"
             value="" <?= $inputFields->PhoneNumber->Required ? " required" : "" ?>>
    </div>
  </div>
<?php endif ?>
<?php if ($inputFields->PostalCode->Show): ?>
  <div class="form-group">
    <label for="postalcode"
           class="<?php echo implode(' ', apply_filters('couverts_label_classes', ['control-label', 'col-md-2'])); ?>"><?php _e('Postal Code', 'couverts'); ?><?= $inputFields->PostalCode->Required ? " *" : "" ?></label>
    <div class="<?php echo implode(' ', apply_filters('couverts_field_container', ['col-md-3'])) ?>">
      <input type="text" class="form-control" id="postalcode" name="postalcode" placeholder="<?php _e('postal code','couverts') ?>"
             value="" <?= $inputFields->PostalCode->Required ? " required" : "" ?>>
    </div>
  </div>
<?php endif ?>
<?php if ($inputFields->BirthDate->Show): ?>
  <div class="form-group">
    <label for="birthdate"
           class="<?php echo implode(' ', apply_filters('couverts_label_classes', ['control-label', 'col-md-2'])); ?>"><?php _e('Birth Date', 'couverts') ?><?= $inputFields->BirthDate->Required ? " *" : "" ?></label>
    <div class="<?php echo implode(' ', apply_filters('couverts_field_container', ['col-md-3'])) ?>">
      <input type="text" class="form-control" id="birthdate" name="birthdate" placeholder="<?php _e('Birth date yyyy-mm-dd','couverts') ?>"
             value="" <?= $inputFields->BirthDate->Required ? " required" : "" ?>>
    </div>
  </div>
<?php endif ?>
<?php if ($inputFields->Comments->Show): ?>
  <div class="form-group">
    <label for="comments"
           class="<?php echo implode(' ', apply_filters('couverts_label_classes', ['control-label', 'col-md-2'])); ?>"><?php _e('Comments', 'couverts') ?><?= $inputFields->Comments->Required ? " *" : "" ?></label>
    <div class="<?php echo implode(' ', apply_filters('couverts_field_container', ['col-md-3'])) ?>">
        <textarea class="form-control" rows="5" id="comments" name="comments"
                  placeholder="<?php _e('comments','couverts') ?>"<?= $inputFields->Comments->Required ? " required" : "" ?>></textarea>
    </div>
  </div>
<?php endif ?>
<?php $language = couverts_language() ?>
<?php foreach ($inputFields->RestaurantSpecificFields AS $field) : ?>
  <div class="form-group">
    <?php if ($field->Type=="Text"): ?>
      <label for="<?= $field->Id ?>"
             class="<?php echo implode(' ', apply_filters('couverts_label_classes', ['control-label', 'col-md-2'])); ?>"><?= $field->Title->$language ?><?= $field->Required ? " *" : "" ?></label>
      <div class="<?php echo implode(' ', apply_filters('couverts_field_container', ['col-md-3'])) ?>">
                    <textarea class="form-control" rows="5" id="<?= $field->Id ?>" name="RestaurantSpecificFields[<?= $field->Id ?>]"
                              placeholder="<?= $field->Description->$language ?>"<?= $field->Required ? " required" : "" ?>></textarea>
      </div>
    <?php elseif ($field->Type=="Number"): ?>
      <label for="<?= $field->Id ?>"
             class="<?php echo implode(' ', apply_filters('couverts_label_classes', ['control-label', 'col-md-2'])); ?>"><?= $field->Title->$language ?><?= $field->Required ? " *" : "" ?></label>
      <div class="<?php echo implode(' ', apply_filters('couverts_field_container', ['col-md-3'])) ?>">
        <input type="number" class="form-control" id="<?= $field->Id ?>" name="<?= $field->Id ?>" placeholder="<?= $field->Description->$language ?>"<?= $field->Required ? " required" : "" ?>>
      </div>
    <?php elseif ($field->Type=="Checkbox"): ?>
      <div
        class="<?php echo implode(' ', apply_filters('couverts_checkbox_container', ['col-md-3', 'col-md-offset-2'])); ?>">
        <div class="checkbox">
          <label><input type="checkbox" name="RestaurantSpecificFields[<?= $field->Id ?>]"> <?= $field->Title->$language ?><?= $field->Required ? " *" : "" ?></label>
        </div>
        <p class="help-block"><?= $field->Description->$language ?></p>
      </div>
    <?php endif; ?>
  </div>
<?php endforeach;