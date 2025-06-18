<x-app-layout>
    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            function deleteService(id) {
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
                        fetch(`/services/${id}`, {
                                method: 'DELETE',
                                headers: {
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                                        'content'),
                                    'Accept': 'application/json'
                                }
                            })
                            .then(response => {
                                if (response.ok) {
                                    Swal.fire('Succesfully!', 'Succesfully deleted service.', 'success')
                                        .then(() => location.reload());
                                } else {
                                    response.json().then(data => {
                                        Swal.fire('Error!', data.message || 'Deleted failed!', 'error');
                                    });
                                }
                            })
                    }
                });
            }

            function editService(id) {
                fetch(`/services/${id}`)
                    .then(response => response.json())
                    .then(data => {
                        document.getElementById('editId').value = data.id;
                        document.getElementById('editPrice').value = data.price;
                        document.getElementById('editDescription').value = data.description;

                        const preview = document.getElementById('editPreview');
                        if (data.photo_url) {
                            preview.src = data.photo_url;
                            preview.classList.remove('hidden');
                        } else {
                            preview.classList.add('hidden');
                        }

                        document.getElementById('editModal').classList.remove('hidden');
                    })
                    .catch(() => {
                        Swal.fire('Error!', 'Unable to retrieve service data.', 'error');
                    });
            }

            function closeEditModal() {
                document.getElementById('editModal').classList.add('hidden');
            }

            function updateService() {
                const id = document.getElementById('editId').value;
                const price = document.getElementById('editPrice').value;
                const description = document.getElementById('editDescription').value;
                const photoInput = document.getElementById('editPhoto');

                const formData = new FormData();
                formData.append('_method', 'PUT');
                formData.append('price', price);
                formData.append('description', description);
                if (photoInput.files.length > 0) {
                    formData.append('photo', photoInput.files[0]);
                }

                fetch(`/services/${id}`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: formData
                    })
                    .then(response => {
                        if (response.ok) {
                            Swal.fire('Successfully!', 'Successfully updated service.', 'success')
                                .then(() => location.reload());
                        } else {
                            response.json().then(data => {
                                Swal.fire('Error!', data.message || 'Update error.', 'error');
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
                    Service List
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-sm text-gray-700 uppercase">Photo</th>
                                <th class="px-6 py-3 text-sm text-gray-700 uppercase">Description</th>
                                <th class="px-6 py-3 text-sm text-gray-700 uppercase">Price</th>
                                <th class="px-6 py-3 text-sm text-gray-700 uppercase">Action</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y text-center">
                            @foreach ($services as $service)
                                <tr>
                                    <td class="px-6 py-4">
                                        @if ($service->photo)
                                            <img src="{{ Storage::url($service->photo) }}"
                                                class="w-20 h-20 object-cover rounded mx-auto">
                                        @else
                                            <span class="text-gray-400">No photo</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-gray-900">{{ $service->description }}</td>
                                    <td class="px-6 py-4 text-gray-900">Rp.
                                        {{ number_format($service->price, 0, ',', '.') }}</td>
                                    <td class="px-6 py-4">
                                        <button onclick="editService({{ $service->id }})"
                                            class="bg-yellow-400 text-white px-3 py-1 rounded text-xs">Edit</button>
                                        <button onclick="deleteService({{ $service->id }})"
                                            class="bg-red-600 text-white px-3 py-1 rounded text-xs">Delete</button>
                                    </td>
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
                <h2 class="text-xl font-semibold text-gray-800 mb-6 border-b pb-2">Edit Service</h2>

                <form id="editForm" class="space-y-4">
                    <input type="hidden" id="editId">

                    <!-- Price -->
                    <div>
                        <label for="editPrice" class="block text-sm font-medium text-gray-700 mb-1">Price</label>
                        <input type="number" id="editPrice"
                            class="w-full border border-gray-300 rounded-xl px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>

                    <!-- Description -->
                    <div>
                        <label for="editDescription"
                            class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                        <textarea id="editDescription" rows="3"
                            class="w-full border border-gray-300 rounded-xl px-3 py-2 resize-none focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                    </div>

                    <!-- Photo Upload -->
                    <div>
                        <label for="editPhoto" class="block text-sm font-medium text-gray-700 mb-1">Photo</label>
                        <img id="editPreview" src="" alt="Preview"
                            class="w-24 h-24 mt-3 object-cover rounded-xl hidden shadow-md border mb-3">
                        <input type="file" id="editPhoto"
                            class="w-full border border-gray-300 rounded-xl px-3 py-2 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                    </div>

                    <!-- Buttons -->
                    <div class="flex justify-end space-x-3 pt-4">
                        <button type="button" onclick="closeEditModal()"
                            class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded-md transition">Cancel</button>
                        <button type="button" onclick="updateService()"
                            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 transition">Save</button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</x-app-layout>
