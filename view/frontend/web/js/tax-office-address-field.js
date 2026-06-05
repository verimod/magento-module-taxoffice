define([
    'jquery'
], function ($) {
    'use strict';

    return function (config, element) {
        $(element).on('change', function () {
            // Handle tax office number field changes
            console.log('Tax office number field changed');
        });
    };
});
