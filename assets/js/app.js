/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import '../css/app.css';
import $ from 'jquery';
import 'bootstrap';

const $mc = $('#main-container');
const isAuthenticated = $mc.data('is-authenticated');

if (!isAuthenticated) {
    $.post($mc.data('login-view'), function (data) {
        $mc.find('#login-form-container').html(data);
    });
}

$mc.on('submit', '#login-form', function (e) {
    e.preventDefault();

    $.post($(this).attr('action'), $(this).serialize(), function (data) {
        $mc.html(data);
    }).fail(function (data) {
        $mc.find('#login-form-container').html(data.responseText);
    });
});

$mc.on('submit', '#post-form', function (e) {
    e.preventDefault();
    const $form = $(this);

    const formData = {};
    $.each($(this).serializeArray(), function (key, fieldData) {
        formData[fieldData.name] = fieldData.value
    });

    $('.jq-post-form-error').remove();
    $.post($(this).attr('action'), JSON.stringify(formData), function (data) {
        if (data === 'ok') {
            $.get($('.jq-home').attr('href'), function (data) {
                $mc.html(data);
                $('.jq-toast-post-success').toast('show');
            });
        }
    }).fail(function (jqXHR) {
        const errors = JSON.parse(jqXHR.responseText);
        $form.find(':input').each(function () {
            const fieldName = $(this).attr('name');
            const formGroup = $(this).closest('.form-group');

            if (!errors.hasOwnProperty(fieldName)) {
                return;
            }

            const $alert = $('<div class="alert alert-danger mt-1 jq-post-form-error" role="alert"></div>');
            $alert.html(errors[fieldName]);
            formGroup.append($alert);
        });
    });
});
