import * as Helper from "../modules/helpers.js"
const appendLoading = (element) => {
    let htmlLoadingElement = `<div class="loader">Loading...</div>`
    element.html(htmlLoadingElement);
}

$(document).on("click", ".view-attachemnts", async function (){
    let modelContent = $(this).siblings(".modal").find(".modal-body"),
        loadingBox = modelContent.children("#property-loading"),
        linksBox = modelContent.children(".links-box"),
        userId = $(this).data("userid"),
        url = $(this).data("url");

    let response = await Helper.ajaxCall(url, {userId}, "POST", () => {
        appendLoading(loadingBox)
        console.log(loadingBox);
        linksBox.empty().fadeOut(400, () => {
            loadingBox.fadeIn();
        })
    })
    if(response.status_number === 'S200'){
        let htmlElement = ``;
        let attachments = response.data;
        attachments.forEach((attachment) => {
            htmlElement += `<div class="download-links-box">
                                <div class="row">
                                    <div class="col-lg-10 text-size-18 link-name-box">${attachment.filename}</div>
                                    <div class="col-lg-2 link-button-download-box"><a href="${attachment.url}" class="btn btn-primary" download>Download</a></div>
                                </div>
                            </div>`;
            linksBox.html(htmlElement)
            loadingBox.empty().fadeOut(400, () => {
                linksBox.fadeIn();
            })

        })
    }
});
