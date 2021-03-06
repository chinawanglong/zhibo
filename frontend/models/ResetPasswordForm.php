<?php
namespace frontend\models;

use common\models\User;
use yii\base\InvalidParamException;
use yii\base\Model;
use Yii;

/**
 * Password reset form
 */
class ResetPasswordForm extends Model
{
    public $oldpass;/*旧密码*/
    public $password;/*新密码*/
    public $password_confirm;/*重复密码*/

    /**
     * @var \common\models\User
     */
    private $_user;


    /**
     * Creates a form model given a token.
     *
     * @param  string                          $token
     * @param  array                           $config name-value pairs that will be used to initialize the object properties
     * @throws \yii\base\InvalidParamException if token is empty or not valid
     */
    public function __construct($token, $config = [])
    {
        if (empty($token) || !is_string($token)) {
            throw new InvalidParamException('Password reset token cannot be blank.');
        }
        $this->_user = User::findByPasswordResetToken($token);
        if (!$this->_user) {
            throw new InvalidParamException('Wrong password reset token.');
        }
        parent::__construct($config);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['password', 'required','message'=>'新密码不能为空'],
           /*['oldpass', 'required','message'=>'旧密码不能为空'],
            ['oldpass','validoldpass'],
            ['password_confirm', 'required','message'=>'重复密码不能为空'],
            [['password','oldpass','password_confirm'], 'string', 'min' => 6,'message'=>'密码长度最小要有6位'],
            ['password_confirm','compare','compareAttribute'=>'password','message'=>'两次密码输入不相同'],*/
        ];
    }
    public function validoldpass(){

    }
    /**
     * Resets password.
     *
     * @return boolean if password was reset.
     */
    public function resetPassword()
    {
        $user = $this->_user;
        $user->setPassword($this->password);
        $user->removePasswordResetToken();

        return $user->save(false);
    }
}
