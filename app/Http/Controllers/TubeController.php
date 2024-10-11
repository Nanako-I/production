<?php

namespace App\Http\Controllers;

use App\Models\Person;
use App\Models\User;
use App\Models\Tube;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;

class TubeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
     public function index()
    {
    //   / 全件データ取得して一覧表示する↓
        // $people は変数名　Person::でPersonモデルにアクセスする
        $tube = Tube::all();
        
        return view('people',compact('tube'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
   public function create(Request $request)
{
    $person = Person::findOrFail($request->people_id);
    return redirect()->route('tube.edit', ['people_id' => $person->id]);
}

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
         // バリデーションルールとメッセージを定義
    $rules = [
        'created_at' => 'required', // 日時の登録を必須にする
        'filename' => 'image|max:2048', // 2MB = 2048KB
    ];
    $messages = [
        'created_at.required' => '時間の登録は必須です。',
        'image' => '画像ファイルを選択してください。（画像ファイルはjpeg, png, bmp, gif, svgのいずれかにしてください）',
        'uploaded' => '画像ファイルを選択してください。（画像ファイルはjpeg, png, bmp, gif, svgのいずれかにしてください）',
        'filename.max' => 'ファイルサイズが大きすぎます。2MB以下のファイルを選択してください。',
         
    ];
    // バリデーション実行
    $validator = Validator::make($request->all(), $rules, $messages);

    if ($validator->fails()) {
        return redirect()->back()->withErrors($validator)->withInput();
    }
         //   画像保存
        $directory = 'public/sample/tube_photo';
        $filename = null;
        $filepath = null;
    
        if ($request->hasFile('filename')) {
            $request->validate([
                'filename' => 'image|max:2048',
            ]);
            // $filename = uniqid() . '.' . $request->file('filename')->getClientOriginalExtension();
            // $filename = $request->file('filename')->getClientOriginalName();	
            
            // 同じファイル名でも上書きされないようユニークなIDをファイル名に追加
            $uniqueId = uniqid();
            $originalFilename = $request->file('filename')->getClientOriginalName();
            $filename = $uniqueId . '_' . $originalFilename;
            $request->file('filename')->storeAs($directory, $filename);
            $filepath = $directory . '/' . $filename;
            
        }
        // バリデーションした内容を保存する↓
        
        $tube = Tube::create([
        'people_id' => $request->people_id,
        'user_id_tube' => $request->user_id_tube,
        'created_at' => $request->created_at,
        'tube' => $request->tube,
        'tube_bikou' => $request->tube_bikou,
        'user_id_medicine' => $request->user_id_medicine,
        'created_at' => $request->created_at,
        'medicine' => $request->medicine,
        'medicine_bikou' => $request->medicine_bikou,
        'updated_at' => $request->updated_at, 
        'filename' => $filename,
        // ファイル名は $filename という変数に保存されているので、それを利用して filename カラムに保存する
        'path' => $filepath,
    ]);
    // dd($tube);
    // return redirect('people/{id}/edit');
     $people = Person::all();
      // 二重送信防止
    $request->session()->regenerateToken();
    return view('people', compact('tube', 'people'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Food  $food
     * @return \Illuminate\Http\Response
     */
    public function show($id)
{
    
    $person = Person::findOrFail($id);
    $tubes = $person->tubes;

    return view('people', compact('tubes'));
    
    // $temperature = Temperature::findOrFail($id);

    // return view('temperaturelist', compact('temperature'));
}

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Food  $food
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $people_id)
{
   
    $person = Person::findOrFail($people_id);
    $today = \Carbon\Carbon::now()->toDateString();
    $selectedDate = $request->input('selected_date', Carbon::now()->toDateString());
    $selectedDateStart = Carbon::parse($selectedDate)->startOfDay();
    $selectedDateEnd = Carbon::parse($selectedDate)->endOfDay();

    $tubesOnSelectedDate = $person->tubes->whereBetween('created_at', [$selectedDateStart, $selectedDateEnd]);
    return view('tubeedit', compact('person', 'selectedDate', 'tubesOnSelectedDate'));
}

public function change(Request $request, $people_id, $id)
    {
        // ユーザーを取得
        $user = User::findOrFail($people_id);
        // ユーザーが持つ体温の記録からユーザーIDを取得
        $user_id = $user->id;
    
        // すべてのユーザーを取得
        $users = User::all();
        $person = Person::findOrFail($people_id);
        $tube = Tube::findOrFail($id);
        return view('tubechange', compact('person', 'tube','users'));
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Food  $food
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Tube $tube)
    {
        $rules = [
        'created_at' => 'required', // 日時の登録を必須にする
        'filename' => 'image|max:2048',// 2MB = 2048KB
        ];
        $messages = [
            'created_at.required' => '時間の登録は必須です。',
            'image' => '画像ファイルを選択してください。（画像ファイルはjpeg, png, bmp, gif, svgのいずれかにしてください）',
            'uploaded' => '画像ファイルを選択してください。（画像ファイルはjpeg, png, bmp, gif, svgのいずれかにしてください）',
            'filename.max' => 'ファイルサイズが大きすぎます。2MB以下のファイルを選択してください。',
        ];
        
        // バリデーション実行
        $validator = Validator::make($request->all(), $rules, $messages);
    
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        
        //データ更新
        $tube = Tube::find($request->id);
        // 画像がアップロードされているかチェック
        if ($request->hasFile('filename')) {
            $request->validate([
                'filename' => 'image|max:2048', // ファイルのバリデーション
            ]);

            // 古い画像ファイルが存在する場合は削除
            if ($tube->path && \Storage::exists($tube->path)) {
                \Storage::delete($tube->path);
            }
    
            $directory = 'public/sample/tube_photo';
            
            
            // 同じファイル名でも上書きされないようユニークなIDをファイル名に追加
            $uniqueId = uniqid();
            $originalFilename = $request->file('filename')->getClientOriginalName();
            $filename = $uniqueId . '_' . $originalFilename;
            
            $request->file('filename')->storeAs($directory, $filename);
            $filepath = $directory . '/' . $filename;
    
            // 更新されたファイル名とパスをセット
            $tube->filename = $filename;
            $tube->path = $filepath;
            
            
     }
        // 他のデータを更新
    $tube->fill($request->except(['filename']));
    $tube->save();

    // セッショントークンを再生成
    $request->session()->regenerateToken();

    $people = Person::all();

    return view('people', compact('tube', 'people'));
}

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Food  $food
     * @return \Illuminate\Http\Response
     */
    
       
        public function destroy($id) 
        {
            $tube = Tube::find($id);
        
            if ($tube) {
        
                // 画像ファイルが存在する場合、削除
                if ($tube->path && \Storage::exists($tube->path)) {
                    \Storage::delete($tube->path);
                }
        
                // Tubeを削除
                $tube->delete();
            }
        
            return redirect()->route('people.index')->with('success', '削除が完了しました。');
        }
        
}
