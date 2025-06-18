<x-app-layout>
    @push('scripts')
        <script>
            function updateServiceDetails() {
                const select = document.getElementById('serviceSelect');
                const priceField = document.getElementById('detailPrice');
                const descField = document.getElementById('detailDescription');
                const photoWrapper = document.getElementById('detailPhotoWrapper');
                const photoImg = document.getElementById('detailPhoto');

                const selectedOption = select.options[select.selectedIndex];

                const price = selectedOption.getAttribute('data-price') || '-';
                const description = selectedOption.getAttribute('data-description') || '-';
                const photoUrl = selectedOption.getAttribute('data-photo');

                priceField.textContent = 'Rp. ' + price;
                descField.textContent = description;

                if (photoUrl) {
                    photoImg.src = photoUrl;
                    photoWrapper.classList.remove('hidden');
                } else {
                    photoWrapper.classList.add('hidden');
                    photoImg.src = '';
                }
            }
        </script>
    @endpush

    <div class="max-w-2xl mx-auto mt-10 p-6 bg-white rounded-lg shadow-md">
        <h2 class="text-2xl font-bold mb-6">Booking Service</h2>

        <form action="{{ route('booking.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <!-- Select Service -->
            <div class="mb-4">
                <label for="service_id" class="block text-gray-700 font-semibold mb-1">Select Service</label>
                <select name="service_id" id="serviceSelect" onchange="updateServiceDetails()" required
                    class="w-full border border-gray-300 rounded-xl px-3 py-2 focus:ring-2 focus:ring-blue-500">
                    <option value="">Select Service</option>
                    @foreach ($services as $service)
                        <option value="{{ $service->id }}"
                            data-price="{{ number_format($service->price, 0, ',', '.') }}"
                            data-description="{{ $service->description }}"
                            data-photo="{{ $service->photo ? Storage::url($service->photo) : '' }}">
                            {{ $service->description }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Service Details Preview -->
            <div class="mb-4">
                <label class="block text-gray-700 font-semibold mb-1">Service Details</label>
                <div class="border-2 p-4 rounded bg-gray-50 space-y-2">
                    <div id="detailPhotoWrapper" class="mt-2 hidden">
                        <strong>Photo:</strong>
                        <img id="detailPhoto" src="" alt="Service Photo"
                            class="w-24 h-24 object-cover rounded mt-1">
                    </div>
                    <p><strong>Price:</strong> <span id="detailPrice">-</span></p>
                    <p><strong>Description:</strong> <span id="detailDescription">-</span></p>
                </div>
            </div>


            <!-- Booking Date -->
            <div class="mb-4">
                <label for="date_booking" class="block text-gray-700 font-semibold mb-1">Booking Date</label>
                <input type="date" name="date_booking" id="date_booking" required
                    class="w-full border border-gray-300 rounded-xl px-3 py-2 focus:ring-2 focus:ring-blue-500">
                @error('date_booking')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Submit -->
            <div class="mt-6 flex justify-end">
                <button type="submit"
                    class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-xl transition">
                    Submit
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
