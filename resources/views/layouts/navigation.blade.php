<div class = "top_total">
<nav x-data="{ open: false }" class="dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700">
    
     <style>
     
    .top_total{
                background-image: linear-gradient(to right, rgb(255, 195, 160,0.5), rgb(255,175,189,1));
            }
    </style>
    
</div> 
    <!-- Primary Navigation Menu -->
    <div class="top_bar mx-auto px-4 sm:px-6 lg:px-8 bg-orange-200">
       
        <style>
            .top_bar{
                background-image: linear-gradient(to right, rgb(255, 195, 160,0.5), rgb(255,175,189,1));
            }
            
        </style>
      
        <div class="flex justify-between h-16">
            <div class="flex ml-1">
                <!-- Logo -->
               
                 
                <div class="shrink-0 flex items-center">
                   @php
                        // ユーザーの情報を取得
                        $user = Auth::user();
                        // ロールまたはパーミッションを取得
                        // $permissions = $user->getPermissionsViaRoles()->pluck('name')->toArray();
                        $roleIds = $user->roles->pluck('id')->toArray();
                
                        //デバッグ用に role_id を表示
                        //dd($roleIds);
                        
                        // デフォルトのURLを設定
                        $url = 'people';
                
                        // ロールに基づいてURLを設定
                        if (array_intersect($roleIds, ['5', '6'])) {
                            $url = 'hogosha';
                        }
                    @endphp
                </div>
                @if (!request()->is('pdf/*/edit'))
                <div class="flex justify-between items-center h-16">
                    <div class="shrink-0 flex items-center">
                        <a href="{{ url($url) }}" >
                        
                            <img src="{{ asset('storage/sample/rainbow_heart_toumei.png') }}" width ="60" height="60">
                        </a>
                    </div>
                </div>
                @endif

                <!-- Navigation Links -->
                <!--<div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">-->
                <!--    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">-->
                <!--        {{ __('Dashboard') }}-->
                <!--    </x-nav-link>-->
                <!--</div>-->
                @hasanyrole('super administrator|facility staff administrator|facility staff user|facility staff reader')
                    <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex {{ request()->is('people') ?  ' text-black' : '' }} px-4 py-2 rounded-md text-3xl font-bold max-w-4xl text-black">
                        <x-nav-link :href="url('people')" :active="request()->is('people')">
                        {{ __('利用者一覧') }}
                        </x-nav-link>
                    </div>
                    
                      <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex {{ request()->is('peopleregister') ? ' text-black' : '' }} px-4 rounded-md text-xl font-bold items-center justify-center">
                         <!--<i class="material-icons md-48" id="face">face</i>-->
                         <x-nav-link :href="url('peopleregister')" :active="request()->is('peopleregister')">
                            {{ __('新規登録') }}
                        </x-nav-link>
                    </div>
                    
                     <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex {{ request()->is('calendar') ? ' text-black' : '' }} px-4 rounded-md text-xl font-bold items-center justify-center">
                         <!--<i class="material-icons md-48" id="face">face</i>-->
                         <x-nav-link :href="url('calendar')" :active="request()->is('calendar')">
                            {{ __('カレンダー') }}
                        </x-nav-link>
                    </div>
                    
                    <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex {{ request()->is('before-invitation') ? ' text-black' : '' }} px-4 rounded-md text-xl font-bold items-center justify-center">
                         <!--<i class="material-icons md-48" id="face">face</i>-->
                         <x-nav-link :href="url('before-invitation')" :active="request()->is('before-invitation')">
                            {{ __('職員・保護者を招待する') }}
                        </x-nav-link>
                    </div>
                @endhasanyrole
                
                @hasanyrole('super administrator|client family user|client family reader')
                    <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex {{ request()->is('invitation') ? ' text-black' : '' }} px-4 rounded-md text-xl font-bold items-center justify-center">
                         <!--<i class="material-icons md-48" id="face">face</i>-->
                         <x-nav-link :href="url('hogosha')" :active="request()->is('hogosha')">
                            {{ __('一覧へ') }}
                        </x-nav-link>
                    </div>
                @endhasanyrole
            </div>

    <!-- Settings Dropdown -->
