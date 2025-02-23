$(document).ready(function() {
    /**
     * Show validation errors on form fields
     *
     * @param {string} formId - Form ID
     * @param {Object|Array} errors - Validation errors
     * @param {Object} options - Options
     * @param {string} options.errorClass - Class to be added to the field with error (default: 'is-invalid')
     * @param {string} options.errorContainerSuffix - Suffix to be added to the field name to create the error container ID (default: 'Error')
     * @param {string} options.errorContainerClass - Class to be added to the error container (default: 'invalid-feedback')
     * @param {boolean} options.createErrorContainer - Whether to create the error container if it doesn't exist (default: true)
     */
    function displayValidationErrors(formId, errors, options = {}) {
        const config = {
            errorClass: 'is-invalid',
            errorContainerSuffix: 'Error',
            errorContainerClass: 'invalid-feedback',
            createErrorContainer: true,
            ...options
        };

        const $form = $(`#${formId}`);
        if (!$form.length) {
            console.error(`Form with id '${formId}' not found`);
            return;
        }

        $form.find(`.${config.errorClass}`).removeClass(config.errorClass);
        $form.find(`.${config.errorContainerClass}`).empty();

        const processErrors = (errors) => {
            if (Array.isArray(errors)) {
                const $generalErrors = $form.find('.general-errors');
                if ($generalErrors.length) {
                    $generalErrors.html(errors.join('<br>')).removeClass('d-none');
                } else {
                    $form.prepend(`
                    <div class="alert alert-danger general-errors mb-3">
                        ${errors.join('<br>')}
                    </div>
                `);
                }
                return;
            }

            Object.entries(errors).forEach(([field, messages]) => {
                const fieldMessages = Array.isArray(messages) ? messages.join('<br>') : messages;
                const $field = $form.find(`[name="${field}"]`);

                if ($field.length) {
                    $field.addClass(config.errorClass);

                    let $errorContainer = $form.find(`#${field}${config.errorContainerSuffix}`);

                    if (!$errorContainer.length && config.createErrorContainer) {
                        $errorContainer = $(`<div id="${field}${config.errorContainerSuffix}" class="${config.errorContainerClass}"></div>`);
                        $field.after($errorContainer);
                    }

                    if ($errorContainer.length) {
                        $errorContainer.html(fieldMessages);
                    }
                }
            });
        };
    }
});