import * as Helper from '../modules/helpers.js'

var isLastLevel = false,
    subCategoriesSelect = $("#SubCategories"),
    subCategoriesBox = subCategoriesSelect.parents("#subCategoriesBox"),
    timeFromElement = $("#TimeFrom"),
    timeToElement = $("#TimeTo"),
    sliceElement = $("#Slice"),
    meridiemAM = sliceElement.data("meridiem-am"),
    meridiemPM = sliceElement.data("meridiem-pm");


const changeTimeSlicing = () => {

    let htmlElements = ``;
    if(timeFromElement.val() !== null && sliceElement.val() !== null && timeToElement.val() !== null ){
        let parts = (timeFromElement.val()).split(":")
        let timeFrom = parseFloat(`${parseInt(parts[0])}.${(parseInt(parts[1]) / 60) * 10}`)

        parts = (timeToElement.val() === "23:59" ? "24:00" : timeToElement.val()).split(":")

        let timeTo =  parseFloat(`${parseInt(parts[0])}.${(parseInt(parts[1]) / 60) * 10}`)
        let slice = parseFloat(sliceElement.val()) / 60
        if((timeTo - timeFrom) % slice === 0){
            htmlElements += `<div class="box-checking">
                                <input type="checkbox" class="select-all">
                                <label for="">All</label>
                            </div>`;
            for (let time = timeFrom; time < timeTo; time += slice){
                let endTime = time + slice;
                let startMinutes = (time - Math.floor(time)) * 60,
                    startHour = time - (time - Math.floor(time)),

                    endMinutes = (endTime - Math.floor(endTime)) * 60,
                    endHour = endTime - (endTime - Math.floor(endTime)),
                    startMeridiem = meridiemAM,
                    endMeridiem = meridiemAM
                let inputValue = (startHour < 10 ?`0${startHour}` : startHour) + ":" + (startMinutes < 10 ?`0${startMinutes}` : startMinutes)


                if(startHour >= 12){
                    startHour =  startHour - 12
                }
                if(endHour >= 12){
                    endHour =  endHour - 12
                    if(endHour === 12){
                        endHour = 0
                        endMeridiem = meridiemAM
                    }else
                        endMeridiem = meridiemPM

                }
                startHour = startHour < 10 ?`0${startHour}` : startHour;
                startMinutes = startMinutes < 10 ?`0${startMinutes}` : startMinutes;

                endHour = endHour < 10 ?`0${endHour}` : endHour;
                endMinutes = endMinutes < 10 ?`0${endMinutes}` : endMinutes;

                htmlElements += `
                    <div class="box-checking">
                        <input type="checkbox" name="times[]" class="checkbox" value="${inputValue  }">
                        <label for="">${startHour}:${startMinutes} ${startMeridiem} - ${endHour}:${endMinutes} ${endMeridiem}</label>
                    </div>
                    `;
            }
        }
    }
    $("#TimeView").html(htmlElements)

}


