<?php

class NullableBehavior extends ModelBehavior {
  /**
   * function beforeSave
   * 
   * Looks for nullable fields in the schema and replaces empty string values for those fields
   * with NULL values. This is helpful as hell when foreign key values are nullable lest you
   * get lots of key constraint errors.
   *
   * @param   model   The model object to be saved.
   * @return  boolean  Success
   */
  function beforeSave( Model $model ) {
    $schema = $model->schema();

    foreach( $schema as $field => $metadata ) {
      if( isset( $model->data[$model->alias][$field] ) && $metadata['null'] ) {
        if( $model->data[$model->alias][$field] == '' ) {
          $model->data[$model->alias][$field] = null;
        }
      }
    }

    return true;
  }
}
