@extends('layouts.touristLayout')

@section('favoris')

    <main class="container mx-auto px-4 pt-24 pb-12">
        <h1 class="text-3xl font-bold mb-8">Your Favorite Properties</h1>

        <div class="mb-8 flex flex-col md:flex-row justify-between items-center">
            <div class="w-full md:w-1/2 mb-4 md:mb-0">
                <input type="text" id="searchInput" placeholder="Search your favorites..." class="w-full px-4 py-2 rounded-md border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div class="flex items-center">
                <label for="sortBy" class="mr-2 text-gray-700">Sort by:</label>
                <select id="sortBy" class="px-2 py-1 rounded-md border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="name">Name</option>
                    <option value="priceAsc">Price: Low to High</option>
                    <option value="priceDesc">Price: High to Low</option>
                    <option value="dateAdded">Date Added</option>
                </select>
            </div>
        </div>
        <div>
            {{ $errors->first() }}
        </div>

        <div id="favoritesList" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($favorites as $favorite)
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <img src="https://placehold.co/600x400" alt="name" class="w-full h-48 object-cover">
                    <div class="p-4">
                        <h3 class="font-bold text-lg mb-2">{{ $favorite->annonce->title }}</h3>
                        <p class="text-gray-600 mb-2">${{ $favorite->annonce->number }}per night</p>
                        <p class="text-gray-700 mb-4">{{ $favorite->annonce->location }}</p>
                        <div class="flex justify-between items-center">
                            <a href="../tourist/listings/{{ $favorite->annonce->id }}" class="text-blue-600 hover:underline">View Details</a>
                        </div>
                        <form action="/tourist/favorites/{{ $favorite->id }}" method="POST">
                            @csrf
                            @method('delete')
                            <button type="submit" class="text-red-600 hover:text-red-800 transition duration-300">
                                Remove from Favorites
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>

        <div id="noFavorites" class="hidden text-center py-12">
            <h2 class="text-2xl font-semibold mb-4">You haven't added any favorites yet!</h2>
            <p class="text-gray-600 mb-6">Start exploring properties and add them to your favorites to see them here.</p>
            <a href="#" class="bg-blue-600 text-white px-6 py-3 rounded-full hover:bg-blue-700 transition duration-300">Explore Properties</a>
        </div>
    </main>

@endsection


{{--https://placehold.co/600x400--}}