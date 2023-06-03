<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ZoomController;
use App\Http\Controllers\Api\ConsultantRequestController;
use App\Models\ConsultantRequest;

Route::post("/update_user_status", "users\UsersController@Update");
Route::post('/save_message_media', "MessageMediaController@index");
Route::post('/update_ads_status' , 'ads\AdsController@Update');
Route::post("login2", "LoginController@login2");

Route::prefix("Ads")->group(function (){
    Route::get("/LoadingScreenAds", "ads\AdsController@LoadingScreenAds");
});

Route::prefix("zoom")->group(function (){
    Route::get('/meetings', [ZoomController::class, 'list']);
    Route::post('/meetings', [ZoomController::class,'create']);
    Route::get('/meetings/{id}', [ZoomController::class,'get'])->where('id', '[0-9]+');
    Route::patch('/meetings/{id}', [ZoomController::class,'update'])->where('id', '[0-9]+');
    Route::delete('/meetings/{id}', [ZoomController::class,'delete'])->where('id', '[0-9]+');
});

Route::get("getAllCategory/{lang_code?}","category_controller@getAllCategory");
Route::get("getAllItemFromCategory/{categoryId}","category_controller@getAllItemFromCategory");
Route::get("branches/{branch_id}/categories", "category_controller@getCategoryByBranch");


Route::prefix("categories_branches")->group(function (){
    Route::get("/", "CategoryBranchController@index");
});

Route::prefix("branches")->group(function (){
    Route::get("/category/{category_id}", "BranchController@index");
});
Route::get("getAllBranches","get_branches_controller@getAllBranches");
Route::get("getBranchDetails/{id}","get_branches_controller@getBranchDetails");

Route::resource("brand","BrandsController");
Route::get("BrandCategory/{brand_id}","BrandsController@BrandCategory");
Route::get("BrandItems/{brand_id}","BrandsController@get_all_items_brands");


Route::get("/items/branch/{branch_id}/category/{category_id}","ItemController@getByBranchAndCategory");

Route::get("getItemDetails/{itemId}","get_item_details_controller@getItemDetails");
Route::get("getItemAddOnsCategory/{itemId}","get_item_details_controller@getItemAddOnsCategory");
Route::get("getListOfAddOns/{addOnsCatId}","get_item_details_controller@getListOfAddOns");
Route::get("getItemImages/{item_id}","get_item_details_controller@getItemImages");
Route::get("getItemSizes/{item_id}","get_item_details_controller@getItemSizes");
Route::get("getItemColor/{item_id}","get_item_details_controller@getItemColor");


Route::post("createOrder","creat_order_controller@createOrder");
Route::get("getMyOrders/{phone_number}/{type}","order_details_controller@getMyOrders");
Route::get("getMyOrderDetails/{order_id}","order_details_controller@getMyOrderDetails");
Route::get("getInvoice","order_details_controller@getInvoice");
Route::get("OrderItemDetails","order_details_controller@getOrderItemDetails");

// cashier orders
Route::post("CreateCashierOrder","posOrderController@store");
Route::get("CashierOrderItemDetails","posOrderController@getOrderItemDetails");
Route::get("getInvoiceFooter/{branch_id}","posOrderController@getInvoiceFooter");
Route::get("get_Print_Order_Number","posOrderController@getPrintNumber");
Route::get("EditPrintOrder","posOrderController@EditPrintOrder");


// vendor controller
Route::get("gelAllItems","vendor_controller@gelAllItems");
Route::get("gelDisableItems","vendor_controller@gelDisableItems");
Route::post("updateStatusToInActive/{itemId}","vendor_controller@updateStatusToInActive");
Route::get("gelEnableItems","vendor_controller@gelEnableItems");
Route::post("updateStatusToActive/{itemId}","vendor_controller@updateStatusToActive");
Route::get("Is_Auto_Print/{vendorID}","vendor_controller@Is_Auto_Print");
Route::get("Edit_auto_print/{vendorID}/{newStatus}","vendor_controller@Edit_auto_print");
Route::get("getEstimation","vendor_controller@getEstimation");
Route::post("updateEstimationTime/{newTime}","vendor_controller@updateEstimationTime");

// order vendor same vendor controller
Route::get("getPendingOrder","vendor_controller@getPendingOrder");
Route::get("getAcceptedOrder","vendor_controller@getAcceptedOrder");
Route::get("getPreparedOrder","vendor_controller@getPreparedOrder");
Route::get("getReadyOrder","vendor_controller@getReadyOrder");
Route::get("getDeliveredOrder","vendor_controller@getDeliveredOrder");
Route::get("getCanceledOrder","vendor_controller@getCanceledOrder");



Route::post("set_order_status", "OrderStatusController@setOrderStatus");

// get slider in home page
Route::get("getSlider","get_main_slider_controller@getSlider");
Route::get("navigateSlider/{type}/{id}","get_main_slider_controller@navigateSlider");
Route::get("getAllPayment","get_all_payment@getAllPayment");

// app settings controller
Route::get("getAppSettings","app_settings_controller@getAppSettings");
Route::post("updateIsAccepting/{isAccept}","app_settings_controller@updateIsAccepting");
Route::get("getIsAcceptOrder","app_settings_controller@getIsAcceptOrder");

Route::get("getCouponByUser/{phone_number}","coupon_controller@getCouponByUser");
Route::post("checkValidCoupon","coupon_controller@checkValidCoupon");
Route::post("use_coupon","coupon_controller@useCoupon");

//Route::get('/Consultants', [ConsultantRequestController::class, 'index']);
Route::post('/Consultants', [ConsultantRequestController::class,'add']);




