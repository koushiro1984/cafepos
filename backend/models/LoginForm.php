<?php
namespace common\models;

use Yii;
use yii\base\Model;

/**
 * Login form
 */
class LoginForm extends Model
{
    public $username;
    public $password;
    public $rememberMe = true;

    private $_user = false;


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            // username and password are both required
            [['username', 'password'], 'required'],
            // rememberMe must be a boolean value
            ['rememberMe', 'boolean'],
            // password is validated by validatePassword()
            ['password', 'validatePassword'],
        ];
    }

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError($attribute, 'Incorrect username or password.');
            }
        }
    }

    /**
     * Logs in a user using the provided username and password.
     *
     * @return boolean whether the user is logged in successfully
     */
    public function login()
    {
        if ($this->validate()) {
            if (Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600 * 24 * 30 : 0)) {
                $data['employee']['nama'] = Yii::$app->user->getIdentity()->kdKaryawan->nama;
                $data['employee']['image'] = Yii::$app->user->getIdentity()->kdKaryawan->image;
                $data['user_level']['id'] = Yii::$app->user->getIdentity()->userLevel->id;
                $data['user_level']['nama_level'] = Yii::$app->user->getIdentity()->userLevel->nama_level;   
                
                $subProgram = Yii::$app->user->getIdentity()->userLevel->defaultAction->sub_program;
                $namaModule = Yii::$app->user->getIdentity()->userLevel->defaultAction->nama_module;
                $moduleAction = Yii::$app->user->getIdentity()->userLevel->defaultAction->module_action;
                $data['user_level']['default_action'] = Yii::getAlias('@root-web') . '/' . $subProgram . '/web/index.php/' . $namaModule . '/' . $moduleAction;
                
                $userAkses = \backend\models\UserAkses::find()
                        ->joinWith(['userLevel', 'userAppModule'])
                        ->andWhere(['user_akses.user_level_id' => $data['user_level']['id']])
                        ->andWhere(['user_akses.is_active' => true])
                        ->asArray()->all();
                
                $data['user_level']['userAkses'] = $userAkses;       
                
                Yii::$app->session->set('user_data', $data);
                return true;
            }
        } else {
            return false;
        }
    }

    /**
     * Finds user by [[username]]
     *
     * @return User|null
     */
    public function getUser()
    {
        if ($this->_user === false) {
            $this->_user = \backend\models\User::findByUsername($this->username);
        }

        return $this->_user;
    }
}
