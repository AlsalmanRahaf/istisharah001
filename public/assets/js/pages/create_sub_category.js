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
                    startMeridiem = meridiemPM

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
});
