@vite(['resources/css/app.css', 'resources/js/app.js'])
<x-app-layout>
  @hasanyrole('super administrator|facility staff administrator|facility staff user|facility staff reader')
<div class="flex flex-row justify-start w-screen overflow-x-auto">
    <div class="slider">


        @csrf
        @if (isset($people) && !empty($people) && count($people) > 0)
        <div class="flex flex-row justify-center tw-flex-row h-150 -m-2">
            @foreach ($people as $person)
                <div class="p-2 h-full lg:w-1/3 md:w-full flex">
                    <div class="slide border-2 p-4 w-full md:w-64 lg:w-100 rounded-lg bg-white">
                        <a href="{{ url('people/'.$person->id.'/edit') }}" class="relative ml-2">
                            <div class="h-30 flex flex-row items-center rounded-lg bg-white">
                                @if ($person->filename)
                                    <img alt="team" class="w-16 h-16 bg-gray-100 object-cover object-center flex-shrink-0 rounded-full mr-4" src="{{ asset('storage/sample/' . $person->filename) }}">
                                @else
                                    <img alt="team" class="w-16 h-16 bg-gray-100 object-cover object-center flex-shrink-0 rounded-full mr-4" src="https://dummyimage.com/80x80">
                                @endif
                                <div class="flex-grow">
                                    <h2 class="h2 text-gray-900 title-font font-bold text-2.5xl">{{ $person->last_name }} {{ $person->first_name }}</h2>
                                    <p class="text-gray-900 font-bold text-xs">{{ $person->date_of_birth }}生まれ</p>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
        @else
            <p>登録された利用者がいません。</p>
        @endif
    </div>
</div>
@endhasanyrole
</x-app-layout>