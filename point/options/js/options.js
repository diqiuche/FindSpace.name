var changeWarn = false;
jQuery(document).ready(function(){
	if(jQuery('#last_tab').val() == ''){
        jQuery('.nhp-opts-group-tab:first').slideDown('fast');
        jQuery('#nhp-opts-group-menu li:first').addClass('active');
    }else{
        tabid = jQuery('#last_tab').val();
        jQuery('#'+tabid+'_section_group').slideDown('fast');
        jQuery('#'+tabid+'_section_group_li').addClass('active');
        if (jQuery('#'+tabid+'_section_group_li').closest('#nhp-opts-homepage-accordion').length)
            jQuery('#nhp-opts-homepage-accordion').show();
        if (jQuery('#'+tabid+'_section_group_li').closest('#nhp-opts-blog-accordion').length)
            jQuery('#nhp-opts-blog-accordion').show();
    }        
    
    jQuery('.nhp-opts-group-tab-link-a').click(function(){
        if (this.id == 'accordion_section_group_li_a') {
           jQuery('#nhp-opts-homepage-accordion').slideToggle();
           return false;
        }
        if (this.id == 'accordion_section_group_li_a_2') {
           jQuery('#nhp-opts-blog-accordion').slideToggle();
           return false;
        }
        var $this = jQuery(this);
        if ($this.parent().hasClass('active'))
            return false;
        var relid = jQuery(this).attr('data-rel');
        
        jQuery('#last_tab').val(relid);
        
        jQuery('.nhp-opts-group-tab').hide();
        jQuery('#'+relid+'_section_group').fadeIn(400);
		
        jQuery('.nhp-opts-group-tab-link-li').removeClass('active');
        $this.parent().addClass('active');

        jQuery("html, body").animate({ scrollTop: jQuery('#nhp-opts-header').offset().top - 48 }, 500);
        return false;        
	});
	
	if(jQuery('#nhp-opts-save').is(':visible')){
		jQuery('#nhp-opts-save').delay(4000).slideUp('slow');
	}
	
	if(jQuery('#nhp-opts-imported').is(':visible')){
		jQuery('#nhp-opts-imported').delay(4000).slideUp('slow');
	}
    
    jQuery('#nhp-opts-footer').find('#savechanges').click(function(e) {
        // add typography data via hidden fields before submitting
        if (typography_isloaded) {
            addTypographyData(jQuery('#google_typography'),jQuery('#nhp-opts-form-wrapper'));
        }
        // AJAX save
        /*
        jQuery('#savechanges').prop('disabled', true).after('<div class="spinner" id="ajax-saving"></div>');
        changeWarn = false;
        var $form = jQuery('#nhp-opts-form-wrapper');
        jQuery.post( $form.attr('action'), $form.serialize() ).done(function() {
            jQuery('#ajax-saving').remove();
            jQuery('#savechanges').prop('disabled', false).after('<div id="ajax-saved">Settings saved!</div>');
            setTimeout(function() { jQuery('#ajax-saved').fadeOut('slow', function() { jQuery('#ajax-saved').remove(); }); }, 2000);
        });
        return false;
        */
        // AJAX save end
    });

	jQuery('input, textarea, select').not('.mts_translate_textarea, #translate_search').change(function(){
        if (!changeWarn) {
          //jQuery('#nhp-opts-save-warn').slideDown('slow');
          changeWarn = true;
        }
	});

    jQuery('#nhp-opts-form-wrapper').submit(function() {
        changeWarn = false;
    });
	
	jQuery('#nhp-opts-import-code-button').click(function(e){
        e.preventDefault();
		jQuery('#nhp-opts-import-code-wrapper').toggle().find('#import-code-value').val('');
	});

	jQuery('#nhp-opts-export-code-copy').click(function(e){
        e.preventDefault();
		jQuery('#nhp-opts-export-code').toggle().select();
	});
    
    // Presets
    jQuery('#presets .preset').each(function() {
        var $this = jQuery(this);
        $this.find('.activate-button').click(function(e) {
            e.preventDefault();
            jQuery('#import-code-value').val($this.find('.preset-data').val());
            jQuery('#nhp-opts-import').click();
        });
    });
    
    // Disallow submission by enter key
    jQuery('#nhp-opts-form-wrapper').find('input').keydown(function(event){
        if ( event.keyCode == 13 ){
            event.preventDefault();
        }
    });
    
    // Floating footer
    var $footer = jQuery('#nhp-opts-footer');
    var $bottom = jQuery('#nhp-opts-bottom');
    
    
    // Needs JS sizing when position:fixed
    var footer_padding = $footer.innerWidth() - $footer.width();
    function resizeFloatingElements() {
        var w = jQuery('#nhp-opts-form-wrapper').width();
        $footer.width(w - footer_padding);
    }
    resizeFloatingElements();
    
    var resizeTimer;
    jQuery(window).resize(function() {
        clearTimeout(resizeTimer);
        resizeTimer = setTimeout(resizeFloatingElements, 100);
    });
    
    // theme translation
    var $translations_container = jQuery('.translate-strings');
    var $translations_toggle = jQuery('#nhp-opts-translate');
    function loadTranslations(page, search) {
        var page = page || 1; // default 1
        $translations_container.empty();
        jQuery.ajax({
			url: ajaxurl, 
            method: 'post',
			data: {  'action' : 'mts_translation_panel', 'page' : page, 'search' : search },
			beforeSend: function() {
				//container.find(".loading").show();
                $translations_container.addClass('loading');
			},
			success: function(data) {
                translation_isloaded = true
				$translations_container.removeClass('loading').html(data);
			}
		});
        jQuery('#translate_search_wrapper').show();
    }
    function saveTranslation(original_string, new_string, elem) {
        var wrapper = elem.closest('.translate-string-wrapper');
        jQuery.ajax({
			url: ajaxurl, 
            method: 'post',
			data: {  'action' : 'mts_save_translation', 'id' : original_string, 'val' : new_string },
			beforeSend: function() {
				elem.prop('disabled', true);
                wrapper.addClass('loading');
			},
			success: function(data) {
                elem.prop('disabled', false);
                wrapper.removeClass('loading');
                if (data == '1') {
                    wrapper.addClass('success');
                    setTimeout(function() {
                        wrapper.addClass('animate').removeClass('success');
                    }, 10);
                    setTimeout(function() {
                        wrapper.removeClass('animate');
                    }, 3000);
                    
                    // update number and %
                    var $info = jQuery('.translation_info');
                    var total = parseInt($info.find('.total').text()),
                        translated = parseInt($info.find('.translated').text());
                    
                    if (new_string == '' && elem.data('translated')) {
                        translated--;
                        $info.find('.translated').text(translated);
                    } else if (new_string != '' && ! elem.data('translated')) {
                        translated++;
                        $info.find('.translated').text(translated);
                    }
                    
                    var percent = translated / total * 100;
                    $info.find('.percent').text('('+percent.toFixed(2)+'%)');
                } else {
                    wrapper.addClass('fail');
                    setTimeout(function() {
                        wrapper.addClass('animate').removeClass('fail');
                    }, 10);
                    setTimeout(function() {
                        wrapper.removeClass('animate');
                    }, 3000);
                }
			}
		});
    }
    var translation_isloaded = false;
    // load on document.ready if needed
    if (! $translations_toggle.is(':checked')) {
        jQuery('#nhp-opts-reset-translations').prop('disabled', true);
    }
    if (jQuery('#last_tab').val() == 'translation_default' && $translations_toggle.is(':checked')) {
		loadTranslations();
    }
    jQuery('#translation_default_section_group_li a').click(function() {
        // we clicked on translations tab
        // load if it's enabled
        if (!translation_isloaded && $translations_toggle.is(':checked')) {
            loadTranslations();
        }
    });
    $translations_toggle.change(function() {
        if (jQuery(this).is(':checked')) {
            if (!translation_isloaded) {
                loadTranslations();
            } else {
                $translations_container.show();
                jQuery('#translate_search_wrapper').show();
            }
            jQuery('#nhp-opts-reset-translations').prop('disabled', false);
        } else {
            $translations_container.hide();
            jQuery('#translate_search_wrapper').hide();
            jQuery('#nhp-opts-reset-translations').prop('disabled', true);
        }
    });
    
    // translate panel v2: instant saving & pagination
    $translations_container.on('change', '.mts_translate_textarea', function(e) {
        $this = jQuery(e.target);
        saveTranslation($this.data('id'), $this.val(), $this);
    }).on('focus', '.mts_translate_textarea', function(e) {
        $this = jQuery(e.target);
        if ($this.val() == '') {
            $this.data('translated', 0);
        } else {
            $this.data('translated', 1);
        }
    }).on('click', '.mts_translation_pagination a', function(e) {
        e.preventDefault();
        $this = jQuery(e.target);
        if (!$this.hasClass('current')) {
            loadTranslations($this.text(), jQuery('#translate_search').val());
        }
    });
    
    jQuery('#translate_search').on('input propertychange', function() {
        var query = jQuery(this).val();
        //if (query.length > 2) { 
            fnDelay(function() {
                loadTranslations(1, query);
            }, 600);
        //}
    });
    
});

jQuery.fn.addHiddenField = function(name, value) {
    this.each(function () {
        var elem_id = name.replace(/\W/g, '-').replace(/--+/g, '-').replace(/(^-|-$)/, '');
        if (jQuery('#'+elem_id).length) {
            // elem exists, change value
            jQuery('#'+elem_id).val(value);
        } else {
            // elem doesn't exist, create
            var input = jQuery("<input>").attr("type", "hidden").attr("id", elem_id).attr("name", name).val(value);
            jQuery(this).append(jQuery(input));
        }
        
    });
    return this;
};

var fnDelay = (function(){
  var timer = 0;
  return function(callback, ms){
    clearTimeout (timer);
    timer = setTimeout(callback, ms);
  };
})();