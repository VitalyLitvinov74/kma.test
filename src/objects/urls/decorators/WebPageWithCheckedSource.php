<?php
declare(strict_types=1);

namespace app\objects\urls\decorators;

use app\objects\exceptions\UrlNotResponding;
use app\objects\forms\IField;
use app\objects\urls\IWebPage;
use app\tables\TableUrlResponses;
use vloop\entities\contracts\Form;

class WebPageWithCheckedSource implements IWebPage
{
    private $origin;

    public function __construct(IWebPage $url)
    {
        $this->origin = $url;
    }

    public function sendToValidation(int $delaySec = 0): void
    {
        $this->origin->sendToValidation($delaySec);
    }

    public function saveResponse(): TableUrlResponses
    {

        $headers = @get_headers($this->struct()->toString());
        if ($headers && strpos($headers[0], '200')) {
            return $this->origin->saveResponse();
        }
        $this->sendToValidation(15);
        throw new UrlNotResponding($this->struct()->toString());
    }

    /**
     * вернет структуру объекта.
     * @return IField
     */
    public function struct(): IField
    {
        return $this->origin->struct();
    }
}