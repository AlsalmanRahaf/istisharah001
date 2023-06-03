<?php
namespace App\Traits;
use Illuminate\Support\Facades\Session;

trait Notifications{

    public $notifications_en_text = [
        "reject_order" => [
            "title" => "Rejected appointment",
            "body" => "your order has been rejected for some reasons , please contact with order management team"
        ],
        "new_booking" => [
            "title" => "New appointment",
            "body" => "You have new appointment and its type is %s on %s from %s to %s with %s"
        ],
        "booking_reminder" => [
            "title" => "Appointment reminder",
            "body" => "You have a appointment and its type is %s on %s from %s to %s with %s"
        ],
        "cancel_booking" => [
            "title" => "Cancelled appointment",
            "body" => "Your appointment has been cancelled by %s and its type is %s on %s from %s to %s"
        ],
        "UnCancelled_booking" => [
            "title" => "Returned appointment",
            "body" => "Your appointment has been returned by %s and its type is %s on %s from %s to %s"
        ],
        "Edit_booking" => [
            "title" => "Modified appointment",
            "body" => "Your appointment has been modified to be on %s from %s to %s with %s"
        ],
        "Edit_booking_doctor" => [
            "title" => "Modified appointment",
            "body" => "Your appointment has been modified to be on %s from %s to %s"
        ],
        "transfer_booking" => [
            "title" => "Transferred appointment",
            "body" => " appointment has been transferred to you on %s from %s to %s with %s"
        ],
        "get_booking" => [
            "title" => "Transferred appointment",
            "body" => "Your appointment has been transferred to another doctor on %s from %s to %s with %s"
        ],
        "pending_order" => [
            "title" => "Your Order ID #%d Has Been Received Successfully",
            "body" => "Your order is now under review"
        ],
        "accept_order" => [
            "title" => "Your Order ID is #%d Has Been Accepted",
            "body" => "the order will be preparing soon .."
        ],
        "prepare_order" => [
            "title" => "Your Order ID #%d Has Been Prepared",
            "body" => "the order will be ready soon .."
        ],
        "ready_order" => [
            "title" => "Your Order ID #%d Is Ready Now",
            "body" => "the order will be delivered to you soon .."
        ],
        "delieverd_order" => [
            "title" => "Your Order ID #%d Has Been Delieverd",
            "body" => "thank your for your choice of golden meal restaurant"
        ],
        "reject_order_store" => [
            "title" => "Your Order ID #%d Has Been Rejected",
            "body" => "your order has been rejected for some reasons , please contact with order management team"
        ],
    ];
    public $notifications_ar_text = [
        "reject_order" => [
            "title" => "تم رفض طلبك رقم %d",
            "body" => "لسبب ما ، يرجى التواصل مع فريق إدارة الطلبات"

        ],
        "new_booking" => [
            "title" => "حجز جديد",
            "body" => "لديك حجز جديد ونوعه %s في تاريخ %s من %s إلى %s مع %s"
        ],
        "booking_reminder" => [
            "title" => "تذكير الحجز",
            "body" => "لديك حجز ونوعه %s في تاريخ %s من %s إلى %s مع %s"
        ],
        "cancel_booking" => [
            "title" => "تم إلغاء الحجز",
            "body" => "لقد تم إلغاء حجز عن طريق %s ونوعه %s في تاريخ %s من %s إلى %s"
        ],
        "UnCancelled_booking" => [
            "title" => "تم استرجاع حجزك",
            "body" => "لقد تم استرجاع حجزك عن طريق %s ونوعها %s من تاريخ %s إلى %s مع %s"
        ],
        "Edit_booking" => [
            "title" => "تم التعديل  على حجزك",
            "body" => "لقد تم تعديل موعدك ليصبح في تاريخ %s من %s الى %s للمريض %s"
        ],
        "Edit_booking_doctor" => [
            "title" => "تم التعديل  على حجزك",
            "body" => "لقد تم تعديل موعدك ليصبح في تاريخ %s من %s الى %s"
        ],
        "transfer_booking" => [
            "title" => "موعد منقول",
            "body" => "تم نقل موعد اليك في تاريخ %s من %s الى %s مع %s"

        ],
        "get_booking" => [
            "title" => "موعد منقول",
            "body" => "تم نقل موعدك  في تاريخ %s من %s الى %s مع %s"
        ],
        "pending_order" => [
            "title" => "تم إستلام طلبك رقم %d بنجاح",
            "body" => "طلبك قيد المراجعة الآن"
        ],
        "accept_order" => [
            "title" => "تمت الموافقة على طلبك رقم %d",
            "body" => "سيتم تحضيره في أقرب وقت"
        ],
        "prepare_order" => [
            "title" => "تم تحضير طلبك رقم %d",
            "body" => "سيتم تجهيزه في أقرب وقت"
        ],
        "ready_order" => [
            "title" => "طلبك رقم %d جاهز الآن",
            "body" => "سيتم توصيله إليك لاحقا"
        ],
        "delieverd_order" => [
            "title" => "تم توصيل طلبك رقم %d",
            "body" => "شكرا لأختيارك مطعم الوجبة الذهبية"
        ],
        "reject_order_store" => [
            "title" => "تم رفض طلبك رقم %d",
            "body" => "لسبب ما ، يرجى التواصل مع فريق إدارة الطلبات"
        ],
    ];

