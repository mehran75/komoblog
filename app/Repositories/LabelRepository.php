<?php


namespace App\Repositories;


use App\Interfaces\LabelInterface;
use App\Model\Label;
use Illuminate\Http\Resources\Json\PaginatedResourceResponse;

class LabelRepository implements LabelInterface
{

    /**
     * @inheritDoc
     */
    public function storeLabel(array $values)
    {
        $label = new Label();
        $label->name = $values['name'];

        $label->save();

        return $label;
    }

    /**
     * @inheritDoc
     */
    public function indexLabels()
    {
        return Label::paginate();
    }

    /**
     * @inheritDoc
     */
    public function showLabel($id)
    {
        return Label::findOrFail($id);
    }

    /**
     * @inheritDoc
     */
    public function updateLabel(array $values, $id)
    {
        $label = Label::findOrFail($id);
        $label->name = $values['name'];
        $label->save();

        return $label;
    }

    /**
     * @inheritDoc
     */
    public function deleteLabel($id): bool
    {
        return Label::findOrFail($id)->delete();
    }
}
