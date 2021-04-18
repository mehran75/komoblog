<?php


namespace App\Repositories;


use App\Interfaces\CategorytInterface;
use App\Model\Category;
use Illuminate\Http\Resources\Json\PaginatedResourceResponse;
use Mockery\Exception;

class CategoryRepository implements CategorytInterface
{

    /**
     * @inheritDoc
     */
    public function storeCategory(array $values)
    {
        $category = new Category();
        $category->name = $values['name'];

        $category->save();

        return $category;
    }

    /**
     * @inheritDoc
     */
    public function indexCategories()
    {
        return Category::paginate();
    }

    /**
     * @inheritDoc
     */
    public function showCategory($id)
    {
        return Category::findOrFail($id);
    }

    /**
     * @inheritDoc
     */
    public function updateCategory(array $values, $id)
    {
        $category = Category::findOrFail($id);
        $category->name = $values['name'];

        return $category;
    }

    /**
     * @inheritDoc
     */
    public function deleteCategory($id): bool
    {
        $category = Category::findOrFail($id);

        if ($category->posts()->exists()) {
            throw new Exception('category could not be removed!');
        }

        return $category->delete();
    }
}
