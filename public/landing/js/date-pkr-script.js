jQuery(document).ready(function () {
        jQuery('#datepicker').datepicker({
            format: 'dd-mm-yyyy',
            startDate: '1d'
        });
    });

jQuery(document).ready(function () {
        jQuery('#datepicker1').datepicker({
            format: 'dd-mm-yyyy',
            startDate: '+1d'
        });
    });