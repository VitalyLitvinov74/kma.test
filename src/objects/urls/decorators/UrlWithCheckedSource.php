<?php
declare(strict_types=1);

namespace app\objects\urls\decorators;

use app\objects\exceptions\UrlNotResponding;
use app\objects\urls\IUrl;
use app\tables\TableUrlResponses;
use vloop\entities\contracts\Form;

class UrlWithCheckedSource implements IUrl
{
    private $origin;

    public function __construct(IUrl $url)
    {
        $this->origin = $url;
    }

    public function sendToValidation(int $delaySec = 0): void
    {
        $this->origin->sendToValidation($delaySec);
    }

    public function saveResponse(Form $pageData): TableUrlResponses
    {
        $fields = $pageData->validatedFields();
        $headers = @get_headers($fields['url']);
        if ($headers && strpos($headers[0], '200')) {
            return $this->origin->add($pageData);
        }
        $this->sendToValidation(15);
        throw new UrlNotResponding($fields['url']);
    }
}