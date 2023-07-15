<x-app-layout>
  <x-slot name="header">
    @include('header')
  </x-slot>

  <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
          <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
              <div class="p-6 text-gray-900">

              </div>
              <h1 class="text-xl text-green-10">Hello World</h1>
              <div class="flex flex-wrap">
                <h1 class="border flex-auto text-lg font-semibold text-slate-900">
                  Classic Utility Jacket
                </h1>
                <div class="text-lg font-semibold text-slate-500">
                  $110.00
                </div>
                <div class="w-full flex-none text-sm font-medium text-slate-700 mt-2">
                  In stock
                </div>
              </div>
              <span class="w-50 border">h222222</span>
              <div class="flex flex-wrap">
                <div class="bg-cyan-300 flex-auto">Item1</div>
                <div class="bg-gray-500">Item2</div>
                <div class="bg-lime-400 w-full">Item3</div>
                <div class="bg-purple-300">Item4</div>
              </div>
              <div class="flex">
                <div class="flex-auto flex">
                  <div>item1</div>
                  <div>item2</div>
                </div>
                <div>
                  Item3
                </div>
              </div>
          </div>
      </div>
  </div>
</x-app-layout>