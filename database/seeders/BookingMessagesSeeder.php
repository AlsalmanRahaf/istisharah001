<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BookingMessagesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $types = [
            ['remark' => 1, 'lang' => "en", 'msg' => "This object doesn't has time slots on this day"],
            ['remark' => 1, 'lang' => "ar", 'msg' => "لا يوجد مواعيد لهذا العنصر في هذا اليوم"],
            ['remark' => 2, 'lang' => "en", 'msg' => "This object not available on this date"],
            ['remark' => 2, 'lang' => "ar", 'msg' => "العنصر غير متاح في هذا التاريخ"],
            ['remark' => 3, 'lang' => "en", 'msg' => "This slot is booked"],
            ['remark' => 3, 'lang' => "ar", 'msg' => "هذا الموعد محجوز"],
            ['remark' => 4, 'lang' => "en", 'msg' => "Booking added successfully"],
            ['remark' => 4, 'lang' => "ar", 'msg' => "تم إضافة الحجز بنجاح"],
            ['remark' => 5, 'lang' => "en", 'msg' => "No bookings found"],
            ['remark' => 5, 'lang' => "ar", 'msg' => "لا يوجد حجوزات"],
            ['remark' => 6, 'lang' => "en", 'msg' => "The meeting will be available in five minutes before the appointment"],
            ['remark' => 6, 'lang' => "ar", 'msg' => "سيكون الاجتماع متاحًا قبل الموعد بخمس دقائق"]
        ];
        DB::table('booking_messages')->insert($types);
    }
}
