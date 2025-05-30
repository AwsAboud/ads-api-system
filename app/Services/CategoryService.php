<?php

namespace App\Services;

use App\Models\Category;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * Service class for handling Category-related operations.
 */
class CategoryService
{
    /**
     * @var array Relations to eager load.
     */
    protected array $relations;

    /**
     * CategoryService constructor.
     * Initialize relations to eager load.
     */
    public function __construct()
    {
        $this->relations = [];
    }

    /**
     * Retrieve all categories with optional eager loading.
     *
     * @return LengthAwarePaginator Paginated list of categories.
     */
    public function getAll(): LengthAwarePaginator
    {
        return Category::with($this->relations)->paginate(10);
    }

    /**
     * Retrieve a single category with optional eager loading.
     *
     * @param Category $category The category model instance.
     * @return Category The loaded category instance.
     */
    public function getOne(Category $category): Category
    {
        return $category->load($this->relations);
    }

    /**
     * Create a new category.
     *
     * @param array $data Attributes for creating the category.
     * @return Category The newly created category with loaded relations.
     */
    public function create(array $data): Category
    {
        $category = Category::create($data);

        return $category->load($this->relations);
    }

    /**
     * Update the given category with provided data.
     *
     * @param array $data Attributes to update.
     * @param Category $category The category model instance to update.
     * @return Category The updated category with loaded relations.
     */
    public function update(array $data, Category $category): Category
    {
        $category->update($data);

        return $category->load($this->relations);
    }

    /** 
     * Delete the specified category.
     *
     * @param Category $category The category model instance to delete.
     * 
     * @throws \Exception If the category has ads attached.
     * 
     * @return bool True if deletion was successful, false otherwise.
     */
    public function delete(Category $category): bool
    {
        if ($category->ads()->exists()) {
            throw new \Exception('You can not delete category attached with ads');
        }
        return $category->delete();
    }

}
