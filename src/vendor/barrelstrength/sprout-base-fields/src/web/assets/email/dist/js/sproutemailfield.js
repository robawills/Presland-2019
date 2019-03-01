/*
 * @link      https://sprout.barrelstrengthdesign.com/
 * @copyright Copyright (c) Barrel Strength Design LLC
 * @license   http://sprout.barrelstrengthdesign.com/license
 */

function checkSproutEmailField(namespaceInputId, id, elementId, fieldHandle, fieldContext) {

    var sproutEmailFieldId = '#' + namespaceInputId;
    var sproutEmailButtonClass = '.' + id;

    // We use setTimeout to make sure our function works every time
    setTimeout(function() {
        // Set up data for the controller.
        var data = {
            'elementId': elementId,
            'fieldContext': fieldContext,
            'fieldHandle': fieldHandle,
            'value': $(sproutEmailFieldId).val()
        };

        // Query the controller so the regex validation is all done through PHP.
        Craft.postActionRequest('sprout-base-fields/fields/email-validate', data, function(response) {
            if (response) {
                $(sproutEmailButtonClass).addClass('fade');
                $(sproutEmailButtonClass + ' a').attr("href", "mailto:" + data.value);
            } else {
                $(sproutEmailButtonClass).removeClass('fade');
            }
        });

    }, 500);
}