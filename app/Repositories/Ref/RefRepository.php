<?php

namespace App\Repositories\Ref;

use App\Ref;
use Cache;

/**
 * Class RefRepository
 * @package App\Repositories\Ref
 */
class RefRepository
{
    /**
     * Configure the Model
     **/
    public function model()
    {
        return Ref::class;
    }

    /**
     * @return mixed
     */
    public static function getAll()
    {
        return Cache::remember('ref:all', 1, function () {
            return Ref::all();
        });
    }

    /**
     * @param string $cat category
     * @param string $sort sort
     * @param string $lang language
     * @return \Illuminate\Support\Collection
     */
    public static function getList($cat = '', $sort = 'sort', $lang = 'ms')
    {
        $data = self::getAll();

        $data = $data->where('status', '1')->where('cat', $cat)->sortBy($sort);

        $list = $data->mapWithKeys(function ($item) {
            return [$item['code'] => $item['descr']];
        });

        return $list;
    }

    /**
     * @param string $cat category
     * @param string $code code
     * @param string $lang language
     * @return string
     */
    public static function getDescr($cat, $code, $lang = 'ms')
    {
        $data = self::getAll();

        $data = $data->where('status', '1')
            ->where('cat', $cat)
            ->where('code', $code)
            ->first();

        if($data) {
            return $lang == 'ms' ? $data->descr : $data->descr_en;
        } else {
            return '';
        }
    }

    /**
     * Get reference subcategory code
     *
     * @param array $arrayInput
     * @return string
     */
    public static function getSubCategoryCode($arrayInput)
    {
        $CA_CMPLCAT = $arrayInput['CA_CMPLCAT'] ?? '';
        $CA_CMPLCD = $arrayInput['CA_CMPLCD'] ?? '';
        $refCategory = $arrayInput['refCategory'] ?? '';
        $refSubCategory = $arrayInput['refSubCategory'] ?? '';

        $subCategoryInitialString = starts_with($CA_CMPLCD, $CA_CMPLCAT);

        $data = self::getAll();

        $subCategories = $data->where('cat', $refSubCategory)
            ->where('status', '1')
            ->filter(function ($item) use ($CA_CMPLCAT) {
                return false !== starts_with($item->code, $CA_CMPLCAT);
            });

        $subCategoryContain = $subCategories->contains('code', $CA_CMPLCD);

        if($subCategoryInitialString && $subCategoryContain) {
            $subCategoryCodeReturn = $CA_CMPLCD;
        } else {
            $subCategoryOther = $data->where('cat', $refSubCategory)
                ->first(function ($item) use ($CA_CMPLCAT) {
                    return (false !== starts_with($item->code, $CA_CMPLCAT))
                    && ((false !== str_contains($item->descr, ['lain', 'Lain']))
                    || (false !== str_contains($item->descr_en, ['other', 'Other'])));
                });

            if($subCategoryOther) {
                $subCategoryCodeReturn = $subCategoryOther->code;
            } else {
                $subCategoryRefActive = $data->where('status', '1')
                    ->where('cat', $refSubCategory)
                    ->first(function ($item) use ($CA_CMPLCAT) {
                        return false !== starts_with($item->code, $CA_CMPLCAT);
                    });

                if($subCategoryRefActive) {
                    $subCategoryCodeReturn = $subCategoryRefActive->code;
                } else {
                    $subCategoryRef = $data->where('cat', $refSubCategory)
                        ->first(function ($item) use ($CA_CMPLCAT) {
                            return false !== starts_with($item->code, $CA_CMPLCAT);
                        });

                    if($subCategoryRef) {
                        $subCategoryCodeReturn = $subCategoryRef->code;
                    } else {
                        $subCategoryCodeReturn = '';
                    }
                }
            }
        }

        return $subCategoryCodeReturn;
    }
}
