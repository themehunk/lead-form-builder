
function openTab(evt, tabName) {
  var i, tabcontent, tablinks;
  tabcontent = document.getElementsByClassName("tabcontent");
  for (i = 0; i < tabcontent.length; i++) {
    tabcontent[i].style.display = "none";
  }
  tablinks = document.getElementsByClassName("tablinks");
  for (i = 0; i < tablinks.length; i++) {
    tablinks[i].className = tablinks[i].className.replace(" active", "");
  }
  document.getElementById(tabName).style.display = "block";
  evt.currentTarget.className += " active";
}


(function($){

    THoptionAdmin = {
        init: function(){
            this.rPlugins = ['hunk-companion', 'one-click-demo-import', 'woocommerce'];
            this._bind();
            this._noticeRemove();
            this._importActiveBtn();
            this._initFilter();
        },

        _noticeRemove: function(){
           jQuery( "div" ).remove(".notice");
        },

        _loaderActive: function($class, $message) {
            $message = $message || "Installing";
            $class.addClass('updating-message').html($message);
            $class.removeClass( 'button-primary' ).addClass( 'disabled' );
        },

        _pluginexists: function(slug) {
            if(this.rPlugins.indexOf(slug) !== -1){
                THoptionAdmin._importActiveBtn();
            }
        },

        _importActiveBtn: function() {
            var rPlugins = this.rPlugins;
            var i = 1;
            rPlugins.forEach(function(element) {
                var activeCheck = document.querySelector('.' + element + '.disabled');
                if(activeCheck) {
                    i++;
                    if(i > 3){
                        var importdemo = jQuery('.importdemo');
                        importdemo.removeClass('disabled').attr('href', THAdmin.oneClickDemo);
                        importdemo.addClass('ztabtn ');
                    }
                }
            });
        },

        /* ── Filter Dropdown ── */
        _initFilter: function() {
            $(document).on('change', '#th-plugin-filter', function() {
                var filter = $(this).val();
                var $grid  = $('#th-plugin-grid');

                if ( typeof thMarket === 'undefined' ) return;

                $grid.html('<div class="th-filter-loading"><span class="th-filter-spinner"></span>' + thMarket.loading + '</div>');

                $.ajax({
                    url  : bajax.ajaxurl,
                    type : 'POST',
                    data : {
                        action       : 'th_filter_plugins',
                        requestNonce : bajax.requestNonce,
                        filter       : filter
                    }
                }).done(function(response) {
                    if (response.success) {
                        $grid.html(response.data.html);
                    } else {
                        $grid.html('<p class="th-no-plugins">' + thMarket.error + '</p>');
                    }
                }).fail(function() {
                    $grid.html('<p class="th-no-plugins">' + thMarket.error + '</p>');
                });
            });
        },

        _installNow: function( event ) {
            var $document = jQuery(document);
            var slug = $(this).data('slug');
            var $message = $( '.install-now.' + slug);

            if ( wp.updates.shouldRequestFilesystemCredentials && ! wp.updates.ajaxLocked ) {
                wp.updates.requestFilesystemCredentials( event );
                $document.on( 'credential-modal-cancel', function() {
                    var $message = $( '.install-now' );
                    $message.text( wp.updates.l10n.installNow );
                    wp.a11y.speak( wp.updates.l10n.updateCancel, 'polite' );
                });
            }
            wp.updates.installPlugin({
                slug: $message.data('slug'),
                init: $message.data('init'),
            });
        },

        _installError: function( event, response ){
            var $card = jQuery( '.install-now');
            $card.removeClass( 'button-primary' )
                .addClass( 'disabled' )
                .html( wp.updates.l10n.installFailedShort );
            console.log(response.errorMessage);
        },

        _pluginInstalling: function(event, args){
            event.preventDefault();
            var $card = jQuery( '.' + args.slug);
            var $button = $card.find( '.button-primary' );
            $button.removeClass( 'install-now button-primary installed button-disabled updated-message' );
            $card.addClass('updating-message').html('Installing Plugin');
            $button.addClass('already-started');
        },

        _activetedPlugin: function(event, args){
            event.preventDefault();
            var $card = jQuery( '.' + args.slug);
            THoptionAdmin._activePluginHomepage(args.slug, $card.data('init'));
        },

        _activePluginHomepage: function($slug, $init){
            var $message = jQuery( '.' + $slug);
            var $setting_class = jQuery( '.setting-' + $slug);

            $message.removeClass( 'install-now button-primary installed button-disabled updated-message' )
                .addClass('updating-message')
                .html($message.data('msg'));

            $.ajax({
                url  : bajax.ajaxurl,
                type : 'POST',
                data : {
                    action       : 'th_activeplugin',
                    requestNonce : bajax.requestNonce,
                    init         : $init,
                    slug         : $slug
                }
            }).done(function(response){
                if (response.success) {
                    $message.removeClass('button-primary updating-message')
                        .addClass('disabled')
                        .html('Activated');
                    $setting_class.show();
                    THoptionAdmin._pluginexists($slug);
                } else {
                    $message.removeClass('updating-message');
                }
            });
        },

        _activePlugin: function(event){
            var $button = jQuery( event.target ),
                $init   = $button.data('init'),
                $slug   = $button.data('slug');
            THoptionAdmin._activePluginHomepage($slug, $init);
        },

        _bind: function(){
            $( document ).on('click',                      '.install-now',           THoptionAdmin._installNow);
            $( document ).on('click',                      '.activate-now',          THoptionAdmin._activePlugin);
            $( document ).on('wp-plugin-install-error',                              THoptionAdmin._installError);
            $( document ).on('wp-plugin-installing',                                 THoptionAdmin._pluginInstalling);
            $( document ).on('wp-plugin-install-success',                            THoptionAdmin._activetedPlugin);
        },
    };

    THoptionAdmin.init();

    /* TABS */
    function _plugins_tabs() {
        $('.tabs-list a').click(function(){
            $('.panel').hide();
            $('.tabs-list a.active').removeClass('active');
            $(this).addClass('active');
            var panel = $(this).attr('href');
            $(panel).fadeIn();
            return false;
        });
        $('.tabs-list a:first').click();
    }

    $(document).ready(function() {
        _plugins_tabs();
    });

})(jQuery);
