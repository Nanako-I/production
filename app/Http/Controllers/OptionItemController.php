<?php

namespace App\Http\Controllers;

use App\Models\Option;
use App\Models\OptionItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Facility;
use App\Models\Person;
use App\Models\Role;
use App\Models\Permission;
use App\Models\Chat;


use Spatie\Permission\Models\Role as SpatieRole;
use App\Enums\RoleType;
use Spatie\Permission\Traits\HasRoles;
use Carbon\Carbon;
use App\Enums\PermissionType;
use App\Enums\RoleType as RoleEnums;
use App\Enums\Role as RoleEnum;

class OptionItemController extends Controller
{
  public function store($people_id, $id, Request $request)
  {
      $request->validate([
        'people_id' => 'required|exists:people,id',
        'option_id' => 'required|exists:options,id',
        'item1' => 'nullable|boolean',
        'item2' => 'nullable|boolean',
        'item3' => 'nullable|boolean',
        'item4' => 'nullable|boolean',
        'item5' => 'nullable|boolean',
        'bikou' => 'nullable|string',
      ]);
  

    OptionItem::create([
      'people_id' => $request->people_id,
      'option_id' => $request->option_id,
      'item1' => $request->has('item1') ? json_encode([$request->item1]) : null,
      'item2' => $request->has('item2') ? json_encode([$request->item2]) : null,
      'item3' => $request->has('item3') ? json_encode([$request->item3]) : null,
      'item4' => $request->has('item4') ? json_encode([$request->item4]) : null,
      'item5' => $request->has('item5') ? json_encode([$request->item5]) : null,
      'bikou' => $request->bikou,
  ]);

  // ログインしているユーザーの情報↓
  $user = auth()->user();

  $user->facility_staffs()->first();

  // facility_staffsメソッドからuserの情報をゲットする↓
  $facilities = $user->facility_staffs()->get();

  $roles = $user->user_roles()->get(); // これでロールが取得できる

  $rolename = $user->getRoleNames(); // ロールの名前を取得
  $isSuperAdmin = $user->hasRole(RoleType::FacilityStaffAdministrator);

  // ロールのIDを取得する場合
  $roleIds = $user->roles->pluck('id');

  $firstFacility = $facilities->first();
  if ($firstFacility) {
      $people = $firstFacility->people_facilities()->get();
  } else {
      $people = []; // まだpeople（利用者が登録されていない時もエラーが出ないようにする）
  }

  foreach ($people as $person) {
      $unreadMessages = Chat::where('people_id', $person->id)
                            ->where('is_read', false)
                            ->where('user_identifier', '!=', $user->id)
                            ->exists();
  
      $person->unreadMessages = $unreadMessages;
      \Log::info("Person {$person->id} unread messages: " . ($unreadMessages ? 'true' : 'false'));
  }

  $selectedItems = [];
  
  // Loop through each person and decode their selected items
  foreach ($people as $person) {
      $selectedItems[$person->id] = json_decode($person->selected_items, true) ?? [];
  }
  $options = Option::whereIn('people_id', $people->pluck('id'))
    ->get(['id', 'people_id', 'title', 'item1', 'item2', 'item3', 'item4', 'item5']);
   
    // 二重送信防止
   $request->session()->regenerateToken();

       return view('people', compact('people', 'selectedItems', 'options'));
    }

//     public function change(Request $request, $people_id, $id)
// {
//     $person = Person::findOrFail($people_id);
//     $optionItem = OptionItem::findOrFail($id); // ここで $optionItem を取得
//     $option = Option::where('id', $optionItem->option_id)->first();

//     return view('optionchange', compact('person', 'optionItem', 'option')); // $optionItem をビューに渡す
// }
public function change(Request $request, $people_id, $id)
{
    $person = Person::findOrFail($people_id);
    $optionItem = OptionItem::findOrFail($id);
    $option = Option::where('id', $optionItem->option_id)->first();

    // Get only the items that have data in the options table
    $validItems = [];
    for ($i = 1; $i <= 5; $i++) {
        $itemKey = "item{$i}";
        if (!empty($option->$itemKey)) {
            $validItems[$itemKey] = [
                'optionData' => $option->$itemKey,
                'itemData' => json_decode($optionItem->$itemKey),
            ];
        }
    }

    return view('optionchange', compact('person', 'optionItem', 'option', 'validItems'));
}
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Food  $food
     * @return \Illuminate\Http\Response
     */
    // public function update(Request $request, OptionItem $option)
    public function update(Request $request, $people_id, $id)
{
    $optionItem = OptionItem::findOrFail($id);
    $option = Option::where('id', $optionItem->option_id)->first();

    // Validate the request
    $request->validate([
        'item1' => 'nullable|boolean',
        'item2' => 'nullable|boolean',
        'item3' => 'nullable|boolean',
        'item4' => 'nullable|boolean',
        'item5' => 'nullable|boolean',
        'bikou' => 'nullable|string',
    ]);

    // Update the OptionItem
    $optionItem->update([
        'item1' => $request->has('item1') ? json_encode([$request->item1]) : null,
        'item2' => $request->has('item2') ? json_encode([$request->item2]) : null,
        'item3' => $request->has('item3') ? json_encode([$request->item3]) : null,
        'item4' => $request->has('item4') ? json_encode([$request->item4]) : null,
        'item5' => $request->has('item5') ? json_encode([$request->item5]) : null,
        'bikou' => $request->bikou,
    ]);

    $person = Person::findOrFail($people_id);
      
           // ログインしているユーザーの情報↓
       $user = auth()->user();
  
       $user->facility_staffs()->first();
  
       // facility_staffsメソッドからuserの情報をゲットする↓
       $facilities = $user->facility_staffs()->get();
  
       $roles = $user->user_roles()->get(); // これでロールが取得できる
  
       $rolename = $user->getRoleNames(); // ロールの名前を取得
       $isSuperAdmin = $user->hasRole(RoleType::FacilityStaffAdministrator);
  
       // ロールのIDを取得する場合
       $roleIds = $user->roles->pluck('id');
  
       $firstFacility = $facilities->first();
       if ($firstFacility) {
           $people = $firstFacility->people_facilities()->get();
       } else {
           $people = []; // まだpeople（利用者が登録されていない時もエラーが出ないようにする）
       }
  
       foreach ($people as $person) {
           $unreadMessages = Chat::where('people_id', $person->id)
                                 ->where('is_read', false)
                                 ->where('user_identifier', '!=', $user->id)
                                 ->exists();
       
           $person->unreadMessages = $unreadMessages;
           \Log::info("Person {$person->id} unread messages: " . ($unreadMessages ? 'true' : 'false'));
       }
  
       $selectedItems = [];
       
       // Loop through each person and decode their selected items
       foreach ($people as $person) {
           $selectedItems[$person->id] = json_decode($person->selected_items, true) ?? [];
       }
   
       $today = \Carbon\Carbon::now()->toDateString();

        foreach ($people as $person) {
            $person->todayOptionItems = OptionItem::where('people_id', $person->id)
                ->whereDate('created_at', $today)
                ->get();
        }
    
       // optionsテーブルから必要なデータを取得
        $options = Option::whereIn('people_id', $people->pluck('id'))
          ->get(['id', 'people_id', 'title', 'item1', 'item2', 'item3', 'item4', 'item5']);
        $personOptions = [];
        foreach ($people as $person) {
        $personOptions[$person->id] = Option::where('people_id', $person->id)
            ->where('flag', 1)
            ->get();
    }

    //    return view('people', compact('people', 'selectedItems', 'options'));
    return view('people', compact('people', 'selectedItems', 'options', 'personOptions', 'optionItem', 'option'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Food  $food
     * @return \Illuminate\Http\Response
     */
    
       
        public function destroy($id) 
        {
            $option = Option::find($id);
        
            if ($option) {
        
              $option->delete();
            }
        
            return redirect()->route('people.index')->with('success', '削除が完了しました。');
        }
    
}