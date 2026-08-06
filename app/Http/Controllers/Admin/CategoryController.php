<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class CategoryController extends Controller
{
    /**
     * Hiển thị danh sách danh mục.
     */
    public function index(Request $request): View
    {
        $sort = $request->input('sort', 'latest');

        $categories = Category::query()
            ->withCount('homestays')
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = trim((string) $request->input('search'));

                $query->where(function ($subQuery) use ($search) {
                    $subQuery
                        ->where('name', 'like', "%{$search}%")
                        ->orWhere('slug', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%");
                });
            })
            ->when($request->filled('status'), function ($query) use ($request) {
                $query->where('status', (int) $request->input('status'));
            })
            ->when($sort === 'oldest', function ($query) {
                $query->orderBy('created_at')->orderBy('id');
            })
            ->when($sort === 'name_asc', function ($query) {
                $query->orderBy('name')->orderBy('id');
            })
            ->when($sort === 'name_desc', function ($query) {
                $query->orderByDesc('name')->orderByDesc('id');
            })
            ->when($sort === 'most_used', function ($query) {
                $query->orderByDesc('homestays_count')->orderByDesc('id');
            })
            ->when($sort === 'least_used', function ($query) {
                $query->orderBy('homestays_count')->orderBy('id');
            })
            ->when($sort === 'latest', function ($query) {
                $query->orderByDesc('created_at')->orderByDesc('id');
            })
            ->paginate(10)
            ->withQueryString();

        $statistics = [
            'total' => Category::query()->count(),
            'active' => Category::query()->where('status', true)->count(),
            'inactive' => Category::query()->where('status', false)->count(),
            'in_use' => Category::query()->whereHas('homestays')->count(),
        ];

        return view(
            'admin.categories.index',
            compact('categories', 'statistics')
        );
    }

    /**
     * Hiển thị form thêm danh mục.
     */
    public function create(): View
    {
        return view('admin.categories.create');
    }

    /**
     * Lưu danh mục mới.
     */
    public function store(StoreCategoryRequest $request): RedirectResponse
    {
        $data = $request->validated();

        $data['slug'] = !empty($data['slug'])
            ? Str::slug($data['slug'])
            : Str::slug($data['name']);

        Category::create($data);

        return redirect()
            ->route('admin.categories.index')
            ->with('success', 'Thêm danh mục thành công.');
    }

    /**
     * Hiển thị chi tiết danh mục.
     */
    public function show(Category $category): View
    {
        $category->loadCount('homestays');

        return view(
            'admin.categories.show',
            compact('category')
        );
    }

    /**
     * Hiển thị form chỉnh sửa danh mục.
     */
    public function edit(Category $category): View
    {
        return view(
            'admin.categories.edit',
            compact('category')
        );
    }

    /**
     * Cập nhật danh mục.
     */
    public function update(
        UpdateCategoryRequest $request,
        Category $category
    ): RedirectResponse {
        $data = $request->validated();

        $data['slug'] = !empty($data['slug'])
            ? Str::slug($data['slug'])
            : Str::slug($data['name']);

        $category->update($data);

        return redirect()
            ->route('admin.categories.index')
            ->with('success', 'Cập nhật danh mục thành công.');
    }

    /**
     * Xóa danh mục.
     */
    public function destroy(Category $category): RedirectResponse
    {
        $category->delete();

        return redirect()
            ->route('admin.categories.index')
            ->with('success', 'Xóa danh mục thành công.');
    }
}