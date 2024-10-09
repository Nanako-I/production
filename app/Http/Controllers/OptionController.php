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

    public function store(Request $request, $people_id,$id)
{
    $request->validate([
        'title' => 'required',
        'item' => 'required|array|min:1',
        'item.*' => 'required|max:32',
    ], [
        'title.required' => 'タイトルは必須です',
        'item.required' => '少なくとも1つの項目を入力してください',
        'item.*.required' => '記録項目は必須です',
        'item.*.max' => '各項目は32文字以内で入力してください',
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
    $selectedItems = json_decode($person->selected_items, true) ?? [];
   // 二重送信防止
   $request->session()->regenerateToken();

    return redirect()->route('show.selected.items', ['people_id' => $people_id, 'id' => $id]);
        // ->with('message', '記録項目が追加されました。');
}


        
}


