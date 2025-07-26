<?php
namespace App\Helpers;
require_once __DIR__ . '/../../jwt-lib/vendor/autoload.php';
//require_once "C:/xampp_7/htdocs/dts/jwt-lib/vendor/autoload.php";

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\SignatureInvalidException;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class JWTHelper
{
    private static $secret;
    private static $algorithm = 'HS256';
    
    public static function init()
    {
        self::$secret = env('JWT_SECRET', config('app.key'));
        if (!self::$secret) {
            throw new \Exception('JWT secret key is not configured');
        }
    }
    
    public static function generateToken($user, $expirationHours = 24)
    {
        self::init();
        
        $issuedAt = time();
        $expiration = $issuedAt + ($expirationHours * 60 * 60);
        
        $payload = [
            'iss' => env('APP_URL', 'localhost'),
            'aud' => env('APP_URL', 'localhost'),
            'sub' => $user->id,
            'iat' => $issuedAt,
            'exp' => $expiration,
            'nbf' => $issuedAt,
            'jti' => uniqid(),
            'user_data' => [
                'id' => $user->id,
                'email' => $user->username,
                'name' => $user->fname,
            ]
        ];
        Log::info('tayongtayongtayong!');
        return JWT::encode($payload, self::$secret, self::$algorithm);
    }
    
    public static function validateToken($token)
    {
        try {
            self::init();
            $decoded = JWT::decode($token, new Key(self::$secret, self::$algorithm));
            return (array) $decoded;
        } catch (ExpiredException $e) {
            Log::warning('JWT Token expired: ' . $e->getMessage());
            return false;
        } catch (SignatureInvalidException $e) {
            Log::warning('JWT Invalid signature: ' . $e->getMessage());
            return false;
        } catch (\Exception $e) {
            Log::error('JWT Token validation error: ' . $e->getMessage());
            return false;
        }
    }
    
    public static function getUserFromToken($token)
    {
        $payload = self::validateToken($token);
        
        if (!$payload) {
            return false;
        }
        
        return $payload['user_data'] ?? false;
    }
    
    public static function generateRedirectToken($jwtToken, $expirationMinutes = 5)
    {
        $redirectData = [
            'jwt' => $jwtToken,
            'expires' => time() + ($expirationMinutes * 60),
            'nonce' => uniqid(),
        ];
        
        return base64_encode(json_encode($redirectData));
    }
    
    public static function validateRedirectToken($redirectToken)
    {
        try {
            $redirectData = json_decode(base64_decode($redirectToken), true);
            
            if (!$redirectData || !isset($redirectData['jwt'], $redirectData['expires'])) {
                return false;
            }
            
            if (time() > $redirectData['expires']) {
                return false;
            }
            
            $jwtData = self::validateToken($redirectData['jwt']);
            
            if (!$jwtData) {
                return false;
            }
            
            return [
                'jwt_data' => $jwtData,
                'redirect_data' => $redirectData
            ];
            
        } catch (\Exception $e) {
            Log::error('Redirect token validation error: ' . $e->getMessage());
            return false;
        }
    }
    
    public static function isTokenExpired($token)
    {
        try {
            self::init();
            $decoded = JWT::decode($token, new Key(self::$secret, self::$algorithm));
            $payload = (array) $decoded;
            return time() >= $payload['exp'];
        } catch (ExpiredException $e) {
            return true;
        } catch (\Exception $e) {
            return true;
        }
    }

    public static function invalidateToken($token)
    {
        try {
            // First decode the token to get its info
            $decoded = JWT::decode($token, new Key((string) self::$secret, 'HS256'));
            
            if (!isset($decoded->jti) || !isset($decoded->exp)) {
                Log::warning('Cannot invalidate token: missing jti or exp claim');
                return false;
            }

            // Calculate how long to keep the token in blacklist (until it would naturally expire)
            $now = Carbon::now()->timestamp;
            $expiration = $decoded->exp;
            $ttlSeconds = max(0, $expiration - $now);

            // Add token ID to blacklist cache
            $blacklistKey = 'jwt_blacklist_' . $decoded->jti;
            Cache::put($blacklistKey, true, $ttlSeconds);
            
            // Optional: Also blacklist by token hash for extra security
            $tokenHash = 'jwt_hash_' . hash('sha256', $token);
            Cache::put($tokenHash, true, $ttlSeconds);

            Log::info('Token invalidated successfully', [
                'jti' => $decoded->jti,
                'ttl_seconds' => $ttlSeconds
            ]);

            return true;

        } catch (ExpiredException $e) {
            // Token is already expired, no need to blacklist
            Log::info('Token already expired, no need to blacklist');
            return true;
        } catch (\Exception $e) {
            Log::error('Token invalidation failed: ' . $e->getMessage());
            return false;
        }
    }
}