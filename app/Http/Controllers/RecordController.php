<?php

namespace App\Http\Controllers;

use App\Models\Time;
use App\Models\Temperature;
use App\Models\Bloodpressure;
use App\Models\Toilet;
use App\Models\Food;
use App\Models\Water;
use App\Models\Medicine;
use App\Models\Kyuuin;
use App\Models\Tube;
use App\Models\Hossa;
use App\Models\Speech;
use App\Models\Activity;
use App\Models\Training;
use App\Models\Lifestyle;
use App\Models\Creative;
use App\Models\Person;
use App\Models\Record;
use App\Models\Option;
use App\Models\OptionItem;

use Illuminate\Http\Request;
use Carbon\Carbon;

class RecordController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
   public function create(Request $request)
{
    $person = Person::findOrFail($request->people_id);
    return redirect()->route('recordedit', ['people_id' => $person->id]);
}

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    // 職員側の連絡帳↓
    public function show(Request $request, $people_id)
{
   
    // $person = Person::findOrFail($people_id);
    $person = Person::with(['foods', 'temperatures', 'toilets', 'waters'])->findOrFail($people_id);
    $today = \Carbon\Carbon::now()->toDateString();
    $selectedDate = $request->input('selected_date', \Carbon\Carbon::now()->toDateString());
    $selectedDateStart = \Carbon\Carbon::parse($selectedDate)->startOfDay();    
    $selectedDateEnd = \Carbon\Carbon::parse($selectedDate)->endOfDay();
    
     // 選択された日付に該当する記録をすべて取得
     $records = Record::where('person_id', $people_id)
     ->whereBetween('kiroku_date', [$selectedDateStart, $selectedDateEnd])
     ->get();

    // 各記録に対して押印情報を取得
    $stamps = [];
    foreach ($records as $record) {
     // 各記録ごとに押印情報を取得
     $stamp = Record::where('id', $record->id)->first();
     $stamps[$record->id] = $stamp;
 }

    $timesOnSelectedDate = $person->times ? $person->times->whereBetween('created_at', [$selectedDateStart, $selectedDateEnd]) : collect();
    $foodsOnSelectedDate = $person->foods->whereBetween('created_at', [$selectedDateStart, $selectedDateEnd]);
    $watersOnSelectedDate = $person->waters->whereBetween('created_at', [$selectedDateStart, $selectedDateEnd]);
    $medicinesOnSelectedDate = $person->medicines->whereBetween('created_at', [$selectedDateStart, $selectedDateEnd]);
    $tubesOnSelectedDate = $person->tubes->whereBetween('created_at', [$selectedDateStart, $selectedDateEnd]);
    $temperaturesOnSelectedDate = $person->temperatures ? $person->temperatures->whereBetween('created_at', [$selectedDateStart, $selectedDateEnd]) : collect();
    $bloodpressuresOnSelectedDate = $person->bloodpressures->whereBetween('created_at', [$selectedDateStart, $selectedDateEnd]);
    $toiletsOnSelectedDate = $person->toilets->whereBetween('created_at', [$selectedDateStart, $selectedDateEnd]);
    $kyuuinsOnSelectedDate = $person->kyuuins ? $person->kyuuins->whereBetween('created_at', [$selectedDateStart, $selectedDateEnd]) : collect();
    $hossasOnSelectedDate = $person->hossas ? $person->hossas->whereBetween('created_at', [$selectedDateStart, $selectedDateEnd]) : collect();
    $speechesOnSelectedDate = $person->speeches ? $person->speeches->whereBetween('created_at', [$selectedDateStart, $selectedDateEnd]) : collect();

    // 選択された日付のオプションデータを取得
    // $optionsOnSelectedDate = $person->option_items ? $person->option_items->whereBetween('created_at', [$selectedDateStart, $selectedDateEnd]) : collect();
    $lastOptions = OptionItem::where('people_id', $people_id)
        ->whereBetween('created_at', [$selectedDateStart, $selectedDateEnd])
        ->latest()
        ->first();
    // 対応するOptionモデルのデータを取得
    $correspondingOption = null;
    if ($lastOptions) {
    $correspondingOption = Option::where('id', $lastOptions->option_id)->first();
    }

    // hanamaruの項目↓
    $lastTime = Time::where('people_id', $people_id)
    ->whereDate('created_at', $selectedDate)
    ->latest()
    ->first();

    $lastMorningActivity = Speech::where('people_id', $people_id)
        ->whereDate('created_at', $selectedDate)
        ->whereNotNull('morning_activity')
        ->latest()
        ->first();

    $lastAfternoonActivity = Speech::where('people_id', $people_id)
    ->whereDate('created_at', $selectedDate)
    ->whereNotNull('afternoon_activity')
    ->latest()
    ->first();
    
    $lastActivity = Activity::where('people_id', $people_id)
        ->whereDate('created_at', $selectedDate)
        ->latest()
        ->first();
        
    $lastTraining = Training::where('people_id', $people_id)
        ->whereDate('created_at', $selectedDate)
        ->latest()
        ->first();
        
    $lastLifestyle = Lifestyle::where('people_id', $people_id)
        ->whereDate('created_at', $selectedDate)
        ->latest()
        ->first();
        
    $lastCreative = Creative::where('people_id', $people_id)
        ->whereDate('created_at', $selectedDate)
        ->latest()
        ->first();    

    return view('recordedit', compact('person', 'selectedDate', 'records', 'stamps','timesOnSelectedDate','foodsOnSelectedDate',  'watersOnSelectedDate' , 'medicinesOnSelectedDate', 'tubesOnSelectedDate',  'temperaturesOnSelectedDate', 'bloodpressuresOnSelectedDate','toiletsOnSelectedDate','kyuuinsOnSelectedDate', 'hossasOnSelectedDate', 'speechesOnSelectedDate' , 'lastTime', 'lastMorningActivity', 'lastAfternoonActivity', 'lastActivity', 'lastTraining', 'lastLifestyle', 'lastCreative','lastOptions', 'correspondingOption'));
}

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Food  $food
     * @return \Illuminate\Http\Response
     */
