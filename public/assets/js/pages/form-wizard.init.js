    const SUCCESSFULLY_INSERT = 201;
    const WIZARD_HELPER = {
        EMPLOYEE_DETAILS_INDEX : 0,
        SOCIAL_INSURANCE_INDEX : 1,
        BANK_DETAILS_INDEX : 2,
        passedSections : [],
        calculateAge : function (data) {
            let [year] = data.split("-");
            const currentYear = new Date().getFullYear();
            return (currentYear - year);
        },
        removeErrorMessageFromValidFields : function (fields) {
            fields.forEach((field) => {
                $(`#${field}Error`).html('');
                $(`#${field}`).removeClass('is-invalid');
            });
        },
        displayErrorMessageFromInvalidFields : function (fields, data) {
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
        }
    };

    let sample = $("#employee-wizard").steps({
        headerTag       : "h3",
        bodyTag         : "section",
        transitionEffect: "slide",
        onInit : function () {
            $('#birthDate').change(function (field) {
                $('#age').val(WIZARD_HELPER.calculateAge(field.target.value));
            });

            // By default we need to check if the first section has object value or filled by the user.
            if(WIZARD_HELPER.retrieveStepInformation(WIZARD_HELPER.EMPLOYEE_DETAILS_INDEX + 1)) {
                let data = WIZARD_HELPER.retrieveStepInformation(WIZARD_HELPER.EMPLOYEE_DETAILS_INDEX + 1);
                Object.keys(data).forEach((field) => $(`#${field}`).val(data[field]));
                WIZARD_HELPER.passedSections.push(WIZARD_HELPER.EMPLOYEE_DETAILS_INDEX);
            }
        },
        onStepChanging : function (_, currentIndex, newIndex) {

            // Check if section already have a old values in local storage.
            if(WIZARD_HELPER.retrieveStepInformation(newIndex + 1)) {
                let data = WIZARD_HELPER.retrieveStepInformation(newIndex + 1);
                console.log(data);
                Object.keys(data).forEach((field) => $(`#${field}`).val(data[field]));
                WIZARD_HELPER.passedSections.push(newIndex);
            }

            // Enable the user to go back to step.
            if(currentIndex > newIndex) {
                return true;
            }

            if(currentIndex === WIZARD_HELPER.EMPLOYEE_DETAILS_INDEX) {
                let employeeDetails = {
                    'firstName'  : $('#firstName').val(),
                    'middleName' : $('#middleName').val(),
                    'lastName'   : $('#lastName').val(),
                    'suffix'     : $('#suffix').val(),
                    'birthDate'  : $('#birthDate').val(),
                    'birthPlace' : $('#birthPlace').val(),
                    'gender'     : $('#gender').val(),
                    'civilStatus': $('#civilStatus').val(),
                    'age'        : $('#age').val(),
                    'phoneNo'    : $('#phoneNo').val(),
                    'email'      : $('#email').val(),
                    'address'    : $('#address').val(),
                    'office'     : $('#office').val(),
                    'position'   : $('#position').val(),
                    'step'       : currentIndex + 1,
                };

                WIZARD_HELPER.removeErrorMessageFromValidFields(Object.keys(employeeDetails));

                if(WIZARD_HELPER.passedSections.includes(currentIndex)) {
                    return true;
                } else {
                    let result = $.ajax({
                        method : 'POST',
                        url    : '/maintenance/employee/create',
                        data   : employeeDetails,
                        async  : !1,
                        success: function (response) {
                            if(response.success) {
                                WIZARD_HELPER.saveStepInformation(employeeDetails.step, employeeDetails);
                            }
                        },
                        error : function (response) {
                            WIZARD_HELPER.displayErrorMessageFromInvalidFields(Object.keys(response.responseJSON.errors), response.responseJSON.errors);
                        }
                    });

                    if(result.status === SUCCESSFULLY_INSERT) {
                        return true;
                    }
                }

            } else if(currentIndex === WIZARD_HELPER.SOCIAL_INSURANCE_INDEX) {
                let socialInsurance = {
                    philHealthNo: $('#philHealthNo').val(),
                    pagIbigNo   : $('#pagIbigNo').val(),
                    tinNo       : $('#tinNo').val(),
                    sssNo       : $('#sssNo').val(),
                    step        : currentIndex + 1,
                };

                WIZARD_HELPER.removeErrorMessageFromValidFields(Object.keys(socialInsurance));

                if(WIZARD_HELPER.passedSections.includes(currentIndex)) {
                    return true;
                } else {
                    let result = $.ajax({
                        method: 'POST',
                        url   : '/maintenance/employee/create',
                        data  : socialInsurance,
                        async : !1,
                        success : function (response) {
                            if(response.success) {
                                WIZARD_HELPER.saveStepInformation(socialInsurance.step, socialInsurance);
                            }
                        },
                        error : function (response) {
                            WIZARD_HELPER.displayErrorMessageFromInvalidFields(Object.keys(response.responseJSON.errors), response.responseJSON.errors);
                        }
                    });

                    if(result.status === SUCCESSFULLY_INSERT) {
                        return true;
                    }
                }

            } else if(currentIndex === WIZARD_HELPER.BANK_DETAILS_INDEX) {
                let bankDetails = {
                    cardNumber    : $('#cardNumber').val(),
                    cardExpiration: $('#cardExpiration').val(),
                    step          : currentIndex + 1,
                };

                WIZARD_HELPER.removeErrorMessageFromValidFields(Object.keys(bankDetails));

                if(WIZARD_HELPER.passedSections.includes(currentIndex)) {
                    return true;
                } else {
                    let result = $.ajax({
                        method: 'POST',
                        url   : '/maintenance/employee/create',
                        data  : bankDetails,
                        async : !1,
                        success : function (response) {
                            if(response.success) {
                                WIZARD_HELPER.saveStepInformation(bankDetails.step, bankDetails);
                            }
                        },
                        error : function (response) {
                            WIZARD_HELPER.displayErrorMessageFromInvalidFields(Object.keys(response.responseJSON.errors), response.responseJSON.errors);
                        }
                    });

                    if(result.status === SUCCESSFULLY_INSERT) {
                        return true;
                    }

                }
            }
        },
        onStepChanged : function (event, currentIndex, priorIndex) {
            WIZARD_HELPER.passedSections.push(priorIndex);
        },
        onFinished : function (event, currentIndex) {
            alert('store all the information of employee');
        }
    });
