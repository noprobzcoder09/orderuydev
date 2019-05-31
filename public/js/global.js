// change current Date to a Given Format
function dateFormat(input_string,format){
    var current = new Date(input_string);
    return current.format(format);
}
// check if a input is in a data of array
function inArray(input,data){
    var status = false;
    for(var i=0;i<data.length;i++){
        if (input == data[i]){
            status = true;
            break;
        }
    }
    return status;
}

function isFormValid(form_name,hasErrorMsg){
    
    var isValid = true;
    var hasErrorMsg = hasErrorMsg == undefined ? true : hasErrorMsg;
    var hasErrorBoarder = true;
    //text
    $(form_name+' input:text').each(function() {
        if (hasErrorBoarder) {
            $(this).removeAttr('style');
            hideHelpBlock($(this));
        }
        if (!$(this).hasClass('not-required')){
            if ($(this).hasClass('form-control') && $(this).val() == '')  {
                if (hasErrorBoarder) {
                    $(this).attr('style','border: 1px solid #e26a6a');
                    if (hasErrorMsg) addHelpBlockError($(this));
                }
                isValid = false;
            }
        }
        if ($(this).hasClass('form-control') && ($(this).attr('data-minlength') != '' && $(this).attr('data-minlength') != undefined)) {
            if ($(this).val().length < $(this).attr('data-minlength')) {
                 if (hasErrorBoarder) {
                    if (hasErrorMsg) addHelpBlockError($(this),'','Minimum at least 3 character.');
                    $(this).attr('style','border: 1px solid #e26a6a');
                }
                isValid = false;
            } else {
                hideHelpBlock($(this));
            }
        }

        if ( $(this).hasClass('form-control') && ($(this).attr('data-creditcard') == 'true' ) ) {
            if ($(this).val().length != 16) {
                 if (hasErrorBoarder) {
                    if (hasErrorMsg) addHelpBlockError($(this),'','Invalid card number.');
                    $(this).attr('style','border: 1px solid #e26a6a');
                }
                isValid = false;
            }

            var reg = new RegExp('^[0-9]+$');
            if ( !reg.test($(this).val()) ) {
                if (hasErrorMsg) addHelpBlockError($(this),'','Invalid card number.');
                $(this).attr('style','border: 1px solid #e26a6a');
                isValid = false;
            }

            if (isValid) {
                hideHelpBlock($(this));
            }
        }

        if ( $(this).hasClass('form-control') && ($(this).attr('data-creditcardseccode') == 'true' ) ) {
            if ($(this).val().length != 3) {
                 if (hasErrorBoarder) {
                    if (hasErrorMsg) addHelpBlockError($(this),'','Invalid security code.');
                    $(this).attr('style','border: 1px solid #e26a6a');
                }
                isValid = false;
            }

            var reg = new RegExp('^[0-9]+$');
            if ( !reg.test($(this).val()) ) {
                if (hasErrorMsg) addHelpBlockError($(this),'','Invalid security code.');
                $(this).attr('style','border: 1px solid #e26a6a');
                isValid = false;
            }

            if (isValid) {
                hideHelpBlock($(this));
            }
        }

        if ($(this).hasClass('form-control') && ($(this).attr('data-minage') != '' && $(this).attr('data-minage') != undefined)) {
            if (isNaN(parseFloat(getAge($(this).val()))) || (parseFloat(getAge($(this).val())) < $(this).attr('data-minage'))) {
                 if (hasErrorBoarder) {
                    if (hasErrorMsg) addHelpBlockError($(this),'','Minimum of age atleast '+$(this).attr('data-minage')+' years old.');
                }
                isValid = false;
            } else {
                hideHelpBlock($(this));
            }
        }

        if ($(this).hasClass('form-control') && ($(this).attr('data-minlength') != '' && $(this).attr('data-minlength') != undefined)) {
            if ($(this).val().length < $(this).attr('data-minlength')) {
                 if (hasErrorBoarder) {
                    addHelpBlockError($(this),'','Minimum at least '+$(this).attr('data-minlength')+' character.');
                    // $(this).attr('style','border: 1px solid red');
                }
                isValid = false;                
            } else {
                hideHelpBlock($(this));
            }
        }
    });
    $(form_name+' input[type="email"]').each(function() {
        if (hasErrorBoarder) {
            $(this).removeAttr('style');
            hideHelpBlock($(this));
        }
        if (!$(this).hasClass('not-required')) {
            if (($(this).val() == '' || $(this).val().split('@').length <= 1)) {
                if (hasErrorBoarder) {
                    $(this).attr('style','border: 1px solid #e26a6a');
                    if (hasErrorMsg) addHelpBlockError($(this),'','Invalid Email address.');
                }
                isValid = false;
            }
        } else {
            if ($(this).val() != '') {
                if ($(this).val().split('@').length <= 1) {
                    if (hasErrorBoarder) {
                        $(this).attr('style','border: 1px solid #e26a6a');
                        if (hasErrorMsg) addHelpBlockError($(this),'','Invalid Email address.');
                    }
                    isValid = false;
                }
            }
        }
    });

    $(form_name+' input[type="file"]').each(function() {
        if (hasErrorBoarder) {
            $(this).removeAttr('style');
            hideHelpBlock($(this));
        }
        if (!$(this).hasClass('not-required')) {
            if ($(this).val() == '') {
                if (hasErrorBoarder) {
                    $(this).attr('style','border: 1px solid #e26a6a');
                    if (hasErrorMsg) addHelpBlockError($(this));
                }
                isValid = false;
            }
        } 
    });

    //Textarea
    $(form_name+' textarea').each(function() {
        if (hasErrorBoarder) {
            $(this).removeAttr('style');
            hideHelpBlock($(this));
        }
        if (!$(this).hasClass('not-required') && ( $(this).hasClass('form-control') && $(this).val() == '') ) {
            if (hasErrorBoarder) {
                $(this).attr('style','border: 1px solid #e26a6a');
                if (hasErrorMsg) addHelpBlockError($(this));
            }
            isValid = false;
        }
        $(this).attr('style',$(this).attr('style')+';min-height: 100px !important');
    });
    //Select
    $(form_name+' select').each(function() {
        if (hasErrorBoarder) {
            $(this).removeAttr('style');
            hideHelpBlock($(this));
        }
        $(form_name+ ' '+' #s2id_'+$(this).attr('name')).removeAttr('style');
        var value = $(this).find('option:selected').val() == undefined ?  '' : $(this).find('option:selected').val();
        if (!$(this).hasClass('not-required') && value.trim() == '') {
            if ($(this).hasClass('select2')) {
                if (hasErrorBoarder) {
                    if (hasErrorMsg) addHelpBlockError($(this));
                    $(form_name+' #s2id_'+$(this).attr('name')).attr('style','border: 1px solid #e26a6a !important');
                }                   
            }
            if (hasErrorBoarder) {
                $(this).attr('style','border: 1px solid #e26a6a');
                if (hasErrorMsg) addHelpBlockError($(this));
            }
            isValid = false;
        }

        $(form_name+ ' '+' #s2id_'+$(this).attr('name')).attr('style',$(this).attr('style')+' '+';width: 100% !important');
    });

    //password
    $(form_name+' input:password').each(function() {
        if (hasErrorBoarder) {
            $(this).removeAttr('style');
            hideHelpBlock($(this));
        }
        if (!$(this).hasClass('not-required') && ( $(this).hasClass('form-control') && $(this).val() == '') ) {
            if (hasErrorBoarder) {
                $(this).attr('style','border: 1px solid #e26a6a');
                if (hasErrorMsg) addHelpBlockError($(this));
            }
            isValid = false;
        } else {
            if ($(this).hasClass('form-control') && ($(this).attr('data-minlength') != '' && $(this).attr('data-minlength') != undefined)) {
                if ($(this).val().length < $(this).attr('data-minlength')) {
                     if (hasErrorBoarder) {
                        addHelpBlockError($(this),'','Minimum at least '+$(this).attr('data-minlength')+' character.');
                        $(this).attr('style','border: 1px solid red');
                    }
                    isValid = false;
                    console.log($(this).attr('name'));
                }
            }
        }
        
        $(this).attr('style',$(this).attr('style'));
    });

    return isValid;
}

