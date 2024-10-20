<x-app-layout>
    <div class="flex items-center justify-center">
        <div class="flex flex-col items-center">
            <!-- フラッシュメッセージの表示（オプション） -->
            @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
            @endif

            <!-- エラーメッセージの表示（APIリクエストエラー用） -->
            <div id="form-error-message" class="error-message hidden bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline">エラーが発生しました。再度お試しください。</span>
            </div>

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

                    .error-message {
                        color: red;
                        font-size: 14px;
                        margin-top: 4px;
                    }
                </style>
                <div class="flex items-center justify-center" style="padding: 20px 0;">
                    <div class="flex flex-col items-center">
                        <h2>事業所の記録項目</h2>
                    </div>
                </div>

                <!-- 記録項目を追加するボタン -->
                <div class="flex items-center justify-center mt-4">
                    <button type="button" id="add-item-button" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-base text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                        記録項目を追加
                    </button>
                </div>

                <!-- 更新ボタン -->
                <div class="flex items-center justify-center mt-4">
                    <button type="submit" id="update-button" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-base text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                        更新
                    </button>
                </div>
            <!-- </form> -->
        </div>
    </div>
 <!-- モーダル -->
 <form action="{{ route('addItem.store', ['facility' => $facility->id, 'id' => $id]) }}" method="POST" id="add-item-form" class="w-full max-w-lg">
        @csrf
    <input type="hidden" name="facility_id" value="{{ $facility->id }}">
    <div id="add-item-modal" class="fixed inset-0 flex items-center justify-center bg-gray-800 bg-opacity-50 hidden">
        <div class="bg-white rounded-lg p-6 w-1/2">
            <h3 class="text-lg font-semibold mb-4">新しい記録項目を追加</h3>

            <!-- エラーメッセージの表示 -->
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

            <!-- タイトル入力フィールド -->
            <input type="hidden" name="facility_id" value="{{ $facility->id }}">
            <label for="new-item-title" class="block text-gray-700 text-base font-bold mb-2">タイトル</label>
            <input type="text" id="new-item-title" name="title" class="border border-gray-300 rounded-md w-full px-3 py-2 mb-4" placeholder="タイトルを入力" maxlength="32">

          

            <!-- 項目入力フィールドのコンテナ -->
            <div id="item-fields-container">
                <label class="block text-gray-700 text-base font-bold mb-2">記録項目</label>
               

                <!-- 項目入力フィールド（最初の一つ） -->
                @foreach(old('item', []) as $i => $value)
                    <div class="item-field mb-2">
                        <input type="text" name="item[]" class="border border-gray-300 rounded-md w-full px-3 py-2" value="{{ $value }}" placeholder="項目を入力" maxlength="32">
                        @error("item.$i")
                            <p class="text-red-500 text-base mt-2">{{ $message }}</p>
                        @enderror
                    </div>
                @endforeach

                <!-- 最初の1つの入力フィールドを表示 -->
                @if(count(old('item', [])) === 0)
                    <div class="item-field mb-2">
                        <input type="text" name="item[]" class="border border-gray-300 rounded-md w-full px-3 py-2" placeholder="項目を入力" maxlength="32">
                    </div>
                @endif
            </div>

            <!-- 「＋」ボタン -->
            <button type="button" id="add-item-field-button" class="mb-4 text-blue-500 hover:text-blue-700">＋ 項目を追加</button>

            <!-- エラーメッセージ -->
            <div id="error-message" class="error-message hidden">32文字以上は入力できません。</div>

            <div class="flex justify-end mt-4">
                <button type="button" id="cancel-add-item" class="inline-flex items-center px-4 py-2 bg-white text-gray-800 border border-gray-800 rounded-md font-semibold text-base uppercase tracking-widest hover:bg-gray-100 active:bg-gray-200 focus:outline-none focus:border-gray-800 focus:ring ring-gray-200 disabled:opacity-25 transition ease-in-out duration-150 mr-2">
                    キャンセル
                </button>
                <button type="submit" id="confirm-add-item" class="inline-flex items-center px-4 py-2 bg-gray-800 text-white border border-transparent rounded-md font-semibold text-base uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                    追加
                </button>
            </div>
        </div>
    </div>
    </form>
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            @if ($errors->any())
                document.getElementById('add-item-modal').classList.remove('hidden');
            @endif
            // モーダル要素
            const modal = document.getElementById('add-item-modal');
            const openModalButton = document.getElementById('add-item-button');
            const closeModalButton = document.getElementById('cancel-add-item');
            const confirmAddItemButton = document.getElementById('confirm-add-item');
            const newItemTitleInput = document.getElementById('title');
            const errorMessage = document.getElementById('error-message');
            const formErrorMessage = document.getElementById('form-error-message');
            const recordItemsForm = document.getElementById('record-items-form');

            // 項目入力フィールドのコンテナ
            const itemFieldsContainer = document.getElementById('item-fields-container');
            const addItemFieldButton = document.getElementById('add-item-field-button');

            // モーダルを開く関数
            function openModal() {
                modal.classList.remove('hidden');
                newItemTitleInput.focus();
            }

            // モーダルを閉じる関数
            function closeModal() {
                modal.classList.add('hidden');
                newItemTitleInput.value = '';
                const itemFields = itemFieldsContainer.querySelectorAll('.item-field');
                itemFields.forEach((field, index) => {
                    if (index === 0) {
                        field.querySelector('input').value = '';
                    } else {
                        field.remove();
                    }
                });
                errorMessage.classList.add('hidden');
                newItemTitleInput.classList.remove('border-red-500');
            }

            // モーダルを開くボタンのイベントリスナー
            openModalButton.addEventListener('click', openModal);

            // モーダルを閉じ
    // 新しい項目を追加するボタンのイベントリスナー
            confirmAddItemButton.addEventListener('click', function() {
                const newItemTitle = newItemTitleInput.value.trim();
                const newItemInputs = document.getElementsByName('item[]');
                const newItems = [];

                // エラーメッセージのリセット
                errorMessage.classList.add('hidden');

                // タイトルのバリデーション
                if (newItemTitle.length > 32) {
                    errorMessage.textContent = 'タイトルは32文字以内で入力してください。';
                    errorMessage.classList.remove('hidden');
                    return;
                } else if (newItemTitle === '') {
                    alert('タイトルを入力してください。');
                    return;
                }

                // 項目のバリデーション
                for (let input of newItemInputs) {
                    const value = input.value.trim();
                    if (value.length > 32) {
                        errorMessage.textContent = '各項目は32文字以内で入力してください。';
                        errorMessage.classList.remove('hidden');
                        return;
                    } else if (value !== '') {
                        newItems.push(value);
                    }
                }

                if (newItems.length === 0) {
                    alert('少なくとも1つの項目を入力してください。');
                    return;
                }

                // フォームデータを作成
        const formData = {
            title: newItemTitle,
            item: newItems
        };

                // モーダルを閉じる
                closeModal();

                // フォームを送信（API経由）
                submitForm(formData);
            });

            // フォームの送信イベントリスナー
            recordItemsForm.addEventListener('submit', function(event) {
                event.preventDefault();

                // フォームデータを収集
                const formData = new FormData(recordItemsForm);

                // フォームを送信
                submitForm(formData);
            });

            


            // フォームを送信する関数
            function submitForm(formData) {
                const personId = formData.get('people_id');

                // Axiosを使用してAPIリクエストを送信
                // TODO: ここでバックエンドで作成したAPIのエンドポイントを指定する、以下のは例
                axios.post(`/api/record-items/${personId}`, formData)
                    .then(response => {
                        // 成功メッセージの表示
                        alert(response.data.message);

                        // ページをリロードして最新の状態を取得
                        window.location.reload();
                    })
                    .catch(error => {
                        // エラーメッセージの表示
                        console.error(error);
                        formErrorMessage.classList.remove('hidden');
                        formErrorMessage.textContent = error.response?.data?.message || 'エラーが発生しました。再度お試しください。';
                    });
            }
        });
    </script>
</x-app-layout>