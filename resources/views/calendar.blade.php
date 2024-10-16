<html lang='ja'>

<head>
  @vite(['resources/css/app.css', 'resources/js/app.js'])
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <link href='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/main.min.css' rel='stylesheet' />
  <!-- Bootstrap Bundle JS (includes Popper) -->
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.0.0-alpha1/js/bootstrap.bundle.min.js"></script>

  <style>
    /* ツールチップのカスタムスタイルを追加 */
    .tooltip-inner {
      /* ツールチップの背景色 */
      background-color: rgba(255, 255, 0, 1);
      /* ツールチップのテキスト色 */
      color: black;
      /* パディング */
      padding: .5em 1em;
      /* 角丸 */
      border-radius: .25em;
    }

    /* 矢印部分の色も合わせます */
    .bs-tooltip-top .arrow::before,
    .bs-tooltip-auto[x-placement^="top"] .arrow::before {
      border-top-color: #333;
    }

    /* ツールチップを前面に表示 */
    .tooltip {
      z-index: 5000;
      /* 非常に高いz-indexを設定 */
    }
  </style>


</head>

<body>
  <div id='calendar'></div>
  <!-- イベント登録モーダル -->
<div class="fixed inset-0 z-50 bg-gray-600 bg-opacity-50 flex items-center justify-center p-6 hidden" id="modalBackdrop">
  <div class="bg-white w-full max-w-2xl rounded-lg shadow-xl z-50 overflow-auto">
    <div class="flex justify-between items-center p-6 border-b">
      <h3 class="text-lg font-semibold" id="modalTitle">来訪日登録</h3>
      <button type="button" class="text-gray-400 hover:text-gray-500 focus:outline-none modal-close-btn">
        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
        </svg>
      </button>
    </div>
    <form id="eventForm" class="p-6 space-y-6" method="POST">
      @csrf
      <div class="grid grid-cols-1 gap-6">
        <div>
          <label for="selectPeople" class="block text-sm font-medium text-gray-700">利用者名</label>
          <select id="selectPeople" name="people_id" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 text-gray-900" required>
            <option value="" disabled selected>選択してください</option>
          </select>
        </div>
        <div>
          <label for="selectVisitType" class="block text-sm font-medium text-gray-700">来訪タイプ</label>
          <select id="selectVisitType" name="visit_type_id" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500" required>
            <option value="" disabled selected>選択してください</option>
          </select>
        </div>
      </div>

      <div class="grid grid-cols-2 gap-6">
        <div>
          <label for="arrival-date" class="block text-sm font-medium text-gray-700">来訪予定日</label>
          <input type="date" id="arrival-date" name="arrival-date" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500" required>
        </div>
        <div>
          <label for="arrival-time" class="block text-sm font-medium text-gray-700">来訪予定時間</label>
          <input type="time" id="arrival-time" name="arrival-time" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500">
        </div>
        <div>
          <label for="exit-date" class="block text-sm font-medium text-gray-700">退館予定日</label>
          <input type="date" id="exit-date" name="exit-date" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500" required>
        </div>
        <div>
          <label for="exit-time" class="block text-sm font-medium text-gray-700">退館予定時間</label>
          <input type="time" id="exit-time" name="exit-time" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500">
        </div>
      </div>

      <div class="grid grid-cols-2 gap-6">
        <div>
          <label class="block text-sm font-medium text-gray-700">迎えの要否</label>
          <div class="mt-1 flex space-x-4 border border-gray-300 rounded-md px-3 py-2">
            <div class="flex items-center">
              <input type="radio" id="pick_up_yes" name="pick_up" value="必要" required class="mr-2">
              <label for="pick_up_yes" class="text-sm">必要</label>
            </div>
            <div class="flex items-center">
              <input type="radio" id="pick_up_no" name="pick_up" value="不要" required class="mr-2">
              <label for="pick_up_no" class="text-sm">不要</label>
            </div>
          </div>
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700">送りの要否</label>
          <div class="mt-1 flex space-x-4 border border-gray-300 rounded-md px-3 py-2">
            <div class="flex items-center">
              <input type="radio" id="drop_off_yes" name="drop_off" value="必要" required class="mr-2">
              <label for="drop_off_yes" class="text-sm">必要</label>
            </div>
            <div class="flex items-center">
              <input type="radio" id="drop_off_no" name="drop_off" value="不要" required class="mr-2">
              <label for="drop_off_no" class="text-sm">不要</label>
            </div>
          </div>
        </div>
      </div>

      <div class="grid grid-cols-2 gap-6">
        <div>
          <label for="pick_up_time" class="block text-sm font-medium text-gray-700">迎え予定時間</label>
          <input type="time" id="pick_up_time" name="pick_up_time" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500">
        </div>
        <div>
          <label for="drop_off_time" class="block text-sm font-medium text-gray-700">送り予定時間</label>
          <input type="time" id="drop_off_time" name="drop_off_time" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500">
        </div>
      </div>

      <div class="grid grid-cols-2 gap-6">
        <div>
          <label for="pick_up_staff" class="block text-sm font-medium text-gray-700">迎え担当者</label>
          <input type="text" id="pick_up_staff" name="pick_up_staff" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500">
        </div>
        <div>
          <label for="drop_off_staff" class="block text-sm font-medium text-gray-700">送り担当者</label>
          <input type="text" id="drop_off_staff" name="drop_off_staff" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500">
        </div>
      </div>

      <div class="flex justify-end space-x-4">
        <button type="button" class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
          キャンセル
        </button>
        <button type="submit" id="submitButton" class="w-full bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 -700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">登録</button> <!-- submitButton の ID を追加 -->
      </div>
    </form>
  </div>
</div>
  <!-- 削除モーダル -->
  <div id="deleteModal" class="fixed inset-0 z-50 bg-gray-600 bg-opacity-50 flex items-center justify-center p-6 hidden">
    <div class="bg-white w-1/4 rounded-lg shadow-xl z-50 overflow-auto p-6">
      <h3 class="text-lg font-semibold">予定を削除しますか？</h3>
      <div class="mt-4 flex justify-end space-x-3">
        <button id="cancelDelete" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">キャンセル</button>
        <button id="confirmDelete" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">削除する</button>
      </div>
    </div>
  </div>
  <!-- オプション選択モーダル -->
  <div id="optionModal" class="fixed inset-0 z-50 bg-gray-600 bg-opacity-50 flex items-center justify-center p-6 hidden">
    <div class="bg-white w-1/4 rounded-lg shadow-xl z-50 overflow-auto p-6">
      <h3 class="text-lg font-semibold">予定の操作</h3>
      <div class="mt-4 flex justify-end space-x-3">
        <button id="cancelOption" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">キャンセル</button>
        <!-- 来所予定のデータのidが取得できず、上手くできないので編集ボタンのみ一旦hiddenにした -->
        <button id="editButton" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline hidden">編集</button>
        <button id="deleteButton" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">削除</button>
      </div>
    </div>
  </div>
</body>

</html>