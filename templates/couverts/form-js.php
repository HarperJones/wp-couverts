<script type="text/javascript">
  var couverts_ajax_url = '<?php echo admin_url('admin-ajax.php') ?>';

  function couverts_get_times( form$ )
  {
    var rdate    = form$.find('[name="reservation_date"]').val(),
        rpersons = form$.find('[name="reservation_party"]').val();

    var postData = {
      'action': 'couverts_available_times',
      'date'  : rdate,
      'party' : rpersons
    };

    jQuery.post(couverts_ajax_url,postData, function(response) {
      var times   = jQuery.parseJSON(response);
      var select$ = form$.find('[name="reservation_time"]');

      var selected = select$.val();

      select$.empty();

      jQuery.each(times.Times, function(index,option) {
        var ts  = option.Hours + ':' + ('00' + option.Minutes).substr(-2),
            opt = jQuery("<option></option>")
              .attr('value', ts)
              .text(ts);

        if ( ts === selected ) {
          opt.attr('selected','');
        }
        select$.append(opt);
      })
    });
  }

  jQuery(document).ready(function($) {

    $('.couverts-form').each(function() {
      couverts_get_times($(this));
    })

    $('.couverts-form select.js-trigger-reload').on('change', function() {
      couverts_get_times($(this).closest('form'));
    })

    // @todo: when click on button in reservation__timeselection formgroup, show reservation__contactinfo
    $('.js-page1-submit').on('click',function(e) {
      var form$    = $(this).closest('form');
      var postData = {
        'action': 'couverts_get_contact_form',
        'dt'    : form$.find('[name="reservation_date"]').val(),
        'ts'    : form$.find('[name="reservation_time"]').val()
      };

      $(this).addClass('btn--loading');

      jQuery.post(couverts_ajax_url,postData, function(response) {
        form$.find('.js-contact-fields').html(response);
        $('.reservation__timeselection').addClass('hidden-xs-up');
        $('.reservation__contactinfo').removeClass('hidden-xs-up');
        $(this).removeClass('btn--loading');
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
          postData = form$.serialize();

      e.preventDefault();

      $(this).addClass('btn--loading');
      jQuery.post(couverts_ajax_url,postData, function(response) {
        console.log(response);
        $(this).removeClass('btn--loading');
        var content = jQuery.parseJSON(response);

        if ( content.response.status === 'ok' ) {
          form$.find('.reservation__confirmation p').html(content.response.message);
          $('.reservation__contactinfo').addClass('hidden-xs-up');
          $('.reservation__confirmation').removeClass('hidden-xs-up');

          var tab$ = form$.find('.reservation__confirmation');
        }

      });

    });
    // @todo: when click of submit, do ajax submit (postdata.action = 'couvert_handle_reservation') and handle feedback
  });
</script>