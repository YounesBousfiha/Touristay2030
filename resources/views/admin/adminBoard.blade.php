@extends('layouts.adminLayout')

@section('statistics')
    <h1 class="text-3xl font-bold mb-8">Admin Statistics Dashboard</h1>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-8">
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-semibold mb-4">Total Registrations</h2>
            <p class="text-4xl font-bold text-blue-600" id="totalRegistrations">0</p>
        </div>
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-semibold mb-4">Total Bookings</h2>
            <p class="text-4xl font-bold text-green-600" id="totalBookings">0</p>
        </div>
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-semibold mb-4">Active Listings</h2>
            <p class="text-4xl font-bold text-purple-600" id="activeListings">0</p>
        </div>
    </div>
@endsection

@section('charts')
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-semibold mb-4">Registrations Over Time</h2>
            <canvas id="registrationsChart"></canvas>
        </div>
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-semibold mb-4">Bookings vs Active Listings</h2>
            <canvas id="bookingsListingsChart"></canvas>
        </div>
    </div>
@endsection

@push('chartJs')
<script>
    const data = {
        registrations: {
            total: 1250,
            byMonth: [80, 120, 180, 220, 150, 200, 300]
        },
        bookings: {
            total: 850,
            byMonth: [50, 80, 120, 150, 100, 130, 220]
        },
        activeListings: {
            total: 450,
            byMonth: [200, 220, 250, 280, 300, 320, 350]
        }
    };

    // Update total numbers
    document.getElementById('totalRegistrations').textContent = data.registrations.total;
    document.getElementById('totalBookings').textContent = data.bookings.total;
    document.getElementById('activeListings').textContent = data.activeListings.total;

    // Registrations Chart
    const registrationsCtx = document.getElementById('registrationsChart').getContext('2d');
    new Chart(registrationsCtx, {
        type: 'line',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul'],
            datasets: [{
                label: 'Registrations',
                data: data.registrations.byMonth,
                borderColor: 'rgb(59, 130, 246)',
                backgroundColor: 'rgba(59, 130, 246, 0.1)',
                tension: 0.1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top',
                },
                title: {
                    display: true,
                    text: 'Registrations by Month'
                }
            }
        }
    });

    // Bookings vs Active Listings Chart
    const bookingsListingsCtx = document.getElementById('bookingsListingsChart').getContext('2d');
    new Chart(bookingsListingsCtx, {
        type: 'bar',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul'],
            datasets: [
                {
                    label: 'Bookings',
                    data: data.bookings.byMonth,
                    backgroundColor: 'rgba(16, 185, 129, 0.6)'
                },
                {
                    label: 'Active Listings',
                    data: data.activeListings.byMonth,
                    backgroundColor: 'rgba(139, 92, 246, 0.6)'
                }
            ]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top',
                },
                title: {
                    display: true,
                    text: 'Bookings vs Active Listings by Month'
                }
            },
            scales: {
                x: {
                    stacked: false,
                },
                y: {
                    stacked: false
                }
            }
        }
    });

    gsap.from('main > div', {
        opacity: 0,
        y: 50,
        duration: 1,
        stagger: 0.2,
        ease: 'power3.out'
    });

    gsap.from('.bg-white', {
        opacity: 0,
        y: 20,
        duration: 0.8,
        stagger: 0.1,
        ease: 'power3.out',
        delay: 0.5
    });
</script>
@endpush