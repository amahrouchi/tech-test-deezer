<?php

namespace exceptions;

class HttpException extends \Exception
{
    /**
     * NotFound exception constant
     * @var string
     */
    const NOT_FOUND = 'notFound';

    /**
     * BadRequest exception constant
     * @var string
     */
    const BAD_REQUEST = 'badRequest';

    /**
     * InternalServerError exception constant
     * @var string
     */
    const INTERNAL_SERVER_ERROR = 'internalServerError';

    /**
     * Builds http exceptions
     * @param string $message
     * @param string $type
     * @return HttpException
     */
    public static function factory($message, $type = self::INTERNAL_SERVER_ERROR)
    {
        $details = ['message' => $message];

        switch ($type)
        {

            case self::BAD_REQUEST:
                $details['code'] = 400;
                $exception       = new BadRequestException();
                break;

            case self::NOT_FOUND:
                $details['code'] = 404;
                $exception       = new NotFoundException();
                break;

            case self::INTERNAL_SERVER_ERROR:
            default:
                $details['code'] = 500;
                $exception       = new InternalServerErrorException();
                break;
        }

        $exception->code    = $details['code'];
        $exception->message = json_encode($details, JSON_NUMERIC_CHECK);

        return $exception;
    }

}
