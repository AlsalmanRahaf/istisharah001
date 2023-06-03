<?php

namespace App\Repositories;

use App\Models\ObjectDetails;
use Illuminate\Http\Request;

class ObjectRepository
{

    public function getById($id){
        return ObjectDetails::findOrFail($id);
    }

    public function delete($object){
        $object->delete();
    }

    public function save(Request $request){
        $object = new ObjectDetails();
        $object->object_id = 1;
        $object->time_slot_type_id = $request->time_slot_type;
        $object->description = $request->description;
        if($object->save())
            return $object->id;
        else
            return false;
    }

    public function update(Request $request){
        $object = ObjectDetails::findOrFail($request->id);
        $object->time_slot_type_id = $request->time_slot_type;
        $object->description = $request->object_description;
        $object->save();
    }
}
