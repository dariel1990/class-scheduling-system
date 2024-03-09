    const SUCCESSFULLY_INSERT = 201;
    $("#employee-wizard").steps({
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
                        WIZARD_HELPER.saveStepInformation(employeeDetails.step, employeeDetails);
                        WIZARD_HELPER.displayErrorMessageFromInvalidFields(Object.keys(response.responseJSON.errors), response.responseJSON.errors);
                    }
                });

                if(result.status === SUCCESSFULLY_INSERT) {
                    return true;
                }

            } else if(currentIndex === WIZARD_HELPER.SOCIAL_INSURANCE_INDEX) {
                let socialInsurance = {
                    philHealthNo: $('#philHealthNo').val(),
                    pagIbigNo   : $('#pagIbigNo').val(),
                    tinNo       : $('#tinNo').val(),
                    sssNo       : $('#sssNo').val(),
                    gsisNo     : $('#gsisNo').val(),
                    step        : currentIndex + 1,
                };

                WIZARD_HELPER.removeErrorMessageFromValidFields(Object.keys(socialInsurance));

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
                        WIZARD_HELPER.saveStepInformation(socialInsurance.step, socialInsurance);
                        WIZARD_HELPER.displayErrorMessageFromInvalidFields(Object.keys(response.responseJSON.errors), response.responseJSON.errors);
                    }
                });

                if(result.status === SUCCESSFULLY_INSERT) {
                    return true;
                }

            } else if(currentIndex === WIZARD_HELPER.BANK_DETAILS_INDEX) {
                let bankDetails = {
                    cardNumber    : $('#cardNumber').val(),
                    cardExpiration: $('#cardExpiration').val(),
                    step          : currentIndex + 1,
                };

                WIZARD_HELPER.removeErrorMessageFromValidFields(Object.keys(bankDetails));

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
                        WIZARD_HELPER.saveStepInformation(bankDetails.step, bankDetails);
                        WIZARD_HELPER.displayErrorMessageFromInvalidFields(Object.keys(response.responseJSON.errors), response.responseJSON.errors);
                    }
                });

                if(result.status === SUCCESSFULLY_INSERT) {
                    return true;
                }

            }
        },
        onStepChanged : function (event, currentIndex, priorIndex) {
            WIZARD_HELPER.passedSections.push(priorIndex);
        },
        onFinished : function (event, currentIndex) {
            // Collect all the information from local storage then insert to server.
            let allInformationOfEmployee  = {
                imagePhoto : $('#imageID').val(),
                step : 4,
            };

            allInformationOfEmployee.employeeDetails  = WIZARD_HELPER.retrieveStepInformation(WIZARD_HELPER.EMPLOYEE_DETAILS_INDEX + 1);
            allInformationOfEmployee.socialInsurance = WIZARD_HELPER.retrieveStepInformation(WIZARD_HELPER.SOCIAL_INSURANCE_INDEX + 1);
            allInformationOfEmployee.bankDetails      = WIZARD_HELPER.retrieveStepInformation(WIZARD_HELPER.BANK_DETAILS_INDEX + 1);

            $.ajax({
                url : '/maintenance/employee/create',
                method : 'POST',
                data : allInformationOfEmployee,
                success : function (response) {
                    if(response.success) {
                        // Clear local storage.
                        WIZARD_HELPER.clearStepInformation(WIZARD_HELPER.EMPLOYEE_DETAILS_INDEX + 1);
                        WIZARD_HELPER.clearStepInformation(WIZARD_HELPER.SOCIAL_INSURANCE_INDEX + 1);
                        WIZARD_HELPER.clearStepInformation(WIZARD_HELPER.BANK_DETAILS_INDEX + 1);
                        WIZARD_HELPER.passedSections = [];

                        $('#employeeDetailsForm')[0].reset();
                        $('#socialInsuranceForm')[0].reset();
                        $('#bankDetailsForm')[0].reset();

                        // Display Sweet Alert.
                        swal(
                            {
                                title: 'Good job!',
                                text: 'Successfully add new employee!',
                                icon: 'success',
                                timer: 5000,
                                buttons : false,
                            }
                        );
                    }
                },
                error : function (response) {

                }
            });
        }
    });
