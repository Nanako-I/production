<x-app-layout>
    <div class="flex items-center justify-center">
        <div class="flex flex-col items-center">
            <form action="{{ route('update.selected.items', $person->id) }}" method="POST" class="w-full max-w-lg">
                @csrf
                @method('PATCH')
                <style>
                    h2 {
                        font-family: Arial, sans-serif;
                        font-size: 20px;
                    }
                    p {
                        font-family: Arial, sans-serif;
                        font-size: 25px;
                        font-weight: bold;
                    }
                </style>
                <div class="flex items-center justify-center" style="padding: 20px 0;">
                    <div class="flex flex-col items-center">
                        <h2>{{$person->last_name}}{{$person->first_name}}さんの記録項目</h2>
                    </div>
                </div>
                <div class="flex flex-col items-center">
                    <div class="flex items-center justify-center">
                        <input type="hidden" name="people_id" value="{{ $person->id }}">
                    </div>
                    
                    <!-- peopleテーブルのmedical_careカラムが1の場合、医療的ケアの記録項目、0の場合は発達支援の記録項目だけ表示させる -->
                    @php
                        $items = ['体温', '食事', 'トイレ', 'トレーニング', '生活習慣', '創作活動', '集団・個人活動'];
                        if ($person->medical_care == 1) {
                            $items = ['体温', 'トイレ', '水分摂取', '内服', '注入', '血圧・脈・SpO2', '吸引', '発作'];
                        }
                    @endphp

                    @foreach($items as $item)
                        <div style="display: flex; flex-direction: row; align-items: center; margin-top: 0.5rem; margin-bottom: 0.5rem;" class="my-3">
                            <input type="checkbox" name="selected_items[]" value="{{ $item }}" {{ in_array($item, $selectedItems) ? 'checked' : '' }} class="w-6 h-6">
                            <p class="text-gray-900 font-bold text-xl px-1.5">{{ $item }}</p>
                        </div>
                    @endforeach
                    <!-- 上記の既存の記録項目だけでなく、ユーザー側で新しく登録項目を追加できるようにしたい -->

                </div>
                <div class="flex items-center justify-center mt-4">
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                        更新
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>

