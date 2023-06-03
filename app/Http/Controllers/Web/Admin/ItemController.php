<?php

namespace App\Http\Controllers\Web\Admin;

use App\Helpers\Dialog\Web\Dialog;
use App\Helpers\Dialog\Web\Types\DangerMessage;
use App\Helpers\Dialog\Web\Types\SuccessMessage;
use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Category;
use App\Models\Item;
use App\Repositories\ItemRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ItemController extends Controller
{

    public ItemRepository $repository;
    public function __construct(ItemRepository $repository)
    {
        $this->repository = $repository;
    }

    public function index(){
        $data['items'] = Item::all();
        return view("admin.items.index", $data);
    }

    public function create(){
        $data['categories'] = Category::all();
        $data['branches'] = Branch::all();
        return view("admin.items.create", $data);
    }

    public function store(Request $request){
        $valid = Validator::make($request->all(), $this->repository->rules("create", $request));
        if ($valid->fails())
            return redirect()->route("admin.items.create")->withInput($request->all())->withErrors($valid->errors()->messages());
        $this->repository->save($request);

        $message = (new SuccessMessage())->title(__('Created Successfully'))
            ->body(__("The Item Has Been Created Successfully"));
        Dialog::flashing($message);
        return redirect()->route("admin.items.index");
    }

    public function edit(Request $request){
        $data['item'] = Item::find($request->id);
        $data['categories'] = Category::all();
        $data['branches'] = Branch::all();
        return view("admin.items.edit", $data);
    }

    public function update(Request $request){
        $valid = Validator::make($request->all(), $this->repository->rules("edit", $request));
        if ($valid->fails())
            return redirect()->route("admin.items.edit", $request->id)->withInput($request->all())->withErrors($valid->errors()->messages());
        $this->repository->update($request);

        $message = (new SuccessMessage())->title(__('Updated Successfully'))
            ->body(__("The Item Has Been Updated Successfully"));
        Dialog::flashing($message);
        return redirect()->route("admin.items.index");
    }

    public function destroy(Request $request){
        $item = Item::find($request->id);
        $item->removeAllFiles();
        $item->delete();

        $message = (new DangerMessage())->title(__('Deleted Successfully'))
            ->body(__("The Item Has Been Deleted Successfully"));
        Dialog::flashing($message);
        return redirect()->route("admin.items.index");
    }
}
