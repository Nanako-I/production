<?php

namespace App\Http\Controllers;

use App\Models\Option;
use App\Models\OptionItem;
use App\Models\Person;
use Illuminate\Http\Request;

class OptionController extends Controller
{
    public function show($id)
    {
        $person = Person::findOrFail($id);
        $options = Option::where('people_id', $id)->get();
        $selectedItems = OptionItem::where('people_id', $id)->pluck('item1', 'item2', 'item3', 'item4', 'item5')->toArray();

        return view('people', compact('person', 'options', 'selectedItems'));
    }

    public function store(Request $request)
{
    $request->validate([
        'new_item_title' => 'required|max:32',
        'new_items' => 'array|min:1|max:5',
        'new_items.*' => 'required|max:32',
    ]);

    $option = new Option();
    $option->title = $request->new_item_title;
    $option->people_id = auth()->user()->id;
    for ($i = 0; $i < 5; $i++) {
        $option->{"item" . ($i + 1)} = $request->new_items[$i] ?? null;
    }
    $option->flag = false;

    $option->save();

    return response()->json(['message' => '記録項目が追加されました。']);
}
}

