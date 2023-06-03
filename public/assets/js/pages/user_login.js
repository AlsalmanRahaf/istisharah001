import Firebase from "../modules/packages_init/firebase.js";
import * as Helper from "../modules/helpers.js"
const firebase = new Firebase();
firebase.render();


const loginBox = $('.login-box');


const finishTimer = (interval, button) => {
    clearInterval(interval);
    button.addClass("ready").prop("disabled", false).empty().text(button.data("text-after-finish"));
}


const startTimerForResend =  () => {
    return new Promise((resolve,reject) => {
        let timerButton = $("#ResendCode"),
            timerElement = timerButton.find(".timer"),
            timer = parseInt(timerElement.text());
        let timerInterval =  setInterval((timerElement) => {
            let timer = parseInt(timerElement.text());
            timer--;
            if(timer <= 0){
                finishTimer(timerInterval, timerButton);
                resolve(true);
            }else
                timerElement.text(timer);

        }, 1000, timerElement);
    });

}


// ------ Events

// Send Code Event
$("#Send").on("click",async function () {
    let phoneNumber = $("#number").val();
    if(phoneNumber !== null){
        if ( await firebase.SendCode(phoneNumber) === true) {
            $('.login-box').toggleClass('flipped');
            await Helper.waitTime(1);
            await startTimerForResend();
        }
    }
});


// Send Code Event
$("#Verify").on("click",async function (){
    let code = $("#verificationCode").val();
    if(code !== null){
        let url = $(this).data("url-call");
        const token = await firebase.VerifyCode(code);
        if(token !== false){
            let response = await Helper.ajaxCall(url, {firebase_token:token});
            if(response.status_number === "S200"){
                window.location.replace(response.data.redirectUrl);
            }
        }
    }
});


// Resend Code Event
$("#ResendCode").on("click",async function (e) {
    e.preventDefault();
    if($(this).hasClass("ready") && !$(this)[0].hasAttribute("disabled")){
        let phoneNumber = $("#number").val();
        if(phoneNumber !== null){
            $(this).attr("disabled", true);
            if ( await firebase.SendCode(phoneNumber) === true) {
                swal("Resent Successfully",  "the verification code has been resent successfully", "success");
                $(this).empty().append($(`<span class="timer">60</span>`)).append(" S").removeClass("ready");
                await Helper.waitTime(1);
                await startTimerForResend();
            }
        }
    }
});


// Change Number Event
$("#ChangeNumber").on("click",async function (e) {
    e.preventDefault();
    loginBox.removeClass('flipped');
    $("#ResendCode").empty().append($(`<span class="timer">60</span>`)).append(" S")
                    .removeClass("ready")
                    .attr("disabled", true);
    $("#number").val('');
    $("#verificationCode").val('');
});






