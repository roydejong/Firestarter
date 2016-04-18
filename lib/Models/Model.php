<?php

namespace Firestarter\Models;

use ActiveRecord\ActiveRecordException;
use ActiveRecord\RelationshipException;
use ActiveRecord\UndefinedPropertyException;

/**
 * An active record database model representing a single record in the database.
 */
class Model extends \ActiveRecord\Model
{
    /**
     * @inheritdoc
     */
    public function __set($name, $value)
    {
        // If a relationship is assigned an object, translate it to a reference id
        $table = static::table();

        if (($table->has_relationship($name)) && ($class = get_class($value))) {
            $relation = $table->get_relationship($name);

            if ($class == $relation->class_name) {
                return $this->assign_attribute($relation->foreign_key[0], $value->id);
            } else {
                throw new RelationshipException();
            }
        }

        return parent::__set($name, $value);
    }

    /**
     * @inheritdoc
     */
    public function __isset($attribute_name)
    {
        try {
            $val = $this->__get($attribute_name);

            if ($val !== false) {
                return true;
            }

            return false;
        } catch (UndefinedPropertyException $e) {
            return false;
        }
    }

    /**
     * @inheritdoc
     */
    public function __call($method, $args)
    {
        try {
            $result = parent::__call($method, $args);
        } catch (ActiveRecordException $e) {
            return null;
        }

        return $result;
    }

    /**
     * Verifies that the data in a given $model is equal to this model.
     *
     * @param Model $model The model to compare this model to.
     * @param bool $checkPrimaryKey If true, compare the primary key as well (default: ignore primary key value).
     * @return bool True if the data in the model equals the data in the current model.
     */
    public function equals(Model $model, $checkPrimaryKey = false)
    {
        $ourAttributes = $this->attributes();
        $theirAttributes = $model->attributes();

        if (!$checkPrimaryKey) {
            $primaryKey = $this->get_primary_key(true);

            unset($ourAttributes[$primaryKey]);
            unset($theirAttributes[$primaryKey]);
        }

        $diff = array_diff($ourAttributes, $theirAttributes);
        return empty($diff);
    }

    /**
     * Utility function for retrieving a Model instance that matches the data currently set on this model.
     * This function only compares the dirty attributes on this model.
     *
     * @return Model|null Model if a matching record was found, otherwise null.
     */
    public function getExisting()
    {
        $conditions = [];

        foreach ($this->dirty_attributes() as $key => $value) {
            $conditionsKey = &$conditions[0];

            if (strlen($conditionsKey) > 0) {
                $conditionsKey .= ' AND ';
            }

            $conditionsKey .= $key;

            if ($value === null) {
                $conditionsKey .= ' IS NULL';
            } else {
                $conditionsKey .= ' = ?';
                $conditions[] = $value;
            }
        }

        return $this->find(array('conditions' => $conditions));
    }
}