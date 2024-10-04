<!-- resources/views/edit_person.blade.php -->
@vite(['resources/css/app.css', 'resources/js/app.js'])
<x-app-layout>

    <!--ヘッダー[START]-->
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight" style="width: 100%;">
            {{ __('利用者情報の修正') }}
        </h2>
    </x-slot>
    <!--ヘッダー[END]-->

    <!-- バリデーションエラーの表示 -->
    @if ($errors->any())
        <div class="flex justify-between p-4 items-center bg-red-500 text-white rounded-lg border-2 border-white">
            @if ($errors->has('name') || $errors->has('date_of_birth'))
                <div><strong>氏名・生年月日は入力必須です。</strong></div> 
            @endif
            @if ($errors->has('duplicate_name_dob'))
                <div><strong>{{ $errors->first('duplicate_name_dob') }}</strong></div>
            @endif
            @if ($errors->has('duplicate_jukyuusha_number'))
                <div><strong>{{ $errors->first('duplicate_jukyuusha_number') }}</strong></div>
            @elseif ($errors->has('jukyuusha_number'))
                <div><strong>受給者証番号は10桁で入力してください。</strong></div>
            @endif
        </div>
    @endif

    <body class="h-full w-full">

    <!-- 修正フォーム -->
    <form action="{{ route('people.update', $person->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PATCH')

    <div style="display: flex; align-items: center; justify-content: center; flex-direction: column;">
        <div class="form-group mb-4 m-2 w-1/2 max-w-md md:w-1/6" style="display: flex; flex-direction: column; align-items: center;">
            <label class="block text-lg font-bold text-gray-700">名前</label>
            <input name="last_name" type="text" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm text-xl font-bold border-gray-300 rounded-md" value="{{ old('last_name', $person->last_name) }}"placeholder="姓">
            <input name="first_name" type="text" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm text-xl font-bold border-gray-300 rounded-md" value="{{ old('first_name', $person->first_name) }}" placeholder="名">

        </div>

        <div class="form-group mb-4 m-2 w-1/2 max-w-md md:w-1/6" style="display: flex; flex-direction: column; align-items: center;">
            <!-- <label class="block text-lg font-bold text-gray-700">名前</label> -->
            <input name="last_name_kana" type="text" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm text-xl font-bold border-gray-300 rounded-md"  value="{{ old('last_name_kana', $person->last_name_kana) }}" placeholder="セイ">
            <input name="first_name_kana" type="text" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm text-xl font-bold border-gray-300 rounded-md" value="{{ old('first_name_kana', $person->first_name_kana) }}" placeholder="メイ">
        </div>
            
            <div class="form-group mb-4 m-2 w-1/2 max-w-md md:w-1/6" style="display: flex; flex-direction: column; align-items: center;">
                <label class="block text-lg font-bold text-gray-700">生年月日</label>
                <input name="date_of_birth" type="date" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm text-xl font-bold border-gray-300 rounded-md" value="{{ old('date_of_birth', $person->date_of_birth) }}" placeholder="生年月日">
            </div>

            <div class="form-group mb-4 m-2 w-1/2 max-w-md md:w-1/6" style="display: flex; flex-direction: column; align-items: center;">
                <label class="block text-lg font-bold text-gray-700">受給者証番号</label>
                <input name="jukyuusha_number" type="text" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm text-xl font-bold border-gray-300 rounded-md" value="{{ old('jukyuusha_number', $person->jukyuusha_number) }}" placeholder="受給者番号">
            </div>

            <div class="form-group mb-4 m-2 w-1/2 max-w-md md:w-1/6" style="display: flex; flex-direction: column; align-items: center;">
                <label class="block text-lg font-bold text-gray-700">医療的ケア</label>
                <input name="medical_care" type="checkbox" value="1" class="mt-1" {{ $person->medical_care ? 'checked' : '' }}>
                <span class="text-gray-500">医療的ケアを必要とする場合はチェックしてください</span>
            </div>

            <div class="form-group mb-4 m-2 w-1/2 max-w-md md:w-1/6" style="display: flex; flex-direction: column; align-items: center;">
                <label class="block text-lg font-bold text-gray-700">プロフィール画像</label>
                <input name="filename" id="filename" type="file" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm text-lg border-gray-300 rounded-md ml-20">
                @if ($person->filename)
                    <div class="mt-2">
                        <img src="{{ asset('storage/' . $person->filename) }}" alt="プロフィール画像" style="max-width: 150px;">
                    </div>
                @endif
            </div>

            <div class="flex flex-col col-span-1">
                <div class="text-gray-700 text-center px-4 py-2 m-2">
                    <button type="submit" class="inline-flex items-center px-6 py-3 bg-gray-800 border border-transparent rounded-md font-semibold text-lg text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                        更新する
                    </button>
                </div>
            </div>
        </div>
    </form>

    </body>
</x-app-layout>