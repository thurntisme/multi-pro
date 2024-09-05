jQuery(document).ready(function ($) {
    $('#payment_type').on('change', function() {
        var paymentType = $(this).val();
        
        if (paymentType === 'yearly') {
            $('#payment_year').hide();
            $('#payment_month').show();
        } else if (paymentType === 'monthly') {
            $('#payment_month').hide();
            $('#payment_year').hide();
        } else {
            $('#payment_year').show();
            $('#payment_month').show();
        }
    });
});  