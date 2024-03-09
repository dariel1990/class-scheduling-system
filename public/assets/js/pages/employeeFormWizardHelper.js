const WIZARD_HELPER = {
    EMPLOYEE_DETAILS_INDEX: 0,
    SOCIAL_INSURANCE_INDEX: 1,
    BANK_DETAILS_INDEX    : 2,
    passedSections        : [],

    calculateAge(data) {
        let [year] = data.split("-");
        const currentYear = new Date().getFullYear();
        return (currentYear - year);
    },
    removeErrorMessageFromValidFields(fields) {
        fields.forEach((field) => {
            $(`#${field}Error`).html('');
            $(`#${field}`).removeClass('is-invalid');
        });
    },
    displayErrorMessageFromInvalidFields(fields, data) {
        fields.forEach((field) => {
            $(`#${field}Error`).html(data[field][0]);
            $(`#${field}`).addClass('is-invalid');
        });
    },
    saveStepInformation(step, data) {
        localStorage.setItem(`SECTION_${step}`, JSON.stringify(data));
    },
    retrieveStepInformation(step) {
        return JSON.parse(localStorage.getItem(`SECTION_${step}`));
    },
    clearStepInformation(step) {
        localStorage.removeItem(`SECTION_${step}`);
    },
};