// 家族側の連絡帳画面↓
public function RecordStampshow(Request $request, $people_id)
{
   
    // $person = Person::findOrFail($people_id);
    $person = Person::with(['foods', 'temperatures', 'toilets', 'waters'])->findOrFail($people_id);
    $today = \Carbon\Carbon::now()->toDateString();
    $selectedDate = $request->input('selected_date', \Carbon\Carbon::now()->toDateString());
    $selectedDateStart = \Carbon\Carbon::parse($selectedDate)->startOfDay();    
    $selectedDateEnd = \Carbon\Carbon::parse($selectedDate)->endOfDay();
    
     // 選択された日付に該当する記録をすべて取得
     $records = Record::where('person_id', $people_id)
     ->whereBetween('kiroku_date', [$selectedDateStart, $selectedDateEnd])
     ->get();

    // 各記録に対して押印情報を取得
    $stamps = [];
    foreach ($records as $record) {
     // 各記録ごとに押印情報を取得
     $stamp = Record::where('id', $record->id)->first();
     $stamps[$record->id] = $stamp;
 }
    $timesOnSelectedDate = $person->times ? $person->times->whereBetween('created_at', [$selectedDateStart, $selectedDateEnd]) : collect();
    $foodsOnSelectedDate = $person->foods->whereBetween('created_at', [$selectedDateStart, $selectedDateEnd]);
    $watersOnSelectedDate = $person->waters->whereBetween('created_at', [$selectedDateStart, $selectedDateEnd]);
    $medicinesOnSelectedDate = $person->medicines->whereBetween('created_at', [$selectedDateStart, $selectedDateEnd]);
    $tubesOnSelectedDate = $person->tubes->whereBetween('created_at', [$selectedDateStart, $selectedDateEnd]);
    // $temperaturesOnSelectedDate = $person->temperatures->whereBetween('created_at', [$selectedDateStart, $selectedDateEnd]);
    $temperaturesOnSelectedDate = $person->temperatures ? $person->temperatures->whereBetween('created_at', [$selectedDateStart, $selectedDateEnd]) : collect();
    // dd($temperaturesOnSelectedDate);
    $bloodpressuresOnSelectedDate = $person->bloodpressures->whereBetween('created_at', [$selectedDateStart, $selectedDateEnd]);
    $toiletsOnSelectedDate = $person->toilets->whereBetween('created_at', [$selectedDateStart, $selectedDateEnd]);
    $kyuuinsOnSelectedDate = $person->kyuuins ? $person->kyuuins->whereBetween('created_at', [$selectedDateStart, $selectedDateEnd]) : collect();
    $hossasOnSelectedDate = $person->hossas ? $person->hossas->whereBetween('created_at', [$selectedDateStart, $selectedDateEnd]) : collect();
    $speechesOnSelectedDate = $person->speeches ? $person->speeches->whereBetween('created_at', [$selectedDateStart, $selectedDateEnd]) : collect();

    // hanamaruの項目↓
    $lastTime = Time::where('people_id', $people_id)
    ->whereDate('created_at', $selectedDate)
    ->latest()
    ->first();

    $lastMorningActivity = Speech::where('people_id', $people_id)
        ->whereDate('created_at', $selectedDate)
        ->whereNotNull('morning_activity')
        ->latest()
        ->first();

    $lastAfternoonActivity = Speech::where('people_id', $people_id)
    ->whereDate('created_at', $selectedDate)
    ->whereNotNull('afternoon_activity')
    ->latest()
    ->first();
    
    $lastActivity = Activity::where('people_id', $people_id)
        ->whereDate('created_at', $selectedDate)
        ->latest()
        ->first();
        
    $lastTraining = Training::where('people_id', $people_id)
        ->whereDate('created_at', $selectedDate)
        ->latest()
        ->first();
        
    $lastLifestyle = Lifestyle::where('people_id', $people_id)
        ->whereDate('created_at', $selectedDate)
        ->latest()
        ->first();
        
    $lastCreative = Creative::where('people_id', $people_id)
        ->whereDate('created_at', $selectedDate)
        ->latest()
        ->first();    

    return view('recordstamp', compact('person', 'selectedDate', 'records', 'stamps','timesOnSelectedDate','foodsOnSelectedDate',  'watersOnSelectedDate' , 'medicinesOnSelectedDate', 'tubesOnSelectedDate',  'temperaturesOnSelectedDate', 'bloodpressuresOnSelectedDate','toiletsOnSelectedDate','kyuuinsOnSelectedDate', 'hossasOnSelectedDate', 'speechesOnSelectedDate' , 'lastTime', 'lastMorningActivity', 'lastAfternoonActivity', 'lastActivity', 'lastTraining', 'lastLifestyle', 'lastCreative',));
}

// 家族側の押印処理↓
public function storeStamp(Request $request, $id)
{
    try {
        // 入力バリデーション
        $validated = $request->validate([
            'hanko_name' => 'required|string|max:255',
            'kiroku_date' => 'required|date',
        ]);

        // 新しい押印を保存
        $record = Record::create([
            'person_id' => $id,
            'hanko_name' => $validated['hanko_name'],
            'kiroku_date' => $validated['kiroku_date'],
        ]);

        \Log::info('Stamp saved successfully', ['record' => $record]);
          // 成功時にメッセージをレスポンスとして返す
    return response()->json([
        'success' => true,
        'message' => '押印が成功しました'
    ]);

    } catch (\Exception $e) {
        \Log::error('Error saving stamp', ['error' => $e->getMessage()]);
        return response()->json(['success' => false, 'error' => '押印の保存中にエラーが発生しました'], 500);
    }
}



    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Food  $food
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $people_id)
{
}


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Food  $food
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Food $food)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Food  $food
     * @return \Illuminate\Http\Response
     */
    public function destroy(Food $food)
    {
        //
    }
}