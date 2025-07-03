<?php

namespace App\Repositories;

use App\Models\Category;

interface CategoryRepositoryInterface
{
    public function all(): \Illuminate\Database\Eloquent\Collection;
    public function find(int|string $id): Category;
    public function create(array $data): Category;
    public function update(Category $category, array $data): Category;
    public function delete(Category $category): void;
}

class CategoryRepository implements CategoryRepositoryInterface
{
    public function all(): \Illuminate\Database\Eloquent\Collection
    {
        return Category::all();
    }

    public function find(int|string $id): Category
    {
        return Category::query()->findOrFail($id);
    }

    public function create(array $data): Category
    {
        return Category::create($data);
    }

    public function update(Category $category, array $data): Category
    {
        $category->update($data);
        return $category;
    }

    public function delete(Category $category): void
    {
        $category->delete();
    }
}
