$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});


    $('#step-increment-table').DataTable({
        processing: true,
        serverSide: true,
        destroy: true,
        retrieve: true,
        pagingType: "full_numbers",
        ajax: '/maintenance/step-increment/list',
        language: {
                processing: '<i class="fa fa-spinner fa-spin fa-2x fa-fw"></i><span class="sr-only">Loading...</span> ',
        },
        columns: [{
                class: 'text-truncate lead',
                data: 'date_step_increment',
                name: 'date_step_increment'
            },
            {
                class: 'text-truncate lead',
                data: 'fullname',
                name: 'fullname',
                searchable: true,
                sortable: true,
                visible: true
            },
            {
                class: 'text-truncate lead',
                data: 'position_name',
                name: 'position_name',
                searchable:  true,
                sortable: false,
                visible: true
            },
            {
                class : 'lead',
                data: 'item_no',
                name: 'item_no',
            },
            {
                class : 'lead',
                data: 'last_latest_appointment',
                name: 'last_latest_appointment',
            },
            {
                class : 'lead',
                data: 'sg_from_and_step_from',
                name: 'sg_from_and_step_from'
            },
            {
                class : 'lead',
                data: 'salary_amount_from',
                name: 'salary_amount_from',
            },
            {
                class : 'lead',
                data: 'sg_to_and_step_to',
                name: 'sg_to_and_step_to'
            },
            {
                class : 'lead',
                data: 'salary_amount_to',
                name: 'salary_amount_to',
            },
            {
                class : 'lead',
                data: 'salary_diff',
                name: 'salary_diff',
            }
        ]
    });


    $('input').on('keyup', function () {
        console.log('hello');
    })


// Events
    // TRANSITION OF FORM TO TABLE
    // $('#addBtn').on('click', ()=> {
    //     $('#addForm').attr("class", "page-header d-none");
    //     $('#stepIncrementTable').attr("class", "page-header d-none");
    //     // $('#btnViewTableContainer').removeClass('float-end d-none');
    //     $('#addForm').removeClass('page-header d-none');
    //     $('#formStepIncrement').removeClass('d-none');
    // });

    // // DISPLAY TABLE
    // $('#btnViewTableContainer').on('click', ()=> {
    //     $('#stepIncrementTable').removeClass('d-none');
    //     $(this).addClass('d-none');
    //     $('#formStepIncrement').addClass('d-none');
    //     $('#addForm').addClass('page-header d-none');
    // });

    // // SHOWS THE DATA VALUE IN INPUT
    // $('#employeeName').on('change', (e)=> {
    //     let employeeID = e.target.value;
    //     let plantilla = $($("#employeeName option:selected")[0]).attr('data-plantilla');
    //     /*let moneyFormat = toLocalString("ph", {maximumFractionDigits:2}) + '.00';*/


    //     if (plantilla) {
    //         plantilla = JSON.parse(plantilla);

    //         $('#employeeId').val(plantilla.employee_id);
    //         $('#plantillaId').val(plantilla.plantilla_id);
    //         $('#officeCode').val(plantilla.office_code);
    //         $('#status').val(plantilla.status);
    //         $('#positionName').val(plantilla.position.position_name);
    //         $('#positionId').val(plantilla.position.position_id);
    //         $('#itemNo').val(plantilla.item_no);
    //         $('#lastAppointment').val(plantilla.date_last_promotion);
    //         $('#salaryGrade').val(plantilla.sg_no);
    //         $("#sgNo2").val(plantilla.sg_no);
    //         $('#stepNo').val(plantilla.step_no);
    //         $('#amount').val(plantilla.salary_amount);
    //         /*$('#amount').val(plantilla.salary_amount)toLocalString('ph', {maximumFractionDigits:2}) + '.00';*/

    //         $('#stepNo2').html('');
    //         $('#stepNo2').append(`<option readonly>Please select</option>`);

    //         for (let step = plantilla.step_no + 1; step <= MAX_NUMBER_OF_STEP_NO; step++) {
    //             $('#stepNo2').append(`<option value='${step}'>${step}</option>`);
    //         }

    //     } else {
    //         $('#officeCode').val('');
    //         $('#status').val('');
    //         $('#positionName').val('');
    //         $('#itemNo').val('');
    //         $('#lastAppointment').val('');
    //         $('#salaryGrade').val('');
    //         $('#stepNo').val('');
    //         $('#amount').val('');
    //     }
    // });

    // //STEP NUMBER CONDITION WITH ERRORS
    // $('#stepNo2').on('change', (e)=> {
    //     let valueSelected = e.target.value;
    //     $.ajax({
    //         url: `/api/step/${$('#sgNo2').val()}/${valueSelected}`,
    //         success: function (response) {
    //             $('#amount2').val(`${response['sg_step' + valueSelected]}`)
    //             var amount = parseFloat($('#amount').val());
    //             var amount2 = parseFloat($('#amount2').val());
    //             var amountDifference = parseFloat(((amount2 - amount) || ''));
    //             $('#monthlyDifference').val(amountDifference);
    //         }
    //     });
    // });


    // // SAVE BUTTON //
    // $('#btnSave').on('click', (e)=> {
    //     e.preventDefault();

    //     let employeeName = $('#employeeName').val();
    //     let sgNo = $('#sgNo2').val();
    //     let stepNo = $('#stepNo2').val();
    //     let amount = $('#amount2').val();
    //     let errors = {};
    //     let filteredError = "";

    //     if (employeeName == "" || employeeName.toLowerCase() == 'search name here') {
    //         $('#employeeName-error-message').html('');
    //         $(".employeeName").addClass("is-invalid");
    //         $('#employeeName-error-message').append(
    //             `<span class="text-danger"> Employee name is required. </span>`);
    //         errors.employee = true;
    //     } else {
    //         $('#employeeName-error-message').html('');
    //         $(".employeeName").removeClass("is-invalid");
    //         errors.employee = false;
    //     }


    //     if (stepNo == "" || stepNo.toLowerCase() == 'please select') {
    //         $('#stepNo2-error-message').html('');
    //         $(".stepNo2").addClass("is-invalid");
    //         $('#stepNo2-error-message').append(`<span> The step no. is required. </span>`);
    //         errors.stepNo = true;
    //     } else {
    //         $('#stepNo2-error-message').html('');
    //         $(".stepNo2").removeClass("is-invalid");
    //         errors.stepNo = false;
    //     }


    //     filteredError = Object.values(errors).filter((error) => error);

    //     // Check if the filtered error array variable has value or not.
    //     // if the length of this array is 0 this means that there is no error
    //     // or all fields that required is filled by the user.
    //     if (filteredError.length === 0) {
    //         $('#formStepIncrement').submit();
    //         swal("Good job!", "Successfully added!", "success")
    //         .then((response) => {
    //             if(response){
    //                 location.reload();
    //             }
    //         });
    //     }
    // });




// let btnCancel = document.querySelector('#btnCancel');

// btnCancel.addEventListener('click', function(e){
//     e.preventDefault();
//     location.reload();
// })
