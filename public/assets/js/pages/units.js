import * as Helper from "../modules/helpers.js";
var formModal = $("#FormModal"),
    form = formModal.find("form"),
    loading = $("#property-loading");


let beforeSend = function(){
    formModal.fadeOut(100,function (){
        loading.append(`<div class="loader">Loading...</div>`);
        loading.fadeIn();
    });
}

$("#submitForm").on("click",async function (e){
    e.preventDefault();
    form.find(".form-input").removeClass("is-invalid").next(".invalid-feedback").fadeOut();
    let url = form.attr('action');

    let formData = new FormData(formModal.find("form")[0]);
    let response = await Helper.formRequest(url, formData, "POST", beforeSend);

    if(response.status_number === "S406"){
        let errors = response.errors;
        Object.entries(errors).forEach(([type, val]) => {

            let input = (type === "types_related" ? $(`select[name="${type}"]`) : $(`input[name="${type}"]`));

            let message = $(`<div class="invalid-feedback"></div>`);
            input.addClass("is-invalid");
            input.nextAll(".invalid-feedback").fadeIn().text(val[0]);
        });
        loading.fadeOut(100,function (){
            loading.empty();
            formModal.fadeIn();
        });

    }else if(response.status_number === "S200"){
        let inputs = form.find(".form-input");
        inputs.val(null).trigger("change");

        swal("Successfully", response.message, "success");
        loading.fadeOut(100,function (){
            loading.empty();
            formModal.fadeIn();
        });
        let unit = response.data.content.unit,
            types = response.data.content.types,
            editUrl = response.data.editUrl,
            destroyUrl = response.data.destroyUrl;
        let htmlElementTypesBoxs = ``;
        for(let type of types){
            htmlElementTypesBoxs += `<span class="box">${type}</span>`;
        }
        let htmlElements = `<div class="card-text category-box">
                                <a href="#" class="box-link">
                                    <span>${unit.name}</span>
                                    <div class="list-boxs mt-2">${htmlElementTypesBoxs}</div>
                                </a>
                                <div class="category-link">
                                    <a href="${editUrl}" class="btn btn-success"> Edit</a>
                                    <form action='${destroyUrl}' method="post" id="delete${unit.id}" style="display: none" data-swal-title="Delete Type" data-swal-text="Are Your Sure To Delete This Type ?" data-yes="Yes" data-no="No" data-success-msg="the type has been deleted succssfully">
                                        <input type="hidden" name="_token" value="${$('meta[name="csrf-token"]').attr('content')}">
                                        <input type="hidden" name="_method" value="delete">
                                    </form>
                                    <span href="#" class="btn btn-danger control-link form-confirm" style="font-size: 14px" data-form-id="#delete${unit.id}">Delete</span>
                                </div>
                            </div>`;
        $("#ContractorTypeList").append($(htmlElements));
    }
});


