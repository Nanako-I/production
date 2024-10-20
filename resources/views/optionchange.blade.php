<x-app-layout>

<!--ヘッダー[START]-->
<div class="flex items-center justify-center">
<div class="flex flex-col items-center">
    <form action="{{ url('people' ) }}" method="POST" class="w-full max-w-lg">
                        @method('PATCH')
                        @csrf
        <style>
            h2 {
              font-family: Arial, sans-serif; /* フォントをArialに設定 */
              font-size: 20px; /* フォントサイズを20ピクセルに設定 */
            }
        </style>
        <div class ="flex items-center justify-center"  style="padding: 20px 0;">
            <div class="flex flex-col items-center">
                <h2>{{$person->last_name}}{{$person->first_name}}さんのトレーニング登録</h2>
                
            </div>
        </div>
    </form>
    <form action="{{ route('options.item.update', ['people_id' => $person->id, 'id' => $optionItem->id]) }}" method="POST">

     
            @csrf
         
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />
        <script src="https://kit.fontawesome.com/de653d534a.js" crossorigin="anonymous"></script>
            <div class="flex flex-col items-center">
                <style>
                  p {
                    font-family: Arial, sans-serif; /* フォントをArialに設定 */
                    font-size: 25px; /* フォントサイズを20ピクセルに設定 */
                    font-weight: bold;
                  }
                </style>
                
                    <div class="flex items-center justify-center">
                         <input type="hidden" name="people_id" value="{{ $person->id }}">
                    </div>
                    <div style="display: flex; flex-direction: column; align-items: center;">
                        <div class="flex items-center justify-center ml-4">
                           
                                
                        </div>
                    
                    <div class="items-center justify-center p-4">
                        <input type="hidden" name="people_id" value="{{ $person->id }}">
                                                        
                                                        
                        <div class="flex flex-col items-center justify-center p-4">
                            <form action="{{ url('optionchange/' . $person->id . '/' . $optionItem->id) }}" method="POST">
                           
                                @csrf
                                @method('PATCH')
                                
                                <input type="hidden" name="people_id" value="{{ $person->id }}">
                                <input type="hidden" name="option_id" value="{{ $optionItem->option_id }}">

                                @foreach($validItems as $itemKey => $item)
                        <div style="display: flex; flex-direction: row; align-items: center; margin-top: 0.5rem; margin-bottom: 0.5rem;" class="my-3">
                            <input type="checkbox" name="{{ $itemKey }}" value="1" class="w-6 h-6" 
                                @if(!empty($item['itemData']) && $item['itemData'][0] === true) checked @endif>
                            <p class="text-gray-900 font-bold text-xl px-1.5">{{ $item['optionData'] }}</p>
                        </div>
                    @endforeach

                                <textarea name="bikou" class="w-full max-w-lg" style="height: 200px;">{{ $optionItem->bikou }}</textarea>

                                <button type="submit" class="bg-gray-800 text-white px-6 py-3 font-bold rounded mt-4">更新</button>
                            </form>
                </div>
             </div>
        </div>
    </div>
</form>

</x-app-layout>