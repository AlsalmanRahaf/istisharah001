import * as Helper from "../modules/helpers.js"
$(document).ready(function (){

    $("#MainCategories").on("change", async function () {
        const option = $(this).find(":selected"),
            $MainCategoriesSelect = $(this),
            $SubCategoriesSelect = $("#SubCategories")

        let url = option.data("sub-url")

        console.log(url)
        if (url !== null && url !== undefined) {
            let response = await Helper.ajaxCall(url, [], "GET");
            let htmlElement = '';
            const data = response.data
            if(data.length > 0){
                htmlElement = '<option value="">None</option>'
                data.forEach((category, ind) => {
                    htmlElement += `<option value="${category.id}">${category.name}</option>`
                })
                $SubCategoriesSelect.empty().append(htmlElement).parents("#SubCategoriesBox").fadeOut(400, function(){
                  $(this).fadeIn()
                })

            }else{
                $SubCategoriesSelect.empty().parents("#SubCategoriesBox").fadeOut()
            }
        }
    });


    $(document).on("change", "#SubCategories", async function () {

        const subCategoryId = $(this).val(),
            mainCategoryId = $("#MainCategories").val(),
            $ServicesBox = $("#ServicesBox"),
            lang = $("html").attr('lang')
        console.log(subCategoryId, mainCategoryId)

        if (subCategoryId && mainCategoryId ) {
            let response = await Helper.ajaxCall(`/${lang}/ajax/main-categories/${mainCategoryId}/sub-categories/${subCategoryId}/services`, [], "GET");
            let htmlElement = '';
            const data = response.data
            console.log(data)
            const servicesSelected = $("#ServicesSelectedBox .service-item input[name='services[]']")
            let servicesSelectedValues = []

            for (const service of servicesSelected)
                servicesSelectedValues[service.value] = true
            if (data.length > 0) {
                data.forEach((service, ind) => {
                    htmlElement += ` <div class="col-lg-3">
                                        <div class="form-group">
                                          <div class="animated-checkbox">
                                                <label>
                                                    <input type="checkbox"  class="services-checkbox checked-creator-action" value="${service.id}" ${servicesSelectedValues[service.id] ? "checked" : ''}>
                                                        <span class="label-text">${service.name}</span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>`;
                })
                //                                            <input type="checkbox" class="services-checkbox checked-action" value="${service.id}" ${servicesSelectedValues[service.id] ? "checked" : ''}>
                $ServicesBox.empty().append(htmlElement).fadeOut(function (){
                    $(this).fadeIn();
                });
            } else {
                $ServicesBox.empty().fadeOut()
            }
        }
    });



    $(document).on("change", ".services-checkbox",function(){

        const isChecked = $(this).is(":checked")
        let text = $(this).siblings(".label-text").text(),
            serviceId = $(this).val()
        if(isChecked){
            if(document.getElementById(`ServiceItem${serviceId}`) === null){
                let htmlElement = `<div class="service-item" id="ServiceItem${serviceId}">
                                        <input type="hidden" value="${serviceId}" name="services[]">
                                        <span class="text">${text}</span>
                                        <span class="icon-close"><i class="fas fa-times"></i></span>
                                    </div>`
                const $ServiceItem = $(htmlElement)
                $("#ServicesSelectedBox").append($ServiceItem)
                $ServiceItem.fadeIn()
                let generalCommission = $("input[name='application_commission']").val()
                 htmlElement = `<div class="form-group">
                                        <div class="row">
                                            <div class="col-lg-2">
                                                <h5 style="padding-top: 7px;">${text}</h5>
                                            </div>
                                            <div class="col-lg-5">
                                                <div class="input-group">
                                                    <input class="form-control" type="number" value="${generalCommission}" name="service_commission_${serviceId}">
                                                    <div class="input-group-append"><span class="input-group-text">%</span></div>
                                                </div>
                                            </div>
                                        </div>
                                        <hr>
                                </div>`;
                $("#ServiceCommission").append(htmlElement)
                $("#ServicesCommissionButton")[0].removeAttribute("disabled")
            }
        }else{
            const item = document.getElementById(`ServiceItem${serviceId}`)
            if(item){
                item.remove()
                $(`input[name='service_commission_${serviceId}']`).parents(".form-group").remove()
            }
        }
    })
    $(document).on("click", ".service-item .icon-close", function (){
        console.log("s")
        const $ServicesCheckboxes = $("#ServicesSelectedBox .service-item input[name='services[]']"),
                serviceId  = $(this).siblings("input").val()
        $(this).parents(".service-item").remove()
    })



});
