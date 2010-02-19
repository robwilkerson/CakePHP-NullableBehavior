<?php

class NullableBehavior extends ModelBehavior {
	/**
	 * function beforeSave
	 * 
	 * Looks for nullable fields in the schema and replaces empty string values for those fields
	 * with NULL values. This is helpful as hell when foreign key values are nullable lest you
	 * get lots of key constraint errors.
	 *
	 * @param		model		The model object to be saved.
	 * @return	void
	 */
	function beforeSave ( $model ) {
		$schema = $model->schema();

		foreach ( $schema as $field => $metadata ) {
			if ( $metadata['null'] ) {
				if ( isset ( $model->data[$model->name][$field] ) && $model->data[$model->name][$field] === '' ) {
					$model->data[$model->name][$field] = null;
				}
			}
		}
	}
}

?>
