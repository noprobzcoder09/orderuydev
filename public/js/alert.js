var Alert = function() {

    function confirm(title, message, yesButton, noButton, success_callback,no_callback, closing_callback, closed_callback) {
        
        if (closed_callback == undefined) {
            closed_callback = function(instance, toast, closeBy) {

            }
        }
        if (closing_callback == undefined) {
            closing_callback = function(instance, toast, closeBy) {

            }
        }

        iziToast.question({
            timeout: 0,
            close: true,
            overlay: true,
            toastOnce: true,
            id: 'question',
            zindex: 99999,
            title: title,
            message: message,
            position: 'center',
            theme: '',
            buttons: [
                ['<button>'+yesButton+'</button>', function (instance, toast) {
                    success_callback(instance, toast);
                    instance.hide(toast, { transitionOut: 'fadeOut' }, 'button');

                }, true],
                ['<button>'+noButton+'</button>', function (instance, toast) {
                    no_callback(instance, toast);
                    instance.hide(toast, { transitionOut: 'fadeOut' }, 'button');

                }]
            ],
            onClosing: closing_callback,
            onClosed: closed_callback
        });
    }

    function fixed(title, message, timeout, position, icon, buttons, close_callback) {

        if (closed_callback == undefined) {
            closed_callback = function(instance, toast, closeBy) {
                
            }
        }
        if (closing_callback == undefined) {
            closing_callback = function(instance, toast, closeBy) {
                
            }
        }

        if ($('.knocker_alert_fixed').hasClass('iziToast-opened')) return;
        iziToast.show({
            class: 'knocker_alert_fixed',
            close: false,
            closeOnEscape: false,
            timeout: timeout,
            theme: 'dark',
            drag: false,
            icon: icon,
            title: title,
            message: message,
            position: position, // bottomRight, bottomLeft, topRight, topLeft, topCenter, bottomCenter
            progressBarColor: 'rgb(0, 255, 184)',
            buttons: buttons,
            onOpening: function(instance, toast){
                console.info('callback abriu!');
            },
            onClosing: close_callback
        });
    }

    function show(title, message, timeout, position, icon, buttons, close_callback) {

        if (closed_callback == undefined) {
            closed_callback = function(instance, toast, closeBy) {
                
            }
        }
        if (closing_callback == undefined) {
            closing_callback = function(instance, toast, closeBy) {
                
            }
        }

        if ($('.knocker_alert_fixed').hasClass('iziToast-opened')) return;
        iziToast.show({
            class: '',
            close: true,
            closeOnEscape: true,
            timeout: timeout,
            theme: 'dark',
            drag: true,
            icon: icon,
            balloon: true,
            toastOnce: true,
            title: title,
            message: message,
            position: position, // bottomRight, bottomLeft, topRight, topLeft, topCenter, bottomCenter
            progressBarColor: 'rgb(0, 255, 184)',
            buttons: buttons,
            onOpening: function(instance, toast){
                console.info('callback abriu!');
            },
            onClosing: close_callback
        });
    }

    function info(title, message) {
        iziToast.show({
            timeout: 0,
            title: title,
            message: message,
            position: 'topRight'
        });
    }

    function success(title, message) {
        iziToast.success({
            title: title,
            message: message,
            position: 'topRight'
        });
    }

    function error(title, message, position) {
        var position = position == undefined ? 'topRight' : position;
        iziToast.error({
            title: title,
            message: message,
            position: position,
            // icon: 'icon-power_settings_new',
            headerColor: '#BD5B5B',
        });
    }

    return {
        confirm: function(title, message, yesButton, noButton, success_callback,no_callback, closing_callback, closed_callback) {
            confirm(title, message, yesButton, noButton, success_callback,no_callback, closing_callback, closed_callback);
        },
        fixed: function(title, message, timeout, position, icon, buttons, close_callback) {
            fixed(title, message, timeout, position, icon, buttons, close_callback)
        },
        show: function(title, message) {
            show(title, message, timeout, position, icon, buttons, close_callback)
        },
        info: function(title, message) {
            info(title, message)
        },
        success: function(title, message) {
            success(title, message)
        },
        error: function(title, message, position) {
            error(title, message, position)
        },
    }
}();