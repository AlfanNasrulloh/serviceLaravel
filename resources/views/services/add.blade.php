<x-app-layout>
    <div class="max-w-2xl mx-auto mt-10 p-6 bg-white rounded-lg shadow-md">
        <h2 class="text-2xl font-bold mb-6">Add Service</h2>

        <form action="{{ route('services.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <!-- Price -->
            <div class="mb-4">
                <label for="price" class="block text-gray-700 font-semibold mb-1">Price</label>
                <input type="number" name="price" id="price" value="{{ old('price') }}"
                    class="w-full border border-gray-300 rounded px-4 py-2 focus:outline-none focus:ring focus:border-blue-300"
                    required>
            </div>

            <!-- Description -->
            <div class="mb-4">
                <label for="description" class="block text-gray-700 font-semibold mb-1">Description</label>
                <textarea name="description" id="description" rows="4"
                    class="w-full border border-gray-300 rounded px-4 py-2 focus:outline-none focus:ring focus:border-blue-300"
                    required>{{ old('description') }}</textarea>
            </div>

            <!-- Photo -->
            <div class="mb-4">
                <label for="photo" class="block text-gray-700 font-semibold mb-1">Photo</label>
                <input type="file" name="photo" id="photo"
                    class="w-full border border-gray-300 rounded-xl px-3 py-2 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
            </div>

            <!-- Submit Button -->
            <div class="mt-6 flex justify-end">
                <button type="submit"
                    class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded w-md">
                    Submit
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