    public function getNotificationTextDetails($key, array $params = []){
        if(array_key_exists($key, $this->notifications_en_text)){
            $notification = $this->getOrderNotificationDetails($key, $params);
            return [
                "title" => [
                    "en" => $notification["title"]["en"],
                    "ar" => $notification["title"]["ar"],
                ],
                "body" => [
                    "en" => $notification["body"]["en"],
                    "ar" => $notification["body"]["ar"],

                ],
            ];
        }
        return [];
    }

    public function getOrderNotificationDetails($key, $params = []){
        $notification = [];
        if(empty($params)){
            $notification["title"]["en"] = $this->notifications_en_text[$key]["title"];
            $notification["title"]["ar"] = $this->notifications_ar_text[$key]["title"];
            $notification["body"]["en"] = $this->notifications_en_text[$key]["body"];
            $notification["body"]["ar"] = $this->notifications_ar_text[$key]["body"];
        }elseif (count($params) == 1) {
            $notification["title"]["en"] = sprintf($this->notifications_en_text[$key]["title"], $params["order_number"]);
            $notification["title"]["ar"] = sprintf($this->notifications_ar_text[$key]["title"], $params["order_number"]);
            $notification["body"]["en"] = sprintf($this->notifications_en_text[$key]["body"], $params["order_number"]);
            $notification["body"]["ar"] = sprintf($this->notifications_ar_text[$key]["body"], $params["order_number"]);
        }elseif($key == "new_booking" || $key == "booking_reminder"){
            if($params["type"]){
                $type_en = "online";
                $type_ar = "عبر الانترنت";
            }else{
                $type_en = "direct";
                $type_ar = "وجاهي";
            }
            $notification["title"]["en"] = $this->notifications_en_text[$key]["title"];
            $notification["title"]["ar"] = $this->notifications_ar_text[$key]["title"];
            $notification["body"]["en"] = sprintf($this->notifications_en_text[$key]["body"], $type_en, $params["date"], $params["from"], $params["to"], $params["name"]);
            $notification["body"]["ar"] = sprintf($this->notifications_ar_text[$key]["body"], $type_ar, $params["date"], $params["from"], $params["to"], $params["name"]);
        }elseif($key == "cancel_booking" || $key == "UnCancelled_booking" ){
            if($params["type"]){
                $type_en = "online";
                $type_ar = "عبر الانترنت";
            }else{
                $type_en = "direct";
                $type_ar = "وجاهي";
            }
            $notification["title"]["en"] = $this->notifications_en_text[$key]["title"];
            $notification["title"]["ar"] = $this->notifications_ar_text[$key]["title"];
            $notification["body"]["en"] = sprintf($this->notifications_en_text[$key]["body"], $params["name"], $type_en, $params["date"], $params["from"], $params["to"]);
            $notification["body"]["ar"] = sprintf($this->notifications_ar_text[$key]["body"], $params["name"], $type_ar, $params["date"], $params["from"], $params["to"]);
        }elseif($key == "Edit_booking"){
            $notification["title"]["en"] = $this->notifications_en_text[$key]["title"];
            $notification["title"]["ar"] = $this->notifications_ar_text[$key]["title"];
            $notification["body"]["en"] = sprintf($this->notifications_en_text[$key]["body"], $params["date"], $params["from"], $params["to"],$params["name"]);
            $notification["body"]["ar"] = sprintf($this->notifications_ar_text[$key]["body"], $params["date"], $params["from"], $params["to"],$params["name"]);
        }elseif($key == "Edit_booking_doctor"){
            $notification["title"]["en"] = $this->notifications_en_text[$key]["title"];
            $notification["title"]["ar"] = $this->notifications_ar_text[$key]["title"];
            $notification["body"]["en"] = sprintf($this->notifications_en_text[$key]["body"], $params["date"], $params["from"], $params["to"]);
            $notification["body"]["ar"] = sprintf($this->notifications_ar_text[$key]["body"], $params["date"], $params["from"], $params["to"]);
        } elseif($key == "transfer_booking"){
            $notification["title"]["en"] = $this->notifications_en_text[$key]["title"];
            $notification["title"]["ar"] = $this->notifications_ar_text[$key]["title"];
            $notification["body"]["en"] = sprintf($this->notifications_en_text[$key]["body"], $params["date"], $params["from"], $params["to"],$params["patient_name"]);
            $notification["body"]["ar"] = sprintf($this->notifications_ar_text[$key]["body"], $params["date"], $params["from"], $params["to"],$params["patient_name"]);
        }elseif ($key == "get_booking"){
            $notification["title"]["en"] = $this->notifications_en_text[$key]["title"];
            $notification["title"]["ar"] = $this->notifications_ar_text[$key]["title"];
            $notification["body"]["en"] = sprintf($this->notifications_en_text[$key]["body"], $params["date"], $params["from"], $params["to"],$params["patient_name"]);
            $notification["body"]["ar"] = sprintf($this->notifications_ar_text[$key]["body"], $params["date"], $params["from"], $params["to"],$params["patient_name"]);
        }
        return $notification;

    }
}
