<?php

namespace App\Http\Controllers;

use App\Models\Option;
use App\Models\OptionItem;
use App\Models\Person;
use App\Models\Facility;
use Illuminate\Http\Request;

class OptionController extends Controller
{
    public function show($id)
{
    $person = Person::findOrFail($id);
    $facility = $person->people_facilities()->first();
    $options = Option::where('people_id', $id)->get();
    $selectedItems = OptionItem::where('people_id', $id)->pluck('item1', 'item2', 'item3', 'item4', 'item5')->toArray();
    
    $additionalItems = $options->map(function ($option) {
        return [
            'id' => $option->id,
            'title' => $option->title,
            'items' => $option->getItemsAsString(),
        ];
    })->toArray();

    return view('people', compact('person', 'facility', 'options', 'selectedItems', 'additionalItems'));
}

    public function store(Request $request, $people_id, $id)
{
    $request->validate([
        'title' => 'required',
        'facility_id' => 'required|exists:facilities,id',
        'item' => 'required|array|min:1',
        'item.*' => 'required|max:32',
    ], [
        'title.required' => 'タイトルは必須です',
        'facility_id.required' => '施設IDは必須です',
        'item.required' => '少なくとも1つの項目を入力してください',
        'item.*.required' => '記録項目は必須です',
        'item.*.max' => '各項目は32文字以内で入力してください',
    ]);

    $person = Person::findOrFail($people_id);

    $option = new Option();
    $option->title = $request->title;
    $option->people_id = $people_id;
    $option->facility_id = $request->facility_id;
    for ($i = 0; $i < 5; $i++) {
        $option->{"item" . ($i + 1)} = $request->item[$i] ?? null;
    }
    $option->flag = false;

    $option->save();

    // 二重送信防止
    $request->session()->regenerateToken();

    return redirect()->route('show.selected.items', ['people_id' => $people_id, 'id' => $id])
        ->with('message', '記録項目が追加されました。');
}

public function itemstore(Request $request, $facility_id, $id)
{
    $request->validate([
        'title' => 'required',
        'facility_id' => 'required|exists:facilities,id',
        'item' => 'required|array|min:1',
        'item.*' => 'required|max:32',
    ], [
        'title.required' => 'タイトルは必須です',
        'facility_id.required' => '施設IDは必須です',
        'item.required' => '少なくとも1つの項目を入力してください',
        'item.*.required' => '記録項目は必須です',
        'item.*.max' => '各項目は32文字以内で入力してください',
    ]);

    // 特定の人物（$person_id）に関連する施設を取得し、
// その施設に紐づく全ての人を取得する
  // 特定の施設に紐づく全ての人を取得する
  $peopleInFacility = Facility::find($facility_id)
  ->people_facilities() // Facility モデルの people_facilities リレーションを使用
  ->pluck('people_id') // person_id を取得
  ->toArray();

    // 各人に対してオプションを作成
    foreach ($peopleInFacility as $person_id) {
        $option = new Option();
        $option->title = $request->title;
        $option->people_id = $person_id;
        $option->facility_id = $facility_id;
        for ($i = 0; $i < 5; $i++) {
            $option->{"item" . ($i + 1)} = $request->item[$i] ?? null;
        }
        $option->flag = true;
        $option->save();
    }

    // 二重送信防止
    $request->session()->regenerateToken();

    return redirect()->route('show.items', ['id' => $id])
        ->with('message', '記録項目が全ての利用者に追加されました。');
}
        
}


