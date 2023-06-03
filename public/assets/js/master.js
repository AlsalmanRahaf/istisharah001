import * as Helper from "./modules/helpers.js"
(function () {
    "use strict";


    $("#form_status").on("click", ".changes-status input[type=\"checkbox\"]",async function (){
    // $(".change-status").on("click",async function (e){

        let url = $(this).data("url"),
            id = $(this).data("id"),
            type = $(this).data("status-type"),
            value = $(this)[0].checked ? 1 : 0,
            checkBox = $(this);

        if(value)
            checkBox.attr("checked", false);
        else
            checkBox.attr("checked", true);
        let data = {id: id, status: value};


        let response = await Helper.ajaxCall(url, data);
        console.log(response.status_number);
        if(response.status_number === "S200"){
            $(this).unbind(e);
            if(value)
                checkBox.attr("checked", true);
            else
                checkBox.attr("checked", false);

       }
    });



    $('.navigateType').on("change",function (){
        let select = $($(this).data("select"));
        select.siblings(".selects").fadeOut(500,function (){
            select.fadeIn(500);
        });
    });
    $('.delete-slider').on('click',function(){
        let branchId = $(this).data("branchid"),
        sliderId = $(this).data("imageid"),
        sliderBox = $(this).parent();

        $.ajax({
            type: "POST",
            url:"/api/branch/slider/delete",
            data:{branchId: branchId, sliderId: sliderId}, // serializes the form's elements.
            success: function(response)
            {
                if(response.status === 1){
                    sliderBox.fadeOut().remove();
                }
            },
            error:function(){
                console.error("you have error");
            }
        });
    });


    $(document).on("click",".form-confirm",function(event){
        event.preventDefault();
        let form = $($(this).data("form-id"));
        let title = form.data("swal-title");
        let text = form.data("swal-text");
        console.log($(this).data("formid"));

        swal({
            title: title,
            text: text,
            icon: "warning",
            buttons: [form.data("no"),form.data("yes")],
            dangerMode: true,
          })
          .then((willDelete) => {
            if (willDelete) {
              swal(form.data("success-msg"), {
                icon: "success",
              });
              setTimeout(function(){form.submit();},1000);
            } else {

            }
          });
    });


    // Show Uploaded Photo
    $(".show-uploaded").on("change",function(){
        let imgsContainerClass = $(this).data("imgs-container-class");

        if($(this).data("upload-type") == "single"){
            let imgBox = document.createElement("div");
            imgBox.classList.add(["img-container"]);
            let img = document.createElement("img");
            var reader = new FileReader();
            reader.onload = function (e) {
                img.setAttribute("src", e.target.result);
            }
            reader.readAsDataURL(this.files[0]);
            imgBox.appendChild(img);
            imgBox.style.display = "none";
            imgBox = $(imgBox);
            $("." + imgsContainerClass).empty().append(imgBox);
            imgBox.fadeIn();

        }else  if($(this).data("upload-type") == "multi"){
            let container = $("." + imgsContainerClass);
            container.empty();
            for (let index = 0; index < this.files.length; index++) {
                let imgBox = document.createElement("div");
                imgBox.classList.add(["img-container"]);
                let img = document.createElement("img");
                var reader = new FileReader();
                reader.onload = function (e) {
                    img.setAttribute("src", e.target.result);
                }
                reader.readAsDataURL(this.files[index]);
                imgBox.appendChild(img);
                imgBox.style.display = "none";
                imgBox = $(imgBox);
                container.append(imgBox);
                imgBox.fadeIn();
            }
        }else  if($(this).data("upload-type") == "mp3"){
            let AudioContainerClass= $(this).data("container-class-audio");
            var reader_AUDIO = new FileReader();
            var x = document.createElement("AUDIO");

            if (x.canPlayType("audio/mpeg")) {
                reader_AUDIO.onload = function (e) {
                    x.setAttribute("src", e.target.result);
                    x.setAttribute("controls", "controls");
                }
                reader_AUDIO.readAsDataURL(this.files[0]);
            }
            $("."+AudioContainerClass).empty().append(x);
        }else  if($(this).data("upload-type") == "video"){
            let VideoContainerClass= $(this).data("container-class-video");

            let imgBox = document.createElement("div");
            imgBox.classList.add(["video-container"]);

            var reader_video = new FileReader();
            var x = document.createElement("video");
                reader_video.onload = function (e) {
                    x.setAttribute("src", e.target.result);
                    x.setAttribute("controls", "controls");

                }
                reader_video.readAsDataURL(this.files[0]);
            imgBox.appendChild(x);
            $("."+VideoContainerClass).empty().append(imgBox);

        }



    });

    $(document).on("click",".box-checking-container .box-checking .select-all", function(){
        let allBoxes = $(this).parent().siblings(".box-checking")

        if($(this).is(":checked")){
            allBoxes.children("input[type=checkbox]").prop("checked", true)
        }else{
            allBoxes.children("input[type=checkbox]").prop("checked", false)
        }
    })

    $(document).on("click", ".box-checking-container .box-checking input.checkbox",function(){
        let allBoxes = $(this).parent().siblings(".box-checking")
        let inputsLength = allBoxes.children("input[type=checkbox].checkbox").length,
            inputsCheckedLength = allBoxes.children("input[type=checkbox].checkbox:checked").length

        if(!$(this).prop("checked"))
            inputsCheckedLength--
        console.log(inputsCheckedLength, inputsLength)
        if(inputsLength === inputsCheckedLength)
            $(".box-checking-container .box-checking .select-all").prop("checked", true)
        else
            $(".box-checking-container .box-checking .select-all").prop("checked", false)

    })


    $("input[type=checkbox].checked-action").on("click" ,(e) => {

        const isChecked = e.target.hasAttribute("checked")
        if(!isChecked)
            e.target.setAttribute("checked", 'checked')
        else
            e.target.removeAttribute("checked")
    })

    $(document).on("click" , "input[type=checkbox].checked-creator-action",(e) => {

        const isChecked = e.target.hasAttribute("checked")
        if(!isChecked)
            e.target.setAttribute("checked", 'checked')
        else
            e.target.removeAttribute("checked")
    })

})();
