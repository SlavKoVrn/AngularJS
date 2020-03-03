<?php
namespace common\modules\rest\models;

class User extends \dektrium\user\models\User
{
    public function fields()
    {
        $fields = parent::fields();
        unset(
            $fields['auth_key'],
            $fields['password_hash'],
            $fields['password_reset_token'],
            $fields['confirmed_at'],
            $fields['unconfirmed_email'],
            $fields['blocked_at'],
            $fields['created_at'],
            $fields['updated_at'],
            $fields['flags']
        );
        return $fields;
    }

}