function addHelpBlockError(self,caption,caption2) {
    var caption = caption == undefined || caption == '' ? 'This field is required.' : caption;
    var caption2 = caption2 == undefined ? '' : caption2;
    self.parent('div').find('span.help-block').removeClass('hide');
    if (self.parent('div').find('span').hasClass('help-block')) {
        self.parent('div').find('span.help-block').html(caption+caption2);
    } else {
        self.parent('div').append('<span class="help-block font-red">'+caption+caption2+'</span>');
    }
}

function hideHelpBlock(self) {
    if (self.parent('div').find('span').hasClass('help-block')) {
       self.parent('div').find('span.help-block').addClass('hide');
    }
}

function showModal(data){
    var modal;

    var name = data.name != undefined ? data.name.toLowerCase() : 'large';

    var content = data.content == undefined ? '' : data.content;

    var title = data.title == undefined ? '' : data.title;

    var button =  data.button != undefined ?  data.button : [];

    var icon = button.icon != undefined ? "<i class='"+button.icon+"'></i> " : '';

    var caption = button.caption != undefined ? button.caption : 'Save';

    var hasActionButton = button.hasActionButton != undefined ? button.hasActionButton : true;

    var hasCloseButton = button.hasCloseButton != undefined ? button.hasCloseButton : true;

    var hasFooter = data.hasFooter == undefined ? true : data.hasFooter;

    var scroll = data.scroll == undefined ? false : data.scroll;
    
    if (name == 'large') {
        modal = '#modal_large';
    }
    else if (name == 'large2') {
        modal = '#modal_large2';
    }
    else if (name == 'basic') {
        modal = '#modal_basic';
    }
    else if (name == 'basic2') {
        modal = '#modal_basic2';
    }else{
        modal = '#modal_basic';
    }
    $(modal).find('.modal-body').removeAttr('style');
    if (scroll) {
        $(modal).find('.modal-body').attr('style','overflow: scroll;height: 500px;');
    }
    modal = $(modal);
    modal.modal({
        show: true,
        backdrop: 'static',
        keyboard: false,
    });
    modal.find('.modal-header h4').html(title);
    modal.find('.modal-body').html(content);
    modal.find('.modal-footer .btn_modal').html(icon+caption);
    if (hasFooter )  {
        modal.find('.modal-footer').removeClass('hide');
    } else {
        modal.find('.modal-footer').addClass('hide');
    }
    if (hasActionButton )  {
        modal.find('.modal-footer .btn_modal').removeClass('fade');
    } else {
        modal.find('.modal-footer .btn_modal').addClass('fade');
    }

    if (hasCloseButton )  {
        modal.find('.modal-footer .btn[data-dismiss="modal"]').removeClass('fade');
        modal.find('.modal-header .close').removeClass('fade');
    } else {
        modal.find('.modal-footer .btn[data-dismiss="modal"]').addClass('fade');
        modal.find('.modal-header .close').addClass('fade');
    }
    btn = '<button data-dismiss="modal" class="btn btn-default" type="button">Close</button>';
    if(button.length >= 1){
          for(i=0; i < button.length; ++i){
             //console.log(button[i]);
             var icon = button[i].icon;
             if(icon != '' && icon != undefined ) icon = "<i class='fa "+ icon +"'></i>"
             btn = '<button class="btn '+ button[i].class  +'" type="button">'+ icon + ' ' + button[i].caption  +'</button>' + btn ;
          }
        modal.find('.modal-footer').html(btn);
    }else{
        if(button.class != undefined) {
            modal.find('.modal-footer .btn_modal').addClass(button.class);
        }
    }
    if (data.class != undefined) {
        modal.addClass(data.class);
    }
}


 function closeModal(name){
    var name = name ? name.toLowerCase() : 'large';
    if (name == 'large') {
        modal = '#modal_large';
    }
    else if (name == 'large2') {
        modal = '#modal_large2';
    }
    else if (name == 'basic') {
        modal = '#modal_basic';
    }
    else if (name == 'basic2') {
        modal = '#modal_basic2';
    } else {
        modal = name;
    }
    

    jQuery(modal).modal('hide');
}

