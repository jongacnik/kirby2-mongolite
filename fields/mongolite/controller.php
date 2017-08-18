<?php

class MongoliteFieldController extends Kirby\Panel\Controllers\Field {

  public function add() {

    $self      = $this;
    $field     = $this->field();
    $model     = $this->model();
    $structure = $this->structure($model);
    
    // abort if the field already has too many items or is readonly
    if($field->readonly || (!is_null($field->limit) && $field->entries()->count() >= $field->limit)) {
      return $this->modal('error', array(
        'text' => l('fields.structure.max.error')
      ));
    }
    
    $modalsize = $this->field()->modalsize();
    $form      = $this->form('add', array($model, $structure), function($form) use($model, $structure, $self, $field) {

      $form->validate();

      if(!$form->isValid()) {
        return false;
      }

      // add to mongo-lite
      $data = $form->serialize();
      $field->database->{$field->collection()}->insert($data);

      $self->redirect($model);

    });

    return $this->modal('add', compact('form', 'modalsize'));

  }

  public function update($entryId) {

    $self      = $this;
    $field     = $this->field();
    $model     = $this->model();
    $structure = $this->structure($model);
    
    $entry = $field->database->{$field->collection()}->findOne(['_id' => $entryId]);

    // abort if the field is readonly
    if($field->readonly) {
      return $this->modal('error', array(
        'text' => l('fields.structure.max.error')
      ));
    }

    if(!$entry) {
      return $this->modal('error', array(
        'text' => l('fields.structure.entry.error')
      ));
    }

    $modalsize = $this->field()->modalsize();
    $form      = $this->form('update', array($model, $structure, $entry), function($form) use($model, $structure, $self, $field, $entryId) {

      // run the form validator
      $form->validate();

      if(!$form->isValid()) {
        return false;
      }

      // update in mongo-lite
      $data = $form->serialize();
      $field->database->{$field->collection()}->update(['_id' => $entryId], $data);

      $self->redirect($model);

    });

    return $this->modal('update', compact('form', 'modalsize'));
        
  }

  public function delete($entryId) {
    
    $self      = $this;
    $field     = $this->field();
    $model     = $this->model();
    $structure = $this->structure($model);
    
    $entry = $field->database->{$field->collection()}->findOne(['_id' => $entryId]);

    // abort if the field is readonly
    if($field->readonly) {
      return $this->modal('error', array(
        'text' => l('fields.structure.max.error')
      ));
    }

    if(!$entry) {
      return $this->modal('error', array(
        'text' => l('fields.structure.entry.error')
      ));
    }

    $form = $this->form('delete', $model, function() use($self, $model, $structure, $field, $entryId) {
      
      // remove from mongo-lite
      $field->database->{$field->collection()}->remove(['_id' => $entryId]);

      $self->redirect($model);
    });
    
    return $this->modal('delete', compact('form'));

  }

  protected function structure($model) {
    return $model->structure()->forField($this->fieldname());
  }

}