<div class="hidden sm:flex sm:items-center sm:ml-6">
    <div x-data="{ open: false }" @click.away="open = false" @close.stop="open = false" class="relative">
        <div @click="open = !open">
            <button class="inline-flex items-center px-3 py-2 border border-transparent text-lg leading-5 font-medium rounded-md text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none transition ease-in-out duration-150">
                <div>{{ Auth::user()->name }}</div>

                <div class="ml-1">
                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </div>
            </button>
        </div>

        <div x-show="open"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="transform opacity-0 scale-95"
             x-transition:enter-end="transform opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-75"
             x-transition:leave-start="transform opacity-100 scale-100"
             x-transition:leave-end="transform opacity-0 scale-95"
             class="absolute z-50 mt-2 w-48 rounded-md shadow-lg origin-top-right right-0"
             style="display: none;"
             @click="open = false">
            <div class="rounded-md ring-1 ring-black ring-opacity-5 py-1 bg-white dark:bg-gray-700">
                <x-dropdown-link :href="route('profile.edit')" class="text-lg">
                    {{ __('プロフィール') }}
                </x-dropdown-link>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-dropdown-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();" class="text-lg">
                        {{ __('ログアウト') }}
                    </x-dropdown-link>
                </form>

                @hasanyrole('super administrator|facility staff administrator|facility staff user|facility staff reader')
                <x-dropdown-link :href="url('people')" class="text-lg">
                    {{ __('利用者一覧') }}
                </x-dropdown-link>

                <x-dropdown-link :href="url('peopleregister')" class="text-lg">
                    {{ __('新規登録') }}
                </x-dropdown-link>
                @endhasanyrole

                @hasanyrole('super administrator|client family user|client family reader')
                <x-dropdown-link :href="url('hogosha')" class="text-lg">
                    {{ __('一覧へ') }}
                </x-dropdown-link>
                @endhasanyrole
            </div>
        </div>
    </div>
</div>

<div id="dropdownButton" class="flex items-center sm:hidden">
    <div x-data="{ open: false }" @click.away="open = false" @close.stop="open = false" class="relative">
        <button @click="open = !open" class="px-3 py-2 border border-transparent text-lg leading-5 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none">
            メニュー
        </button>
        <div x-show="open"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="transform opacity-0 scale-95"
             x-transition:enter-end="transform opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-75"
             x-transition:leave-start="transform opacity-100 scale-100"
             x-transition:leave-end="transform opacity-0 scale-95"
             class="absolute z-50 mt-2 w-48 rounded-md shadow-lg origin-top-right right-0"
             style="display: none;">
            <div class="rounded-md ring-1 ring-black ring-opacity-5 py-1 bg-white">
                <x-dropdown-link :href="route('profile.edit')" class="text-lg">
                    {{ __('プロフィール') }}
                </x-dropdown-link>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-dropdown-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();" class="text-lg">
                        {{ __('ログアウト') }}
                    </x-dropdown-link>
                </form>

                @hasanyrole('super administrator|facility staff administrator|facility staff user|facility staff reader')
                <x-dropdown-link :href="url('people')" class="text-lg">
                    {{ __('利用者一覧') }}
                </x-dropdown-link>

                <x-dropdown-link :href="url('peopleregister')" class="text-lg">
                    {{ __('新規登録') }}
                </x-dropdown-link>
                @endhasanyrole

                @hasanyrole('super administrator|client family user|client family reader')
                <x-dropdown-link :href="url('hogosha')" class="text-lg">
                    {{ __('一覧へ') }}
                </x-dropdown-link>
                @endhasanyrole
            </div>
        </div>
    </div>
</div>



