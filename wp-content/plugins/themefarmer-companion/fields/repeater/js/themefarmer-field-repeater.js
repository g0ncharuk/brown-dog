wp.customize.controlConstructor['themefarmer-repeater'] = wp.customize.Control.extend({

    // When we're finished loading continue processing
    ready: function() {

        'use strict';

        var control = this;
        control.initThemeFarmerControl();
    },

    initThemeFarmerControl: function() {

        'use strict';

        var control = this;

        // Set the sortable container.
        control.sortableContainer = control.container.find('ul.themefarmer-repeater').first();
        // Init sortable.
        control.sortableContainer.sortable({
            axis: 'y',
            items: '> li',
            stop: function() {
                control.updateValue();
            }
        }).click(function() {

            // Update value on click.
            control.updateValue();
        });
        var container = control.container;
        container.on('keyup', '.themefarmer-repeater-field, .themefarmer-repeater-repeater-field', function(event) {
            jQuery(this).trigger('change');
        });

        container.on('change', '.themefarmer-repeater-field, .themefarmer-repeater-repeater-field', function(event) {
            var selector = jQuery(this).parents('.themefarmer-repeater');
            control.updateValue();
        });

        container.on('click', '.themefarmer-repeater-add-new', function(event) {
            var repeater = jQuery(this).siblings('.themefarmer-repeater');
            var index = repeater.children('.themefarmer-repeater-item').length;
            console.log(index);
            if(index >= control.params.max_items){
                jQuery(this).before('<div class="tf-error-message">Max '+control.params.row_label+' Limit is '+control.params.max_items+', Please Upgrade to pro to add more '+control.params.row_label+'<br> <a href="'+control.params.pro_link+'" target="_blank" class="button button-primary themefarmer-upsale-bt" id="themefarmer-pro-button">Get The PRO Version! </a></div>');
                return;
            }
            var repeater_item = jQuery(this).siblings('.themefarmer-repeater-copy').children('.themefarmer-repeater-item-copy').clone();
            repeater_item.removeClass('themefarmer-repeater-item-copy').addClass('themefarmer-repeater-item');
            repeater_item.find('.index').text(index + 1);
            repeater.append(repeater_item);
            var selector = jQuery(this).siblings('.themefarmer-repeater');
            control.updateValue();
        });

        container.on('click', '.themefarmer-repeater-add-repeater', function(event) {
            var r_repeater = jQuery(this).siblings('.themefarmer-repeater-repeater-group');
            var r_repeater_item = jQuery(this).parents('.themefarmer-repeater-repeater').siblings('.themefarmer-repeater-repeater-copy').children('.themefarmer-repeater-repeater-group-copy').clone();
            r_repeater_item.removeClass('themefarmer-repeater-repeater-group-copy').addClass('themefarmer-repeater-repeater-group');
            jQuery(this).before(r_repeater_item);
            var selector = jQuery(this).parents('.themefarmer-repeater');
            control.updateValue();
        });

        container.on('click', '.themefarmer-repeater-remove-item', function(event) {
            var selector = jQuery(this).parents('.themefarmer-repeater');
            jQuery(this).parents('.themefarmer-repeater-item').remove();
            control.updateValue();
        });

        container.on('click', '.themefarmer-repeater-remove-repeater', function(event) {
            var selector = jQuery(this).parents('.themefarmer-repeater');
            jQuery(this).parents('.themefarmer-repeater-repeater-group').remove();
            control.updateValue();
        });
    },

    /**
     * Updates the sorting list
     */
    updateValue: function() {

        'use strict';

        var control = this,
            data = [];

        this.sortableContainer.find('li').each(function(index, obj) {
            var repeater_item = {};
            jQuery(this).children().find('span.index').text(index + 1);

            jQuery(this).find('.themefarmer-repeater-field').each(function(i, iobj) {
                var tf_index = jQuery(this).data('tf-index');
                var tf_value = jQuery(this).val();
                repeater_item[tf_index] = tf_value;
            });

            var repeater_repeater_item = [];
            jQuery(this).children().find('.themefarmer-repeater-repeater-group').each(function(j, jobj) {
                // var rr_index = jQuery(this).data('tf-index');
                var r_r_data = {};
                jQuery(this).find('.themefarmer-repeater-repeater-field').each(function(k, kobj) {
                    var r_tf_index = jQuery(this).data('tf-index');
                    var r_tf_value = jQuery(this).val();
                    var r_r_e_data = {};
                    r_r_data[r_tf_index] = r_tf_value;
                });
                repeater_repeater_item.push(r_r_data);
            });
            var rr_index = jQuery(this).children().find('.themefarmer-repeater-repeater').data('tf-index');
            if(rr_index){
                repeater_item[rr_index] = repeater_repeater_item;
            }
            data.push(repeater_item);
        });
        control.setting.set(data);
    }
});

jQuery(document).ready(function($) {
    'use strict';
    jQuery('.tf-color-field').wpColorPicker();

    var button_class = '.cimage-select-button';
    jQuery(document).on('click', button_class, function(e) {
        var url_selector = jQuery(this).siblings('.image-select-field');
        var wireframe = null;
        if (wireframe) {
            wireframe.open();
            return;
        }

        
        wireframe =  wp.media.frames.wireframe = wp.media({
            title: 'Select Image',
            button: {
                text: 'Select Image'
            },
            library: {
                type: 'image'
            },
            multiple: false,
        });
        wireframe.on('select', function() {
            var attachment = wireframe.state().get('selection').first().toJSON();
            url_selector.val(attachment.url);
            url_selector.trigger('change');
        });
        wireframe.open();
    });
    
    jQuery(document).on('click', '.repeater-head', function(event) {
        var r_contaier = jQuery(this).parent();
        if (r_contaier.hasClass('repeater-expanded')) {
            r_contaier.removeClass('repeater-expanded');
            r_contaier.children('.repeater-body').hide('fast');

        } else {
            r_contaier.addClass('repeater-expanded');
            r_contaier.children('.repeater-body').show('fast');
        }
    });


});


function media_upload(button_class) {
    'use strict';
    jQuery('body').on('click', button_class, function() {
        var button_id = '#' + jQuery(this).attr('id');
        var display_field = jQuery(this).parent().children('input:text');
        var _custom_media = true;

        wp.media.editor.send.attachment = function(props, attachment) {

            if (_custom_media) {
                if (typeof display_field !== 'undefined') {
                    switch (props.size) {
                        case 'full':
                            display_field.val(attachment.sizes.full.url);
                            display_field.trigger('change');
                            break;
                        case 'medium':
                            display_field.val(attachment.sizes.medium.url);
                            display_field.trigger('change');
                            break;
                        case 'thumbnail':
                            display_field.val(attachment.sizes.thumbnail.url);
                            display_field.trigger('change');
                            break;
                        default:
                            display_field.val(attachment.url);
                            display_field.trigger('change');
                    }
                }
                _custom_media = false;
            } else {
                return wp.media.editor.send.attachment(button_id, [props, attachment]);
            }
        };
        wp.media.editor.open(button_class);
        window.send_to_editor = function(html) {

        };
        return false;
    });
}