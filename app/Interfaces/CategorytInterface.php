<?php

namespace App\Interfaces;

use App\Model\Category;
use Illuminate\Http\Resources\Json\PaginatedResourceResponse;

interface CategorytInterface
{


    /**
     * Create new post
     * @param array $values
     * @return category
     */
    public function storeCategory(array $values);


    /**
     * get all of the published posts paginated.
     * @param $postId
     * @return PaginatedResourceResponse : returns categories
     */
    public function indexCategories();

    /**
     * get all of the published posts paginated.
     * @param $id : category id
     * @return Category
     */
    public function showCategory($id);


    /**
     *  update a post
     * @param array $values
     * @param $id : category id
     * @return Category
     */
    public function updateCategory(array $values, $id);

    /**
     *  delete a post
     * @param $id
     * @return bool
     */
    public function deleteCategory($id): bool;



}
