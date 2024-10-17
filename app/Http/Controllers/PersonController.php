<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Facility;
use App\Models\Person;
use App\Models\Role;
use App\Models\Permission;
use App\Models\Chat;
use App\Models\Option;
use App\Models\OptionItem;
use App\Models\ScheduledVisit;

use Spatie\Permission\Models\Role as SpatieRole;
use App\Enums\RoleType;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Enums\PermissionType;
use App\Enums\RoleType as RoleEnums;
use App\Enums\Role as RoleEnum;

class PersonController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    
     public function index(User $user)
     {
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
        // dd($options);

        // 各利用者の訪問データを取得して送迎の要否を確認
        // foreach ($people as $person) {
        //     $scheduledVisit = ScheduledVisit::where('people_id', $person->id)->first();
        //     $person->transport = $scheduledVisit ? $scheduledVisit->transport : '未登録';
        // }

    return view('people', compact('people', 'selectedItems', 'options', 'personOptions'));
    }
     
  

    public function show($id)
{
    $person = Person::findOrFail($id);
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


    // options テーブルから追加項目を取得
    $options = Option::whereIn('people_id', $people->pluck('id'))
        ->get(['title', 'item1', 'item2', 'item3', 'item4', 'item5']);
        $selectedItems = [];
    
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

    // foreach ($options as $option) {
    //     $items = collect([$option->item1, $option->item2, $option->item3, $option->item4, $option->item5])
    //         ->filter()
    //         ->implode(', '); // NULL 以外の値をカンマ区切りで結合

    //     if ($items) {
    //         $additionalItems[] = [
    //             'title' => $option->title,
    //             'items' => $items,
    //         ];
    //     }
    // }

    // return view('select_item', compact('person', 'selectedItems', 'personOptions', 'id'));
    return view('people', compact('people', 'selectedItems', 'personOptions', 'options', 'id'));
}

    /**
     * Show the form for creating a new resource.
     *
 
     */
    // 利用者全員の一覧（peoplelistビュー）
    public function list()
{
    $user = auth()->user();
    $facilities = $user->facility_staffs()->get();
    $firstFacility = $facilities->first();

    // Retrieve people associated with the first facility
    if ($firstFacility) {
        $people = $firstFacility->people_facilities()->get();
    } else {
        $people = []; // Handle case when no people are registered
    }

    return view('peoplelist', compact('people'));

    public function create()
    {
        return view('peopleregister');
    }

    public function store(Request $request)
    {
        $storeData = $request->validate([
            
            'date_of_birth' => 'required|max:255',
            'jukyuusha_number' => 'required|digits:10',
        ]);
        
        $user = auth()->user();
        $facilities = $user->facility_staffs()->get();
        $firstFacility = $facilities->first();
    // dd($firstFacility);
        if ($firstFacility) {
            
            // 名前と生年月日が一致する利用者を検索
        $existingPersonByNameAndDob = $firstFacility->people_facilities()
            ->where('last_name', $request->last_name)
            ->where('first_name', $request->first_name)
            ->where('date_of_birth', $request->date_of_birth)
            ->first();

        // 受給者番号が一致する利用者を検索
        $existingPersonByJukyuushaNumber = $firstFacility->people_facilities()
            ->where('jukyuusha_number', $request->jukyuusha_number)
            ->first();
            // 名前と生年月日が一致する場合
        if ($existingPersonByNameAndDob) {
            return back()->withInput($request->all())
                         ->withErrors(['duplicate_name_dob' => '同じ名前と生年月日の人がすでに存在します。']);
        }

        // 受給者番号が一致する場合
        if ($existingPersonByJukyuushaNumber) {
            return back()->withInput($request->all())
                         ->withErrors(['duplicate_jukyuusha_number' => '同じ受給者番号の人がすでに存在します。']);
        }
    }
   
        

        $directory = 'public/sample';
        $filename = null;
        $filepath = null;

        if ($request->hasFile('filename')) {
            $request->validate([
                'filename' => 'image|max:2048',
            ]);
            $filename = uniqid() . '.' . $request->file('filename')->getClientOriginalExtension();
            $filename = $request->file('filename')->getClientOriginalName();
            $request->file('filename')->storeAs($directory, $filename);
            $filepath = $directory . '/' . $filename;
        }

        $newpeople = Person::create([
            'last_name' => $request->last_name,
            'first_name' => $request->first_name,
            'last_name_kana' => $request->last_name_kana,
            'first_name_kana' => $request->first_name_kana,
            'date_of_birth' => $request->date_of_birth,
            'gender' => $request->gender,
            'jukyuusha_number' => $request->jukyuusha_number,
            'medical_care' => $request->medical_care,
            'filename' => $filename,
            'path' => $filepath,

        ]);
        


        // 現在ログインしているユーザーが属する施設にpeople（利用者）を紐づける↓
        // syncWithoutDetaching＝完全重複以外は、重複OK
        $newpeople->people_facilities()->syncWithoutDetaching($firstFacility->id);

        if ($firstFacility) {
            $people = $firstFacility->people_facilities()->get();
        } else {
            $people = []; // まだpeople（利用者が登録されていない時もエラーが出ないようにする）
        }

        // 二重送信防止
        $request->session()->regenerateToken();
        return view('people', compact('people'));
    }


    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Person  $person
     * @return \Illuminate\Http\Response
     */
   


    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Person  $person
     * @return \Illuminate\Http\Response
     */

    // 利用者情報更新画面の表示↓
    public function edit($id)
{
    $person = Person::findOrFail($id);
    return view('peopleedit', compact('person'));
}

    
    //  利用者情報更新
    public function update(Request $request, $id)
    {
        $request->validate([
            'date_of_birth' => 'required|date',
            'jukyuusha_number' => 'required|digits:10',
        ]);

        $user = auth()->user();
        $facilities = $user->facility_staffs()->get();
        $firstFacility = $facilities->first();
    // dd($firstFacility);
        if ($firstFacility) {
            
            // 名前と生年月日が一致する利用者を検索
        $existingPersonByNameAndDob = $firstFacility->people_facilities()
            ->where('last_name', $request->last_name)
            ->where('first_name', $request->first_name)
            ->where('date_of_birth', $request->date_of_birth)
            ->first();

        // 受給者番号が一致する利用者を検索
        $existingPersonByJukyuushaNumber = $firstFacility->people_facilities()
            ->where('jukyuusha_number', $request->jukyuusha_number)
            ->first();
            // 名前と生年月日が一致する場合
        if ($existingPersonByNameAndDob) {
            return back()->withInput($request->all())
                         ->withErrors(['duplicate_name_dob' => '同じ名前と生年月日の人がすでに存在します。']);
        }

        // 受給者番号が一致する場合
        if ($existingPersonByJukyuushaNumber) {
            return back()->withInput($request->all())
                         ->withErrors(['duplicate_jukyuusha_number' => '同じ受給者番号の人がすでに存在します。']);
        }
    }

        $person = Person::findOrFail($id);

        //   画像保存
        $directory = 'public/sample';
        $filename = $person->filename; // 更新しない場合既存のファイル名を保持
        $filepath = $person->path; // 既存のパスを保持
    
        if ($request->hasFile('filename')) {
            $request->validate([
                'filename' => 'image|max:2048',
            ]);

        // 古い画像ファイルが存在する場合は削除
        if ($person->path && \Storage::exists($person->path)) {
            \Storage::delete($person->path);
        }   
            // 同じファイル名でも上書きされないようユニークなIDをファイル名に追加
            $uniqueId = uniqid();
            $originalFilename = $request->file('filename')->getClientOriginalName();
            $filename = $uniqueId . '_' . $originalFilename;
            $request->file('filename')->storeAs($directory, $filename);
            $filepath = $directory . '/' . $filename;
            
        }
        // バリデーションした内容を保存する↓
        
        

        $person->update([
            'last_name' => $request->last_name,
            'first_name' => $request->first_name,
            'last_name_kana' => $request->last_name_kana,
            'first_name_kana' => $request->first_name_kana,
            'date_of_birth' => $request->date_of_birth,
            'gender' => $request->gender,
            'jukyuusha_number' => $request->jukyuusha_number,
            'medical_care' => $request->medical_care,
            'filename' => $filename,
            'path' => $filepath,
        ]);
        $people = Person::all();
        // 二重送信防止
        $request->session()->regenerateToken();

        // ログインしているユーザーの情報↓
        $user = auth()->user();

        $user->facility_staffs()->first();

        // facility_staffsメソッドからuserの情報をゲットする↓
        $facilities = $user->facility_staffs()->get();

        // dd($facilities);
        $roles = $user->user_roles()->get(); // これでロールが取得できる
        //   dd($roles);

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

        return view('people', compact('people', 'selectedItems'))->with('success', '利用者情報が更新されました。');
        }

    // 登録項目の選択↓
    public function showSelectedItems($people_id, $id)
{
    $person = Person::findOrFail($id);
    $selectedItems = json_decode($person->selected_items, true) ?? [];
    
    // options テーブルから追加項目を取得
    $options = Option::where('people_id', $people_id)
        ->get(['title', 'item1', 'item2', 'item3', 'item4', 'item5']);
    
    $additionalItems = [];

    foreach ($options as $option) {
        $items = collect([$option->item1, $option->item2, $option->item3, $option->item4, $option->item5])
            ->filter()
            ->implode(', '); // NULL 以外の値をカンマ区切りで結合

        if ($items) {
            $additionalItems[] = [
                'title' => $option->title,
                'items' => $items,
            ];
        }
    }

    return view('select_item', compact('person', 'selectedItems', 'additionalItems', 'id'));
}



public function updateSelectedItems(Request $request, $id)  
{
    $person = Person::findOrFail($id);
    $selectedItems = $request->input('selected_items', []); // チェックされた項目を取得
    // dd($selectedItems);
    $selectedAdditionalItems = $request->input('selected_additional_items', []); // チェックされた追加項目を取得
    // dd($selectedAdditionalItems);
    
    $additionalItems = DB::table('options')->where('people_id', $id)->get(['id', 'title']);

    // dd($additionalItems);
    // selected_items と selected_additional_items をマージして JSON エンコード
    // チェックされた追加項目のみを取得する
  
    $filteredAdditionalItems = [];
    foreach ($additionalItems as $item) {
        if (in_array($item->id, $selectedAdditionalItems)) {
            $filteredAdditionalItems[] = $item->id;
        }
    }

    // selected_items と フィルタリングされた selected_additional_items をマージ
    $combinedSelectedItems = array_merge($selectedItems, $filteredAdditionalItems);
    // dd($combinedSelectedItems);
    // JSON 形式で保存
    $person->selected_items = json_encode(array_unique($combinedSelectedItems), JSON_UNESCAPED_UNICODE); 
    $person->save();
    
    
    // $selectedItems に追加項目を追加または削除
    $selectedItemIds = array_merge(
        $selectedItems,
        $selectedAdditionalItems
    );

    // JSON 形式で保存
    $person->selected_items = json_encode(array_unique($selectedItemIds), JSON_UNESCAPED_UNICODE);
    $person->save();

    // オプションを取得し、フラグを更新
    $options = Option::where('people_id', $id)->get();
    foreach ($options as $option) {
        $option->flag = in_array($option->id, $selectedItemIds) ? 1 : 0;
        $option->save();
    }

    return redirect()->route('people.index', $person->id)->with('success', '記録項目が更新されました。');
}



// 新しく項目を追加するメソッド
private function getAdditionalItems($id)
{
    // ここで追加の項目を取得するロジックを実装します
    // 例えば、データベースから取得したり、他のソースから情報を集めたりします

    // 仮の実装例：
    $person = Person::findOrFail($id);
    $options = Option::where('people_id', $id)->get();

    $additionalItems = [];
    foreach ($options as $option) {
        $items = [];
        for ($i = 1; $i <= 5; $i++) {
            $itemKey = "item{$i}";
            if (!is_null($option->$itemKey) && $option->$itemKey !== '') {
                $items[] = $option->$itemKey;
            }
        }
        
        $additionalItems[] = [
            'title' => $option->title,
            'items' => implode(', ', $items)
        ];
    }

    return $additionalItems;
}




    public function uploadForm()
    {
        // return view('people');変更↓
        return view('peopleregister');
    }





    public function __invoke()
    {
        return view('person');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Person  $person
     * @return \Illuminate\Http\Response
     */
    public function destroy(Person $person)
    {

        $person->delete();
        return redirect('/people');
    }
}