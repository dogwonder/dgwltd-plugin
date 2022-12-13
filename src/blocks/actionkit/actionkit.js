//Global vars
let ONEORG_USER = 0;
window.cf_country = window.userCountry || 'United Kingdom';

(function () {

    //Validate AK forms for humans
    function validateAKForm(form) {
        let ak_forms = document.querySelectorAll(form);
        if(!ak_forms) return;
        let ak_validate = '<input type="hidden" name="action_personcheck" value="1">';
        ak_forms.forEach(function (ak_form) {
            ak_form.insertAdjacentHTML('beforeend', ak_validate);
        });
    }

    // Handler when the DOM is fully loaded and after webfont's etc
    window.addEventListener("load", function(){

        validateAKForm('.ak-form');

        actionkit.forms.contextRoot = 'https://one.actionkit.com/context/';
        actionkit.forms.initPage();
        actionkit.forms.initForm('act');

    });
    
})();