@extends('layouts.touristLayout')

@section('hebergement')
    <main class="container mx-auto px-4 pt-24 pb-12">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="md:col-span-2">
                <h1 class="text-3xl font-bold mb-4">Luxury Apartment near Stadium</h1>
                <div class="mb-6">
                    <img src="https://placehold.co/600x400" alt="Luxury Apartment" class="w-full h-96 object-cover rounded-lg shadow-md">
                </div>
                <div class="mb-6">
                    <h2 class="text-2xl font-semibold mb-2">About this property</h2>
                    <p class="text-gray-700">This spacious 2-bedroom apartment offers a stunning view of the city skyline and is conveniently located near the main stadium. Perfect for sports enthusiasts and travelers looking for a comfortable and luxurious stay during the World Cup 2030.</p>
                </div>
                <div class="mb-6">
                    <h2 class="text-2xl font-semibold mb-2">Amenities</h2>
                    <ul class="grid grid-cols-2 gap-2">
                        @foreach(json_decode($announce->amenities) as $amenity => $available)
                            @if($available)
                                <li class="flex items-center">
                                    <svg class="w-5 h-5 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg> {{ ucfirst(str_replace('_', ' ', $amenity)) }}
                                </li>
                            @else
                                <li class="flex items-center">
                                    <svg class="w-5 h-5 mr-2 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg> {{ ucfirst(str_replace('_', ' ', $amenity)) }}
                                </li>
                            @endif
                        @endforeach
                    </ul>
                </div>
                <div>
                    <h2 class="text-2xl font-semibold mb-2">Location</h2>
                    <p class="text-gray-700 mb-2">Located in the heart of the city {{ $announce->location }}</p>
                    <img src="https://placehold.co/600x400" alt="Map" class="w-full h-64 object-cover rounded-lg shadow-md">
                </div>
                <div class="mt-4">
                    <form action="/tourist/favorites" method="POST">
                        @csrf
                        <input type="hidden" name="announce_id" value="{{ $announce->id }}">
                        <button type="submit" class="text-red-500 hover:text-red-700">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
                            </svg>
                            Add to Favorites
                        </button>
                    </form>
                </div>
            </div>
            <div class="md:col-span-1">
                <div class="bg-white rounded-lg shadow-md p-6 sticky top-24">
                    <h2 class="text-2xl font-semibold mb-4">${{$announce->number}} <span class="text-gray-600 text-lg">per night</span></h2>
                    <div class="mb-4">
                        <label for="check-in" class="block text-gray-700 text-sm font-bold mb-2">Check-in</label>
                        <input type="date" id="check-in" name="check-in" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div class="mb-4">
                        <label for="check-out" class="block text-gray-700 text-sm font-bold mb-2">Check-out</label>
                        <input type="date" id="check-out" name="check-out" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div class="mb-4">
                        <label for="guests" class="block text-gray-700 text-sm font-bold mb-2">Guests</label>
                        <select id="guests" name="guests" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option>1 guest</option>
                            <option>2 guests</option>
                            <option>3 guests</option>
                            <option>4 guests</option>
                        </select>
                    </div>
                    <button id="reserveBtn" class="w-full bg-blue-800 text-white py-2 px-4 rounded-md hover:bg-blue-700 transition duration-300">Reserve</button>
                </div>
            </div>
        </div>
    </main>

    <div id="reservationModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3 text-center">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Confirm Your Reservation</h3>
                <div class="mt-2 px-7 py-3">
                    <p class="text-sm text-gray-500">
                        You're about to book Luxury Apartment near Stadium for the following dates:
                    </p>
                    <p class="text-sm text-gray-700 mt-2">
                        Check-in: <span id="modalCheckIn"></span>
                    </p>
                    <p class="text-sm text-gray-700">
                        Check-out: <span id="modalCheckOut"></span>
                    </p>
                    <p class="text-sm text-gray-700">
                        Guests: <span id="modalGuests"></span>
                    </p>
                    <p class="text-lg font-semibold text-blue-800 mt-4">
                        Total: $<span id="modalTotal"></span>
                    </p>
                </div>
                <div class="items-center px-4 py-3">
                    <button id="confirmReservation" class="px-4 py-2 bg-blue-800 text-white text-base font-medium rounded-md w-full shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        Confirm Reservation
                    </button>
                    <button id="cancelReservation" class="mt-3 px-4 py-2 bg-gray-100 text-gray-700 text-base font-medium rounded-md w-full shadow-sm hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-400">
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        gsap.from('main > div', {
            opacity: 0,
            y: 50,
            duration: 1,
            ease: 'power3.out'
        });

        gsap.from('.sticky', {
            opacity: 0,
            x: 50,
            duration: 1,
            delay: 0.5,
            ease: 'power3.out'
        });

        // Modal functionality
        const reserveBtn = document.getElementById('reserveBtn');
        const modal = document.getElementById('reservationModal');
        const confirmBtn = document.getElementById('confirmReservation');
        const cancelBtn = document.getElementById('cancelReservation');

        reserveBtn.addEventListener('click', () => {
            const checkIn = document.getElementById('check-in').value;
            const checkOut = document.getElementById('check-out').value;
            const guests = document.getElementById('guests').value;

            document.getElementById('modalCheckIn').textContent = checkIn;
            document.getElementById('modalCheckOut').textContent = checkOut;
            document.getElementById('modalGuests').textContent = guests;

            // Calculate total (assuming $150 per night)
            const nights = (new Date(checkOut) - new Date(checkIn)) / (1000 * 60 * 60 * 24);
            const total = nights * 150;
            document.getElementById('modalTotal').textContent = total;

            modal.classList.remove('hidden');
        });

        confirmBtn.addEventListener('click', () => {
            alert('Reservation confirmed! Thank you for choosing TouriStay2030.');
            modal.classList.add('hidden');
        });

        cancelBtn.addEventListener('click', () => {
            modal.classList.add('hidden');
        });
    </script>
@endsection