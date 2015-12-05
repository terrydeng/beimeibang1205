<?php
namespace Common\Model;

use Think\Model;

class ThemeModel extends Model
{
    protected $name = '';
    protected $tokenFile = '/token.ini';
    public function setTheme($name)
    {
        if (M('Config')->where(array('name' => '_THEME_NOW_THEME'))->count()) {
            $res = M('Config')->where(array('name' => '_THEME_NOW_THEME'))->setField('value', $name);
        } else {
            $config['name'] = '_THEME_NOW_THEME';
            $config['type'] = 0;
            $config['title'] = '';
            $config['group'] = 0;
            $config['extra'] = '';
            $config['remark'] = '';
            $config['create_time'] = time();
            $config['update_time'] = time();
            $config['status'] = 1;
            $config['value'] = $name;
            $config['sort'] = 0;
            $res = M('Config')->add($config);
        }

        if ($res) {
            S('conf_THEME_NOW_THEME', $name);
            cookie('TO_LOOK_THEME', $name, array('prefix' => 'OSV2'));
            clean_cache(RUNTIME_PATH . 'Cache/');//清除模板缓存
            return true;

        } else {
            $this->error = L('_WRITE_DATABASE_FAILURE_WITH_PERIOD_');
            return false;
        }
    }

    /**获取主题
     * @return mixed
     */
    public function getTheme($name)
    {

        if (is_file(OS_THEME_PATH . $name . '/info.php')) {
            $tpl = require_once(OS_THEME_PATH . $name . '/info.php');
            $tpl['path'] = OS_THEME_PATH . $name;
            $tpl['file_name'] = $name;
            $tpl['token'] = file_get_contents(OS_THEME_PATH . $name . '/token.ini');
        }
        return $tpl;
    }

    public function setToken($name,$token)
    {
        $this->name = $name;
        @chmod($this->getRelativePath($this->tokenFile), 0777);
        $result = file_put_contents($this->getRelativePath($this->tokenFile), $token);
        @chmod($this->getRelativePath($this->tokenFile), 0777);
        return $result;
    }

    private function getRelativePath($file)
    {
        return OS_THEME_PATH . $this->name . $file;
    }
}


?>