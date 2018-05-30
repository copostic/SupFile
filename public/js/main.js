var registerTest = {
    'email': 'copostic@hotmail.fr',
    'password': 'test',
    'password_verify': 'test',
    'first_name': 'Corentin',
    'last_name': 'POSTIC'
};

var loginTest = {
    'email': 'copostic@hotmail.fr',
    'password': 'test'
};

$('#sendForm').on('click', function(e) {
    console.log('Sent');
    var $form = $(this);
    e.preventDefault();
    $.ajax({
        type: 'POST',
        url: 'http://supfile.tk/auth/login',
        data: loginTest,
        success: function(data) {
            console.log(data);
        }
    })
});