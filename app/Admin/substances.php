<?php
use App\Models\Substance;
use App\Models\Drug;
use SleepingOwl\Admin\Model\ModelConfiguration;

AdminSection::registerModel(Substance::class, function (ModelConfiguration $model) {
    $model->setTitle('Вещества');
    // Display
    $model->onDisplay(function () {
        $display = AdminDisplay::table()->setColumns([
            AdminColumn::text('name')->setLabel('Название')->setWidth('200px'),
            AdminColumn::text('visible')->setLabel('Видимость')->setWidth('200px'),

            
        ]);
        $display->paginate(10);
        return $display;
    });
    // Create And Edit
    $model->onCreateAndEdit(function() {

        $form = AdminForm::panel()->addBody(
            AdminFormElement::text('name', 'Название'),
            AdminFormElement::text('visible', 'Видимость'),
            
        );
        return $form;
    });
});