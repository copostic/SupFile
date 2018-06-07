$('form#login, form#register').on('submit', function(e) {
    e.preventDefault();
    var url = $(this).attr('id') === 'login' ? '/auth/login' : '/auth/register';
    var data = $(this).serialize();
    $.ajax({
        type: 'POST',
        url: url,
        data: data,
        success: function(data) {
            data = JSON.parse(data);
            if (data.success) {
                window.location.href = '/explorer';
            } else {
                $('div#errorMessage').show().html(data.message);
            }
        },
        error: function(data) {
            console.log(data);
        }
    })
});

$('div#modal > div.modal-content > span.close').on('click', function() {
    $('div.modal-content > div.content').html('');
    $(this).parents('div#modal').hide();
});