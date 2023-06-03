import * as Helper from "../modules/helpers.js"
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
        let fields = `
                  <div class="row">
                    <div class="col-lg-6 col-md-8">
                        <div class="form-group">
                           <div class="animated-checkbox">
                              <label>
                                <input type="checkbox" name="is_required"><span class="label-text"> Is Required ?</span>
                              </label>
                            </div>
                            <div class="invalid-feedback" style="display: none"></div>
                        </div>
                    </div>
                 </div>`;
        $(`input[name="type"]`).val(type)
        formCustomInputsBox.empty().append(fields);
        selections.fadeOut(500, function () {
            selectionOptions.fadeIn();
        });
        form.children("input[name='type']").val(type);
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
                        console.log(input.next())
                        input.next().fadeIn().text(response.errors[type]);
                    }
                }

            }
            loading.fadeOut(100,function (){
                loading.empty();
                selectionOptions.fadeIn();
            });

        }else if (response.status_number === 'S200') {
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


});
