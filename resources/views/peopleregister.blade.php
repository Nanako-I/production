<!-- resources/views/books.blade.php -->
@vite(['resources/css/app.css', 'resources/js/app.js'])
<x-app-layout>

 
            
        <!-- バリデーションエラーの表示に使用-->
       <!-- resources/views/components/errors.blade.php -->
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
 
    
       <body>
            
 <form action="{{ url('peopleregister') }}" method="POST" class="w-full" enctype="multipart/form-data">
          @csrf
            
    <div style="display: flex; align-items: center; justify-content: center; flex-direction: column;">
        <div class="form-group mb-4 m-2 w-1/2 max-w-md md:w-1/6" style="display: flex; flex-direction: column; align-items: center;">
            <label class="block text-lg font-bold text-gray-700">名前</label>
            <input name="last_name" type="text" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm text-xl font-bold border-gray-300 rounded-md" placeholder="姓">
            <input name="first_name" type="text" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm text-xl font-bold border-gray-300 rounded-md" placeholder="名">

        </div>

        <div class="form-group mb-4 m-2 w-1/2 max-w-md md:w-1/6" style="display: flex; flex-direction: column; align-items: center;">
            <!-- <label class="block text-lg font-bold text-gray-700">名前</label> -->
            <input name="last_name_kana" type="text" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm text-xl font-bold border-gray-300 rounded-md" placeholder="セイ">
            <input name="first_name_kana" type="text" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm text-xl font-bold border-gray-300 rounded-md" placeholder="メイ">

        </div>
        
        <div class="form-group mb-4 m-2 w-1/2 max-w-md md:w-1/6" style="display: flex; flex-direction: column; align-items: center;">
            <label class="block text-lg font-bold text-gray-700">生年月日</label>
            <input name="date_of_birth" type="date" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm text-xl font-bold border-gray-300 rounded-md" placeholder="生年月日">
        </div>
        
        
      <div class="form-group mb-4 m-2 w-1/2 max-w-md md:w-1/6" style="display: flex; flex-direction: column; align-items: center;">
        <label class="block text-lg font-bold text-gray-700">受給者証番号</label>
        <input name="jukyuusha_number" type="text" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm text-xl font-bold border-gray-300 rounded-md" placeholder="受給者番号">
      </div>

      <div class="form-group mb-4 m-2 w-1/2 max-w-md md:w-1/6" style="display: flex; flex-direction: column; align-items: center;">
          <label class="block text-lg font-bold text-gray-700">医療的ケア</label>
          <input name="medical_care" type="checkbox" value="1" class="mt-1">
          <span class="text-gray-500">医療的ケアを必要とする場合はチェックしてください</span>
      </div>

  

        <div class="form-group mb-4 m-2" style="display: flex; flex-direction: column; align-items: center;">
          <label class="block text-lg font-bold text-gray-700">プロフィール画像</label>
          <div style="margin-left: 10px;">
            <input name="filename" id="filename" type="file" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm text-lg border-gray-300 rounded-md ml-20">
            </div>
        </div>
      
        <div class="flex flex-col col-span-1">
            <div class="text-gray-700 text-center px-4 py-2 m-2">
              <button type="submit" class="inline-flex items-center px-6 py-3 bg-gray-800 border border-transparent rounded-md font-semibold text-lg text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                送信
              </button>
            </div>
        </div>
    </div>
</form>  
   
 <script src="https://unpkg.com/vue@3.2.47/dist/vue.global.prod.js"></script>
 <!--jquery3.6.4をCDN経由で呼び出し↓-->
 <script src="https://code.jquery.com/jquery-3.6.4.min.js" integrity="sha256-oP6HI9z1XaZNBrJURtCoUT5SUnxFr8s3BzRl+cbzUq8=" crossorigin="anonymous"></script>

 <script>
 document.getElementById('filename').addEventListener('click', function() {
        // 選択されたファイルに対する処理を追加する（例: アップロード処理など）
        console.log('ファイルが選択されました:', this.files[0].name);
    });
  </script>
  <div id="result"></div>
</div>

</body>
</html>
</x-app-layout>