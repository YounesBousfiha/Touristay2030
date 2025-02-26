@extends('layouts.touristLayout')

@section('explore')
    <main class="container mx-auto px-4 pt-24 pb-12">
    <h1 class="text-3xl font-bold mb-8">World Cup 2030 Accommodations</h1>

    <div class="mb-8 flex flex-col md:flex-row justify-between items-center">
        <div class="w-full md:w-1/2 mb-4 md:mb-0">
            <input type="text" id="searchInput" placeholder="Search properties..." class="w-full px-4 py-2 rounded-md border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>
        <div class="flex items-center">
            <form action="{{ route('listings.index') }}" method="GET" class="mb-4">
                <label for="per_page" class="mr-2">Items per page:</label>
                <select name="per_page" id="per_page" onchange="this.form.submit()" class="p-2 border rounded">
                    <option value="4" {{ request('per_page') == 4 ? 'selected' : '' }}>4</option>
                    <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10</option>
                    <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25</option>
                </select>
            </form>
        </div>
    </div>

    <div id="propertyList" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
        @foreach($listings as $entry)
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <img src="https://placehold.co/600x400" alt="x" class="w-full h-48 object-cover">
                <div class="p-4">
                    <h3 class="font-bold text-lg mb-2">{{ $entry->title }}</h3>
                    <p class="text-gray-600">{{ $entry->number }} $</p>
                    <a href="../tourist/listings/{{ $entry->id }}" class="mt-4 inline-block bg-blue-800 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition duration-300">View Details</a>
                </div>
            </div>
        @endforeach
    </div>

    <div class="mt-8 flex justify-center">
        {{ $listings->appends(['per_page' => request('per_page')])->links() }}
    </div>
    </main>
@endsection