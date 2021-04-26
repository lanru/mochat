<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://doc.hyperf.io
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf-cloud/hyperf/blob/master/LICENSE
 */

namespace HyperfTest;

use App\Constants\User\Status;
use App\Contract\UserServiceInterface;
use Hyperf\Di\Annotation\Inject;
use Hyperf\Testing\Client;
use MoChat\Framework\Constants\ErrorCode;
use MoChat\Framework\Exception\CommonException;
use PHPUnit\Framework\TestCase;
use Psr\SimpleCache\CacheInterface;
use Psr\SimpleCache\InvalidArgumentException;
use Qbhy\HyperfAuth\AuthManager;
use Qbhy\SimpleJwt\JWTManager;

/**
 * Class HttpTestCase.
 * @method get($uri, $data = [], $heagetElapsedders = [])
 * @method put($uri, $data = [], $headers = [])
 * @method post($uri, $data = [], $headers = [])
 * @method json($uri, $data = [], $headers = [])
 * @method file($uri, $data = [], $headers = [])
 */
abstract class AdminTestCase extends TestCase
{
    /**
     * @var Client
     */
    protected $client;

    /**
     * @var mixed|CacheInterface
     */
    protected $cache;

    // token缓存key
    protected $cacheKey = 'test_admin_token';

    // token
    protected $header = [];

    protected $signArray = [];

    /**
     * @Inject
     * @var AuthManager
     */
    protected $auth;
    /**
     * @Inject
     * @var UserServiceInterface
     */
    protected $user;


    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        $this->client = make(Client::class);
        $this->cache = make(CacheInterface::class);
        $this->user = make(UserServiceInterface::class);
        $this->auth = make(AuthManager::class);
        // 登录接口
        try {
            $this->login();
        } catch (InvalidArgumentException $e) {
        }
    }

    public function __call($name, $arguments)
    {
        return $this->client->{$name}(...$arguments);
    }

    /**
     * @return mixed|string
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function login(): string
    {
        $token = $this->cache->get($this->cacheKey);
        if (!$token) {
            $userId = intval(env('USER_ID'));
            // 模型数据
            $userData = $this->user->getUserModelById($userId, ['id', 'status', 'password']);
            if (!$userData) {
                throw new CommonException(ErrorCode::AUTH_LOGIN_FAILED);
            }
            // 判断账户状态
            if ($userData['status'] != Status::NORMAL) {
                throw new CommonException(ErrorCode::ACCESS_REFUSE, sprintf('账户%s，无法登录', Status::getMessage($userData['status'])));
            }
            // 逻辑处理
            $guard = $this->auth->guard('jwt');
            /** @var JWTManager $jwt */
            $jwt = $guard->getJwtManager();
            // 响应参数
            $token = $guard->login($userData);
            // 设置到缓存
            $expire = $jwt->getTtl();
            $this->cache->set($this->cacheKey, $token, $expire);
        }
        $this->header['Authorization'] = 'Bearer ' . $token;
        return $token;
    }

    /**
     * @param array $result
     * @return false|string
     */
    public function pretty(array $result)
    {
        $this->assertSame(0, 0); // 表示成功

        echo json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . PHP_EOL;
    }


    /**
     * 执行POST请求，自动添加签名字符串
     * @param $uri
     * @param array $postData
     * @param string $method
     */
    public function doPost($uri, array $postData = [])
    {
        return $this->post($uri, $postData, $this->header);
    }
}
