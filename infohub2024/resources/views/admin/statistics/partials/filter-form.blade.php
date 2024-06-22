<form action="{{ request()->url() }}" method="GET" class="mb-4">
    <div class="mb-6">
        <div class="flex justify-between mb-4">
            <button type="button" id="simpleBtn" class="shadow bg-blue-500 hover:bg-blue-400 focus:shadow-outline focus:outline-none text-white font-bold py-2 px-4 rounded">
                Uproszczone
            </button>
            <button type="button" id="advancedBtn" class="shadow bg-gray-500 hover:bg-gray-400 focus:shadow-outline focus:outline-none text-white font-bold py-2 px-4 rounded">
                Zaawansowane
            </button>
        </div>
        <div id="simpleOptions" style="display:none;">
            <button type="button" onclick="setPredefinedDate('today')" class="shadow bg-blue-500 hover:bg-blue-400 focus:shadow-outline focus:outline-none text-white font-bold py-2 px-4 rounded">
                Dziś
            </button>
            <button type="button" onclick="setPredefinedDate('yesterday')" class="shadow bg-blue-500 hover:bg-blue-400 focus:shadow-outline focus:outline-none text-white font-bold py-2 px-4 rounded">
                Wczoraj
            </button>
            <button type="button" onclick="setPredefinedDate('last_7_days')" class="shadow bg-blue-500 hover:bg-blue-400 focus:shadow-outline focus:outline-none text-white font-bold py-2 px-4 rounded">
                Ostatnie 7 dni
            </button>
            <button type="button" onclick="setPredefinedDate('last_30_days')" class="shadow bg-blue-500 hover:bg-blue-400 focus:shadow-outline focus:outline-none text-white font-bold py-2 px-4 rounded">
                Ostatnie 30 dni
            </button>
        </div>
        <div id="advancedOptions" style="display:none;">
            <div class="flex flex-wrap -mx-3 mb-6">
                <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
                    <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="from">
                        Od
                    </label>
                    <input class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="from" name="from" type="date" value="{{ request()->input('from', now()->subMonth()->toDateString()) }}">
                </div>
                <div class="w-full md:w-1/2 px-3">
                    <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="to">
                        Do
                    </label>
                    <input class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="to" name="to" type="date" value="{{ request()->input('to', now()->toDateString()) }}">
                </div>
            </div>
        </div>
    </div>
    <div class="flex justify-end">
        <button type="submit" class="shadow bg-blue-500 hover:bg-blue-400 focus:shadow-outline focus:outline-none text-white font-bold py-2 px-4 rounded">
            Filtruj
        </button>
    </div>
</form>
