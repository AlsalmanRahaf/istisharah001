import * as Helper from "../modules/helpers.js"
// $(document).ready(function(){
//     let selections = $("#selections"),
//         selectionOptions = $("#selection-options"),
//         form = selectionOptions.children("form"),
//         loading = $("#property-loading"),
//         formCustomInputsBox = form.find(".form-custom-inputs-box");
//
//
//     const loadingBox = () => {
//         selectionOptions.fadeOut(100,function (){
//             loading.append(`<div class="loader">Loading...</div>`);
//             loading.fadeIn();
//         });
//     }
//
//
//
//
//
//
//     $("#submitForm").on("click", async () => {
//         let inputs = form.find(".form-input").removeClass("is-invalid").next(".invalid-feedback").fadeOut();
//         let formData = new FormData(form[0]);
//         let response = await Helper.formRequest(form.attr("action"), formData, "POST", loadingBox);
//         console.log(response)
//         //   throw new Error("my error message");
//         if(response.status_number === 'S400'){
//             for (const type in response.errors){
//
//                 if(Array.isArray(response.errors[type]) || typeof response.errors[type] === 'object'){
//                     let inputs = $(`input[name="${type}[]"]`);
//                     console.log("inputs")
//                     for (const index in response.errors[type]){
//                         let input = $(inputs[index]);
//                         input.addClass("is-invalid");
//                         input.next().fadeIn().text(response.errors[type][index]);
//                     }
//                 }else{
//                     if(type !== 'type'){
//                         let input = $(`input[name="${type}"]`)
//                         input.addClass("is-invalid");
//                         console.log(input.next())
//                         input.next().fadeIn().text(response.errors[type]);
//                     }
//                 }
//
//             }
//             loading.fadeOut(100,function (){
//                 loading.empty();
//                 selectionOptions.fadeIn();
//             });
//
//         }else if (response.status_number === 'S200') {
//             swal("Successfully", response.message, "success");
//             loading.fadeOut(400, function () {
//                 loading.empty();
//                 formCustomInputsBox.empty();
//                 selections.fadeIn(400, function () {
//                     form.find(".form-input").val("");
//                 });
//             });
//             $(`#${response.data.type}`).remove();
//
//         }
//
//     });
//
//     $("#revertProperty").on("click",function(){
//         selectionOptions.fadeOut(400,function (){
//             formCustomInputsBox.empty();
//             selections.fadeIn(400,function (){
//                 form.find(".form-input").val("").removeClass("is-invalid").next(".invalid-feedback").css("display","none");
//             });
//         });
//     });
//
//
// });
//












$(document).ready(function(){
    let selections = $("#selections"),
        selectionOptions = $("#selection-options"),
        form = selectionOptions.children("form"),
        loading = $("#property-loading"),
        formCustomInputsBox = form.find(".form-custom-inputs-box");
        const loadingBox = () => {
            selectionOptions.fadeOut(100,function (){
                loading.append(`<div class="loader">Loading...</div>`);
                loading.fadeIn();
            });
        }



    $(".selection-action").on("click",function(){

        let type = $(this).data("type");
        if(type === "SL" || type === "DP" || type === "QS") {

            let SLFields = $(`
                    <h3>Options</h3>
                    <hr>
                    <div class="row">
                        <div class="col-lg-4 col-md-8">
                            <div class="form-group">
                                <label>Option Engligh Title</label>
                                <input type="text" name="option_en_title[]" class="form-control form-input">
                                <div class="invalid-feedback" style="display: none"></div>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-8">
                            <div class="form-group">
                                <label>Option Arabic Title</label>
                                <input type="text" name="option_ar_title[]" class="form-control form-input">
                                <div class="invalid-feedback" style="display: none"></div>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-8">
                            <div class="form-group">
                                <label>Price</label>
                                <input type="text" name="price[]" class="form-control form-input" value="0">
                                <div class="invalid-feedback" style="display: none"></div>
                            </div>
                        </div>
                    </div>
                    <div class="text-left">
                        <span class="btn btn-primary add-option">Add Option</span>
                    </div>
               `);

            formCustomInputsBox.empty().append(SLFields);

            selections.fadeOut(500, function () {
                selectionOptions.fadeIn();
            });
        } else if(type === "SW") {
            let fields = $(`
                    <hr>
                    <div class="row">
                        <div class="col-lg-4 col-md-8">
                            <div class="form-group">
                                <label>Price</label>
                                <input type="text" name="price" class="form-control form-input" value="0">
                                <div class="invalid-feedback" style="display: none"></div>
                            </div>
                        </div>
                    </div>
               `);

            formCustomInputsBox.empty().append(fields);
            selections.fadeOut(500, function () {
                selectionOptions.fadeIn();
            });
        }

        form.children("input[name='type']").val(type);

    });

    $(document).on("click",".add-option",function (){
        let fields = `
       <div class="row option">
            <div class="col-lg-12 text-right">
                <button class="btn btn-danger remove"><i class="fas fa-times"></i></button>
            </div>
            <div class="col-lg-4 col-md-8">
                <div class="form-group">
                    <label>Option Engligh Title</label>
                    <input type="text" name="option_en_title[]" class="form-control form-input">
                    <div class="invalid-feedback" style="display: none"></div>
                </div>
            </div>
            <div class="col-lg-4 col-md-8">
                <div class="form-group">
                    <label>Option Arabic Title</label>
                    <input type="text" name="option_ar_title[]" class="form-control form-input">
                    <div class="invalid-feedback" style="display: none"></div>
                </div>
            </div>
            <div class="col-lg-4 col-md-8">
                <div class="form-group">
                    <label>Price</label>
                    <input type="text" name="price[]" class="form-control form-input" value="0">
                    <div class="invalid-feedback" style="display: none"></div>
                </div>
            </div>
        </div>`;
        $(this).parent().prev().after(fields);
    });


    $("#submitForm").on("click", async () => {
        let inputs = form.find(".form-input").removeClass("is-invalid").next(".invalid-feedback").fadeOut();
        let formData = new FormData(form[0]);
        let response = await Helper.formRequest(form.attr("action"), formData, "POST", loadingBox);
        console.log(response)
        //   throw new Error("my error message");
        if(response.status_number === 'S400'){
            for (const type in response.errors){

                if(Array.isArray(response.errors[type]) || typeof response.errors[type] === 'object'){
                    let inputs = $(`input[name="${type}[]"]`);
                    console.log("inputs")
                    for (const index in response.errors[type]){
                        let input = $(inputs[index]);
                        input.addClass("is-invalid");
                        input.next().fadeIn().text(response.errors[type][index]);
                    }
                }else{
                    if(type !== 'type'){
                        let input = $(`input[name="${type}"]`)
                        input.addClass("is-invalid");
                        input.next().fadeIn().text(response.errors[type]);
                    }
                }

            }
            loading.fadeOut(100,function (){
                loading.empty();
                selectionOptions.fadeIn();
            });

        }else if (response.status_number === 'S201') {
            swal("Successfully", response.message, "success");
            loading.fadeOut(400, function () {
                loading.empty();
                formCustomInputsBox.empty();
                selections.fadeIn(400, function () {
                    form.find(".form-input").val("");
                });
            });
            $(`#${response.data.type}`).remove();

        }
    });

    $("#revertProperty").on("click",function(){
        selectionOptions.fadeOut(400,function (){
            formCustomInputsBox.empty();
            selections.fadeIn(400,function (){
                form.find(".form-input").val("").removeClass("is-invalid").next(".invalid-feedback").css("display","none");
            });
        });
    });

    $(document).on("click",".option .remove", function (){
        $(this).parents(".option").remove();
    });

});


