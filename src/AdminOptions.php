<?php
/**
 * Created by PhpStorm.
 * User: alihoroztepe
 * Date: 03/10/16
 * Time: 14:56
 */
namespace HarperJones\Couverts;


class AdminOptions
{

  public function __construct()
  {
    add_action('admin_menu', array(&$this, 'couverts_add_admin_menu'));
    add_action('admin_init', array(&$this, 'couverts_settings_init'));
  }


  function couverts_add_admin_menu()
  {

    add_options_page('Couverts', 'Couverts', 'manage_options', 'couverts', array(&$this, 'couverts_options_page'));

  }


  function couverts_settings_init()
  {

    register_setting('pluginPage', 'couverts_settings');

    add_settings_section(
      'couverts_pluginPage_section',
      '',
      '',
      'pluginPage'
    );

    add_settings_field(
      'COUVERTS_RESTAURANT_CODE',
      __('Restaurant Code', 'couverts'),
      array(&$this, 'couverts_restaurant_id_field_render'),
      'pluginPage',
      'couverts_pluginPage_section'
    );

    add_settings_field(
      'COUVERTS_API_KEY',
      __('Api Key', 'couverts'),
      array(&$this, 'couverts_api_key_field_render'),
      'pluginPage',
      'couverts_pluginPage_section'
    );

    add_settings_field(
      'COUVERTS_API_URL',
      __('Operation Mode', 'couverts'),
      array(&$this, 'couverts_test_switch_field_render'),
      'pluginPage',
      'couverts_pluginPage_section'
    );

    add_settings_field(
      'COUVERTS_LANGUAGE',
      __('Language', 'couverts'),
      array(&$this, 'couverts_couverts_language_field_render'),
      'pluginPage',
      'couverts_pluginPage_section'
    );

    add_settings_field(
      'COUVERTS_CACHE_TIMEOUT',
      __('Advanced Setting: Cache timeout(ms)', 'couverts'),
      array(&$this, 'couverts_cache_timeout_field_render'),
      'pluginPage',
      'couverts_pluginPage_section'
    );


  }


  function couverts_restaurant_id_field_render()
  {

    $options = get_option('couverts_settings');
    ?>
    <input type='text' name='couverts_settings[COUVERTS_RESTAURANT_CODE]'
           value='<?php echo $options['COUVERTS_RESTAURANT_CODE']; ?>'>
    <?php

  }


  function couverts_api_key_field_render()
  {

    $options = get_option('couverts_settings');
    ?>
    <input type='text' name='couverts_settings[COUVERTS_API_KEY]'
           value='<?php echo $options['COUVERTS_API_KEY']; ?>'>
    <?php

  }


  function couverts_test_switch_field_render()
  {

    $options = get_option('couverts_settings');
    ?>
    <select name='couverts_settings[COUVERTS_API_URL]'>
      <option value='https://api.testing.couverts.nl' <?php selected($options['COUVERTS_API_URL'], 'https://api.testing.couverts.nl'); ?>>Test</option>
      <option value='https://api.couverts.nl/' <?php selected($options['COUVERTS_API_URL'], 'https://api.couverts.nl/'); ?>>Live</option>
    </select>
    <?php

  }


  function couverts_couverts_language_field_render()
  {

    $options = get_option('couverts_settings');
    ?>
    <select name='couverts_settings[COUVERTS_LANGUAGE]'>
      <option value='Dutch' <?php selected($options['COUVERTS_LANGUAGE'], 'Dutch'); ?>>Dutch</option>
      <option value='English' <?php selected($options['COUVERTS_LANGUAGE'], 'English'); ?>>English</option>
    </select>

    <?php

  }


  function couverts_cache_timeout_field_render()
  {

    $options = get_option('couverts_settings');
    ?>
    <input type='text' name='couverts_settings[COUVERTS_CACHE_TIMEOUT]'
           value='<?php echo ($options['COUVERTS_CACHE_TIMEOUT'] != '') ? $options['COUVERTS_CACHE_TIMEOUT'] : 300; ?>'>
    <?php

    if($_GET['settings-updated'] == 'true'){
      delete_transient('drc-couverts-basic-info');
    }

  }


  function couverts_options_page()
  {

    ?>
    <form action='options.php' method='post'>

      <h2>Dinner Reservations Calendar with Couverts</h2>

      <?php
      settings_fields('pluginPage');
      do_settings_sections('pluginPage');
      submit_button();
      ?>

    </form>
    <?php

  }
}