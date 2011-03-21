<?php
declare(ENCODING = 'utf-8');
namespace F3\Twitcode\Lib\oauth;

class EpiOAuthException extends \Exception
{
  public static function raise($response, $debug)
  {
    $message = $response->responseText;

    switch($response->code)
    {
      case 400:
        throw new EpiOAuthBadRequestException($message, $response->code);
      case 401:
        throw new EpiOAuthUnauthorizedException($message, $response->code);
      default:
        throw new EpiOAuthException($message, $response->code);
    }
  }
}
