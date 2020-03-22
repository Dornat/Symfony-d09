/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import '../css/app.css';
import $ from 'jquery';
import 'popper.js';
import 'bootstrap';

$("body").tooltip({ selector: '[data-toggle=tooltip]' });

const $mc = $('#main-container');
const isAuthenticated = $mc.data('is-authenticated');

if (!isAuthenticated) {
    $.post($mc.data('login-view'), function (data) {
        $mc.find('#login-form-container').html(data);
    });
}

console.log($mc.find('#login-form'));
$mc.on('submit', '#login-form', function (e) {
    e.preventDefault();
    const $form = $(this);

    $('.jq-post-form-error').remove();
    $.post($form.attr('action'), jsonStringifyFormData($form), function (data) {
        $mc.html(data);
    }).fail(function (jqXHR) {
        const error = JSON.parse(jqXHR.responseText);
        const $alert = $('<div class="alert alert-danger mt-1 jq-post-form-error" role="alert"></div>');
        $alert.html(error.message);
        $form.prepend($alert);
    });
});

$mc.find('#post-form').on('submit', function (e) {
    e.preventDefault();
    const $form = $(this);

    $('.jq-post-form-error').remove();
    $.post($form.attr('action'), jsonStringifyFormData($form), function (data) {
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

$mc.find('.jq-post-view').on('click', function () {
    const url = $(this).data('post-url');
    const modal = $mc.find('#post-view-modal');

    $.post(url, function (data) {
        modal.find('.modal-content').html('');
        modal.find('.modal-content').append(data);
        modal.modal('show');
    });
});

function jsonStringifyFormData($form) {
    const formData = {};
    $.each($form.serializeArray(), function (key, fieldData) {
        formData[fieldData.name] = fieldData.value
    });

    return JSON.stringify(formData);
}
