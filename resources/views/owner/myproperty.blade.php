@extends('layouts.ownerLayout')

@section('propertyManager')
    <main class="container mx-auto px-4 pt-24 pb-12">
        <h1 class="text-3xl font-bold mb-8">Manage Your Properties</h1>
        <button id="addPropertyBtn" class="mb-8 bg-green-600 text-white px-6 py-2 rounded-full hover:bg-green-500 transition duration-300">
            Add New Property
        </button>

        <div id="propertyList" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($propertys as $property)
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <img src="https://placehold.co/600x400/png" alt="placeHolder" class="w-full h-48 object-cover">
                    <div class="p-4">
                        <h3 class="font-bold text-lg mb-2">{{ $property->title }}</h3>
                        <p class="text-gray-600 mb-2">${{ $property->number }} per night</p>
                        <p class="text-gray-700 mb-4">{{ $property->description }}</p>
                        <div class="flex justify-between">
                            <form action="/owner/property/{{ $property->id }}" method="POST">
                                @csrf
                                @method('delete')
                                <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded-md hover:bg-red-700 transition duration-300">Delete</button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </main>

    <!-- Add/Edit Property Modal -->
    <div id="propertyModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <h3 id="modalTitle" class="text-lg font-medium text-gray-900 mb-4">Add New Property</h3>
            <form id="propertyForm">
                <div class="mb-4">
                    <label for="propertyName" class="block text-gray-700 text-sm font-bold mb-2">Property Name</label>
                    <input type="text" id="propertyName" name="propertyName" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>
                <div class="mb-4">
                    <label for="propertyPrice" class="block text-gray-700 text-sm font-bold mb-2">Price per Night ($)</label>
                    <input type="number" id="propertyPrice" name="propertyPrice" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>
                <div class="mb-4">
                    <label for="propertyDescription" class="block text-gray-700 text-sm font-bold mb-2">Description</label>
                    <textarea id="propertyDescription" name="propertyDescription" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required></textarea>
                </div>
                <div class="mb-4">
                    <label for="propertyImage" class="block text-gray-700 text-sm font-bold mb-2">Image URL</label>
                    <input type="url" id="propertyImage" name="propertyImage" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>
                <div class="flex justify-end">
                    <button type="button" id="cancelPropertyBtn" class="mr-2 px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-400">Cancel</button>
                    <button type="submit" id="savePropertyBtn" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">Save Property</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="deleteModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Confirm Deletion</h3>
            <p class="text-gray-700 mb-4">Are you sure you want to delete this property? This action cannot be undone.</p>
            <div class="flex justify-end">
                <button id="cancelDeleteBtn" class="mr-2 px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-400">Cancel</button>
                <button id="confirmDeleteBtn" class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500">Delete Property</button>
            </div>
        </div>
    </div>

    <script>

        let editingPropertyId = null;


        function showPropertyModal(isEditing = false) {
            modalTitle.textContent = isEditing ? 'Edit Property' : 'Add New Property';
            propertyModal.classList.remove('hidden');
        }

        function hidePropertyModal() {
            propertyModal.classList.add('hidden');
            propertyForm.reset();
            editingPropertyId = null;
        }

        function showDeleteModal(propertyId) {
            editingPropertyId = propertyId;
            deleteModal.classList.remove('hidden');
        }

        function hideDeleteModal() {
            deleteModal.classList.add('hidden');
            editingPropertyId = null;
        }

        function editProperty(propertyId) {
            const property = properties.find(p => p.id === propertyId);
            if (property) {
                editingPropertyId = propertyId;
                document.getElementById('propertyName').value = property.name;
                document.getElementById('propertyPrice').value = property.price;
                document.getElementById('propertyDescription').value = property.description;
                document.getElementById('propertyImage').value = property.image;
                showPropertyModal(true);
            }
        }

        function deleteProperty() {
            properties = properties.filter(p => p.id !== editingPropertyId);
            renderProperties();
            hideDeleteModal();
        }

        addPropertyBtn.addEventListener('click', () => showPropertyModal());
        cancelPropertyBtn.addEventListener('click', hidePropertyModal);
        cancelDeleteBtn.addEventListener('click', hideDeleteModal);
        confirmDeleteBtn.addEventListener('click', deleteProperty);

        propertyForm.addEventListener('submit', (e) => {
            e.preventDefault();
            const formData = new FormData(propertyForm);
            const newProperty = {
                id: editingPropertyId || Date.now(),
                name: formData.get('propertyName'),
                price: parseFloat(formData.get('propertyPrice')),
                description: formData.get('propertyDescription'),
                image: formData.get('propertyImage'),
            };

            if (editingPropertyId) {
                const index = properties.findIndex(p => p.id === editingPropertyId);
                if (index !== -1) {
                    properties[index] = newProperty;
                }
            } else {
                properties.push(newProperty);
            }

            renderProperties();
            hidePropertyModal();
        });


        // GSAP animation
        gsap.from('#propertyList > div', {
            opacity: 0,
            y: 50,
            duration: 0.6,
            stagger: 0.1,
            ease: 'power3.out'
        });
    </script>
@endsection
