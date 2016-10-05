<script type="text/javascript">
  var couverts_ajax_url   = '<?php echo admin_url('admin-ajax.php') ?>';
  var couverts_day_config = <?php echo couverts_get_day_config_js(90); ?>

  function couverts_get_times( form$ )
  {
    var rdate    = form$.find('[name="reservation_date"]').val(),
      rpersons = form$.find('[name="reservation_party"]').val();

    var postData = {
      'action': 'couverts_available_times',
      'date'  : rdate,
      'party' : rpersons
    };

    var select$ = form$.find('[name="reservation_time"]');
    select$.prop('disabled', true);

    jQuery.post(couverts_ajax_url,postData, function(response) {

      try {
        var times   = jQuery.parseJSON(response);
        select$.prop('disabled', false);

        var selected = select$.val();

        select$.empty();

        if(times.NoTimesAvailable) {

          var opt = jQuery("<option></option>")
            .attr('value', '')
            .attr('selected', 'selected')
            .prop('disabled', true)
            .text('<?php echo _e('No available timeslots','couverts') ?>');

          select$.append(opt);

        } else {
          jQuery.each(times.Times, function (index, option) {
            var ts = option.Hours + ':' + ('00' + option.Minutes).substr(-2),
              opt = jQuery("<option></option>")
                .attr('value', ts)
                .text(ts);

            if (ts === selected) {
              opt.attr('selected', 'selected');
            }
            select$.append(opt);
          });
        }
      } catch (err) {
        jQuery('.reservation__timeselection').html(response);
      }

    });
  };

  function couverts_change_party_size(form$, curdate)
  {
    if ( couverts_day_config[curdate] ) {
      var selectElement$    = form$.find('[name="reservation_party"]');
      var selectedPartySize = selectElement$.val();
      var labelFormatSingle = '__SIZE__ <?php echo _e('Person','couverts') ?>';
      var labelFormatPlural = '__SIZE__ <?php echo _e('Persons','couverts') ?>';

      selectElement$.empty();

      for ( var size = couverts_day_config[curdate].min; size <= couverts_day_config[curdate].max; size++ ) {
        var label = (size === 1) ? labelFormatSingle : labelFormatPlural;
        var last$ = jQuery("<option></option>")
            .attr("value", size)
            .text(label.replace('__SIZE__', size));

        if ( last$ ) {
          if ( size == selectedPartySize ) {
            last$.attr('selected','selected');
          }
        }
        selectElement$.append(last$);
      }
    }
  };

  jQuery(document).ready(function($) {

    $('.couverts-form').each(function() {
      couverts_get_times($(this));
    })

    $('.couverts-form [data-trigger-times="true"]').on('change', function() {
      couverts_get_times($(this).closest('form'));
    })

    $('.couverts-form [data-trigger-size="true"]').on('change',function() {
      var form$    = $(this).closest('form');
      var selected = $(this).val();

      couverts_change_party_size(form$,selected);
    });

    $('.js-page1-submit').on('click',function(e) {
      var form$    = $(this).closest('form');
      var button$  = $(this);
      var postData = {
        'action': 'couverts_get_contact_form',
        'dt'    : form$.find('[name="reservation_date"]').val(),
        'ts'    : form$.find('[name="reservation_time"]').val()
      };

      if(!postData.ts) {
        return false;
      }

      button$.addClass('btn--loading');

      jQuery.post(couverts_ajax_url,postData, function(response) {
        form$.find('.js-contact-fields').html(response);
        $('.reservation__timeselection').addClass('hidden-xs-up');
        $('.reservation__contactinfo').removeClass('hidden-xs-up');
        button$.removeClass('btn--loading');
      });

      e.preventDefault();
    });

    $('.js-page2-back').on('click',function(e) {
      $('.reservation__contactinfo').addClass('hidden-xs-up');
      $('.reservation__timeselection').removeClass('hidden-xs-up');
      e.preventDefault();
    });

    $('.js-page2-submit').on('click',function(e) {
      var form$    = $(this).closest('form');
      var button$  = form$.find('.reservation__contactinfo .js-page2-submit');
      var postData = form$.serialize();

      e.preventDefault();

      button$.addClass('btn--loading');
      jQuery.post(couverts_ajax_url,postData, function(response) {
        button$.removeClass('btn--loading');
        var content = jQuery.parseJSON(response);

        if ( content.response.status === 'ok' ) {
          form$.find('.reservation__confirmation p').html(content.response.message);
          $('.reservation__contactinfo').addClass('hidden-xs-up');
          $('.reservation__confirmation').removeClass('hidden-xs-up');

        }

      });

    });
  });
</script>