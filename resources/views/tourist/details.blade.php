@extends('layouts.touristLayout')

@section('hebergement')
    <main class="container mx-auto px-4 pt-24 pb-12">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="md:col-span-2" data-id="{{ $announce->id }}" data-price="{{ $announce->number }}">
                <h1 class="text-3xl font-bold mb-4">{{ $announce->title }}</h1>
                <h2 class="text-2xl mb-4">{{ $errors->first() }}</h2>
                <div class="mb-6">
                    <img src="{{ $announce->image_url ?? 'https://placehold.co/600x400' }}" alt="{{ $announce->title }}" class="w-full h-96 object-cover rounded-lg shadow-md">
                </div>
                <div class="mb-6">
                    <h2 class="text-2xl font-semibold mb-2">About this property</h2>
                    <p class="text-gray-700">{{ $announce->description }}</p>
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
                    <h2 class="text-2xl font-semibold mb-4">${{ $announce->number }} <span class="text-gray-600 text-lg">per night</span></h2>
                    <button id="checkAvailabilityBtn" class="w-full bg-blue-800 text-white py-2 px-4 rounded-md hover:bg-blue-700 transition duration-300">Check Availability</button>
                </div>
            </div>
        </div>
    </main>

    <!-- Availability Modal -->
    <div id="availabilityModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
        <div class="relative top-20 mx-auto p-5 border w-11/12 max-w-2xl shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <h3 class="text-lg leading-6 font-medium text-gray-900 mb-2">Check Availability</h3>
                <div id="calendar" class="mb-4"></div>
                <form id="reservationForm" class="mt-4">
                    <div class="mb-4">
                        <label for="check-in" class="block text-gray-700 text-sm font-bold mb-2">Check-in</label>
                        <input type="text" id="check-in" name="check-in" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" readonly>
                    </div>
                    <div class="mb-4">
                        <label for="check-out" class="block text-gray-700 text-sm font-bold mb-2">Check-out</label>
                        <input type="text" id="check-out" name="check-out" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" readonly>
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
                    <button type="submit" class="w-full bg-blue-800 text-white py-2 px-4 rounded-md hover:bg-blue-700 transition duration-300">Reserve</button>
                </form>
                <button id="closeModal" class="mt-4 w-full bg-gray-200 text-gray-800 py-2 px-4 rounded-md hover:bg-gray-300 transition duration-300">Close</button>
            </div>
        </div>
    </div>

    <!-- Reservation Confirmation Modal -->
    <div id="reservationModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3 text-center">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Confirm Your Reservation</h3>
                <div class="mt-2 px-7 py-3">
                    <p class="text-sm text-gray-500">
                        You're about to book {{ $announce->title }} for the following dates:
                    </p>
                    <input type="hidden" id="annonce_id_modal" name="annonce_id" value="{{ $announce->id }}">
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
                    <div id="card"></div>
                    <div type="text" id="card-element" name="card"></div>
                    <input type="hidden" name="stripe-token" id="stripe-token" value="">
                </div>
                <div class="items-center px-4 py-3">
                    <button id="confirmReservation" onclick="sendReservationData()" class="px-4 py-2 bg-blue-800 text-white text-base font-medium rounded-md w-full shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        Confirm Reservation
                    </button>
                    <button id="cancelReservation" class="mt-3 px-4 py-2 bg-gray-100 text-gray-700 text-base font-medium rounded-md w-full shadow-sm hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-400">
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>
    <script src="https://js.stripe.com/v3/"></script>
    <script>
        var stripe = new Stripe('{{ env("STRIPE_KEY") }}');
        var elements = stripe.elements();
        var cardElement = elements.create("card");
        cardElement.mount("#card-element");

        async function sendReservationData() {
            const price = document.getElementById('modalTotal').textContent;
            const start = document.getElementById('modalCheckIn').textContent;
            const end = document.getElementById('modalCheckOut').textContent;
            const annonce_id = document.getElementById('annonce_id_modal').value;
            const stripe_token = (await stripe.createToken(cardElement));
            if(!stripe_token.token){
                return;
            }
            const token = stripe_token.token.id;
            const data = {
                amount: price,
                start: start,
                end: end,
                annonce_id: annonce_id,
                stripe_token: token,
            };

            fetch('/reservations/create', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify(data)
            })
                .then(response => response.json())
                .then(data => {
                    console.log('Success:', data);
                })
                .catch((error) => {
                    console.error('Error:', error);
                });

        }

        document.addEventListener('DOMContentLoaded', function() {


            async function fetchAvailability() {
                let id = document.querySelector('[data-id]').dataset.id;
                console.log(id);
                const response = await fetch(`/reservations/${id}`);
                console.log(response.status);
                if (response.ok && response.headers.get('content-type').includes('application/json')) {
                    const data = await response.json();
                    bookedDates = [data];
                    console.log(bookedDates);
                } else {
                    console.error('Failed to fetch availability:', response.status, response.statusText);
                }
            }


            let bookedDates = [];
            fetchAvailability();
            const checkAvailabilityBtn = document.getElementById('checkAvailabilityBtn');
            const availabilityModal = document.getElementById('availabilityModal');
            const closeModal = document.getElementById('closeModal');
            const reservationForm = document.getElementById('reservationForm');
            const reservationModal = document.getElementById('reservationModal');
            const confirmReservationBtn = document.getElementById('confirmReservation');
            const cancelReservationBtn = document.getElementById('cancelReservation');

            let calendar;
            let selectedStart, selectedEnd;

            checkAvailabilityBtn.addEventListener('click', () => {
                availabilityModal.classList.remove('hidden');
                if (!calendar) {
                    initializeCalendar();
                }
            });

            closeModal.addEventListener('click', () => {
                availabilityModal.classList.add('hidden');
            });

            function initializeCalendar() {
                const calendarEl = document.getElementById('calendar');

                calendar = new FullCalendar.Calendar(calendarEl, {
                    initialView: 'dayGridMonth',
                    selectable: true,
                    validRange: { start: new Date() }, // Prevent past dates

                    selectAllow: function(selectInfo) {
                        return !isDateRangeBooked(selectInfo.start, selectInfo.end);
                    },

                    select: function(info) {
                        selectedStart = info.start;
                        selectedEnd = info.end;
                        document.getElementById('check-in').value = formatDate(selectedStart);
                        document.getElementById('check-out').value = formatDate(new Date(selectedEnd.getTime() - 86400000)); // Subtract one day
                    },

                    events: bookedDates.map(range => ({
                        start: range.from,
                        end: new Date(new Date(range.to).getTime() + 86400000).toISOString().split('T')[0], // Fix for FullCalendar's end date behavior
                        display: 'background',
                        color: '#EF4444'
                    }))
                });

                calendar.render();

                function isDateRangeBooked(start, end) {
                    return bookedDates.some(range => {
                        let rangeStart = new Date(range.from);
                        let rangeEnd = new Date(range.to);
                        rangeEnd.setDate(rangeEnd.getDate() + 1); // Adjust end date

                        return (start < rangeEnd && end > rangeStart); // Overlapping check
                    });
                }
            }



            function formatDate(date) {
                return date.toISOString().split('T')[0];
            }

            reservationForm.addEventListener('submit', (e) => {
                e.preventDefault();
                const checkIn = document.getElementById('check-in').value;
                const checkOut = document.getElementById('check-out').value;
                const guests = document.getElementById('guests').value;

                document.getElementById('modalCheckIn').textContent = checkIn;
                document.getElementById('modalCheckOut').textContent = checkOut;
                document.getElementById('modalGuests').textContent = guests;

                const nights = (new Date(checkOut) - new Date(checkIn)) / (1000 * 60 * 60 * 24);
                const price = document.querySelector('[data-price]').dataset.price;
                const total = nights * price;
                document.getElementById('modalTotal').textContent = total;

                availabilityModal.classList.add('hidden');
                reservationModal.classList.remove('hidden');
            });

            confirmReservationBtn.addEventListener('click', () => {
                // Here you would typically send a request to your backend to process the reservation
                alert('Reservation confirmed! Thank you for choosing TouriStay2030.');
                reservationModal.classList.add('hidden');
            });

            cancelReservationBtn.addEventListener('click', () => {
                reservationModal.classList.add('hidden');
            });

            // GSAP animations
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
        });
    </script>
@endsection