<script type="text/template" id="RowTemplate">
    <div class="row option-size-box">
        <div class="col-lg-6">
            <div class="form-group">
                <label class="control-label">{{__("Image Color")}}</label>
                <div>
                    <button class="btn btn-primary form-control button-upload-file" >
                        <input class="input-file show-uploaded" data-upload-type="single" multiple data-imgs-container-class="uploaded-images-4" type="file" name="image_color[]">
                        <span class="upload-file-content">
                            <i class="fas fa-upload fa-lg upload-file-content-icon left"></i>
                            <span class="upload-file-content-text">{{__("Upload Photo")}}</span>
                        </span>
                    </button>
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-md-5 col-sm-6">
            <div class="uploaded-images-4 all-uploaded" ></div>
        </div>
        <div class="col-lg-6">
            <div class="form-group">
                <label class="form-control-label" for="input-username"> {{__("color")}}</label>
                <input type="color"  name="colors[]" class="form-control" placeholder="color">
            </div>
        </div>
        <div class="col-lg-6 text-center">
            <div class="form-group">
                <label class="control-label">{{__('Delete')}}</label>
                <div class="d-block">
                    <span class="delete-option" style="color: #7e0000; font-size: 21px;cursor: pointer"><i class="far fa-trash-alt"></i></span>
                </div>
            </div>
        </div>
    </div>
</script>
<script type="text/template"  id="RowTemplate2">
    <div class="row option-size-box">
        <div class="col-lg-6">
            <div class="form-group">
                <label class="form-control-label" for="input-username"> {{__("size")}}</label>
                <input type="text" name="sizes[]" class="form-control" placeholder="Size">
            </div>
        </div>
        <div class="col-lg-6 text-center">
            <div class="form-group">
                <label class="control-label">{{__('Delete')}}</label>
                <div class="d-block">
                    <span class="delete-option" style="color: #7e0000; font-size: 21px;cursor: pointer"><i class="far fa-trash-alt"></i></span>
                </div>
            </div>
        </div>
    </div>
</script>
