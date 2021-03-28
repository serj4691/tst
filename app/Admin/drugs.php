<?php
use App\Models\Substance;
use App\Models\Drug;
use SleepingOwl\Admin\Model\ModelConfiguration;

AdminSection::registerModel(Drug::class, function (ModelConfiguration $model) {
    $model->setTitle('Drugs');
    // Display
    $model->onDisplay(function () {
        $display = AdminDisplay::table()->setColumns([
            AdminColumn::text('name')->setLabel('Название')->setWidth('400px'),
            
        ]);
        $display->paginate(10);
        return $display;
    });
    // Create And Edit
    $model->onCreateAndEdit(function() {

        $form = AdminForm::panel()->addBody(
            AdminFormElement::text('name', 'Название'),
            
        );
        return $form;
    });
});