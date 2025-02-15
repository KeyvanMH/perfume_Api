<?php

namespace App\Http\Const;

class DefaultConst {
    const PAGINATION_NUMBER = 15;
    const INVALID_INPUT = 'ورودی نامعتبر';
    const FAIL = 'شکست در انجام عملیات';
    const SUCCESSFUL = 'با موفقیت انجام شد';
    const MAX_CART_LIMIT = 50;
    const OUT_OF_DATE = 'منقضی شده است';
    const NOT_VALID = 'معتبر نمی باشد';
    const NOT_FOUND = 'یافت نشد';
    const UNAUTHORIZE = 'دسترسی ندارید';
    const MIME_TYPE = 'mimes:jpeg,png,jpg,webp';

    const IMAGE_MAX_SIZE = 'max:5000';
    const FAILED_BRAND_EXISTENCE = 'برند مورد نظر یافت نشد';
    const FAILED_CATEGORY_EXISTENCE = 'دسته بندی مورد نظر یافت نشد';
    const INVALID_MIME_TYPE = 'فرمت تصویر مجاز نیست';
    const INVALID_IMAGE = 'تصویر نامعتبر است';
}
