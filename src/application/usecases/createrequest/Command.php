<?php

namespace app\application\usecases\createrequest;

use app\domain\valueobjects\Amount;
use app\domain\valueobjects\Id;
use app\domain\valueobjects\Term;

readonly class Command
{
    public function __construct(
        public Id $userId,
        public Amount $amount,
        public Term $term,
    ) {
    }
}