</div>
</div>
<style>
    * {
        margin: 0;
        padding: 0;
    }
    
    body {
        overflow-x: hidden;
        position: relative;
    }
    
    .top_total {
        background-image: linear-gradient(to right, rgb(255, 195, 160,0.5), rgb(255,175,189,1));
    }
    
    .top_bar {
        background-image: linear-gradient(to right, rgb(255, 195, 160,0.5), rgb(255,175,189,1));
    }
    
    @media (max-width: 640px) {
        .top_bar {
            padding: 0 1rem;
        }
        
        .top_bar > div {
            width: 100%;
        }
        
        #dropdownButton {
            margin-left: auto;
        }
        
        .hidden.sm\:flex {
            display: none !important;
        }
    }

            
            .row {
              margin: 5px;
              width: 20px;
              height: 2px;
              background-color: black;
            }
            
            li {
              list-style: none;
              margin-top: 5px;
              color: #fff;
              padding: 0 10px;
            }
            
        
            
            .show {
              position: absolute;
              top: 0;
              right: 0;
              background-color: gray;
              padding: 20px 50px;
            }
            
            .close {
              margin-bottom: 20px;
              cursor: pointer;
            }
            
            .square_btn {
              display: block;
              position: relative;
            }
            
            .square_btn::before,
            .square_btn::after {
              content: "";
              position: absolute;
              top: 0;
              left: 0;
              width: 1px; /* 棒の幅（太さ） */
              height: 20px; /* 棒の高さ */
              background: #fff; /* バツ印の色 */
            }
            
            .square_btn::before {
              transform: translate(-50%, -50%) rotate(45deg);
            }
            
            .square_btn::after {
              transform: translate(-50%, -50%) rotate(-45deg);
            }
        </style>
</nav>
<!-- JavaScript 部分 -->
<script>
// ボタンとメニュー要素を取得
    const toggleButton = document.getElementById('toggleButton');
    const menu = document.getElementById('responsiveMenu');
    let closemenu = document.getElementById("closemenu");

    // トグルボタンがクリックされた時の処理
    toggleButton.addEventListener('click', function() {
    // alert('クリック');
        // メニューの表示状態を切り替える
        menu.classList.toggle('show');
        responsiveMenu.style.display = "flex";
        
        if (responsiveMenu.classList.contains('show')) {
        responsiveMenu.style.display = "flex";
    } else {
        responsiveMenu.style.display = "none";
    }
});

close.addEventListener('click', function () {
    responsiveMenu.classList.remove('show');
    responsiveMenu.style.display = "none"; // closeボタンが押されたら非表示にする
    hamburger.style.display = "block";
});

    
    
// ハンバーガーメニュー↓

let hamburger = document.getElementById("hamburger");
let hamburgerMenu = document.getElementById("hamburger-menu");
let close = document.getElementById("close");

// ページが読み込まれたときには何もしない
document.addEventListener("DOMContentLoaded", function() {
    hamburgerMenu.style.display = "none";
});

hamburger.addEventListener('click', function () {
    hamburgerMenu.classList.toggle('show');
    
    // hamburger-menuの表示状態に合わせてdisplayプロパティを切り替える
    if (hamburgerMenu.classList.contains('show')) {
        hamburgerMenu.style.display = "flex";
    } else {
        hamburgerMenu.style.display = "none";
    }
});

close.addEventListener('click', function () {
    hamburgerMenu.classList.remove('show');
    hamburgerMenu.style.display = "none"; // closeボタンが押されたら非表示にする
    hamburger.style.display = "block";
});
</script>



<!--<style>-->
<!--.fade-enter-active, .fade-leave-active, .slide-enter-active, .slide-leave-active {-->
<!--  transition: opacity 0.3s, transform 0.3s;-->
<!--}-->
<!--.fade-enter, .fade-leave-to, .slide-enter, .slide-leave-to {-->
<!--  opacity: 0;-->
<!--}-->
<!--.slide-enter, .slide-leave-to {-->
<!--  transform: translateX(-100%);-->
<!--}-->
<!--</style>-->