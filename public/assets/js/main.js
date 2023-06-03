(function () {
	"use strict";
    var lang = $('html').attr('lang');

	var treeviewMenu = $('.app-menu');

	// Toggle Sidebar
	$('[data-toggle="sidebar"]').click(function(event) {
		event.preventDefault();
		$('.app').toggleClass('sidenav-toggled');
	});

	// Activate sidebar treeview toggle
	$("[data-toggle='treeview']").click(function(event) {
		event.preventDefault();
		if(!$(this).parent().hasClass('is-expanded')) {
			treeviewMenu.find("[data-toggle='treeview']").parent().removeClass('is-expanded');
		}
		$(this).parent().toggleClass('is-expanded');
	});

	// Set initial active toggle
	$("[data-toggle='treeview.'].is-expanded").parent().toggleClass('is-expanded');

	//Activate bootstrip tooltips
	$("[data-toggle='tooltip']").tooltip();

	// $("toggle-flip input[type=\"checkbox\"]")

    $(".dropdown-menu").addClass(lang === "ar" ? "dropdown-menu-left" : "dropdown-menu-right")


    $(".change-status input[type=\"checkbox\"]").on("click",function (){
        alert("dd");


        let val = $(this).data("status");
        let userId = $(this).data("userid");
        let status = val === 0 ? 1 : 0;
        let checkBox = $(this);

        $.ajax({
            type: "POST",
            url:"/api/en/update_user_status",
            data:{user_id:userId,status: status,ajax:1}, // serializes the form's elements.
            success: function(response)
            {
                if(response.status_number === 'S201'){
                    checkBox.attr('checked', true);
                }else{
                    checkBox.attr('checked', false);
                }
            },
            error:function(){
                alert("error")
                console.error("you have error");
            }
        });
    });

    $(".change-statusAds input[type=\"checkbox\"]").on("click",function (){

        let val = $(this).data("status");
        let adsId = $(this).data("adsid");
        let status = val === 0 ? 1 : 0;
        let checkBox = $(this);

        $.ajax({
            type: "POST",
            url:"/api/en/update_ads_status",
            data:{ads_id:adsId,status: status,ajax:1}, // serializes the form's elements.
            success: function(response)
            {
                if(response.status_number === 'S201'){
                    checkBox.attr('checked', true);
                }else{
                    checkBox.attr('checked', false);
                }
            },
            error:function(){
                alert("error")
                console.error("you have error");
            }
        });
    });






    $("#select-type-ads").on("change",function (){
        let type=$(this).val(),
            adsVideo=$(".ads-video"),
            adsPhoto=$(".ads-photo"),
            adsText=$(".ads-text"),
            adsmp3=$(".ads-mp3")
        ;

        switch (type){
            case "1":
                adsText.show(500);
                adsVideo.hide();
                adsPhoto.hide();
                adsmp3.hide();

                break;
            case "3":
                adsVideo.show(500);
                adsText.hide();
                adsPhoto.hide();
                adsmp3.hide();

                break;
            case "2":
                adsPhoto.show(500);
                adsVideo.hide();
                adsText.hide();
                adsmp3.hide();

                break;
            case "4":
                adsmp3.show(500);
                adsPhoto.hide();
                adsVideo.hide();
                adsText.hide();
                break;
            default:
                alert("select one");
        }
    });


})();
