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

    public function store(Request $request, $people_id)
{
    $request->validate([
         'title' => 'required',
        // 'new_items' => 'array|min:1|max:5',
        // 'new_items.*' => 'required|max:32',
    ]);
    $person = Person::findOrFail($people_id);

    $option = new Option();
    $option->title = $request->title;
    $option->people_id = $people_id;
    for ($i = 0; $i < 5; $i++) {
        $option->{"item" . ($i + 1)} = $request->item[$i] ?? null;
    }
    $option->flag = false;

    $option->save();

    return response()->json(['message' => '記録項目が追加されました。']);
}
}

