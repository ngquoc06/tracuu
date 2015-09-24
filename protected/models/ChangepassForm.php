<?php

/**
 * This is the model class for table "tbl_user".
 *
 * The followings are the available columns in table 'tbl_user':
 * @property integer $id
 * @property string $role
 * @property string $username
 * @property string $password
 * @property string $fullname
 * @property integer $phone
 * @property string $email
 * @property string $registered_date
 * @property string $last_visited_date
 * @property string $activekey
 * @property integer $active
 * @property integer $status
 */
class ChangepassForm extends CFormModel
{
    public $current_password;
    public $password;
    public $password2;

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
            // NOTE: you should only define rules for those attributes that
            // will receive user inputs.
            return array(
                    array('current_password, password, password2', 'required','message'=>'Trường thông tin {attribute} không được để trống.'),
                    array('username', 'length', 'max'=>64),
                    array('password', 'length', 'max'=>64),
                    array('password2', 'compare', 'compareAttribute'=>'password', 'operator'=>'=','message'=>'{attribute} không đúng.'),
                    // The following rule is used by search().
                    // Please remove those attributes that should not be searched.
            );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
            return array(
                    'current_password' => 'Mật khẩu hiện tại',
                    'password' => 'Mật khẩu mới',
                    'password2' => 'Xác nhận mật khẩu mới',
            );
    }

    public function validateCurrentPassword($attribute,$params) {
        $user = User::model()->find(array('select'=>'username, password','condition'=>'username=:username','params'=>array(':username'=>Yii::app()->user->name)));
        if(empty($user) || (!empty($user) && $user->password != md5($this->attributes)))
            $this->addError ($attribute, 'Mật khẩu hiện tại không đúng');
    }
}