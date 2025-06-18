<x-app-layout>
    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            function deleteBooking(id) {
                Swal.fire({
                    title: 'Are you sure deleted?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Yes, deleted!',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        fetch(`/booking/${id}`, {
                                method: 'DELETE',
                                headers: {
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                                        'content'),
                                    'Accept': 'application/json'
                                }
                            })
                            .then(response => {
                                if (response.ok) {
                                    Swal.fire('Succes!', 'Successfully deleted service.', 'success')
                                        .then(() => location.reload());
                                } else {
                                    response.json().then(data => {
                                        Swal.fire('Error!', data.message || 'Deleted failed!.', 'error');
                                    });
                                }
                            })
                    }
                });
            }

            function editBooking(id) {
                fetch(`/booking/${id}`)
                    .then(response => {
                        if (!response.ok) throw new Error("Data not found");
                        return response.json();
                    })
                    .then(data => {
                        document.getElementById('editId').value = data.id;
                        document.getElementById('editDateBooking').value = data.date_booking;
                        document.getElementById('editStatus').value = data.status;
                        document.getElementById('editServiceSelect').value = data.service_id;
                        updateBookingDetails(true);
                        document.getElementById('editModal').classList.remove('hidden');
                    })
                    .catch(() => {
                        Swal.fire('Error!', 'Unable to retrieve booking data.', 'error');
                    });
            }

            function closeEditModal() {
                document.getElementById('editModal').classList.add('hidden');
            }

            function updateBookingDetails(isEdit = false) {
                const select = document.getElementById(isEdit ? 'editServiceSelect' : 'serviceSelect');
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

            function updateBooking() {
                const id = document.getElementById('editId').value;
                const date_booking = document.getElementById('editDateBooking').value;
                const status = document.getElementById('editStatus').value;
                const service_id = document.getElementById('editServiceSelect').value;

                fetch(`/booking/${id}`, {
                        method: 'PUT',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({
                            date_booking,
                            status,
                            service_id
                        })
                    })
                    .then(response => {
                        if (response.ok) {
                            Swal.fire('Successfully!', 'Successfully updated.', 'success')
                                .then(() => location.reload());
                        } else {
                            response.json().then(data => {
                                Swal.fire('Error!', data.message || 'Updated failed.', 'error');
                            });
                        }
                    })
            }
        </script>
    @endpush

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg overflow-hidden">
                <div class="p-6 text-xl font-semibold text-gray-900 border-b border-gray-200">
                    Booking Service List
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-sm text-gray-700 uppercase">Name</th>
                                <th class="px-6 py-3 text-sm text-gray-700 uppercase">Service</th>
                                <th class="px-6 py-3 text-sm text-gray-700 uppercase">Price</th>
                                <th class="px-6 py-3 text-sm text-gray-700 uppercase">Booking Date</th>
                                <th class="px-6 py-3 text-sm text-gray-700 uppercase">Status</th>
                                @if (auth()->check() && auth()->user()->role === 'admin')
                                    <th class="px-6 py-3 text-sm text-gray-700 uppercase">Action</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y text-center">
                            @foreach ($bookings as $booking)
                                <tr>
                                    @if (auth()->check() && auth()->user()->role === 'admin')
                                        <td class="px-6 py-4">{{ $booking->user->name ?? '-' }}</td>
                                    @else
                                        <td class="px-6 py-4">User</td>
                                    @endif
                                    <td class="px-6 py-4">{{ $booking->service->description ?? '-' }}</td>
                                    <td class="px-6 py-4">Rp.
                                        {{ number_format($booking->service->price ?? 0, 0, ',', '.') }}</td>
                                    <td class="px-6 py-4">{{ $booking->date_booking }}</td>
                                    <td class="px-6 py-4">
                                        <span
                                            class="px-2 py-1 rounded-full text-white text-sm font-semibold 
                                            {{ $booking->status === 'pending' ? 'bg-yellow-400' : 'bg-green-500' }}">
                                            {{ ucfirst($booking->status ?? '-') }}
                                        </span>
                                    </td>
                                    @if (auth()->check() && auth()->user()->role === 'admin')
                                        <td class="px-6 py-4">
                                            @if ($booking->service && $booking->user)
                                                <button onclick="editBooking({{ $booking->id }})"
                                                    class="bg-yellow-400 text-white px-3 py-1 rounded text-xs">Edit</button>
                                            @endif
                                            <button onclick="deleteBooking({{ $booking->id }})"
                                                class="bg-red-600 text-white px-3 py-1 rounded text-xs">Delete</button>
                                        </td>
                                    @endif
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Modal Edit -->
        <div id="editModal"
            class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 hidden transition-opacity duration-300">
            <div class="bg-white rounded-2xl shadow-lg w-full max-w-xl p-6 relative">
                <h2 class="text-xl font-semibold text-gray-800 mb-6 border-b pb-2">Edit Booking Service</h2>

                <form id="editForm" class="space-y-4">
                    <input type="hidden" id="editId">
                    <div class="mb-4">
                        <label for="editUserName" class="block text-gray-700 font-semibold mb-1">Name</label>
                        <input type="text" id="editUserName"
                            class="w-full border border-gray-300 rounded-xl px-3 py-2 bg-gray-100 text-gray-700"
                            value="{{ $booking->user->name ?? '-' }}" readonly>
                    </div>

                    <!-- Select Service -->
                    <div class="mb-4">
                        <label for="editServiceSelect" class="block text-gray-700 font-semibold mb-1">Select
                            Service</label>
                        <select id="editServiceSelect" onchange="updateBookingDetails(true)" required
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

                    <!-- Service Details -->
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
                    <div>
                        <label for="editDateBooking" class="block text-gray-700 font-semibold mb-1">Booking Date</label>
                        <input type="date" id="editDateBooking"
                            class="w-full border border-gray-300 rounded-xl px-3 py-2 focus:ring-2 focus:ring-blue-500">
                    </div>

                    <!-- Status -->
                    <div>
                        <label for="editStatus" class="block text-gray-700 font-semibold mb-1">Status</label>
                        <select id="editStatus"
                            class="w-full border border-gray-300 rounded-xl px-3 py-2 focus:ring-2 focus:ring-blue-500">
                            <option value="pending">Pending</option>
                            <option value="completed">Completed</option>
                        </select>
                    </div>

                    <div class="flex justify-end space-x-3 pt-4">
                        <button type="button" onclick="closeEditModal()"
                            class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded-md transition">Cancel</button>
                        <button type="button" onclick="updateBooking()"
                            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md transition">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
