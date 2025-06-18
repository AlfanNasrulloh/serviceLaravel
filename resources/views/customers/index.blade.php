<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg overflow-hidden">
                <div class="p-6 text-xl font-semibold text-gray-900 border-b border-gray-200">
                    {{ __('User List') }}
                </div>

                <div class="overflow-x-auto ">
                    <table id="myTable" class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-sm font-medium text-gray-700 uppercase">Name</th>
                                <th class="px-6 py-3 text-sm font-medium text-gray-700 uppercase">Email</th>
                                <th class="px-6 py-3 text-sm font-medium text-gray-700 uppercase">Role</th>
                                {{-- <th class="px-6 py-3 text-sm font-medium text-gray-700 uppercase">Action</th> --}}
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200 text-center">
                            @forelse ($customers as $customer)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $customer->name }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $customer->email }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ ucfirst($customer->role) }}</td>
                                    {{-- <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 flex gap-2"> --}}

                                @empty
                                    {{-- </td> --}}
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
