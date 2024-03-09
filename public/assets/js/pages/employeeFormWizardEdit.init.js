const SUCCESSFULLY_UPDATE = 200;
const EMPLOYEE_ID = $('#employeeID').val();
let isPageTitle = false;


$("#employee-wizard").steps({
    headerTag       : "h3",
    bodyTag         : "section",
    transitionEffect: "slide",
    saveState       : true,
    enableAllSteps  : true,
    onInit          : function () {
        $('#birthDate').change(function (field) {
            $('#age').val(WIZARD_HELPER.calculateAge(field.target.value));
        });

        // trigger change method of birthDate.
        $('#birthDate').trigger('change');
    },
    onStepChanging : function (event, currentIndex, newIndex) {

        // Enable the user to go back to step.
        if(currentIndex > newIndex) {
            return true;
        }

        if(currentIndex === WIZARD_HELPER.EMPLOYEE_DETAILS_INDEX) {
            let employeeDetails = {
                employeeID : $('#employeeID').val(),
                firstName  : $('#firstName').val(),
                middleName : $('#middleName').val(),
                lastName   : $('#lastName').val(),
                suffix     : $('#suffix').val(),
                birthDate  : $('#birthDate').val(),
                birthPlace : $('#birthPlace').val(),
                gender     : $('#gender').val(),
                civilStatus: $('#civilStatus').val(),
                age        : $('#age').val(),
                phoneNo    : $('#phoneNo').val(),
                email      : $('#email').val(),
                address    : $('#address').val(),
                office     : $('#office').val(),
                position   : $('#position').val(),
                step       : currentIndex + 1,
            };

            let result = $.ajax({
                method : 'PUT',
                url    : `/maintenance/employee/${EMPLOYEE_ID}/edit`,
                data   : employeeDetails,
                async  : !1,
                success: function (response) {
                    if(response.success) {
                    }
                },
                error : function (response) {
                    WIZARD_HELPER.displayErrorMessageFromInvalidFields(Object.keys(response.responseJSON.errors), response.responseJSON.errors);
                }
            });

            if(result.status === SUCCESSFULLY_UPDATE) {
                return true;
            }
        } else if(currentIndex === WIZARD_HELPER.SOCIAL_INSURANCE_INDEX) {
            let socialInsurance = {
                philHealthNo: $('#philHealthNo').val(),
                pagIbigNo   : $('#pagIbigNo').val(),
                tinNo       : $('#tinNo').val(),
                gsisNo      : $('#gsisNo').val(),
                step        : currentIndex + 1,
            };

            WIZARD_HELPER.removeErrorMessageFromValidFields(Object.keys(socialInsurance));

            let result = $.ajax({
                method : 'PUT',
                url    : `/maintenance/employee/${EMPLOYEE_ID}/edit`,
                data   : socialInsurance,
                async  : !1,
                success: function (_) {
                },
                error : function (response) {
                    WIZARD_HELPER.displayErrorMessageFromInvalidFields(Object.keys(response.responseJSON.errors), response.responseJSON.errors);
                }
            });

            if(result.status === SUCCESSFULLY_UPDATE) {
                return true;
            }
        } else if(currentIndex === WIZARD_HELPER.BANK_DETAILS_INDEX ) {
            let bankDetails = {
                cardNumber    : $('#cardNumber').val(),
                cardExpiration: $('#cardExpiration').val(),
                step          : currentIndex + 1,
            };

            WIZARD_HELPER.removeErrorMessageFromValidFields(Object.keys(bankDetails));

            let result = $.ajax({
                method : 'PUT',
                url    : `/maintenance/employee/${EMPLOYEE_ID}/edit`,
                data   : bankDetails,
                async  : !1,
                success: function (_) {
                },
                error : function (response) {
                    WIZARD_HELPER.displayErrorMessageFromInvalidFields(Object.keys(response.responseJSON.errors), response.responseJSON.errors);
                }
            });

            if(result.status === SUCCESSFULLY_UPDATE) {
                return true;
            }
        }
    },
    onStepChanged : function (event, currentIndex, priorIndex) {
    },
    onFinished : function (event, currentIndex) {
        // Collect all the information from local storage then insert to server.
        let allInformationOfEmployee  = {
            employeeID: $('#employeeID').val(),
            imagePhoto   : $('#imageID').val(),
            step      : 4,
        };

        allInformationOfEmployee.employeeDetails  = {
            firstName  : $('#firstName').val(),
            middleName : $('#middleName').val(),
            lastName   : $('#lastName').val(),
            suffix     : $('#suffix').val(),
            birthDate  : $('#birthDate').val(),
            birthPlace : $('#birthPlace').val(),
            email      : $('#email').val(),
            gender     : $('#gender').val(),
            civilStatus: $('#civilStatus').val(),
            email      : $('#email').val(),
            phoneNo    : $('#phoneNo').val(),
            address    : $('#address').val(),
            office     : $('#office').val(),
            position   : $('#position').val(),
        };
        allInformationOfEmployee.socialInsurance =  {
            philHealthNo: $('#philHealthNo').val(),
            pagIbigNo   : $('#pagIbigNo').val(),
            tinNo       : $('#tinNo').val(),
            gsisNo      : $('#gsisNo').val(),
        };

        allInformationOfEmployee.bankDetails  = {
            cardNumber    : $('#cardNumber').val(),
            cardExpiration: $('#cardExpiration').val(),
        };

        $.ajax({
            url : `/maintenance/employee/${allInformationOfEmployee.employeeID}/edit`,
            method : 'PUT',
            data : allInformationOfEmployee,
            success : function (response) {
                if(response.success) {
                    // Clear local storage.
                    WIZARD_HELPER.clearStepInformation(WIZARD_HELPER.EMPLOYEE_DETAILS_INDEX + 1);
                    WIZARD_HELPER.clearStepInformation(WIZARD_HELPER.SOCIAL_INSURANCE_INDEX + 1);
                    WIZARD_HELPER.clearStepInformation(WIZARD_HELPER.BANK_DETAILS_INDEX + 1);

                    // Display Sweet Alert.
                    swal(
                        {
                            title: 'Good job!',
                            text: 'Employee successfully update',
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