function confirmEvent(title, message, _callback) {
    bootbox.confirm({
        title: title,
        message: message,
        buttons: {
            confirm: {
                label: 'Yes',
                className: 'btn-success'
            },
            cancel: {
                label: 'No',
                className: 'btn-danger'
            }
        },
        callback: _callback
    });
}


function format_number(num){
    if (isNaN(num) || num == undefined) return 0;
     num = num.toString().replace(/\$|\,/g, '');
    if (isNaN(num))
    {
        num = "0";
    }

    sign = (num == (num = Math.abs(num)));
    num = Math.floor(num * 100 + 0.50000000001);
    cents = num % 100;
    num = Math.floor(num / 100).toString();

    if (cents < 10)
    {
        cents = "0" + cents;
    }
    for (var i = 0; i < Math.floor((num.length - (1 + i)) / 3); i++)
    {
        num = num.substring(0, num.length - (4 * i + 3)) + ',' + num.substring(num.length - (4 * i + 3));
    }

    return (((sign) ? '' : '-')  + num + '.' + cents);
    
}

function getParameterByName(name) {
    name = name.replace(/[\[]/, "\\\[").replace(/[\]]/, "\\\]");
    var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
        results = regex.exec(location.search);
    return results == null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
}

function clear_value(form_name){
    $(form_name+ " input[type=text], "+form_name+ " input[type=password], "+form_name+ " input[type=email], "+form_name+", "+form_name+ " input[type=hidden], "+form_name+ " textarea"+", "+form_name+ " input[type=file]").each(function(){
        if ( !$(this).hasClass('do-not-clear') ) {
            var val = '';
            if ($(this).attr('data-default') != '' && $(this).attr('data-default') != undefined) {
                val = $(this).attr('data-default');
            }
            $(this).val(val);
        }
    });
    $(form_name+ " select").each(function(){
        if ( !$(this).hasClass('do-not-clear') ) {
            var val = '';
            if ($(this).attr('data-default') != '' && $(this).attr('data-default') != undefined) {
                val = $(this).attr('data-default');
            }
            console.log($(this).attr('data-default'));
            $(this).val(val);
        }
    });
    return false;
}


function isNumberKey(evt) {
 var charCode = (evt.which) ? evt.which : event.keyCode
 if (charCode > 31 && (charCode < 48 || charCode > 57))
    return false;

 return true;
}