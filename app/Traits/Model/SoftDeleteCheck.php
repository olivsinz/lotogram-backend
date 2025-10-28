<?php

namespace App\Traits\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;

trait SoftDeleteCheck
{
    public static function bootSoftDeleteCheck()
    {
        static::deleting(function (Model $model) {
            if ($model->hasRelatedRecords()) {
                return false;
            }
        });
    }

    public function hasRelatedRecords()
    {
        foreach ($this->checkRelations() as $relation) {
            if ($relation instanceof Relation && $relation->exists()) {
                return true;
            }
        }

        return false;
    }

    protected function checkRelations()
    {
        $relations = [];

        foreach (get_class_methods($this) as $method) {
            // Metodu çağır ve sonucu kontrol et
            if (!method_exists('Illuminate\Database\Eloquent\Model', $method) && is_callable([$this, $method])) {
                try {
                    $result = $this->$method();
                    if ($result instanceof Relation) {
                        $relations[$method] = $result;
                    }
                } catch (\Throwable $e) {
                    // Eğer bir metot çağrısı sırasında hata oluşursa, bu metodu atla
                    continue;
                }
            }
        }
        dd($relations);
        return $relations;
    }

}
