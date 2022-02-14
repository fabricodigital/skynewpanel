<?php

namespace App\Traits\Revisionable\Admin;

use App\Models\Admin\FaqCategory;
use App\Traits\Revisionable\Revisionable;

trait FaqQuestionRevisionable
{
    use Revisionable;

    protected $revisionableAttributes = ['question_text', 'answer_text'];

    protected $revisionableMediaCollections = ['attachments'];

    protected $revisionableBelongsToRelations = [
        [
            'attribute' => 'category_id',
            'relation' => 'category',
            'related_title_attributes' => ['title'],
            'class' => FaqCategory::class,
        ],
    ];

    public static $revisionableRoute = 'admin.faq-questions.show';
}

