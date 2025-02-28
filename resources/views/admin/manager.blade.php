@extends('layouts.adminLayout')

@section('managerboard')
    <main class="container mx-auto px-4 pt-24 pb-12">
        <h1 class="text-3xl font-bold mb-8">Manage Fraudulent Listings</h1>

        <div class="mb-8 flex flex-col md:flex-row justify-between items-center">
            <div class="w-full md:w-1/2 mb-4 md:mb-0">
                <input type="text" id="searchInput" placeholder="Search listings..." class="w-full px-4 py-2 rounded-md border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div class="flex items-center">
                <label for="filterStatus" class="mr-2 text-gray-700">Filter by status:</label>
                <select id="filterStatus" class="px-2 py-1 rounded-md border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="all">All</option>
                    <option value="flagged">Flagged</option>
                    <option value="underReview">Under Review</option>
                    <option value="verified">Verified</option>
                </select>
            </div>
        </div>

        <div id="listingList" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($annonces as $entry)
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <img src="https://placehold.co/600x400" alt="name" class="w-full h-48 object-cover">
                    <div class="p-4">
                        <h3 class="font-bold text-lg mb-2">{{ $entry->title }}</h3>
                        <p class="text-gray-600 mb-2">{{ $entry->number }}$ per night</p>
                        <p class="text-gray-700 mb-2">Owner: ${listing.owner}</p>
                        <p class="text-gray-700 mb-2">Status: <span> active</span></p>
                        <form action="{{ route('admin.remove', ['id' => $entry->id]) }}" method="POST">
                            @csrf
                            @method('delete')
                            <div class="flex justify-between">
                                <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded-md hover:bg-red-700 transition duration-300">Delete</button>
                            </div>
                        </form>
                    </div>
                </div>

            @endforeach
        </div>
    </main>
@endsection