$(document).ready(function(){

    $("#HasDay").on("click", (e) => {
        var $serviceAvailability = $("#ServiceAvailability"),
            $daysSection = $serviceAvailability.find("#DaysSection"),
            $timeSection = $serviceAvailability.find("#TimesSection"),
            $hasTime = $("#HasTime")
        if(e.target.hasAttribute("checked")){
            if(!$hasTime[0].hasAttribute("checked")){
                $serviceAvailability.fadeOut();
            }
            $daysSection.fadeOut();
            e.target.removeAttribute("checked")
        }else{
            if($serviceAvailability.css("display") === "none"){
                $serviceAvailability.fadeIn();
            }
            $daysSection.fadeIn();
            e.target.setAttribute("checked", 'checked')
        }
    })

    $("#HasTime").on("click", (e) => {
        var $serviceAvailability = $("#ServiceAvailability"),
            $timeSection = $serviceAvailability.find("#TimesSection"),
            $hasDay = $("#HasDay")
        if(e.target.hasAttribute("checked")){
            if(!$hasDay[0].hasAttribute("checked")){
                $serviceAvailability.fadeOut();
            }
            $timeSection.fadeOut();
            e.target.removeAttribute("checked")

        }else{
            if($serviceAvailability.css("display") === "none"){
                $serviceAvailability.fadeIn();
            }
            $timeSection.fadeIn();
            e.target.setAttribute("checked", 'checked')

        }
    })

    $("#TimeFrom").on('keyup, change', () => { changeTimeSlicing();})
    $("#TimeTo").on('keyup, change', () => {  changeTimeSlicing();})
    $("#Slice").on('keyup, change', () => { changeTimeSlicing();})


    $("#HasCounter").on("click", (e) => {
        const priceType = $("#PriceType")[0].value
        const $PricesControl = $("#PricesControl")
        console.log(e.target.hasAttribute("checked"))

        if(e.target.hasAttribute("checked")){
            switch (priceType){
                case "u":
                    $PricesControl.empty()
                break;
                case "f":
                    $PricesControl.empty().html($(`script#TemplateFixedPriceWithCounter`).html())
                break;
                case "r":
                    $PricesControl.empty().html($(`script#TemplateRangePriceWithCounter`).html())
                break;
            }
        }else{
            switch (priceType){
                case "u":
                    $PricesControl.empty()
                    break;
                case "f":
                    $PricesControl.empty().html($(`script#TemplateFixedPriceWithoutCounter`).html())
                    break;
                case "r":
                    $PricesControl.empty().html($(`script#TemplateRangePriceWithoutCounter`).html())
                    break;
            }
        }
    })

    $("#PriceType").on("change" , (e) => {
        const priceType = e.target.value;
        const withCounter = $("#HasCounter")[0].hasAttribute("checked")
        const $PricesControl = $("#PricesControl")
        const $CounterBox = $(".counter-box")
        let scriptId = null;
        switch (priceType){
            case "u":
                $PricesControl.empty()
                $CounterBox.fadeOut();
            break;
            case "f":
                scriptId = "TemplateFixedPriceWithoutCounter"
                if(withCounter)
                    scriptId = "TemplateFixedPriceWithCounter"
                $PricesControl.empty().html($(`script#${scriptId}`).html())
                $CounterBox.fadeIn();
            break;
            case "r":
                scriptId = "TemplateRangePriceWithoutCounter"
                if(withCounter)
                    scriptId = "TemplateRangePriceWithCounter"
                $PricesControl.empty().html($(`script#${scriptId}`).html())
                $CounterBox.fadeIn();
            break;
        }
    })

    $(document).on("click", ".add-new-row", () => {
        const priceType = $("#PriceType")[0].value
        let htmlElement = null;
        switch (priceType){
            case "f":
                htmlElement = $(`script#TemplateRowFixedPrice`).html()
            break;
            case "r":
                htmlElement = $(`script#TemplateRowRangePrice`).html()
            break;
        }
        $('.quantity-prices-box').append($(htmlElement))
    })

    $(document).on("click", "span.delete-option", async function () {
        if ($(this).hasClass("server-delete")) {
            const button = $(this)
            const id = $(this).data("id"),
                url = $(this).data('url'),
                lang = $('html').attr('lang')
            console.log("ss")


            swal({
                title: button.data('title'),
                icon: "warning",
                buttons: [button.data("no"),button.data("yes")],
                dangerMode: true,
            }).then(async (willDelete) => {
                if(willDelete){
                    const response = await Helper.ajaxCall(url, {id})
                    if(response.status_number === 'S201'){
                        swal($(this).data("success-message"), {
                            icon: "success",
                        });
                        setTimeout(() => {
                            location.reload();
                        }, 1500);
                    }else{
                        swal($(this).data("error-message"), {
                            icon: "error",
                        });
                    }
                }
            })

        } else
            $(this).parents(".option-quantity-box").remove()
    })




});

