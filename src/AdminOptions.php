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
    add_action('admin_menu', array($this, 'addAdminMenu'));
    add_action('admin_init', array($this, 'settingsInit'));

    add_filter('plugin_action_links_' . COUVERTS_PLUGIN_FILE, array($this,'addSettingsLink'));
  }


  public function addAdminMenu()
  {

    add_options_page('Couverts', 'Couverts', 'manage_options', 'couverts', array($this, 'optionsPage'));

  }

  public function addSettingsLink($links)
  {
    array_unshift($links,'<a href="' . admin_url('options-general.php?page=couverts') . '">' . __('Settings','couverts') . '</a>');
    return $links;
  }

  public function settingsInit()
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
      array($this, 'renderRestaurantIdField'),
      'pluginPage',
      'couverts_pluginPage_section'
    );

    add_settings_field(
      'COUVERTS_API_KEY',
      __('Api Key', 'couverts'),
      array($this, 'renderApiKeyField'),
      'pluginPage',
      'couverts_pluginPage_section'
    );

    add_settings_field(
      'COUVERTS_API_URL',
      __('Operation Mode', 'couverts'),
      array($this, 'renderStageField'),
      'pluginPage',
      'couverts_pluginPage_section'
    );

    add_settings_field(
      'COUVERTS_LANGUAGE',
      __('Language', 'couverts'),
      array($this, 'renderLanguageField'),
      'pluginPage',
      'couverts_pluginPage_section'
    );

    add_settings_field(
      'COUVERTS_CACHE_TIMEOUT',
      __('Advanced Setting: Cache timeout(ms)', 'couverts'),
      array($this, 'renderCacheTimeoutField'),
      'pluginPage',
      'couverts_pluginPage_section'
    );


  }


  public function renderRestaurantIdField()
  {

    ?>
    <input type='text' name='couverts_settings[COUVERTS_RESTAURANT_CODE]' <?= $this->codeDefined('COUVERTS_RESTAURANT_CODE') ? 'readonly' : '' ?>
           value='<?php echo Config::getRestaurantCode() ?>' size="5">
    <?php

  }


  public function renderApiKeyField()
  {
    ?>
    <input type='text' name='couverts_settings[COUVERTS_API_KEY]' <?= $this->codeDefined('COUVERTS_API_KEY') ? 'readonly' : '' ?>
           value='<?php echo Config::getApiKey() ?>' size="42">
    <?php

  }


  public function renderStageField()
  {

    $url = Config::getAPiURL();
    ?>
    <select name='couverts_settings[COUVERTS_API_URL]' <?= $this->codeDefined('COUVERTS_API_URL') ? 'disabled' : '' ?>>
      <option value='https://api.testing.couverts.nl' <?php selected($url, 'https://api.testing.couverts.nl'); ?>><?php _e('Test','couverts'); ?></option>
      <option value='https://api.couverts.nl/' <?php selected($url, 'https://api.couverts.nl/'); ?>><?php _e('Live','couverts'); ?></option>
    </select>
    <?php

  }


  public function renderLanguageField()
  {
    $lang = Config::getLanguage();

    ?>
    <select name='couverts_settings[COUVERTS_LANGUAGE]' <?= $this->codeDefined('COUVERTS_API_URL') ? 'disabled' : '' ?>>
      <option value='Dutch' <?php selected($lang, 'Dutch'); ?>><?php _e('Dutch','couverts'); ?></option>
      <option value='English' <?php selected($lang, 'English'); ?>><?php _e('English','couverts'); ?></option>
    </select>

    <?php

  }


  public function renderCacheTimeoutField()
  {

    $options = get_option('couverts_settings');
    ?>
    <input type='text' name='couverts_settings[COUVERTS_CACHE_TIMEOUT]' <?= $this->codeDefined('COUVERTS_CACHE_TIMEOUT') ? 'readonly' : '' ?>
           value='<?php echo ($options['COUVERTS_CACHE_TIMEOUT'] != '') ? $options['COUVERTS_CACHE_TIMEOUT'] : 300; ?>'>
    <?php

    if($_GET['settings-updated'] == 'true'){
      delete_transient('drc-couverts-basic-info');
    }

  }


  public function optionsPage()
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

  private function codeDefined($option)
  {
    return defined($option) || getenv($option);
  }
}