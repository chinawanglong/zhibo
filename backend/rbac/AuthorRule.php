<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 15-8-24
 * Time: 上午10:33
 */
namespace app\rbac;

use yii\rbac\Rule;

/**
 * Checks if authorID matches user passed via params
 * 检查authorID是否与已经通过参数（译注：不明白！）的用户匹配
 */
class AuthorRule extends Rule
{
    public $name = 'updateurpost';

    /**
     * @param string|integer $user the user ID.
     * @param Item $item the role or permission that this rule is associated with
     * @param array $params parameters passed to ManagerInterface::checkAccess().
     * @return boolean a value indicating whether the rule permits the role or permission it is associated with.
     */
    public function execute($user, $item, $params)
    {
        return isset($params['post']) ? ($params['post']['createdBy'] == $user) : false;
    }
}
?>