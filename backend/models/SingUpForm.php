<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use common\models\User;

/**
 * Signup form
 */
class SingUpForm extends Model
{
    public $username;
    public $email;
    public $password;
    public $password2;


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['username', 'trim'],
            ['username', 'required'],
            ['username', 'unique', 'targetClass' => '\common\models\User', 'message' => 'This username has already been taken.'],
            ['username', 'string', 'min' => 2, 'max' => 255],

            ['email', 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            ['email', 'unique', 'targetClass' => '\common\models\User', 'message' => 'This email address has already been taken.'],

            ['password, password2', 'required'],
            ['password, password2', 'string', 'min' => Yii::$app->params['user.passwordMinLength']],
            ['password, password2', 'match', 'pattern' => '/^\S*(?=\S*[a-z])(?=\S*[A-Z])(?=\S*[\d])\S*$/'],
            [
                'password2', 'compare', 'compareAttribute' => 'password',
                'message' => "Las contraseñas no coinciden",
            ],

        ];
    }

    /**
     * Signs user up.
     *
     * @return bool whether the creating new account was successful and email was sent
     */
    public function signup()
    {
        
       /* if (!$this->validate()) {
            return null;
        }
        */
        
        $user = new User();
        
        $user->username = $this->username;
        $user->email = $this->email;
        $user->setPassword("abc123");
        $user->generateAuthKey();
        $user->generateEmailVerificationToken();
        $user->status = 9;
        $user->created_at = strtotime('today');
        $user->updated_at = strtotime('today');

       // echo "<pre>"; var_dump($user);echo "</pre>";die;
     $user->save() && $this->sendEmail($user);
     // $hola = $this->sendEmail($user);
    //  var_dump($hola);die;
    }

    /**
     * Sends confirmation email to user
     * @param User $user user model to with email should be send
     * @return bool whether the email was sent
     */
    protected function sendEmail($user)
    {
        
        
        return Yii::$app
            ->mailer
            ->compose(
                ['html' => 'emailVerify-html', 'text' => 'emailVerify-text'],
                ['user' => $user]
            )
            ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name . ' robot'])
            ->setTo($this->email)
            ->setSubject('Registro de cuenta ' . Yii::$app->name)
            ->send();

    }
}
