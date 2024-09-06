<?php

namespace Narsil\Localization\Http\Resources;

#region USE

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Narsil\Localization\Models\Language;
use Narsil\Localization\Models\Translation;
use Narsil\Localization\Models\TranslationValue;
use Narsil\Localization\Services\LocalizationService;
use Narsil\Tables\Constants\Types;
use Narsil\Tables\Http\Resources\ShowTableResource;
use Narsil\Tables\Structures\ModelColumn;

#endregion

/**
 * @version 1.0.0
 *
 * @author Jonathan Rigaux
 */
class TranslationShowTableResource extends ShowTableResource
{
    #region PUBLIC METHODS

    /**
     * @param Request $request
     *
     * @return array
     */
    public function toArray(Request $request): array
    {
        $language = Language::locale();

        $attributes = $this->resource->toArray();

        $attributes[TranslationValue::VALUE] = $this->resource->{Translation::RELATIONSHIP_VALUES}
            ->where(TranslationValue::LANGUAGE_ID, $language->{Language::ID})
            ->first();

        $attributes[Translation::RELATIONSHIP_VALUES] = null;

        return array_filter($attributes);
    }

    #endregion

    #region PROTECTED METHODS

    /**
     * @return Collection<ModelColumn>
     */
    protected function getColumns(): Collection
    {
        $columns = parent::getColumns();

        $columns->put(
            TranslationValue::VALUE,
            (new ModelColumn())
                ->setAccessorKey(TranslationValue::VALUE)
                ->setForeignTable(TranslationValue::TABLE)
                ->setHeader(LocalizationService::trans("validation.attributes.value"))
                ->setId(TranslationValue::VALUE)
                ->setRelation(Translation::RELATIONSHIP_VALUES)
                ->setType(Types::STRING)
        );

        return $columns;
    }

    #endregion
}
