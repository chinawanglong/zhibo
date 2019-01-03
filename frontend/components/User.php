<?php
/**
 *
 * @author Ricardo Obregón <ricardo@obregon.co>
 * @created 24/11/13 07:40 PM
 */

namespace frontend\components;

use yii\helpers\ArrayHelper;
use yii\web\IdentityInterface;
use yii\web\User as BaseUser;
use Yii;


/**
 * User is the class for the "user" application component that manages the user authentication status.
 *
 * @property \app\modules\rbac\models\User $identity The identity object associated with the currently logged user. Null
 * is returned if the user is not logged in (not authenticated).
 *
 * @author Ricardo Obregón <robregonm@gmail.com>
 */
class User extends BaseUser
{
	/**
	 * @inheritdoc
	 */
	public $identityClass = 'backend\models\User';

	/**
	 * @inheritdoc
	 */
	public $enableAutoLogin = true;

	/**
	 * @inheritdoc
	 */
	public $loginUrl = ['/site/login'];

//	/**
//	 * @inheritdoc
//	 */
//	protected function afterLogin($identity, $cookieBased, $duration)
//	{
//		parent::afterLogin($identity, $cookieBased, $duration);
//		$this->identity->setScenario(self::EVENT_AFTER_LOGIN);
//		$this->identity->setAttribute('last_visit_time', new Expression('CURRENT_TIMESTAMP'));
//		// $this->identity->setAttribute('login_ip', ip2long(\Yii::$app->getRequest()->getUserIP()));
//		$this->identity->save(false);
//	}

	public function getIsAdmin()
	{
		if ($this->isGuest) {
			return false;
		}
		return $this->identity->getIsAdmin();
	}

	/*public function can($operation, $params = [], $allowCaching = true)
	{
		// Always return true when SuperAdmin user
		if ($this->getIsAdmin()) {
			return true;
		}
		return parent::can($operation, $params, $allowCaching);
	}*/

}