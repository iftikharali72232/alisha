@extends('user.shop.layout')

@section('title', 'Categories - ' . $shop->name)

@section('shop-content')
<div class="mb-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Categories</h1>
            <p class="text-gray-600">Organize your products into categories</p>
        </div>
        <button onclick="openModal('create')"
            class="bg-pink-500 text-white px-4 py-2 rounded-lg hover:bg-pink-600 transition flex items-center gap-2">
            <i class="fas fa-plus"></i>
            <span>Add Category</span>
        </button>
    </div>
</div>

@if(session('success'))
    <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg">
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
        {{ session('error') }}
    </div>
@endif

<div class="bg-white rounded-lg shadow overflow-hidden">
    @if($categories->count() > 0)
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Image</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Slug</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Products</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @foreach($categories as $category)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4">
                            @if($category->image)
                                <img src="{{ Storage::url($category->image) }}" alt="{{ $category->name }}"
                                    class="w-12 h-12 rounded-lg object-cover">
                            @else
                                <div class="w-12 h-12 rounded-lg bg-gray-100 flex items-center justify-center">
                                    <i class="fas fa-folder text-gray-400"></i>
                                </div>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <div class="font-medium text-gray-800">{{ $category->name }}</div>
                            @if($category->description)
                                <div class="text-sm text-gray-500 truncate max-w-xs">{{ $category->description }}</div>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">{{ $category->slug }}</td>
                        <td class="px-6 py-4">
                            <span class="bg-gray-100 text-gray-700 px-2 py-1 rounded-full text-sm">
                                {{ $category->products_count ?? $category->products()->count() }} products
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            @if($category->is_active)
                                <span class="bg-green-100 text-green-700 px-2 py-1 rounded-full text-sm">Active</span>
                            @else
                                <span class="bg-gray-100 text-gray-700 px-2 py-1 rounded-full text-sm">Inactive</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                <button onclick="editCategory({{ json_encode($category) }})"
                                    class="text-blue-500 hover:text-blue-700 p-1" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <form action="{{ route('user.shop.categories.destroy', $category) }}" method="POST"
                                    onsubmit="return confirm('Are you sure? Products in this category will be uncategorized.')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:text-red-700 p-1" title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="px-6 py-4 border-t">
            {{ $categories->links() }}
        </div>
    @else
        <div class="text-center py-12">
            <i class="fas fa-folder-open text-4xl text-gray-300 mb-4"></i>
            <h3 class="text-lg font-medium text-gray-700 mb-2">No categories yet</h3>
            <p class="text-gray-500 mb-4">Create categories to organize your products</p>
            <button onclick="openModal('create')"
                class="bg-pink-500 text-white px-4 py-2 rounded-lg hover:bg-pink-600 transition">
                <i class="fas fa-plus mr-2"></i>Add First Category
            </button>
        </div>
    @endif
</div>

<!-- Create/Edit Category Modal -->
<div id="categoryModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-lg shadow-xl max-w-md w-full mx-4">
        <div class="px-6 py-4 border-b flex items-center justify-between">
            <h3 class="text-lg font-semibold text-gray-800" id="modalTitle">Add Category</h3>
            <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600">
                <i class="fas fa-times"></i>
            </button>
        </div>
        
        <form id="categoryForm" method="POST" enctype="multipart/form-data" class="p-6">
            @csrf
            <input type="hidden" name="_method" id="formMethod" value="POST">
            
            <div class="space-y-4">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">
                        Category Name <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="name" id="categoryName" required
                        class="w-full border rounded-lg px-4 py-2 focus:ring-pink-500 focus:border-pink-500"
                        placeholder="Enter category name">
                </div>

                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                    <textarea name="description" id="categoryDescription" rows="3"
                        class="w-full border rounded-lg px-4 py-2 focus:ring-pink-500 focus:border-pink-500"
                        placeholder="Optional description"></textarea>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Category Image</label>
                    <div class="flex items-center gap-4">
                        <div id="imagePreview" class="w-20 h-20 rounded-lg bg-gray-100 flex items-center justify-center overflow-hidden">
                            <i class="fas fa-folder text-2xl text-gray-400"></i>
                        </div>
                        <label class="cursor-pointer bg-white px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition">
                            <span class="text-sm text-gray-600">Choose file</span>
                            <input type="file" name="image" id="categoryImage" accept="image/*" class="hidden"
                                onchange="previewModalImage(this)">
                        </label>
                    </div>
                </div>

                <div class="flex items-center gap-2">
                    <input type="checkbox" name="is_active" id="categoryActive" value="1" checked
                        class="rounded text-pink-500 focus:ring-pink-500">
                    <label for="categoryActive" class="text-sm text-gray-700">Active</label>
                </div>
            </div>

            <div class="mt-6 flex justify-end gap-3">
                <button type="button" onclick="closeModal()"
                    class="px-4 py-2 border rounded-lg text-gray-600 hover:bg-gray-50 transition">
                    Cancel
                </button>
                <button type="submit"
                    class="px-4 py-2 bg-pink-500 text-white rounded-lg hover:bg-pink-600 transition">
                    <span id="submitText">Create Category</span>
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
function openModal(mode) {
    const modal = document.getElementById('categoryModal');
    modal.classList.remove('hidden');
    modal.classList.add('flex');
    
    if (mode === 'create') {
        document.getElementById('modalTitle').textContent = 'Add Category';
        document.getElementById('submitText').textContent = 'Create Category';
        document.getElementById('categoryForm').action = "{{ route('user.shop.categories.store') }}";
        document.getElementById('formMethod').value = 'POST';
        document.getElementById('categoryName').value = '';
        document.getElementById('categoryDescription').value = '';
        document.getElementById('categoryActive').checked = true;
        document.getElementById('imagePreview').innerHTML = '<i class="fas fa-folder text-2xl text-gray-400"></i>';
    }
}

function closeModal() {
    const modal = document.getElementById('categoryModal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
}

function editCategory(category) {
    document.getElementById('modalTitle').textContent = 'Edit Category';
    document.getElementById('submitText').textContent = 'Update Category';
    document.getElementById('categoryForm').action = `/user/shop/categories/${category.id}`;
    document.getElementById('formMethod').value = 'PUT';
    document.getElementById('categoryName').value = category.name;
    document.getElementById('categoryDescription').value = category.description || '';
    document.getElementById('categoryActive').checked = category.is_active;
    
    if (category.image) {
        document.getElementById('imagePreview').innerHTML = `<img src="/storage/${category.image}" class="w-full h-full object-cover">`;
    } else {
        document.getElementById('imagePreview').innerHTML = '<i class="fas fa-folder text-2xl text-gray-400"></i>';
    }
    
    openModal('edit');
}

function previewModalImage(input) {
    const preview = document.getElementById('imagePreview');
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.innerHTML = `<img src="${e.target.result}" class="w-full h-full object-cover">`;
        }
        reader.readAsDataURL(input.files[0]);
    }
}

// Close modal on outside click
document.getElementById('categoryModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeModal();
    }
});
</script>
@endpush
@endsection
