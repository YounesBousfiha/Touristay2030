@extends('layouts.touristLayout')

@section('explore')
    <main class="container mx-auto px-4 pt-24 pb-12">
    <h1 class="text-3xl font-bold mb-8">World Cup 2030 Accommodations</h1>

    <div class="mb-8 flex flex-col md:flex-row justify-between items-center">
        <div class="w-full md:w-1/2 mb-4 md:mb-0">
            <input type="text" id="searchInput" placeholder="Search properties..." class="w-full px-4 py-2 rounded-md border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>
        <div class="flex items-center">
            <label for="itemsPerPage" class="mr-2 text-gray-700">Items per page:</label>
            <select id="itemsPerPage" class="px-2 py-1 rounded-md border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="4">4</option>
                <option value="8" selected>8</option>
                <option value="12">12</option>
                <option value="16">16</option>
            </select>
        </div>
    </div>

    <div id="propertyList" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
        <!-- Property cards will be dynamically inserted here -->
    </div>

    <div class="mt-8 flex justify-center">
        <nav class="inline-flex rounded-md shadow">
            <button id="prevPage" class="px-4 py-2 text-sm font-medium text-blue-800 bg-white border border-gray-300 rounded-l-md hover:bg-gray-50">
                Previous
            </button>
            <span id="currentPage" class="px-4 py-2 text-sm font-medium text-blue-800 bg-white border-t border-b border-gray-300">
                    Page 1
                </span>
            <button id="nextPage" class="px-4 py-2 text-sm font-medium text-blue-800 bg-white border border-gray-300 rounded-r-md hover:bg-gray-50">
                Next
            </button>
            </nav>
    </div>
    </main>
@endsection