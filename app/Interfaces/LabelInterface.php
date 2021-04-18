<?php

namespace App\Interfaces;

use App\Model\Label;
use Illuminate\Http\Resources\Json\PaginatedResourceResponse;

interface LabelInterface
{


    /**
     * Create new post
     * @param array $values
     * @return label
     */
    public function storeLabel(array $values);


    /**
     * get all of the published posts paginated.
     * @param $postId
     * @return PaginatedResourceResponse : returns labels
     */
    public function indexLabels();

    /**
     * get all of the published posts paginated.
     * @param $id : label id
     * @return Label
     */
    public function showLabel($id);


    /**
     *  update a post
     * @param array $values
     * @param $id : label id
     * @return Label
     */
    public function updateLabel(array $values, $id);

    /**
     *  delete a post
     * @param $id
     * @return bool
     */
    public function deleteLabel($id): bool;



}
